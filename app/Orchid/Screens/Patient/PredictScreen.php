<?php

namespace App\Orchid\Screens\Patient;

use App\Orchid\Layouts\Patient\PredictListLayout;
use App\Services\Api\ApiService;
use Exception;
use Orchid\Screen\Components\Cells\Text;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use phpDocumentor\Reflection\Types\Integer;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Regressors\GradientBoost;

class PredictScreen extends Screen
{
    public ?string $message = null;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     * @throws Exception
     */
    public function query(): iterable
    {
        $grouping = app(ApiService::class)->getGrouping(['grouping' => 'month']);

        // Собираем уникальные месяцы
        $allMonths = array_unique(array_merge(
            array_keys($grouping['admissions']),
            array_keys($grouping['discharges']),
        ));
        sort($allMonths);

        if (count($allMonths) < 6) {
            $this->message = 'Предсказания могут быть не точными из-за недостаточного количества тестовых данных! Добавьте больше данных в систему учета для более точных предсказаний.';
        }

        $admissionsSamples = [];
        $admissionsLabels = [];

        $dischargesSamples = [];
        $dischargesLabels = [];

        foreach ($allMonths as $monthStr) {
            $date = \DateTime::createFromFormat('Y-m', $monthStr);
            $timestamp = $date->getTimestamp();
            $year = (int)$date->format('Y');
            $month = (int)$date->format('n');
            $index = array_search($monthStr, $allMonths); // Признак — порядок

            $admissionsSamples[] = [$year, $month, $index];
            $admissionsLabels[] = $grouping['admissions'][$monthStr] ?? 0;

            $dischargesSamples[] = [$year, $month, $index];
            $dischargesLabels[] = $grouping['discharges'][$monthStr] ?? 0;
        }

        // Обучение
        $admissionModel = new GradientBoost();
        $dischargeModel = new GradientBoost();

        $admissionModel->train(new Labeled($admissionsSamples, $admissionsLabels));
        $dischargeModel->train(new Labeled($dischargesSamples, $dischargesLabels));

        // Генерация 12 будущих месяцев
        $futurePredictions = [];
        $lastDate = \DateTime::createFromFormat('Y-m', end($allMonths));

        for ($i = 1; $i <= 12; $i++) {
            $futureDate = (clone $lastDate)->modify("+$i month");
            $futureKey = $futureDate->format('Y-m');
            $year = (int)$futureDate->format('Y');
            $month = (int)$futureDate->format('n');
            $index = count($allMonths) + $i;

            $sample = new Unlabeled([[$year, $month, $index]]);

            $admission = round($admissionModel->predict($sample)[0]);
            $discharge = round($dischargeModel->predict($sample)[0]);

            $futurePredictions[] = [
                'month' => $futureKey,
                'admissions' => (int)$admission,
                'discharges' => (int)$discharge,
            ];
        }

        return [
            'predict' => $futurePredictions,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Предсказания данных';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return "Предсказания количества поступивших и выписанных пациентов санатория 'Журавлик' на ближайшие 12 месяцев. {$this->message}";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            PredictListLayout::class,
        ];
    }
}
