<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Regressors\GradientBoost;

class PatientFlowController extends Controller
{
    /**
     * Отображает страницу с графиком потока пациентов.
     * @throws Exception
     */
    public function index(Request $request): View
    {
        // 1) Получаем параметр группировки из query-string (или 'day' по умолчанию)
        $groupingType = $request->query('grouping', 'day');

        // 2) Вызываем ApiService для получения данных
        $groups = app(ApiService::class)->getGrouping(['grouping' => $groupingType]);

        // Проверяем, есть ли данные
        $hasAdmissions = !empty($groups['admissions']) && is_array($groups['admissions']);
        $hasDischarges = !empty($groups['discharges']) && is_array($groups['discharges']);

        // 3) Собираем все даты из admissions и discharges (если данные есть)
        $admissionKeys = $hasAdmissions ? array_keys($groups['admissions']) : [];
        $dischargeKeys = $hasDischarges ? array_keys($groups['discharges']) : [];

        $periods = collect($admissionKeys)
            ->merge($dischargeKeys)
            ->unique()
            ->sort()
            ->values();

        // 4) Форматируем даты для отображения
        $formattedDates = $periods->map(function ($date) use ($groupingType) {
            return match ($groupingType) {
                'day'   => Carbon::parse($date)->locale('ru')->translatedFormat('d M Y'),
                'month' => Carbon::parse($date)->locale('ru')->translatedFormat('F Y'),
                default => $date,
            };
        });

        // 5) Собираем данные для графика (0 если данных нет)
        $admissionsData = $periods->map(fn($period) => ($hasAdmissions && isset($groups['admissions'][$period])) ? $groups['admissions'][$period] : 0);
        $dischargesData = $periods->map(fn($period) => ($hasDischarges && isset($groups['discharges'][$period])) ? $groups['discharges'][$period] : 0);

        // 6) Формируем массив для представления
        $charts = [
            [
                'name'   => 'Принятые пациенты',
                'values' => $admissionsData,
                'labels' => $formattedDates,
                'color'  => 'rgba(75, 192, 192, 0.6)',
            ],
            [
                'name'   => 'Выписанные пациенты',
                'values' => $dischargesData,
                'labels' => $formattedDates,
                'color'  => 'rgba(255, 99, 132, 0.6)',
            ],
        ];

        // 7) Получаем данные по группировке «месяцы» для ML прогноза
        $grouping = app(ApiService::class)->getGrouping(['grouping' => 'month']);

        $hasAdmissionsMonthly = !empty($grouping['admissions']) && is_array($grouping['admissions']);
        $hasDischargesMonthly = !empty($grouping['discharges']) && is_array($grouping['discharges']);

        // 8) Собираем уникальные месяцы
        $admissionMonths = $hasAdmissionsMonthly ? array_keys($grouping['admissions']) : [];
        $dischargeMonths = $hasDischargesMonthly ? array_keys($grouping['discharges']) : [];

        $allMonths = array_unique(array_merge($admissionMonths, $dischargeMonths));
        sort($allMonths, SORT_STRING);

        // 9) Проверяем, достаточно ли данных для прогноза
        $message = null;
        $futurePredictions = [];
        $admissionModel = null;
        $dischargeModel = null;

        // Если нет данных вообще
        if (empty($allMonths)) {
            $message = 'Нет исторических данных для построения прогноза. Добавьте данные о поступлениях и выписках пациентов.';
        }
        // Если данных мало (менее 3 месяцев для простой модели)
        elseif (count($allMonths) < 1) {
            $message = 'Недостаточно данных для прогнозирования. Необходимо минимум 3 месяца истории. ' .
                'Текущее количество месяцев: ' . count($allMonths);
        }
        else {
            // 10) Подготавливаем выборки для обучения (только если есть данные)
            $admissionsSamples = [];
            $admissionsLabels  = [];
            $dischargesSamples = [];
            $dischargesLabels  = [];

            foreach ($allMonths as $index => $monthStr) {
                $dateObj = \DateTime::createFromFormat('Y-m', $monthStr);
                if (!$dateObj) {
                    continue;
                }
                $year  = (int)$dateObj->format('Y');
                $month = (int)$dateObj->format('n');
                $sample = [$year, $month, $index];

                // Для поступлений
                $admissionsSamples[] = $sample;
                $admissionsLabels[]  = ($hasAdmissionsMonthly && isset($grouping['admissions'][$monthStr]))
                    ? $grouping['admissions'][$monthStr]
                    : 0;

                // Для выписок
                $dischargesSamples[] = $sample;
                $dischargesLabels[]  = ($hasDischargesMonthly && isset($grouping['discharges'][$monthStr]))
                    ? $grouping['discharges'][$monthStr]
                    : 0;
            }

            // 11) Обучаем модели (только если есть данные)
            try {
                $admissionModel = new GradientBoost();
                $dischargeModel = new GradientBoost();

                // Обучаем модель поступлений (даже если все метки 0 - это нормально)
                if (!empty($admissionsSamples)) {
                    $admissionModel->train(new Labeled($admissionsSamples, $admissionsLabels));
                }

                // Обучаем модель выписок (даже если все метки 0 - это нормально)
                if (!empty($dischargesSamples)) {
                    $dischargeModel->train(new Labeled($dischargesSamples, $dischargesLabels));
                }

                // 12) Генерируем прогнозы на следующие 12 месяцев
                $lastMonthStr = end($allMonths);
                $lastDateObj  = \DateTime::createFromFormat('Y-m', $lastMonthStr);

                for ($i = 1; $i <= 12; $i++) {
                    $futureDate = (clone $lastDateObj)->modify("+$i month");
                    $futureKey  = $futureDate->format('Y-m');
                    $year       = (int)$futureDate->format('Y');
                    $month      = (int)$futureDate->format('n');
                    $index = count($allMonths) + $i;

                    $feature = [[$year, $month, $index]];

                    // Прогноз для поступлений
                    $admission = 0;
                    if ($admissionModel && !empty($admissionsSamples)) {
                        $admission = round($admissionModel->predict(new Unlabeled($feature))[0]);
                    }

                    // Прогноз для выписок
                    $discharge = 0;
                    if ($dischargeModel && !empty($dischargesSamples)) {
                        $discharge = round($dischargeModel->predict(new Unlabeled($feature))[0]);
                    }

                    $futurePredictions[] = [
                        'month'       => $futureKey,
                        'admissions'  => max(0, (int)$admission),  // не может быть отрицательным
                        'discharges'  => max(0, (int)$discharge),
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('ML Model training failed: ' . $e->getMessage());
                $message = 'Не удалось построить прогноз: ' . $e->getMessage();
                $futurePredictions = [];
            }
        }

        // 13) Получаем статистику (с защитой от отсутствия данных)
        try {
            $currentPatient = app(ApiService::class)->getCurrentPatient();
            $todayReceipt = app(ApiService::class)->getTodayReceipt();
            $todayDischarge = app(ApiService::class)->getTodayDischarge();
            $logs = app(ApiService::class)->getLogs(config('app.api_log_token', env('API_LOG_TOKEN')));

            $statistic = [
                'currentPatient' => $currentPatient->first(),
                'todayPatient' => $todayReceipt->first(),
                'todayDischarge' => $todayDischarge->first(),
                'total' => is_array($logs) ? count($logs) : 0,
            ];
        } catch (\Exception $e) {
            \Log::error('Failed to get statistics: ' . $e->getMessage());
            $statistic = [
                'currentPatient' => null,
                'todayPatient' => null,
                'todayDischarge' => null,
                'total' => 0,
            ];
        }

        // 14) Возвращаем Blade-шаблон
        return view('analytics.analytics', [
            'grouping' => $groupingType,
            'charts'   => $charts,
            'predictions' => $futurePredictions,
            'message'     => $message,
            'statistic' => $statistic,
        ]);
    }
}
