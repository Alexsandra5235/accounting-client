<x-app-layout>
    <!-- Ошибки -->
    @error('error_excel')
    <div class="card mb-6 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-red-800">Ошибка формирования отчета</h4>
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.parentElement.remove()"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @enderror

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Форма 1: Лист ежедневного учета -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between w-full">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                            Лист ежедневного учета (Учетная форма N 007/у)
                        </h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            <i class="fas fa-file-excel mr-1"></i> XLSX
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">
                        Формирование детализированного отчета о движении пациентов за указанный период
                    </p>
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route('excel.download') }}" class="space-y-6">
                        @csrf
                        @method('post')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="daily_date1" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-plus mr-1 text-blue-600"></i>
                                    Дата начала
                                </label>
                                <input type="date"
                                       id="daily_date1"
                                       name="date1"
                                       value="{{ old('date1') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>
                                @error('date1')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="daily_date2" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-check mr-1 text-blue-600"></i>
                                    Дата окончания
                                </label>
                                <input type="date"
                                       id="daily_date2"
                                       name="date2"
                                       value="{{ old('date2') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       required>
                                @error('date2')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-gray-200">
                            <div class="flex gap-3">
                                <button type="submit" name="action" value="download" class="btn btn-primary">
                                    <i class="fas fa-download mr-2"></i>
                                    Скачать отчет
                                </button>
                                <button type="submit" name="action" value="open" formtarget="_blank" class="btn btn-outline">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Открыть в новом окне
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Форма 2: Сводная ведомость -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between w-full">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar text-green-600"></i>
                            Сводная ведомость учета (Учетная форма N 016/у)
                        </h3>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            <i class="fas fa-file-excel mr-1"></i> XLSX
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">
                        Формирование статистического отчета с группировкой по диагнозам и исходам
                    </p>
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route('excel.download.summary') }}" class="space-y-6">
                        @csrf
                        @method('post')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="summary_date1" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-plus mr-1 text-green-600"></i>
                                    Дата начала
                                </label>
                                <input type="date"
                                       id="summary_date1"
                                       name="date1"
                                       value="{{ old('date1') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                       required>
                                @error('date1')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="summary_date2" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-calendar-check mr-1 text-green-600"></i>
                                    Дата окончания
                                </label>
                                <input type="date"
                                       id="summary_date2"
                                       name="date2"
                                       value="{{ old('date2') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                       required>
                                @error('date2')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-gray-200">
                            <div class="flex gap-3">
                                <button type="submit" name="action" value="download" class="btn btn-primary bg-green-600 hover:bg-green-700 focus:ring-green-500">
                                    <i class="fas fa-download mr-2"></i>
                                    Скачать отчет
                                </button>
                                <button type="submit" name="action" value="open" formtarget="_blank" class="btn btn-outline border-green-600 text-green-600 hover:bg-green-600 hover:text-white">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Открыть в новом окне
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-clock-rotate-left text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">История отчетов</h4>
                                <p class="text-sm text-gray-600">Просмотр ранее сформированных документов</p>
                                <a href="{{ route('history.report') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 mt-2">
                                    Перейти в историю
                                    <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-gradient-to-r from-purple-50 to-pink-50 border-purple-200">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-question-circle text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Помощь</h4>
                                <p class="text-sm text-gray-600">Информация о форматах отчетов</p>
                                <button onclick="showHelpModal()" class="inline-flex items-center text-sm text-purple-600 hover:text-purple-800 mt-2">
                                    Показать подсказку
                                    <i class="fas fa-info-circle ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно помощи -->
    <div id="helpModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <button class="modal-close" onclick="closeHelpModal()">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-excel text-green-600 mr-2"></i>
                Информация об отчетах
            </h3>

            <div class="space-y-4">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Лист ежедневного учета
                    </h4>
                    <p class="text-sm text-gray-700">Учетная форма N 007/у "Лист ежедневного учета движения пациентов и коечного фонда медицинской организации, оказывающей медицинскую помощь в стационарных условиях, в условиях дневного стационара"</p>

                    <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                        <li>Полная информация о поступлении</li>
                        <li>Данные пациента и диагнозы</li>
                        <li>Исходы госпитализации</li>
                        <li>Сортировка по дате поступления</li>
                    </ul>
                </div>

                <div class="p-4 bg-green-50 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Сводная ведомость
                    </h4>
                    <p class="text-sm text-gray-700">Учетная форма N 016/у "Сводная ведомость учета движения пациентов и коечного фонда медицинской организации, оказывающей медицинскую помощь в стационарных условиях, в условиях дневного стационара"</p>
                    <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                        <li>Количество поступлений по дням</li>
                        <li>Распределение по диагнозам (МКБ)</li>
                        <li>Статистика исходов</li>
                        <li>Средний возраст пациентов</li>
                        <li>Графики и диаграммы</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="closeHelpModal()" class="btn btn-primary">
                    <i class="fas fa-check mr-2"></i>
                    Понятно
                </button>
            </div>
        </div>
    </div>

    <script>
        function showHelpModal() {
            document.getElementById('helpModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeHelpModal() {
            document.getElementById('helpModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Валидация дат
        document.addEventListener('DOMContentLoaded', function() {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            const today = new Date().toISOString().split('T')[0];

            dateInputs.forEach(input => {
                input.max = today;

                input.addEventListener('change', function() {
                    const form = this.closest('form');
                    const date1 = form.querySelector('input[name="date1"]');
                    const date2 = form.querySelector('input[name="date2"]');

                    if (date1 && date2 && date1.value && date2.value) {
                        if (date2.value < date1.value) {
                            alert('Дата окончания не может быть раньше даты начала');
                            date2.value = date1.value;
                        }

                        // Проверка на период > 31 дня
                        const start = new Date(date1.value);
                        const end = new Date(date2.value);
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));


                    }
                });
            });
        });
    </script>

    <style>
        /* Стили для модального окна */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 28px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: none;
            border: none;
            font-size: 20px;
            color: #6b7280;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .modal-close:hover {
            background: #f3f4f6;
            color: #374151;
        }

        @media (max-width: 640px) {
            .modal-content {
                padding: 20px;
                width: 95%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</x-app-layout>
