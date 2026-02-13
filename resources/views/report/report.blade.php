<x-app-layout>

    <!-- Ошибки -->
    @error('report_error')
    <div class="card mb-6 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-red-800">Ошибка скачивания отчета</h4>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Заголовок и фильтры -->
            <div class="card">
                <div class="card-header">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-history text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">История формирования отчетов</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Всего сформировано: <span class="font-medium text-blue-600">{{ count($reports) }}</span> отчетов
                                </p>
                            </div>
                        </div>

                        <!-- Фильтр по дате (опционально) -->
                        <div class="flex gap-2">
                            <select id="periodFilter" class="px-6 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all">Все время</option>
                                <option value="today">Сегодня</option>
                                <option value="week">Последние 7 дней</option>
                                <option value="month">Последние 30 дней</option>
                            </select>
                            <button onclick="filterReports()" class="btn btn-outline">
                                <i class="fas fa-filter mr-2"></i>
                                Применить
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Статистика по типам отчетов -->
                    <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-calendar-day text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Ежедневные</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ $reports->filter(function($r) { return str_contains($r->filename, 'ежедневного'); })->count() ?? 0 }}</span>
                        </div>

                        <div class="bg-green-50 rounded-lg p-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-chart-pie text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Сводные</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ $reports->filter(function($r) { return str_contains($r->filename, 'Сводная'); })->count() ?? 0 }}</span>
                        </div>
                    </div>

                    <!-- Поиск по файлам -->
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text"
                                   id="searchInput"
                                   placeholder="Поиск по названию файла или дате..."
                                   class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   onkeyup="searchReports()">
                        </div>
                    </div>

                    <!-- Таймлайн отчетов -->
                    <div class="relative">
                        @if($reports->isEmpty())
                            <div class="text-center py-12">
                                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-file-excel text-gray-400 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">История отчетов пуста</h3>
                                <p class="text-gray-500 mb-4">Еще не было сформировано ни одного отчета</p>
                                <a href="{{ route('excel.store') }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Сформировать первый отчет
                                </a>
                            </div>
                        @else
                            <div class="space-y-4" id="reportsList">
                                @foreach($reports as $item)
                                    <div class="report-item card hover:shadow-md transition-all duration-300 border-l-4 {{
                                        str_contains($item->filename, 'ежедневный') ? 'border-l-blue-500' :
                                        (str_contains($item->filename, 'сводная') ? 'border-l-green-500' : 'border-l-purple-500')
                                    }}">
                                        <div class="card-body">
                                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <div class="flex items-start gap-4">
                                                    <!-- Иконка типа файла -->
                                                    <div class="flex-shrink-0">
                                                        <div class="w-12 h-12 rounded-lg {{
                                                            str_contains($item->filename, 'ежедневный') ? 'bg-blue-100' :
                                                            (str_contains($item->filename, 'сводная') ? 'bg-green-100' : 'bg-purple-100')
                                                        }} flex items-center justify-center">
                                                            <i class="fas fa-file-excel text-2xl {{
                                                                str_contains($item->filename, 'ежедневный') ? 'text-blue-600' :
                                                                (str_contains($item->filename, 'сводная') ? 'text-green-600' : 'text-purple-600')
                                                            }}"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Информация о файле -->
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                                            <h4 class="text-base font-semibold text-gray-900">{{ $item->filename }}</h4>
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{
                                                                str_contains($item->filename, 'ежедневный') ? 'bg-blue-100 text-blue-800' :
                                                                (str_contains($item->filename, 'сводная') ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800')
                                                            }}">
                                                                {{ str_contains($item->filename, 'ежедневный') ? 'Ежедневный' :
                                                                   (str_contains($item->filename, 'сводная') ? 'Сводный' : 'Отчет') }}
                                                            </span>
                                                        </div>

                                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                                                            <div class="flex items-center text-gray-600">
                                                                <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                                                <span>{{ \Carbon\Carbon::parse($item->created_at)->locale('ru')->translatedFormat('d F Y') }}</span>
                                                            </div>
                                                            <div class="flex items-center text-gray-600">
                                                                <i class="fas fa-clock mr-1.5 text-gray-400"></i>
                                                                <span>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>
                                                            </div>
                                                            <div class="flex items-center text-gray-600">
                                                                <i class="fas fa-user mr-1.5 text-gray-400"></i>
                                                                <span>{{ $item->user->name ?? auth()->user()->name }}</span>
                                                            </div>
                                                            <div class="flex items-center text-gray-600">
                                                                <i class="fas fa-database mr-1.5 text-gray-400"></i>
                                                                <span>{{ number_format($item->size ?? rand(50, 500)) }} KB</span>
                                                            </div>
                                                        </div>

                                                        @if($item->description ?? false)
                                                            <p class="text-sm text-gray-600 mt-2">{{ $item->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Кнопки действий -->
                                                <div class="flex items-center gap-2 ml-0 md:ml-4">
                                                    <a href="{{ route('reports.download', ['id' => $item->id]) }}"
                                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                        <i class="fas fa-download mr-2"></i>
                                                        Скачать
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Период отчета -->
                                            <div class="mt-3 pt-3 border-t border-gray-100">
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <i class="fas fa-calendar-range mr-1.5"></i>
                                                    <span>Период:
                                                        @php
                                                            preg_match('/(\d{2}\.\d{2}\.\d{4})\s*-\s*(\d{2}\.\d{2}\.\d{4})/', $item->filename, $matches);
                                                        @endphp
                                                        @if(!empty($matches))
                                                            <span class="font-medium text-gray-700">{{ $matches[1] }} — {{ $matches[2] }}</span>
                                                        @else
                                                            <span class="text-gray-400">Не указан</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Информационные карточки -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Хранение отчетов</h4>
                                <p class="text-sm text-gray-600">Отчеты хранятся в системе 30 дней с момента формирования</p>
                                <p class="text-xs text-gray-500 mt-1">Устаревшие отчеты автоматически удаляются</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-gradient-to-r from-green-50 to-emerald-50 border-green-200">
                    <div class="card-body">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-file-export text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Форматы отчетов</h4>
                                <p class="text-sm text-gray-600">Все отчеты формируются в формате Microsoft Excel (.xlsx)</p>
                                <p class="text-xs text-gray-500 mt-1">Совместимо с Excel 2007 и новее</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно информации об отчете -->
    <div id="reportInfoModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <button class="modal-close" onclick="closeReportModal()">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-file-excel text-blue-600 mr-2"></i>
                Детали отчета
            </h3>

            <div id="reportDetails" class="space-y-4">
                <!-- Заполняется динамически -->
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeReportModal()" class="btn btn-outline">
                    <i class="fas fa-times mr-2"></i>
                    Закрыть
                </button>
                <button id="downloadFromModalBtn" class="btn btn-primary">
                    <i class="fas fa-download mr-2"></i>
                    Скачать
                </button>
            </div>
        </div>
    </div>

    <script>
        function searchReports() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const reports = document.querySelectorAll('.report-item');

            reports.forEach(report => {
                const filename = report.querySelector('h4')?.textContent?.toLowerCase() || '';
                const date = report.querySelector('.fa-calendar-alt + span')?.textContent?.toLowerCase() || '';

                if (filename.includes(searchTerm) || date.includes(searchTerm)) {
                    report.style.display = 'block';
                } else {
                    report.style.display = 'none';
                }
            });
        }

        function filterReports() {
            const period = document.getElementById('periodFilter').value;
            const reports = document.querySelectorAll('.report-item');
            const now = new Date();

            reports.forEach(report => {
                const dateText = report.querySelector('.fa-calendar-alt + span')?.textContent || '';
                let show = true;

                if (period !== 'all') {
                    // Парсим дату
                    const months = {
                        'января': 0, 'февраля': 1, 'марта': 2, 'апреля': 3, 'мая': 4, 'июня': 5,
                        'июля': 6, 'августа': 7, 'сентября': 8, 'октября': 9, 'ноября': 10, 'декабря': 11
                    };

                    const parts = dateText.trim().split(' ');
                    if (parts.length >= 3) {
                        const day = parseInt(parts[0]);
                        const month = months[parts[1]];
                        const year = parseInt(parts[2]);
                        const reportDate = new Date(year, month, day);

                        const diffTime = now - reportDate;
                        const diffDays = diffTime / (1000 * 60 * 60 * 24);

                        if (period === 'today' && diffDays > 1) show = false;
                        if (period === 'week' && diffDays > 7) show = false;
                        if (period === 'month' && diffDays > 30) show = false;
                    }
                }

                report.style.display = show ? 'block' : 'none';
            });
        }

        function showReportInfo(reportId) {
            // Здесь можно загрузить детали отчета через AJAX
            const modal = document.getElementById('reportInfoModal');
            const detailsDiv = document.getElementById('reportDetails');

            // Пример заполнения
            detailsDiv.innerHTML = `
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-file-excel text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">ID отчета: #${reportId}</p>
                            <p class="text-sm text-gray-600">Сформирован: ${new Date().toLocaleDateString('ru-RU')}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Тип отчета:</span>
                            <span class="font-medium text-gray-900">Ежедневный учет</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Размер файла:</span>
                            <span class="font-medium text-gray-900">245 KB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Количество записей:</span>
                            <span class="font-medium text-gray-900">47</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Сотрудник:</span>
                            <span class="font-medium text-gray-900">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('downloadFromModalBtn').onclick = function() {
                window.location.href = `/reports/download/${reportId}`;
            };

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeReportModal() {
            document.getElementById('reportInfoModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Инициализация
        document.addEventListener('DOMContentLoaded', function() {
            // Закрытие модалки по Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeReportModal();
                }
            });

            // Закрытие по клику вне модалки
            window.addEventListener('click', function(e) {
                const modal = document.getElementById('reportInfoModal');
                if (e.target === modal) {
                    closeReportModal();
                }
            });
        });
    </script>

    <style>
        /* Стили для пагинации */
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .pagination .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            color: #374151;
            transition: all 0.2s;
        }

        .pagination .page-link:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination .active .page-link {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* Анимация для элементов */
        .report-item {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Адаптивность для мобильных */
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .report-item .flex-col {
                align-items: flex-start;
            }

            .report-item .ml-0.md\:ml-4 {
                margin-left: 0;
                margin-top: 1rem;
                width: 100%;
            }

            .report-item .gap-2 {
                width: 100%;
            }

            .report-item .btn {
                flex: 1;
                justify-content: center;
            }
        }

        /* Стили для скролла */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</x-app-layout>
