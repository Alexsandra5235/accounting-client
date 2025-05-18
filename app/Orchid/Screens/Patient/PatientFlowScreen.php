<?php

namespace App\Orchid\Screens\Patient;

use App\Orchid\Layouts\Chart\PatientFlowChart;
use App\Services\Api\ApiService;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Chart;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PatientFlowScreen extends Screen
{
    public string $name = 'Поток пациентов';

    public string $description = 'Диаграмма поступлений и выписок пациентов';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     * @throws Exception
     */
    public function query(string $request = null): iterable
    {
        $grouping = $request ?? 'day';

        $groups = app(ApiService::class)->getGrouping(['grouping' => $request] ?? ['grouping' => $grouping]);

        // Объединяем периоды чтобы все даты были в графике
        $periods = collect($groups->get('admissions'))->keys()
            ->merge(collect($groups->get('discharges'))->keys())
            ->unique()
            ->sort()
            ->values();

        // Готовим финальные массивы для графика
        $admissionsData = $periods->map(fn($period) => $groups['admissions'][$period] ?? 0);
        $dischargesData = $periods->map(fn($period) => $groups['discharges'][$period] ?? 0);

        return [
            'grouping' => $grouping,
            'charts' => [
                [
                    'name'   => 'Принятые пациенты',
                    'values' => $admissionsData,
                    'labels' => $periods,
                ],
                [
                    'name'   => 'Выписанные пациенты',
                    'values' => $dischargesData,
                    'labels' => $periods,
                ],
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Статистика санатория';
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
     * @throws Exception
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Select::make('grouping')
                    ->title('Группировать по')
                    ->options([
                        'day' => 'Дням',
                        'month' => 'Месяцам',
                        'year' => 'Годам',
                    ])
                    ->help('Выберите интервал для отображения данных')
                    ->value(request()->query('grouping'))
                    ->canSee(true),

                Button::make('Применить')
                    ->method('filter'), // Название метода будет "filter" (ниже)
            ]),

            PatientFlowChart::make('charts', 'Поток пациентов')
                ->description('Диаграмма показывает количество поступивших и выписанных пациентов.'),
        ];
    }

    public function filter(Request $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('platform.patient.flow', ['request' => $request->input('grouping', 'day')]);
    }
}
