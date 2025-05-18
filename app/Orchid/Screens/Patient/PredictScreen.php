<?php

namespace App\Orchid\Screens\Patient;

use App\Orchid\Layouts\Patient\PredictListLayout;
use App\Services\Api\ApiService;
use Exception;
use Orchid\Screen\Components\Cells\Text;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;

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
        $groups = app(ApiService::class)->getGrouping(['grouping' => 'month']);

        // Меняем данные на массивы для обучения
        $admissionsData = [];
        $dischargesData = [];
        $months = [];

        if (count($groups['admissions']) < 6 || count($groups['discharges']) < 6) {
            $this->message = 'Предсказания могут быть неточными из-за недостаточного количества тестовых данных!';
        }

        foreach ($groups['admissions'] as $month => $count) {
            $months[] = strtotime($month);
            $admissionsData[] = $count;
        }

        foreach ($groups['discharges'] as $month => $count) {
            $dischargesData[] = $count;
        }

        // Создаём модели для прогноза с использованием SVR
        $admissionModel = new SVR(Kernel::LINEAR);
        $dischargeModel = new SVR(Kernel::LINEAR);

        // Обучаем модели
        $admissionModel->train(array_map(function($month) { return [$month]; }, $months), $admissionsData);
        $dischargeModel->train(array_map(function($month) { return [$month]; }, $months), $dischargesData);

        $yearProjections = [];
        for ($i = 1; $i <= 12; $i++) {
            $futureMonth = strtotime('+' . $i . ' month', end($months));
            $monthYearKey = date('Y-m', $futureMonth);

            // Прогноз для данного месяца
            $yearProjections[] = [
                'month' => $monthYearKey,
                'admissions' => $admissionModel->predict([[$futureMonth]]),
                'discharges' => $dischargeModel->predict([[$futureMonth]]),
            ];
        }

        return [
            'predict' => $yearProjections,
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
