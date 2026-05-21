@push('styles')
    @vite('resources/css/log-show.css')
@endpush

@push('scripts')
    @vite('resources/js/report.js')
@endpush

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

    <div class="log-show-page">
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
                                        Всего сформировано: <span class="font-medium text-blue-600">{{ $reports->total() }}</span> отчетов
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

                                {{-- Пагинация --}}
                                @if($reports->hasPages())
                                    <div class="pagination-container mt-6">
                                        <div class="pagination-info">
                                        <span>
                                            Показано {{ $reports->firstItem() ?? 0 }}–{{ $reports->lastItem() ?? 0 }}
                                            из {{ $reports->total() }} отчетов
                                        </span>

                                            @if($reports->total() > $reports->perPage())
                                                <span>
                                                Страница {{ $reports->currentPage() }} из {{ $reports->lastPage() }}
                                            </span>
                                            @endif
                                        </div>

                                        @if($reports->hasPages())
                                            <div class="pagination-controls">
                                                {{-- Кнопка "Первая" --}}
                                                @if(!$reports->onFirstPage())
                                                    <a href="{{ $reports->url(1) }}" class="pagination-btn">
                                                        <i class="fas fa-angle-double-left"></i>
                                                    </a>
                                                @else
                                                    <span class="pagination-btn disabled">
                                                    <i class="fas fa-angle-double-left"></i>
                                                </span>
                                                @endif

                                                {{-- Кнопка "Назад" --}}
                                                @if($reports->onFirstPage())
                                                    <span class="pagination-btn disabled">
                                                    <i class="fas fa-angle-left"></i>
                                                </span>
                                                @else
                                                    <a href="{{ $reports->previousPageUrl() }}" class="pagination-btn">
                                                        <i class="fas fa-angle-left"></i>
                                                    </a>
                                                @endif

                                                {{-- Номера страниц --}}
                                                @php
                                                    $start = max(1, $reports->currentPage() - 2);
                                                    $end = min($start + 4, $reports->lastPage());
                                                    $start = max(1, $end - 4);
                                                @endphp

                                                @if($start > 1)
                                                    <a href="{{ $reports->url(1) }}" class="pagination-btn">1</a>
                                                    @if($start > 2)
                                                        <span class="pagination-ellipsis">...</span>
                                                    @endif
                                                @endif

                                                @for($page = $start; $page <= $end; $page++)
                                                    @if($page == $reports->currentPage())
                                                        <span class="pagination-btn active">{{ $page }}</span>
                                                    @else
                                                        <a href="{{ $reports->url($page) }}" class="pagination-btn">{{ $page }}</a>
                                                    @endif
                                                @endfor

                                                @if($end < $reports->lastPage())
                                                    @if($end < $reports->lastPage() - 1)
                                                        <span class="pagination-ellipsis">...</span>
                                                    @endif
                                                    <a href="{{ $reports->url($reports->lastPage()) }}" class="pagination-btn">
                                                        {{ $reports->lastPage() }}
                                                    </a>
                                                @endif

                                                {{-- Кнопка "Вперед" --}}
                                                @if($reports->hasMorePages())
                                                    <a href="{{ $reports->nextPageUrl() }}" class="pagination-btn">
                                                        <i class="fas fa-angle-right"></i>
                                                    </a>
                                                @else
                                                    <span class="pagination-btn disabled">
                                                    <i class="fas fa-angle-right"></i>
                                                </span>
                                                @endif

                                                {{-- Кнопка "Последняя" --}}
                                                @if($reports->hasMorePages())
                                                    <a href="{{ $reports->url($reports->lastPage()) }}" class="pagination-btn">
                                                        <i class="fas fa-angle-double-right"></i>
                                                    </a>
                                                @else
                                                    <span class="pagination-btn disabled">
                                                    <i class="fas fa-angle-double-right"></i>
                                                </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
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
    </div>
</x-app-layout>
