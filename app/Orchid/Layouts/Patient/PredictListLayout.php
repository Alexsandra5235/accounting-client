<?php

namespace App\Orchid\Layouts\Patient;

use DateTime;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PredictListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'predict';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('month', 'Месяц')
                ->sort()
                ->filter('', 'month')
                ->render(function ($predict) {
                    $dateTime = DateTime::createFromFormat('Y-m', $predict['month']);
                    return $dateTime ? $dateTime->format('F Y') : $predict['month'];
                }),
            TD::make('admissions', 'Поступления')
                ->sort()
                ->filter('', 'admissions')
                ->render(function ($predict) {
                    return $predict['admissions'][0];
                }),
            TD::make('discharges', 'Выписки')
                ->sort()
                ->filter('', 'discharges')
                ->render(function ($predict) {
                    return $predict['discharges'][0];
                }),
        ];
    }
}
