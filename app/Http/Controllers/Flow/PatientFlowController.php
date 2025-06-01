<?php

namespace App\Http\Controllers\Flow;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PatientFlowController extends Controller
{
    /**
     * Отображает страницу с графиком потока пациентов.
     * @throws Exception
     */
    public function index(Request $request): View
    {
        // 1) Получаем параметр группировки из query-string (или 'day' по умолчанию)
        $grouping = $request->query('grouping', 'day');

        // 2) Вызываем тот же ApiService, что и в Orchid-экране, чтобы получить данные.
        //    Предполагается, что у вас есть mapped route в ApiService: getGrouping(['grouping' => ...])
        $groups = app(ApiService::class)
            ->getGrouping(['grouping' => $grouping]);

        // 3) Собираем все даты (ключи) из admissions и discharges,
        //    чтобы обеспечить, что даже если в каком-то дне/месяце нет данных, точка все равно попадёт на график.
        $periods = collect($groups->get('admissions'))->keys()
            ->merge(collect($groups->get('discharges'))->keys())
            ->unique()
            ->sort()
            ->values();

        // 4) Форматируем даты для отображения (для легенды графика или оси X)
        $formattedDates = $periods->map(function ($date) use ($grouping) {
            return match ($grouping) {
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

        // 7) Возвращаем Blade-шаблон и передаём туда:
        //    - текущую группировку (чтобы сбросить значение select)
        //    - сформированные данные для Chart.js
        return view('analytics.analytics', [
            'grouping' => $grouping,
            'charts'   => $charts,
        ]);
    }
}
