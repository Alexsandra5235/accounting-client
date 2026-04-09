(function () {
    'use strict';

    // Инициализация графиков после загрузки страницы
    document.addEventListener('DOMContentLoaded', function () {
        initPatientFlowChart();
        initPredictionChart();
        initDiagnosisChart();
    });

    // Инициализация графика движения пациентов
    function initPatientFlowChart() {
        const canvas = document.getElementById('patientFlowChart');
        if (!canvas) return;

        // Уничтожаем существующий график
        let existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }

        const dataEl = document.getElementById('patientFlowData');
        if (!dataEl) return;

        let chartsData;
        try {
            chartsData = JSON.parse(dataEl.textContent);
        } catch (e) {
            console.error('Ошибка парсинга данных графика:', e);
            return;
        }

        if (!chartsData || chartsData.length === 0) {
            return;
        }

        // Преобразуем коллекции в массивы
        const processedData = chartsData.map(item => {
            // Проверяем, является ли values коллекцией
            let values = item.values;
            if (values && typeof values === 'object' && values.items !== undefined) {
                values = Object.values(values.items);
            } else if (values && typeof values === 'object' && !Array.isArray(values)) {
                values = Object.values(values);
            }

            // Проверяем, является ли labels коллекцией
            let labels = item.labels;
            if (labels && typeof labels === 'object' && labels.items !== undefined) {
                labels = Object.values(labels.items);
            } else if (labels && typeof labels === 'object' && !Array.isArray(labels)) {
                labels = Object.values(labels);
            }

            return {
                name: item.name,
                values: values || [],
                labels: labels || [],
                color: item.color
            };
        });

        const labels = processedData[0]?.labels || [];

        // Разделяем данные по типам
        let admissionsData = [];
        let dischargesData = [];
        let inpatientData = [];

        processedData.forEach(item => {
            if (item.name.includes('Принятые') || item.name.includes('Поступления')) {
                admissionsData = item.values;
            } else if (item.name.includes('Выписанные') || item.name.includes('Выписки')) {
                dischargesData = item.values;
            } else if (item.name.includes('В стационаре')) {
                inpatientData = item.values;
            }
        });

        const ctx = canvas.getContext('2d');

        // Формируем datasets
        const datasets = [];

        if (admissionsData.length > 0) {
            datasets.push({
                label: 'Поступления',
                data: admissionsData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                tension: 0.3,
                fill: false
            });
        }

        if (dischargesData.length > 0) {
            datasets.push({
                label: 'Выписки',
                data: dischargesData,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#ef4444',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                tension: 0.3,
                fill: false
            });
        }

        if (inpatientData.length > 0) {
            datasets.push({
                label: 'В стационаре',
                data: inpatientData,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#10b981',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                tension: 0.3,
                fill: false
            });
        }

        if (datasets.length === 0) return;

        try {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 800
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'white',
                            titleColor: '#111827',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function (context) {
                                    return ` ${context.dataset.label}: ${context.raw} чел.`;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {display: false, drawBorder: true, color: '#f3f4f6'},
                            ticks: {maxRotation: 45, minRotation: 45, font: {size: 11}}
                        },
                        y: {
                            beginAtZero: true,
                            grid: {color: '#f3f4f6'},
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: {size: 11}
                            },
                            title: {
                                display: true,
                                text: 'Количество пациентов',
                                font: {size: 12, weight: '500'},
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });

            canvas.style.height = '300px';

        } catch (e) {
            console.error('Ошибка при создании графика:', e);
        }
    }

    // Инициализация графика прогноза
    function initPredictionChart() {
        const canvas = document.getElementById('predictionChart');
        if (!canvas) return;

        let existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }

        const dataEl = document.getElementById('predictionData');
        if (!dataEl) return;

        let predictions;
        try {
            predictions = JSON.parse(dataEl.textContent);
        } catch (e) {
            return;
        }

        if (!predictions || predictions.length === 0) return;

        const labels = predictions.map(p => {
            const date = new Date(p.month);
            return date.toLocaleDateString('ru-RU', {month: 'short', year: '2-digit'});
        });

        const ctx = canvas.getContext('2d');

        try {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Поступления',
                            data: predictions.map(p => p.admissions),
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: '#3b82f6',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Выписки',
                            data: predictions.map(p => p.discharges),
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: '#ef4444',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 800
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {boxWidth: 12, font: {size: 11}}
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: {size: 11}
                            }
                        }
                    }
                }
            });

            canvas.style.height = '200px';

        } catch (e) {
            console.error('Ошибка при создании графика прогноза:', e);
        }
    }

    // Инициализация круговой диаграммы диагнозов
    function initDiagnosisChart() {
        const canvas = document.getElementById('diagnosisChart');
        if (!canvas) return;

        let existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }

        const dataEl = document.getElementById('diagnosisData');
        if (!dataEl) return;

        let diagnoses;
        try {
            diagnoses = JSON.parse(dataEl.textContent);
        } catch (e) {
            return;
        }

        if (!diagnoses || diagnoses.length === 0) return;

        const colors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444'];

        const ctx = canvas.getContext('2d');

        try {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: diagnoses.map(d => d.name.split(' ').slice(0, 2).join(' ') + '...'),
                    datasets: [{
                        data: diagnoses.map(d => d.count),
                        backgroundColor: colors.slice(0, diagnoses.length),
                        borderColor: 'white',
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: {
                        duration: 800
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {size: 11},
                                padding: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} чел. (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

        } catch (e) {
            console.error('Ошибка при создании диаграммы диагнозов:', e);
        }
    }
})();
