@push('styles')
    @vite('resources/css/log-show.css')
@endpush

@push('scripts')
    @vite('resources/js/history.js')
@endpush

<x-app-layout>
    <div class="log-show-page">
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Заголовок и фильтры -->
            <div class="card">
                <div class="card-header">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-clock-rotate-left text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">История взаимодействия с системой</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Хронология действий пользователей в системе
                                </p>
                            </div>
                        </div>

                        <!-- Фильтры -->
                        <form method="GET" action="{{ route('history') }}" id="filterForm" class="flex gap-2">
                            <select name="action" id="actionFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="padding-right: 35px">
                                <option value="all" {{ request('action') == 'all' ? 'selected' : '' }}>Все действия</option>
                                <option value="add" {{ request('action') == 'add' ? 'selected' : '' }}>Создание</option>
                                <option value="edit" {{ request('action') == 'edit' ? 'selected' : '' }}>Редактирование</option>
                                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Удаление</option>
                            </select>

                            <select name="date" id="dateFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" {{ request('date') == 'all' ? 'selected' : '' }}>За все время</option>
                                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Сегодня</option>
                                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>Последние 7 дней</option>
                                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>Последние 30 дней</option>
                            </select>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter mr-2"></i>
                                Применить
                            </button>

                            <a href="{{ route('history') }}" class="btn btn-outline">
                                <i class="fas fa-undo mr-2"></i>
                                Сбросить
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Таймлайн событий -->
            <div class="card">
                <div class="card-body">
                    @if($history->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-history text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">История взаимодействий пуста</h3>
                            <p class="text-gray-500">Пока не было зарегистрировано ни одного действия в системе</p>
                        </div>
                    @else
                        <!-- Поиск -->
                        <div class="mb-6">
                            <form method="GET" action="{{ route('history') }}" id="searchForm">
                                <input type="hidden" name="action" value="{{ request('action', 'all') }}">
                                <input type="hidden" name="date" value="{{ request('date', 'all') }}">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text"
                                           name="search"
                                           id="searchInput"
                                           value="{{ request('search') }}"
                                           placeholder="Поиск по событиям, пользователям или записям..."
                                           class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </form>
                        </div>

                        <!-- Таймлайн -->
                        <div class="relative" id="timelineContainer">
                            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-200 via-purple-200 to-pink-200"></div>

                            <div class="space-y-6" id="timelineItems">
                                @foreach($history as $index => $item)
                                    @php
                                        $actionValue = $item->action?->value ?? 'default';

                                        $actionColor = match($actionValue) {
                                            'add' => 'green',
                                            'edit' => 'blue',
                                            'delete' => 'red',
                                            default => 'gray'
                                        };

                                        $actionIcon = match($actionValue) {
                                            'add' => 'fa-plus-circle',
                                            'edit' => 'fa-edit',
                                            'delete' => 'fa-trash-alt',
                                            default => 'fa-circle'
                                        };

                                        $actionName = $item->action ? $item->action->getEnum()->message() : 'Неизвестное действие';
                                        $itemDate = \Carbon\Carbon::parse($item->created_at);
                                    @endphp
                                    <div class="timeline-item relative flex items-start group">
                                        <!-- Иконка события -->
                                        <div class="relative z-10">
                                            <div class="w-16 h-16 rounded-full bg-{{ $actionColor }}-100 flex items-center justify-center border-4 border-white shadow-md group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas {{ $actionIcon }} text-{{ $actionColor }}-600 text-xl"></i>
                                            </div>
                                        </div>

                                        <!-- Контент события -->
                                        <div class="flex-1 ml-6 bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                                                <div class="flex items-center gap-3">
                                                    <span class="px-3 py-1.5 bg-{{ $actionColor }}-100 text-{{ $actionColor }}-800 text-xs font-medium rounded-full">
                                                        <i class="fas {{ $actionIcon }} mr-1"></i>
                                                        {{ $actionName }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        {{ $itemDate->locale('ru')->translatedFormat('d M Y, H:i') }}
                                                    </span>
                                                </div>

                                                @if($item->user_id && $item->user)
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-700">{{ $item->user->name ?? 'Пользователь' }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="text-gray-700 mb-4">
                                                {{ $item->action ? $item->action->getEnum()->fullMessage($item->log_id, $item->user_id) : 'Нет описания' }}
                                            </div>

                                            <div class="flex flex-wrap items-center gap-3">
                                                @if($item->log_id)
                                                    <a href="{{ route('log.find', ['id' => $item->log_id]) }}"
                                                       target="_blank"
                                                       class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg transition-colors">
                                                        <i class="fas fa-file-medical mr-2"></i>
                                                        Запись пациента: {{ $item->log()['patient']['name'] ?? 'Просмотр' }}
                                                    </a>
                                                @endif

                                                @if($item->user_id && $item->user)
                                                    <button onclick='openUserModal(@json($item->user))'
                                                            class="inline-flex items-center px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 text-sm font-medium rounded-lg transition-colors">
                                                        <i class="fas fa-user-circle mr-2"></i>
                                                        Информация о сотруднике
                                                    </button>
                                                @endif

                                                @if($item->diff)
                                                    <button onclick='openChangesModal(@json($item->diff))'
                                                            class="inline-flex items-center px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium rounded-lg transition-colors">
                                                        <i class="fas fa-code-branch mr-2"></i>
                                                        Просмотр изменений
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ПАГИНАЦИЯ -->
                        <div class="flex items-center justify-between mt-8">
                            <div class="text-sm text-gray-600">
                                @if($history->total() > 0)
                                    Показано с {{ $history->firstItem() }} по {{ $history->lastItem() }}
                                    из {{ $history->total() }} записей
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if ($history->onFirstPage())
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                                        <i class="fas fa-chevron-left"></i> Назад
                                    </span>
                                @else
                                    <a href="{{ $history->previousPageUrl() }}"
                                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                                        <i class="fas fa-chevron-left"></i> Назад
                                    </a>
                                @endif

                                <span class="px-3 py-1 bg-blue-600 text-white rounded-md">
                                    {{ $history->currentPage() }} / {{ $history->lastPage() }}
                                </span>

                                @if ($history->hasMorePages())
                                    <a href="{{ $history->nextPageUrl() }}"
                                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                                        Вперед <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-md cursor-not-allowed">
                                        Вперед <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Модальные окна (оставляем без изменений) -->
    <div id="userInfoModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-all" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-white shadow-lg flex items-center justify-center">
                            <i class="fas fa-user-circle text-blue-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Информация о сотруднике</h3>
                            <p class="text-blue-100 text-sm" id="userModalSubtitle">Данные пользователя</p>
                        </div>
                    </div>
                    <button onclick="closeUserModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="userModalContent" class="space-y-4"></div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                <button onclick="closeUserModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i> Закрыть
                </button>
                <a id="editUserProfileLink" href="#" target="_blank" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-edit"></i> Редактировать
                </a>
            </div>
        </div>
    </div>

    <div id="changesInfoModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-all" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-white shadow-lg flex items-center justify-center">
                            <i class="fas fa-code-branch text-amber-600 text-3xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Детали изменений</h3>
                            <p class="text-amber-100 text-sm">Просмотр изменений в записи</p>
                        </div>
                    </div>
                    <button onclick="closeChangesModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                <div id="changesModalContent" class="space-y-4"></div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button onclick="closeChangesModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-check"></i> Закрыть
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
