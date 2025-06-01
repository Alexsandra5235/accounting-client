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

        // 2) Вызываем тот же ApiService, что и в Orchid-экране, чтобы получить данные.
        //    Предполагается, что у вас есть mapped route в ApiService: getGrouping(['grouping' => ...])
        $groups = app(ApiService::class)
            ->getGrouping(['grouping' => $groupingType]);

        // 3) Собираем все даты (ключи) из admissions и discharges,
        //    чтобы обеспечить, что даже если в каком-то дне/месяце нет данных, точка все равно попадёт на график.
        $periods = collect($groups->get('admissions'))->keys()
            ->merge(collect($groups->get('discharges'))->keys())
            ->unique()
            ->sort()
            ->values();

        // 4) Форматируем даты для отображения (для легенды графика или оси X)
        $formattedDates = $periods->map(function ($date) use ($groupingType) {
            return match ($groupingType) {
                'day'   => Carbon::parse($date)->format('d M Y'),    // напр.: 01 июн 2025
                'month' => Carbon::parse($date)->format('F Y'),      // напр.: June 2025
                default => $date,
            };
        });

        // 5) Собираем сами данные для графика (число поступлений/выписок)
        $admissionsData = $periods->map(fn($period) => $groups['admissions'][$period] ?? 0);
        $dischargesData = $periods->map(fn($period) => $groups['discharges'][$period] ?? 0);

        // 6) Формируем массив, который передадим в представление
        $charts = [
            [
                'name'   => 'Принятые пациенты',
                'values' => $admissionsData,
                'labels' => $formattedDates,
                'color'  => 'rgba(75, 192, 192, 0.6)', // можно задать цвет (прозрачный)
            ],
            [
                'name'   => 'Выписанные пациенты',
                'values' => $dischargesData,
                'labels' => $formattedDates,
                'color'  => 'rgba(255, 99, 132, 0.6)',
            ],
        ];

        //yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
        // 1) Получаем данные по группировке «месяцы» точно так же, как в Orchid-экране
        $grouping = app(ApiService::class)
            ->getGrouping(['grouping' => 'month']);

        // 2) Собираем все уникальные ключи месяцев (формат YYYY-MM)
        $allMonths = array_unique(array_merge(
            array_keys($grouping['admissions']),
            array_keys($grouping['discharges']),
        ));
        sort($allMonths, SORT_STRING);

        // 3) Если данных исторических очень мало — формируем предупреждение
        $message = null;
        if (count($allMonths) < 6) {
            $message = 'Предсказания могут быть не точными из-за недостаточного количества тестовых данных! ' .
                'Добавьте больше исторических записей для более точных прогнозов.';
        }

        // 4) Подготавливаем массивы выборок (features) и меток (labels) для обучения
        $admissionsSamples = [];
        $admissionsLabels  = [];

        $dischargesSamples = [];
        $dischargesLabels  = [];

        foreach ($allMonths as $index => $monthStr) {
            // monthStr — "2024-01", "2024-02" и т. д.
            $dateObj = \DateTime::createFromFormat('Y-m', $monthStr);
            if (!$dateObj) {
                continue;
            }
            $year  = (int)$dateObj->format('Y');
            $month = (int)$dateObj->format('n');
            // Используем индекс, чтобы передавать в модель «порядок» месяца
            $sample = [$year, $month, $index];

            $admissionsSamples[] = $sample;
            $admissionsLabels[]  = $grouping['admissions'][$monthStr] ?? 0;

            $dischargesSamples[] = $sample;
            $dischargesLabels[]  = $grouping['discharges'][$monthStr] ?? 0;
        }

        // 5) Обучаем две модели (отдельно для поступлений и для выписок)
        $admissionModel = new GradientBoost();
        $dischargeModel = new GradientBoost();

        $admissionModel->train(new Labeled($admissionsSamples, $admissionsLabels));
        $dischargeModel->train(new Labeled($dischargesSamples, $dischargesLabels));

        // 6) Генерируем прогнозы на следующие 12 месяцев
        $futurePredictions = [];
        // Последний месяц из массива $allMonths
        $lastMonthStr = end($allMonths);
        $lastDateObj  = \DateTime::createFromFormat('Y-m', $lastMonthStr);

        for ($i = 1; $i <= 12; $i++) {
            $futureDate = (clone $lastDateObj)->modify("+$i month");
            $futureKey  = $futureDate->format('Y-m'); // например, "2025-07"
            $year       = (int)$futureDate->format('Y');
            $month      = (int)$futureDate->format('n');
            // Индекс для будущего sample = (количество исторических месяцев) + i
            $index = count($allMonths) + $i;

            $feature = [[$year, $month, $index]];
            $admission  = round($admissionModel->predict(new Unlabeled($feature))[0]);
            $discharge  = round($dischargeModel->predict(new Unlabeled($feature))[0]);

            $futurePredictions[] = [
                'month'       => $futureKey,
                'admissions'  => (int)$admission,
                'discharges'  => (int)$discharge,
            ];
        }

        // 7) Возвращаем Blade-шаблон и передаём туда:
        //    - текущую группировку (чтобы сбросить значение select)
        //    - сформированные данные для Chart.js
        return view('analytics.analytics', [
            'grouping' => $groupingType,
            'charts'   => $charts,
            'predictions' => $futurePredictions,
            'message'     => $message,
        ]);
    }
}
