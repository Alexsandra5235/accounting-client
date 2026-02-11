<x-app-layout>
    <!-- Информация о записи и кнопки действий -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Информация о записи</h3>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6 mt-1">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Создана:</span>
                                <span class="text-blue-600 font-medium">{{ \Carbon\Carbon::parse($log->created_at)->addHours(7)->locale('ru')->translatedFormat('d M Y H:i') }}</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Изменена:</span>
                                <span class="text-blue-600 font-medium">{{ \Carbon\Carbon::parse($log->updated_at)->addHours(7)->locale('ru')->translatedFormat('d M Y H:i') }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('log.edit', ['id' => $log->id]) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>
                        Редактировать
                    </a>

                    <form action="{{ route('log.destroy', ['id' => $log->id]) }}"
                          method="post"
                          onsubmit="return confirmDeletion({{ json_encode($log->patient->name) }})"
                          class="inline">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-outline border-red-500 text-red-600 hover:bg-red-500 hover:text-white transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Удалить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Прогресс просмотра -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-900">Основные данные</span>
                    </div>
                    <div class="h-1 w-8 bg-blue-200"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-semibold">
                            <i class="fas fa-info-circle text-sm"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-600">Дополнительная</span>
                    </div>
                    <div class="h-1 w-8 bg-gray-200"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-semibold">
                            <i class="fas fa-sign-out-alt text-sm"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-600">Выписка</span>
                    </div>
                    <div class="h-1 w-8 bg-gray-200"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-semibold">
                            <i class="fas fa-times-circle text-sm"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-600">Отказ</span>
                    </div>
                </div>
                <span class="text-xs text-gray-500">
                    <i class="fas fa-lock mr-1"></i> Режим просмотра
                </span>
            </div>
        </div>
    </div>

    <!-- Блок 1: Информация о пациенте -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-circle"></i>
                Основная информация о пациенте
            </h3>
        </div>

        <div class="card-body">
            <!-- ФИО и возраст -->
            <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg mb-6">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 flex items-center justify-center text-white text-2xl font-semibold shadow-md">
                    {{ strtoupper(substr($log->patient->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $log->patient->name }}</h2>
                    <div class="flex flex-wrap gap-3 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ \Carbon\Carbon::parse($log->patient->birth_day)->age }} лет
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-venus-mars mr-1"></i>
                            {{ $log->patient->gender }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-id-card mr-1"></i>
                            Карта: {{ $log->patient->medical_card }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Основная информация -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Дата поступления</p>
                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($log->log_receipt->date_receipt)->locale('ru')->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->log_receipt->time_receipt)->format('H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-birthday-cake text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Дата рождения</p>
                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($log->patient->birth_day)->locale('ru')->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->patient->birth_day)->age }} полных лет</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-passport text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Паспортные данные</p>
                        <p class="font-medium text-gray-900">{{ $log->patient->passport ?: '—' }}</p>
                        <p class="text-xs text-gray-500">{{ $log->patient->nationality ?: 'Гражданство не указано' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-home text-yellow-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Адрес регистрации</p>
                        <p class="font-medium text-gray-900">{{ $log->patient->address ?: '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-building text-indigo-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Адрес пребывания</p>
                        <p class="font-medium text-gray-900">{{ $log->patient->register_place ?: '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-address-card text-red-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">СНИЛС / Полис</p>
                        <p class="font-medium text-gray-900">{{ $log->patient->snils ?: '—' }}</p>
                        <p class="text-xs text-gray-500">{{ $log->patient->polis ?: 'Полис не указан' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Блок 2: Дополнительная информация -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-stethoscope"></i>
                Дополнительная информация и диагнозы
            </h3>
        </div>

        <div class="card-body space-y-6">
            <!-- Контакт и доставка -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-phone-alt text-green-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Контакт представителя</h4>
                    </div>
                    <p class="text-gray-700">{{ $log->log_receipt->phone_agent ?: 'Не указан' }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-ambulance text-orange-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900">Способ доставки</h4>
                    </div>
                    <p class="text-gray-700">{{ $log->log_receipt->delivered ?: 'Не указан' }}</p>
                </div>
            </div>

            <!-- Диагнозы -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-file-medical text-blue-600"></i>
                        <h4 class="font-semibold text-gray-900">Диагноз заболевания</h4>
                    </div>
                    @if($log->patient->diagnosis && $log->patient->diagnosis->state)
                        <p class="text-lg font-mono font-bold text-blue-700">{{ $log->patient->diagnosis->state->code }}</p>
                        <p class="text-gray-700 mt-1">{{ $log->patient->diagnosis->state->value }}</p>
                    @else
                        <p class="text-gray-500 italic">Не указан</p>
                    @endif
                </div>

                <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-bolt text-red-600"></i>
                        <h4 class="font-semibold text-gray-900">Травма / Отравление</h4>
                    </div>
                    @if($log->patient->diagnosis && $log->patient->diagnosis->wound)
                        <p class="text-lg font-mono font-bold text-red-700">{{ $log->patient->diagnosis->wound->code }}</p>
                        <p class="text-gray-700 mt-1">{{ $log->patient->diagnosis->wound->value }}</p>
                    @else
                        <p class="text-gray-500 italic">Не указано</p>
                    @endif
                </div>
            </div>

            <!-- Алкоголь и исследования -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-flask text-gray-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Факт употребления</p>
                        <p class="font-medium">{{ $log->log_receipt->fact_alcohol ?: 'Не указано' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-plus text-gray-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Дата взятия пробы</p>
                        <p class="font-medium">{{ $log->log_receipt->datetime_alcohol ? \Carbon\Carbon::parse($log->log_receipt->datetime_alcohol)->format('d.m.Y H:i') : '—' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-microscope text-gray-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Результаты</p>
                        <p class="font-medium">{{ $log->log_receipt->result_research ?: '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Отделение -->
            <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-procedures text-teal-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Отделение госпитализации</p>
                    <p class="font-medium text-gray-900">{{ $log->log_receipt->section_medical ?: 'Не указано' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Блок 3: Выписка пациента -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-sign-out-alt"></i>
                Выписка пациента
            </h3>
            @if($log->log_discharge->outcome)
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                    {{ $log->log_discharge->outcome }}
                </span>
            @endif
        </div>

        <div class="card-body">
            @if($log->log_discharge->outcome)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Исход</p>
                            <p class="font-medium text-gray-900">{{ $log->log_discharge->outcome }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-calendar-times text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Дата и время исхода</p>
                            <p class="font-medium text-gray-900">{{ $log->log_discharge->datetime_discharge ? \Carbon\Carbon::parse($log->log_discharge->datetime_discharge)->format('d.m.Y H:i') : '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-hospital text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">МО перевода</p>
                            <p class="font-medium text-gray-900">{{ $log->log_discharge->section_transferred ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 md:col-span-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-bullhorn text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Дата уведомления</p>
                            <p class="font-medium text-gray-900">{{ $log->log_discharge->datetime_inform ? \Carbon\Carbon::parse($log->log_discharge->datetime_inform)->format('d.m.Y H:i') : '—' }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <i class="fas fa-file-export text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">Информация о выписке отсутствует</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Блок 4: Отказ в госпитализации -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-times-circle"></i>
                Отказ в госпитализации
            </h3>
            @if($log->log_reject->reason_refusal)
                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                    Отказ
                </span>
            @endif
        </div>

        <div class="card-body">
            @if($log->log_reject->reason_refusal)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-ban text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Причина отказа</p>
                            <p class="font-medium text-gray-900">{{ $log->log_reject->reason_refusal }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-user-md text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Медицинский работник</p>
                            <p class="font-medium text-gray-900">{{ $log->log_reject->name_medical_worker ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 md:col-span-2">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-sticky-note text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 uppercase">Дополнительные сведения</p>
                            <p class="font-medium text-gray-900">{{ $log->log_reject->add_info ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <i class="fas fa-check-circle text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-500">Отказ в госпитализации не зарегистрирован</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Кнопка возврата -->
    <div class="flex justify-center mt-8">
        <a href="{{ route('dashboard') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i>
            Вернуться к списку пациентов
        </a>
    </div>

    <script>
        function confirmDeletion(patientName) {
            return confirm(`Вы уверены, что хотите удалить запись пациента "${patientName}"?\n\nЭто действие невозможно будет отменить.`);
        }
    </script>
</x-app-layout>
