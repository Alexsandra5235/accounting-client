<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Regressors\GradientBoost;

class PredictController extends Controller
{
    /**
     * Отображает веб-страницу с прогнозом на ближайшие 12 месяцев.
     *
     * @return View
     * @throws Exception
     */
    public function index(): View
    {
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

        // 7) Отдаём данные в представление
        return view('predict.index', [
            'predictions' => $futurePredictions,
            'message'     => $message,
        ]);
    }
}
