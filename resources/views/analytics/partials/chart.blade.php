<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('График движения пациентов санатория') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Выберите тип группировки данных движения пациентов для отображения на графике.") }}
        </p>
    </header>

    <form class="mt-6 space-y-6" method="get" action="{{ route('patient.flow') }}">
        @csrf
        @method('get')

        <div>
            <x-input-label for="grouping" :value="__('Группировать по:')" />
            <x-select id="grouping" name="grouping" class="mt-1 block w-full">
                <option value="day"   {{ $grouping === 'day'   ? 'selected' : '' }}>Дням</option>
                <option value="month" {{ $grouping === 'month' ? 'selected' : '' }}>Месяцам</option>
                <option value="year"  {{ $grouping === 'year'  ? 'selected' : '' }}>Годам</option>
            </x-select>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Применить') }}</x-primary-button>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <canvas id="patientFlowChart" height="100"></canvas>
        </div>

        <script id="patientFlowData" type="application/json" data-grouping="{{ $grouping }}">
            {!! json_encode($charts) !!}
        </script>
    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('patientFlowChart');
            if (!canvas) {
                return;
            }

            const dataEl = document.getElementById('patientFlowData');
            let chartsData;
            try {
                chartsData = JSON.parse(dataEl.textContent);
            } catch (e) {
                console.error('Не удалось распарсить JSON c данными для графика:', e);
                return;
            }

            const grouping = dataEl.dataset.grouping || 'day';

            const labels = Array.isArray(chartsData) && chartsData.length
                ? chartsData[0].labels
                : [];

            const datasets = chartsData.map(item => ({
                label: item.name,
                data: item.values,
                backgroundColor: item.color || 'rgba(0, 0, 0, 0.1)',
                borderColor: item.color || 'rgba(0, 0, 0, 0.4)',
                fill: false,
                tension: 0.1,
            }));

            const config = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets,
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    stacked: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Поток пациентов',
                            font: {
                                size: 18
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                        legend: {
                            position: 'top',
                        },
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: grouping === 'day'
                                    ? 'Даты'
                                    : (grouping === 'month' ? 'Месяцы' : 'Годы')
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Количество пациентов'
                            },
                            beginAtZero: true,
                            suggestedMax: Math.max(...datasets.flatMap(d => d.data)) + 5
                        }
                    }
                }
            };

            new Chart(canvas.getContext('2d'), config);
        });
    })();
</script>
