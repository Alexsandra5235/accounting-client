@push('styles')
    @vite('resources/css/log-add.css')
@endpush

@push('scripts')
    @vite('resources/js/log-add.js')
@endpush

@push('scripts')
    @vite('resources/js/log-edit.js')
@endpush

<x-app-layout>
    <div class="log-add-page">
    <!-- Информация о записи и кнопка удаления -->
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

                <form action="{{ route('log.destroy', ['id' => $log->id]) }}"
                      method="post"
                      onsubmit="return confirmDeletion({{ json_encode($log->patient->name) }})"
                      class="inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline border-red-500 text-red-600 hover:bg-red-500 hover:text-white transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Удалить запись
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Ошибки -->
    @error('error_update')
    <div class="card mb-6 border-red-200 bg-red-50">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-red-800">Ошибка сохранения записи</h4>
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

    <!-- Форма редактирования -->
    <form action="{{ route('log.update', ['id' => $log->id]) }}" method="post" id="patientForm">
        @csrf
        @method('put')

        <!-- Раздел 1: Основная информация -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-circle"></i>
                    Основная информация о пациенте
                </h3>
                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">Обязательно</span>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_receipt" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar-alt mr-1 text-blue-600"></i>
                            Дата поступления
                        </label>
                        <input type="date"
                               id="date_receipt"
                               name="date_receipt"
                               value="{{ \Carbon\Carbon::parse($log->log_receipt->date_receipt)->format('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required>
                        @error('date_receipt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time_receipt" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clock mr-1 text-blue-600"></i>
                            Время поступления
                        </label>
                        <input type="time"
                               id="time_receipt"
                               name="time_receipt"
                               value="{{ \Carbon\Carbon::parse($log->log_receipt->time_receipt)->format('H:i') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required>
                        @error('time_receipt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user mr-1 text-blue-600"></i>
                            Фамилия, имя, отчество (при наличии)
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ $log->patient->name }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Иванов Иван Иванович"
                               required>
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_day" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-birthday-cake mr-1 text-blue-600"></i>
                            Дата рождения
                        </label>
                        <input type="date"
                               id="birth_day"
                               name="birth_day"
                               value="{{ $log->patient->birth_day }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required>
                        @error('birth_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($log->patient->birth_day)
                            <div class="mt-1 text-xs text-gray-500">
                                Возраст: {{ \Carbon\Carbon::parse($log->patient->birth_day)->age }} лет
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-venus-mars mr-1 text-blue-600"></i>
                            Пол
                        </label>
                        <select id="gender"
                                name="gender"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                required>
                            <option value="">Выберите пол</option>
                            <option value="мужской" {{ $log->patient->gender == 'мужской' ? 'selected' : '' }}>Мужской</option>
                            <option value="женский" {{ $log->patient->gender == 'женский' ? 'selected' : '' }}>Женский</option>
                        </select>
                        @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="medical_card" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-file-medical mr-1 text-blue-600"></i>
                            Номер медицинской карты
                        </label>
                        <input type="text"
                               id="medical_card"
                               name="medical_card"
                               value="{{ $log->patient->medical_card }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="МК-123456"
                               required>
                        @error('medical_card')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passport" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-id-card mr-1 text-blue-600"></i>
                            Паспортные данные
                        </label>
                        <input type="text"
                               id="passport"
                               name="passport"
                               value="{{ $log->patient->passport }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="4510 123456">
                        @error('passport')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-globe mr-1 text-blue-600"></i>
                            Гражданство
                        </label>
                        <input type="text"
                               id="nationality"
                               name="nationality"
                               value="{{ $log->patient->nationality }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Российская Федерация">
                        @error('nationality')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Адреса -->
                <div class="space-y-4">
                    <div>
                        <x-autocomplete
                            name="address"
                            placeholder="Начните вводить адрес..."
                            url="{{ route('address.suggest') }}"
                            label="Адрес регистрации по месту жительства"
                            icon="home"
                            initial="{{ old('address', $log->patient->address) }}"
                        />
                    </div>

                    <div>
                        <x-autocomplete
                            name="register_place"
                            placeholder="Начните вводить адрес..."
                            url="{{ route('address.suggest.place') }}"
                            label="Адрес регистрации по месту пребывания"
                            icon="building"
                            initial="{{ old('register_place', $log->patient->register_place) }}"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="snils" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-address-card mr-1 text-blue-600"></i>
                            СНИЛС
                        </label>
                        <input type="text"
                               id="snils"
                               name="snils"
                               value="{{ $log->patient->snils }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="123-456-789 01">
                        @error('snils')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="polis" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-heartbeat mr-1 text-blue-600"></i>
                            Полис ОМС
                        </label>
                        <input type="text"
                               id="polis"
                               name="polis"
                               value="{{ $log->patient->polis }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="1234 5678901234">
                        @error('polis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Раздел 2: Дополнительная информация -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Дополнительная информация
                </h3>
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Рекомендуется</span>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone_agent" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-phone mr-1 text-green-600"></i>
                            Телефон представителя
                        </label>
                        <input type="text"
                               id="phone_agent"
                               name="phone_agent"
                               value="{{ $log->log_receipt->phone_agent }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               placeholder="+7 (999) 123-45-67">
                        @error('phone_agent')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="delivered" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-ambulance mr-1 text-green-600"></i>
                            Доставлен (направлен)
                        </label>
                        <select id="delivered"
                                name="delivered"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                            <option value="">Выберите способ доставки</option>
                            <option value="полицией" {{ $log->log_receipt->delivered == 'полицией' ? 'selected' : '' }}>Полицией</option>
                            <option value="выездной бригадой скорой медицинской помощи" {{ $log->log_receipt->delivered == 'выездной бригадой скорой медицинской помощи' ? 'selected' : '' }}>Скорой помощью</option>
                            <option value="другой медицинской организацией" {{ $log->log_receipt->delivered == 'другой медицинской организацией' ? 'selected' : '' }}>Другой медорганизацией</option>
                            <option value="обратился самостоятельно" {{ $log->log_receipt->delivered == 'обратился самостоятельно' ? 'selected' : '' }}>Обратился самостоятельно</option>
                        </select>
                        @error('delivered')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- МКБ диагнозы -->
                <div class="space-y-4">
                    <div>
                        <x-mkd-autocomplete
                            name="state"
                            placeholder="Начните вводить диагноз..."
                            url="{{ route('mkd.suggestState') }}"
                            label="Диагноз заболевания (код по МКБ)"
                            icon="file-medical"
                            initial="{{ old('state_code', $log->patient->diagnosis->state->code ?? '') }}"
                            hidden="{{ old('state_value', $log->patient->diagnosis->state->value ?? '') }}"
                        />
                    </div>

                    <div>
                        <x-mkd-autocomplete
                            name="wound"
                            placeholder="Начните вводить диагноз..."
                            url="{{ route('mkd.suggestWound') }}"
                            label="Причина травмы/отравления (код по МКБ)"
                            icon="band-aid"
                            initial="{{ old('wound_code', $log->patient->diagnosis->wound->code ?? '') }}"
                            hidden="{{ old('wound_value', $log->patient->diagnosis->wound->value ?? '') }}"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fact_alcohol" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-flask mr-1 text-green-600"></i>
                            Факт употребления алкоголя/ПАВ
                        </label>
                        <input type="text"
                               id="fact_alcohol"
                               name="fact_alcohol"
                               value="{{ $log->log_receipt->fact_alcohol }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               placeholder="Наличие/отсутствие признаков">
                        @error('fact_alcohol')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="datetime_alcohol" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar-plus mr-1 text-green-600"></i>
                            Дата/время взятия пробы
                        </label>
                        <input type="datetime-local"
                               id="datetime_alcohol"
                               name="datetime_alcohol"
                               value="{{ $log->log_receipt->datetime_alcohol }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        @error('datetime_alcohol')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="result_research" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-microscope mr-1 text-green-600"></i>
                            Результаты исследований
                        </label>
                        <input type="text"
                               id="result_research"
                               name="result_research"
                               value="{{ $log->log_receipt->result_research }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               placeholder="Результаты лабораторных анализов">
                        @error('result_research')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="section_medical" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-procedures mr-1 text-green-600"></i>
                            Отделение госпитализации
                        </label>
                        <input type="text"
                               id="section_medical"
                               name="section_medical"
                               value="{{ $log->log_receipt->section_medical }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                               placeholder="Терапевтическое отделение">
                        @error('section_medical')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Раздел 3: Выписка пациента -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-sign-out-alt"></i>
                    Выписка пациента
                </h3>
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">При необходимости</span>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="outcome" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clipboard-check mr-1 text-yellow-600"></i>
                            Исход госпитализации
                        </label>
                        <select id="outcome"
                                name="outcome"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                            <option value="">Выберите исход</option>
                            <option value="выписан" {{ $log->log_discharge->outcome == 'выписан' ? 'selected' : '' }}>Выписан</option>
                            <option value="переведен в другую медицинскую организацию" {{ $log->log_discharge->outcome == 'переведен в другую медицинскую организацию' ? 'selected' : '' }}>Переведен в другую МО</option>
                            <option value="умер" {{ $log->log_discharge->outcome == 'умер' ? 'selected' : '' }}>Умер</option>
                        </select>
                        @error('outcome')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="datetime_discharge" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar-times mr-1 text-yellow-600"></i>
                            Дата и время исхода
                        </label>
                        <input type="datetime-local"
                               id="datetime_discharge"
                               name="datetime_discharge"
                               value="{{ $log->log_discharge->datetime_discharge }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                        @error('datetime_discharge')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="medicalOrgField" class="{{ $log->log_discharge->outcome != 'переведен в другую медицинскую организацию' ? 'hidden' : '' }}">
                    <label for="section_transferred" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-hospital mr-1 text-yellow-600"></i>
                        Медицинская организация перевода
                    </label>
                    <input type="text"
                           id="section_transferred"
                           name="section_transferred"
                           value="{{ $log->log_discharge->section_transferred }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                           placeholder="Городская больница №1">
                    @error('section_transferred')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="datetime_inform" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-bullhorn mr-1 text-yellow-600"></i>
                        Дата/время уведомления
                    </label>
                    <input type="datetime-local"
                           id="datetime_inform"
                           name="datetime_inform"
                           value="{{ $log->log_discharge->datetime_inform }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                    @error('datetime_inform')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Раздел 4: Отказ в госпитализации -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-times-circle"></i>
                    Отказ в госпитализации
                </h3>
                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded">При отказе</span>
            </div>
            <div class="card-body space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="reason_refusal" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-ban mr-1 text-red-600"></i>
                            Причина отказа
                        </label>
                        <select id="reason_refusal"
                                name="reason_refusal"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                            <option value="">Выберите причину отказа</option>
                            <option value="отказался пациент" {{ $log->log_reject->reason_refusal == 'отказался пациент' ? 'selected' : '' }}>Отказался пациент</option>
                            <option value="отсутствие показаний" {{ $log->log_reject->reason_refusal == 'отсутствие показаний' ? 'selected' : '' }}>Отсутствие показаний</option>
                            <option value="помощь оказана в приемном отделении медицинской организации" {{ $log->log_reject->reason_refusal == 'помощь оказана в приемном отделении медицинской организации' ? 'selected' : '' }}>Помощь оказана в приемном отделении</option>
                            <option value="направлен в другую медицинскую организацию" {{ $log->log_reject->reason_refusal == 'направлен в другую медицинскую организацию' ? 'selected' : '' }}>Направлен в другую МО</option>
                            <option value="иная причина" {{ $log->log_reject->reason_refusal == 'иная причина' ? 'selected' : '' }}>Иная причина</option>
                        </select>
                        @error('reason_refusal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name_medical_worker" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user-md mr-1 text-red-600"></i>
                            Медицинский работник
                        </label>
                        <input type="text"
                               id="name_medical_worker"
                               name="name_medical_worker"
                               value="{{ $log->log_reject->name_medical_worker }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                               placeholder="Петров Пётр Петрович">
                        @error('name_medical_worker')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="add_info" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-sticky-note mr-1 text-red-600"></i>
                        Дополнительные сведения
                    </label>
                    <textarea id="add_info"
                              name="add_info"
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"
                              placeholder="Дополнительная информация об отказе...">{{ $log->log_reject->add_info }}</textarea>
                    @error('add_info')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Кнопки действий -->
        <div class="card mb-6" id="actionsCardAnchor">
            <div class="card-body">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Все поля отмеченные как "Обязательно" должны быть заполнены
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline btn-cancel">
                            <i class="fas fa-times mr-2"></i>
                            Отмена
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Сохранить изменения
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="floatingActions" class="floating-actions hidden">
        <div class="floating-actions-inner">
            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-cancel">
                <i class="fas fa-times mr-2"></i>
                Отмена
            </a>
            <button type="submit" form="patientForm" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>
                Сохранить изменения
            </button>
        </div>
    </div>
    </div>
</x-app-layout>
