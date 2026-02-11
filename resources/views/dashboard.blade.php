<x-app-layout>
    <!-- Статистика сверху -->
    <!-- Поиск пациента -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-search"></i>
                Поиск пациента
            </h3>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="text-gray-600">
                    Поиск по ФИО пациента. Результаты будут содержать записи, в которых имя пациента включает введенный фрагмент.
                </div>
            </div>

            <form method="post" action="{{ route('log.search') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="search_name" class="block text-sm font-medium text-gray-700 mb-1">
                        ФИО пациента
                    </label>
                    <input type="text"
                           id="search_name"
                           name="search_name"
                           value="{{ $search_name ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Введите ФИО пациента..."
                           autofocus>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Найти пациента
                    </button>

                    @if(request()->has('search_name') && !empty($search_name))
                        <a href="{{ route('dashboard') }}" class="btn btn-outline">
                            <i class="fas fa-times"></i>
                            Сбросить поиск
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Сообщения об ошибках -->
    @error('error_show')
    <div class="card mb-4 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-800">Ошибка просмотра записи</h4>
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
    @enderror

    @error('error_edit')
    <div class="card mb-4 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-800">Ошибка редактирования записи</h4>
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
    @enderror

    @error('error_delete')
    <div class="card mb-4 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-red-800">Ошибка удаления записи</h4>
                    <p class="text-red-600 mt-1">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
    @enderror

    <!-- Список пациентов -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-injured"></i>
                Пациенты санатория
            </h3>
            @if(!empty($logs))
                <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                    {{ is_countable($logs) ? count($logs) : 0 }} записей
                </span>
            @endif
        </div>

        <div class="card-body">
            @if(empty($logs))
                <div class="text-center py-8">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-user-slash text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Записи не найдены</h3>
                    <p class="text-gray-500">
                        @if(request()->has('search_name') && !empty($search_name))
                            Пациенты по запросу "{{ $search_name }}" не найдены
                        @else
                            В системе пока нет пациентов
                        @endif
                    </p>
                    <a href="{{ route('log.add') }}" class="btn btn-primary mt-4">
                        <i class="fas fa-plus"></i>
                        Добавить первого пациента
                    </a>
                </div>
            @else
                <div class="mb-4">
                    <p class="text-gray-600">
                        В таблице представлена информация о пациентах. Для взаимодействия с данными используйте кнопки действий.
                    </p>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-calendar"></i>
                                    Дата приема
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-clock"></i>
                                    Время
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-user"></i>
                                    ФИО пациента
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-birthday-cake"></i>
                                    Дата рождения
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-file-medical"></i>
                                    Мед. карта
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-cog"></i>
                                    Действия
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($logs as $log)
                            @php
                                // Обработка как массива или объекта
                                $patientName = is_array($log)
                                    ? ($log['patient']['name'] ?? $log['patient']->name ?? 'Не указано')
                                    : ($log->patient->name ?? 'Не указано');

                                $patientBirthDay = is_array($log)
                                    ? ($log['patient']['birth_day'] ?? $log['patient']->birth_day ?? null)
                                    : ($log->patient->birth_day ?? null);

                                $patientMedicalCard = is_array($log)
                                    ? ($log['patient']['medical_card'] ?? $log['patient']->medical_card ?? 'Не указано')
                                    : ($log->patient->medical_card ?? 'Не указано');

                                $dateReceipt = is_array($log)
                                    ? ($log['log_receipt']['date_receipt'] ?? $log['log_receipt']->date_receipt ?? null)
                                    : ($log->log_receipt->date_receipt ?? null);

                                $timeReceipt = is_array($log)
                                    ? ($log['log_receipt']['time_receipt'] ?? $log['log_receipt']->time_receipt ?? null)
                                    : ($log->log_receipt->time_receipt ?? null);

                                $logId = is_array($log) ? ($log['id'] ?? $log->id) : $log->id;

                                // Вычисление возраста
                                $age = $patientBirthDay ? \Carbon\Carbon::parse($patientBirthDay)->age : 'N/A';
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-calendar-day text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                                @if($dateReceipt)
                                                {{ \Carbon\Carbon::parse($dateReceipt)->translatedFormat('d M Y') }}
                                            @else
                                                Не указана
                                            @endif
                                            </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($timeReceipt)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($timeReceipt)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Не указано</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $patientName }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $logId }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($patientBirthDay)
                                            {{ \Carbon\Carbon::parse($patientBirthDay)->translatedFormat('d M Y') }}
                                        @else
                                            Не указана
                                        @endif
                                    </div>
                                    @if($patientBirthDay && $age !== 'N/A')
                                        <div class="text-xs text-gray-500">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-user-clock mr-1"></i>
                                                {{ $age }} лет
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-2">
                                            <i class="fas fa-file-medical-alt text-green-600"></i>
                                        </div>
                                        <span class="text-sm font-mono font-medium text-gray-900">
                                                {{ $patientMedicalCard }}
                                            </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('log.find', ['id' => $logId]) }}"
                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                            <i class="fas fa-eye mr-1"></i>
                                            Просмотр
                                        </a>
                                        <a href="{{ route('log.edit', ['id' => $logId]) }}"
                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                            <i class="fas fa-edit mr-1"></i>
                                            Редактировать
                                        </a>
                                        <form method="POST" action="{{ route('log.destroy', ['id' => $logId]) }}"
                                              onsubmit="return confirmDeletion('{{ addslashes($patientName) }}')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                <i class="fas fa-trash-alt mr-1"></i>
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Информация о результатах -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            Отображено {{ is_countable($logs) ? count($logs) : 0 }} записей
                            @if(request()->has('search_name') && !empty($search_name))
                                по запросу "<span class="font-semibold">{{ $search_name }}</span>"
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-database mr-2"></i>
                            Обновлено: {{ now()->translatedFormat('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Быстрые действия -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('log.add') }}" class="card group hover:shadow-lg transition-shadow">
            <div class="card-body text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Добавить пациента</h4>
                <p class="text-sm text-gray-600">Создать новую запись о поступлении</p>
            </div>
        </a>

        <a href="{{ route('excel.store') }}" class="card group hover:shadow-lg transition-shadow">
            <div class="card-body text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-excel"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Отчеты</h4>
                <p class="text-sm text-gray-600">Сформировать статистические отчеты</p>
            </div>
        </a>

        <a href="{{ route('patient.flow') }}" class="card group hover:shadow-lg transition-shadow">
            <div class="card-body text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center text-white text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Статистика</h4>
                <p class="text-sm text-gray-600">Анализ движения пациентов</p>
            </div>
        </a>
    </div>

    <script>
        function confirmDeletion(patientName) {
            return confirm(`Вы уверены, что хотите удалить запись пациента "${patientName}"?\n\nЭто действие невозможно будет отменить.`);
        }
    </script>

    <style>
        /* Кастомные стили для таблицы */
        table th {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        }

        table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table tr:hover {
            background-color: #f3f4f6;
        }

        /* Адаптивность для мобильных */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .card-body .flex.items-center.gap-2 {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .card-body .flex.items-center.gap-2 a,
            .card-body .flex.items-center.gap-2 form {
                flex: 1;
                min-width: 120px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .card-header .ml-2 {
                margin-left: 0 !important;
                margin-top: 0.5rem;
            }
        }
    </style>
</x-app-layout>
