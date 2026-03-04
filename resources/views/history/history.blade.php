<x-app-layout>
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
                        <div class="flex gap-2" id="filterContainer">
                            <select id="actionFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="padding-right: 35px">
                                <option value="all">Все действия</option>
                                <option value="add">Создание</option>
                                <option value="edit">Редактирование</option>
                                <option value="delete">Удаление</option>
                            </select>
                            <select id="dateFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all">За все время</option>
                                <option value="today">Сегодня</option>
                                <option value="week">Последние 7 дней</option>
                                <option value="month">Последние 30 дней</option>
                            </select>
                            <button onclick="applyFilters()" class="btn btn-primary">
                                <i class="fas fa-filter mr-2"></i>
                                Применить
                            </button>
                            <button onclick="resetFilters()" class="btn btn-outline">
                                <i class="fas fa-undo mr-2"></i>
                                Сбросить
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Таймлайн событий -->
            <div class="card">
                <div class="card-body">
                    @if(empty($history) || count($history) === 0)
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
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text"
                                       id="searchInput"
                                       placeholder="Поиск по событиям, пользователям или записям..."
                                       class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                       onkeyup="searchHistory()">
                            </div>
                        </div>

                        <!-- Таймлайн -->
                        <div class="relative" id="timelineContainer">
                            <!-- Линия времени -->
                            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-200 via-purple-200 to-pink-200"></div>

                            <div class="space-y-6" id="timelineItems">
                                @foreach($history as $index => $item)
                                    @php
                                        $actionValue = $item->action->value;

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
                                    <div class="timeline-item relative flex items-start group"
                                         data-action="{{ $actionValue }}"
                                         data-date="{{ $itemDate->format('Y-m-d') }}"
                                         data-search="{{ strtolower($actionName . ' ' . ($item->user->name ?? '') . ' ' . ($item->log()['patient']['name'] ?? '')) }}">
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
                                                            {{ strtoupper(substr($item->user->name ?? 'U', 0, 0)) }}
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для информации о сотруднике -->
    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold" id="userInitials">
                        U
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Информация о сотруднике</h3>
                        <p class="text-sm text-gray-500">ID: <span id="userId"></span></p>
                    </div>
                </div>
                <button onclick="closeModal('userModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="userModalBody" class="p-6">
                <!-- Заполняется динамически -->
            </div>
            <div class="flex justify-end gap-3 p-6 bg-gray-50 border-t border-gray-200">
                <button onclick="closeModal('userModal')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Закрыть
                </button>
                <a id="editUserLink" href="#" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors inline-flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Редактировать профиль
                </a>
            </div>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ ИНФОРМАЦИИ О СОТРУДНИКЕ -->
    <div id="userInfoModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-all" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all">
            <!-- Шапка модалки -->
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

            <!-- Тело модалки -->
            <div class="p-6">
                <div id="userModalContent" class="space-y-4">
                    <!-- Контент будет вставлен через JavaScript -->
                </div>
            </div>

            <!-- Футер модалки -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                <button onclick="closeUserModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Закрыть
                </button>
                <a id="editUserProfileLink" href="#" target="_blank" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Редактировать
                </a>
            </div>
        </div>
    </div>

    <!-- МОДАЛЬНОЕ ОКНО ДЛЯ ПРОСМОТРА ИЗМЕНЕНИЙ -->
    <div id="changesInfoModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-all" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 overflow-hidden transform transition-all">
            <!-- Шапка модалки -->
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

            <!-- Тело модалки -->
            <div class="p-6 max-h-96 overflow-y-auto">
                <div id="changesModalContent" class="space-y-4">
                    <!-- Контент будет вставлен через JavaScript -->
                </div>
            </div>

            <!-- Футер модалки -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button onclick="closeChangesModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-check"></i>
                    Закрыть
                </button>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // МОДАЛЬНЫЕ ОКНА - ПРОСТОЕ И НАДЕЖНОЕ РЕШЕНИЕ
        // ============================================

        // ----- МОДАЛКА ДЛЯ СОТРУДНИКА -----
        function openUserModal(user) {
            console.log('Открытие модалки сотрудника:', user);

            try {
                // Преобразуем в объект если пришла строка
                const userData = typeof user === 'string' ? JSON.parse(user) : user;

                if (!userData || !userData.id) {
                    alert('Данные сотрудника отсутствуют');
                    return;
                }

                // Форматируем дату
                let createdDate = 'Не указана';
                if (userData.created_at) {
                    const date = new Date(userData.created_at);
                    createdDate = date.toLocaleDateString('ru-RU', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                }

                // Собираем HTML контент
                const content = `
            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                            <i class="fas fa-user text-blue-600 mr-1"></i> ФИО
                        </p>
                        <p class="font-semibold text-gray-900 text-lg">${userData.name || '—'}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                            <i class="fas fa-envelope text-blue-600 mr-1"></i> Email
                        </p>
                        <p class="font-semibold text-gray-900">${userData.email || '—'}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                            <i class="fas fa-id-card text-blue-600 mr-1"></i> ID сотрудника
                        </p>
                        <p class="font-semibold text-gray-900">#${userData.id || '—'}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">
                            <i class="fas fa-calendar text-blue-600 mr-1"></i> Дата регистрации
                        </p>
                        <p class="font-semibold text-gray-900">${createdDate}</p>
                    </div>
                </div>
            </div>
        `;

                // Вставляем контент
                document.getElementById('userModalContent').innerHTML = content;
                document.getElementById('userModalSubtitle').innerHTML = `ID: ${userData.id}`;

                // Обновляем ссылку на редактирование
                const editLink = document.getElementById('editUserProfileLink');
                if (editLink) {
                    editLink.href = `/platform/systems/users/edit/${userData.id}`;
                }

                // Показываем модалку
                document.getElementById('userInfoModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';

            } catch (error) {
                console.error('Ошибка в openUserModal:', error);
                alert('Не удалось загрузить информацию о сотруднике');
            }
        }

        function closeUserModal() {
            document.getElementById('userInfoModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // ----- МОДАЛКА ДЛЯ ИЗМЕНЕНИЙ -----
        function openChangesModal(diff) {
            console.log('Открытие модалки изменений:', diff);

            try {
                // Преобразуем в объект если пришла строка
                const changes = typeof diff === 'string' ? JSON.parse(diff) : diff;

                if (!changes || Object.keys(changes).length === 0) {
                    document.getElementById('changesModalContent').innerHTML = `
                <div class="text-center py-10">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-info-circle text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Нет данных об изменениях</p>
                    <p class="text-gray-400 text-sm mt-1">Запись была создана или изменения не отслеживаются</p>
                </div>
            `;
                } else {
                    // Строим таблицу изменений
                    let tableHTML = `
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Поле</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-red-50">Было</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider bg-green-50">Стало</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

                    let hasChanges = false;

                    Object.entries(changes).forEach(([field, values]) => {
                        if (values && (values.before !== undefined || values.after !== undefined)) {
                            hasChanges = true;

                            const before = values.before !== null && values.before !== undefined ? values.before : '—';
                            const after = values.after !== null && values.after !== undefined ? values.after : '—';

                            // Форматируем название поля
                            let fieldName = field;
                            switch(field) {
                                case 'name': fieldName = 'ФИО пациента'; break;
                                case 'birth_day': fieldName = 'Дата рождения'; break;
                                case 'gender': fieldName = 'Пол'; break;
                                case 'medical_card': fieldName = 'Номер мед. карты'; break;
                                case 'passport': fieldName = 'Паспорт'; break;
                                case 'nationality': fieldName = 'Гражданство'; break;
                                case 'address': fieldName = 'Адрес регистрации'; break;
                                case 'register_place': fieldName = 'Адрес пребывания'; break;
                                case 'phone_agent': fieldName = 'Телефон представителя'; break;
                                case 'delivered': fieldName = 'Доставлен'; break;
                                case 'diagnosis': fieldName = 'Диагноз'; break;
                                case 'outcome': fieldName = 'Исход'; break;
                            }

                            tableHTML += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-sm font-semibold text-gray-900">${fieldName}</td>
                            <td class="px-5 py-4 text-sm text-gray-800 bg-red-50 border-l border-red-100">${before}</td>
                            <td class="px-5 py-4 text-sm text-gray-800 bg-green-50 border-l border-green-100">${after}</td>
                        </tr>
                    `;
                        }
                    });

                    tableHTML += `
                        </tbody>
                    </table>
                </div>
            `;

                    if (!hasChanges) {
                        tableHTML = `
                    <div class="text-center py-10">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-info-circle text-gray-400 text-3xl"></i>
                        </div>
                        <p class="text-gray-600 font-medium">Нет изменений для отображения</p>
                    </div>
                `;
                    }

                    document.getElementById('changesModalContent').innerHTML = tableHTML;
                }

                // Показываем модалку
                document.getElementById('changesInfoModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';

            } catch (error) {
                console.error('Ошибка в openChangesModal:', error);
                document.getElementById('changesModalContent').innerHTML = `
            <div class="text-center py-10 text-red-600">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                </div>
                <p class="font-medium">Ошибка при загрузке данных</p>
                <p class="text-sm text-gray-500 mt-1">${error.message}</p>
            </div>
        `;
                document.getElementById('changesInfoModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeChangesModal() {
            document.getElementById('changesInfoModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Закрытие по клику вне модалки
        window.addEventListener('click', function(event) {
            const userModal = document.getElementById('userInfoModal');
            const changesModal = document.getElementById('changesInfoModal');

            if (event.target === userModal) {
                closeUserModal();
            }
            if (event.target === changesModal) {
                closeChangesModal();
            }
        });

        // Закрытие по Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeUserModal();
                closeChangesModal();
            }
        });

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Модальные окна инициализированы');

            // Добавляем обработчики для кнопок с data-атрибутами
            document.querySelectorAll('[data-user-id]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.dataset.userId;
                    const userName = this.dataset.name;
                    const userEmail = this.dataset.email;

                    openUserModal({
                        id: userId,
                        name: userName,
                        email: userEmail,
                        created_at: new Date().toISOString()
                    });
                });
            });

            document.querySelectorAll('[data-changes]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    try {
                        const changes = JSON.parse(this.dataset.changes);
                        openChangesModal(changes);
                    } catch (e) {
                        console.error('Ошибка парсинга changes:', e);
                        alert('Не удалось загрузить изменения');
                    }
                });
            });
        });
    </script>

    <style>
        /* Анимации для модальных окон */
        #userInfoModal, #changesInfoModal {
            animation: fadeIn 0.2s ease;
        }

        #userInfoModal > div, #changesInfoModal > div {
            animation: slideIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Стили для скролла в модалке изменений */
        #changesModalContent {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f1f5f9;
        }

        #changesModalContent::-webkit-scrollbar {
            width: 6px;
        }

        #changesModalContent::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        #changesModalContent::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        #changesModalContent::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <script>
        // ============================================
        // ФИЛЬТРАЦИЯ ИСТОРИИ
        // ============================================

        function applyFilters() {
            console.log('Применение фильтров...');

            const actionFilter = document.getElementById('actionFilter')?.value || 'all';
            const dateFilter = document.getElementById('dateFilter')?.value || 'all';
            const timelineItems = document.querySelectorAll('.timeline-item');

            if (!timelineItems.length) {
                console.log('Нет элементов для фильтрации');
                return;
            }

            // Текущая дата для сравнения
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const weekAgo = new Date(today);
            weekAgo.setDate(weekAgo.getDate() - 7);
            const monthAgo = new Date(today);
            monthAgo.setDate(monthAgo.getDate() - 30);

            let visibleCount = 0;

            timelineItems.forEach((item, index) => {
                let show = true;

                // 1. ФИЛЬТР ПО ДЕЙСТВИЮ
                if (actionFilter !== 'all') {
                    const itemAction = item.getAttribute('data-action');
                    if (itemAction !== actionFilter) {
                        show = false;
                    }
                }

                // 2. ФИЛЬТР ПО ДАТЕ
                if (show && dateFilter !== 'all') {
                    const itemDateStr = item.getAttribute('data-date');

                    if (itemDateStr) {
                        const itemDate = new Date(itemDateStr + 'T00:00:00');

                        switch(dateFilter) {
                            case 'today':
                                if (itemDate < today) show = false;
                                break;
                            case 'week':
                                if (itemDate < weekAgo) show = false;
                                break;
                            case 'month':
                                if (itemDate < monthAgo) show = false;
                                break;
                        }
                    }
                }

                // Показываем или скрываем элемент
                item.style.display = show ? 'flex' : 'none';
                if (show) visibleCount++;
            });

            // Показываем уведомление о результате
            showNotification(`Показано ${visibleCount} из ${timelineItems.length} событий`);
        }

        function resetFilters() {
            console.log('Сброс фильтров...');

            const actionSelect = document.getElementById('actionFilter');
            const dateSelect = document.getElementById('dateFilter');
            const searchInput = document.getElementById('searchInput');

            if (actionSelect) actionSelect.value = 'all';
            if (dateSelect) dateSelect.value = 'all';
            if (searchInput) searchInput.value = '';

            // Показываем все элементы
            const timelineItems = document.querySelectorAll('.timeline-item');
            timelineItems.forEach(item => {
                item.style.display = 'flex';
            });

            showNotification('Фильтры сброшены');
        }

        function searchHistory() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase().trim() || '';
            const items = document.querySelectorAll('.timeline-item');

            if (searchTerm === '') {
                // Если поиск пустой, применяем текущие фильтры
                applyFilters();
                return;
            }

            let visibleCount = 0;

            items.forEach(item => {
                const searchData = item.getAttribute('data-search') || item.textContent.toLowerCase();

                if (searchData.includes(searchTerm)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function showNotification(message) {
            // Удаляем предыдущее уведомление, если есть
            const oldNotification = document.querySelector('.custom-notification');
            if (oldNotification) oldNotification.remove();

            // Создаем новое уведомление
            const notification = document.createElement('div');
            notification.className = 'custom-notification fixed top-20 right-5 bg-gray-800 text-white px-5 py-3 rounded-lg shadow-xl z-50 flex items-center gap-3';
            notification.style.animation = 'slideInNotification 0.3s ease, fadeOutNotification 0.3s ease 2.7s forwards';
            notification.innerHTML = `
        <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center">
            <i class="fas fa-info-circle text-white text-xs"></i>
        </div>
        <span class="text-sm font-medium">${message}</span>
    `;

            document.body.appendChild(notification);

            // Автоматически удаляем через 3 секунды
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Добавляем стили для уведомлений
        const style = document.createElement('style');
        style.textContent = `
    @keyframes slideInNotification {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeOutNotification {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(20px);
        }
    }

    .timeline-item {
        transition: all 0.3s ease;
    }
`;
        document.head.appendChild(style);

        // Инициализация при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Фильтры инициализированы');

            // Добавляем data-атрибуты, если их нет
            const timelineItems = document.querySelectorAll('.timeline-item');
            timelineItems.forEach(item => {
                // Убедимся, что у элементов есть data-action
                if (!item.hasAttribute('data-action')) {
                    const actionSpan = item.querySelector('.bg-green-100, .bg-blue-100, .bg-red-100, .bg-gray-100');
                    if (actionSpan) {
                        const text = actionSpan.textContent.toLowerCase();
                        if (text.includes('созда')) item.setAttribute('data-action', 'create');
                        else if (text.includes('редактир')) item.setAttribute('data-action', 'update');
                        else if (text.includes('удал')) item.setAttribute('data-action', 'delete');
                        else item.setAttribute('data-action', 'other');
                    }
                }

                // Добавляем data-date, если его нет
                if (!item.hasAttribute('data-date')) {
                    const dateSpan = item.querySelector('.fa-calendar-alt')?.parentElement;
                    if (dateSpan) {
                        const dateText = dateSpan.textContent.trim();
                        // Пытаемся извлечь дату
                        const match = dateText.match(/(\d{1,2})\s+([а-я]+)\s+(\d{4})/);
                        if (match) {
                            const months = {
                                'января': '01', 'февраля': '02', 'марта': '03', 'апреля': '04',
                                'мая': '05', 'июня': '06', 'июля': '07', 'августа': '08',
                                'сентября': '09', 'октября': '10', 'ноября': '11', 'декабря': '12'
                            };
                            const day = match[1].padStart(2, '0');
                            const month = months[match[2].toLowerCase()] || '01';
                            const year = match[3];
                            item.setAttribute('data-date', `${year}-${month}-${day}`);
                        } else {
                            // Если не удалось извлечь, ставим текущую дату
                            const now = new Date();
                            const year = now.getFullYear();
                            const month = String(now.getMonth() + 1).padStart(2, '0');
                            const day = String(now.getDate()).padStart(2, '0');
                            item.setAttribute('data-date', `${year}-${month}-${day}`);
                        }
                    }
                }
            });
        });

        // Переопределяем обработчики для кнопок фильтрации
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.querySelector('button[onclick="applyFilters()"]');
            if (filterBtn) {
                filterBtn.onclick = function(e) {
                    e.preventDefault();
                    applyFilters();
                    return false;
                };
            }

            const resetBtn = document.querySelector('button[onclick="resetFilters()"]');
            if (resetBtn) {
                resetBtn.onclick = function(e) {
                    e.preventDefault();
                    resetFilters();
                    return false;
                };
            }

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    searchHistory();
                });
            }
        });
    </script>
</x-app-layout>
