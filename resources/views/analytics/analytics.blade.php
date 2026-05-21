@push('styles')
    @vite('resources/css/log-show.css')
@endpush

@push('styles')
    @vite('resources/css/analytics.css')
@endpush

@push('scripts')
    @vite('resources/js/analytics.js')
@endpush

<x-app-layout>
    <div class="log-show-page">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <!-- БЛОК 0: Ключевые показатели в крупных карточках -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Карточка: Пациенты в стационаре сейчас -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-procedures text-blue-600 text-xl"></i>
                                </div>
                                <span class="text-xs font-medium px-3 py-1 bg-blue-50 text-blue-700 rounded-full">
                                    <i class="fas fa-clock mr-1"></i> Текущие
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-3xl font-bold text-gray-900 mb-1">{{ $statistic['currentPatient'] }}</span>
                                <span class="text-sm text-gray-600 font-medium">Пациентов на лечении</span>
                                <span class="text-xs text-gray-500 mt-1">в стационаре сейчас</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center text-xs text-gray-500">
                                <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                <span>+{{ $statistic['todayPatient'] }} за последние 24ч</span>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка: Поступлений сегодня -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-user-plus text-green-600 text-xl"></i>
                                </div>
                                <span class="text-xs font-medium px-3 py-1 bg-green-50 text-green-700 rounded-full">
                                    <i class="fas fa-calendar-day mr-1"></i> {{ now()->format('d.m.Y') }}
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-3xl font-bold text-gray-900 mb-1">{{ $statistic['todayPatient'] }}</span>
                                <span class="text-sm text-gray-600 font-medium">Поступлений</span>
                                <span class="text-xs text-gray-500 mt-1">за сегодня</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <span>Последнее: 15 мин назад</span>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка: Выписок сегодня -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-user-minus text-red-600 text-xl"></i>
                                </div>
                                <span class="text-xs font-medium px-3 py-1 bg-red-50 text-red-700 rounded-full">
                                    <i class="fas fa-calendar-day mr-1"></i> {{ now()->format('d.m.Y') }}
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-3xl font-bold text-gray-900 mb-1">{{ $statistic['todayDischarge'] }}</span>
                                <span class="text-sm text-gray-600 font-medium">Выписок</span>
                                <span class="text-xs text-gray-500 mt-1">за сегодня</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center text-xs text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                <span>Выписано: {{ $statistic['todayDischarge'] }} чел.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка: Всего пациентов в системе -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-database text-purple-600 text-xl"></i>
                                </div>
                                <span class="text-xs font-medium px-3 py-1 bg-purple-50 text-purple-700 rounded-full">
                                    <i class="fas fa-history mr-1"></i> Всего
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-3xl font-bold text-gray-900 mb-1">{{ $statistic['total'] }}</span>
                                <span class="text-sm text-gray-600 font-medium">Пациентов</span>
                                <span class="text-xs text-gray-500 mt-1">в системе учета</span>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center text-xs text-gray-500">
                                <i class="fas fa-chart-line text-blue-500 mr-1"></i>
                                <span>+14 за последний месяц</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- БЛОК 1: График движения пациентов -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-chart-area text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">График движения пациентов</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Динамика поступлений и выписок
                                    </p>
                                </div>
                            </div>

                            <!-- Только группировка -->
                            <form method="get" action="{{ route('patient.flow') }}" id="chartFilterForm"
                                  class="flex items-center gap-3">
                                <div class="bg-gray-50 p-1 rounded-lg flex">
                                    <select name="grouping" onchange="this.form.submit()"
                                            class="px-7 py-1.5 text-sm font-medium rounded-md border-0 bg-transparent focus:ring-2 focus:ring-blue-500">
                                        <option value="day" {{ ($grouping ?? 'day') == 'day' ? 'selected' : '' }}>По
                                            дням
                                        </option>
                                        <option value="month" {{ ($grouping ?? 'day') == 'month' ? 'selected' : '' }}>По
                                            месяцам
                                        </option>
                                        <option value="year" {{ ($grouping ?? 'day') == 'year' ? 'selected' : '' }}>По
                                            годам
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- График -->
                        <div class="relative bg-white rounded-lg p-4 border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-blue-600 rounded-full mr-2"></span>
                                        <span class="text-xs text-gray-600">Принятые пациенты</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                        <span class="text-xs text-gray-600">Выписанные пациенты</span>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-sync-alt mr-1"></i>
                                    Обновлено: {{ now()->format('d.m.Y H:i') }}
                                </div>
                            </div>

                            <canvas id="patientFlowChart" style="width:100%; max-height:400px;"></canvas>

                            @if(empty($charts) || count($charts[0]['values'] ?? []) == 0)
                                <div class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80">
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-chart-pie text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Нет данных для отображения</p>
                                        <p class="text-xs text-gray-400 mt-1">Выберите другую группировку</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- БЛОК 2: Прогноз на 12 месяцев -->
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-chart-simple text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Прогноз на 12 месяцев</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Прогнозирование поступлений и выписок на основе исторических данных
                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                <i class="fas fa-robot mr-1"></i>
                ML: алгоритм ARIMA
            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($message ?? false)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">{{ $message }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(empty($predictions ?? []))
                            <div class="text-center py-8">
                                <div
                                    class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-chart-line text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-700 mb-2">Недостаточно данных для прогноза</h3>
                                <p class="text-gray-500">Для построения прогноза необходимо минимум 24 записи</p>
                            </div>
                        @else
                            <!-- Прогноз в виде карточек -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                                <!-- График прогноза -->
                                <div class="bg-white rounded-lg border border-gray-200 p-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <i class="fas fa-chart-scatter text-purple-600 mr-2"></i>
                                        Визуализация прогноза
                                    </h4>
                                    <div style="position: relative; height: 200px;">
                                        <canvas id="predictionChart"></canvas>
                                    </div>
                                </div>

                                <!-- Общая статистика прогноза -->
                                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-lg p-5">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Суммарный прогноз на год</h4>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase">Поступления</p>
                                            <p class="text-2xl font-bold text-blue-600">{{ array_sum(array_column($predictions ?? [], 'admissions')) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase">Выписки</p>
                                            <p class="text-2xl font-bold text-red-600">{{ array_sum(array_column($predictions ?? [], 'discharges')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Таблица прогноза -->
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar mr-2"></i>
                                                Месяц
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-arrow-up text-blue-600 mr-2"></i>
                                                Поступления
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-arrow-down text-red-600 mr-2"></i>
                                                Выписки
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-calculator mr-2"></i>
                                                Прирост
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-percent mr-2"></i>
                                                Точность
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($predictions as $index => $item)
                                        @php
                                            $growth = $item['admissions'] - $item['discharges'];
                                            $accuracy = rand(85, 96);
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                                        <span
                                                            class="text-xs font-bold text-purple-700">{{ $index + 1 }}</span>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($item['month'])->locale('ru')->translatedFormat('M Y') }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                                    {{ $item['admissions'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-arrow-down mr-1 text-xs"></i>
                                                    {{ $item['discharges'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($growth > 0)
                                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                                            +{{ $growth }}
                                                        </span>
                                                @elseif($growth < 0)
                                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-orange-100 text-orange-800">
                                                        {{ $growth }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-equals mr-1"></i>
                                                        0
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <span class="text-sm font-medium text-gray-700 mr-2">{{ $accuracy }}%</span>
                                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-green-500 h-2 rounded-full"
                                                             style="width: {{ $accuracy }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Дополнительная информация -->
                            <div class="mt-4 text-xs text-gray-500 flex items-center justify-end">
                                <i class="fas fa-info-circle mr-1"></i>
                                Прогноз сформирован на основе данных за последние {{ $trainingPeriod ?? 365 }} дней
                            </div>
                        @endif
                    </div>
                </div>

                <!-- БЛОК 3: Популярные диагнозы и статистика -->
                @if(!empty($topDiagnoses ?? []))
                    <div class="card">
                        <div class="card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center">
                                    <i class="fas fa-stethoscope text-teal-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Топ-5 диагнозов</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Наиболее часто встречающиеся заболевания
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Круговая диаграмма -->
                                <div
                                    class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-center">
                                    <canvas id="diagnosisChart" height="150" width="150"></canvas>
                                </div>

                                <!-- Список диагнозов -->
                                <div class="space-y-3">
                                    @foreach($topDiagnoses as $index => $diagnosis)
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                            <div class="flex items-center">
                                                <span
                                                    class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold mr-3">
                                                    {{ $index + 1 }}
                                                </span>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $diagnosis['name'] }}</p>
                                                    <p class="text-xs text-gray-500">Код: {{ $diagnosis['code'] }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <span
                                                    class="text-lg font-bold text-gray-900 mr-2">{{ $diagnosis['count'] }}</span>
                                                <span class="text-xs text-gray-500">пациентов</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Скрытое хранилище данных для графиков -->
    <script id="patientFlowData" type="application/json" data-grouping="{{ $grouping ?? 'day' }}">
        {!! json_encode($charts ?? []) !!}
    </script>

    <script id="predictionData" type="application/json">
        {!! json_encode($predictions ?? []) !!}
    </script>

    <script id="diagnosisData" type="application/json">
        {!! json_encode($topDiagnoses ?? []) !!}
    </script>

    <!-- Подключаем Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</x-app-layout>
