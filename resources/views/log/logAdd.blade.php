<x-app-layout>

    <!-- Ошибки -->
    @error('error_store')
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

    <!-- Прогресс шагов -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Добавление новой записи пациента</h3>
                <p class="text-gray-600">Заполните все разделы формы для создания новой записи о поступлении пациента</p>
            </div>

            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">1</div>
                        <span class="ml-2 text-sm font-medium text-gray-900">Основные данные</span>
                    </div>
                    <div class="h-1 w-8 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">2</div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Дополнительная информация</span>
                    </div>
                    <div class="h-1 w-8 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">3</div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Выписка</span>
                    </div>
                    <div class="h-1 w-8 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-semibold">4</div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Завершение</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('log.store') }}" method="post" id="patientForm">
        @csrf

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
                               value="{{ old('date_receipt', \Carbon\Carbon::parse(now())->format('Y-m-d')) }}"
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
                               value="{{ old('time_receipt', now()->format('H:i')) }}"
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
                               value="{{ old('name') }}"
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
                               value="{{ old('birth_day') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               required>
                        @error('birth_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                            <option value="мужской" {{ old('gender') == 'мужской' ? 'selected' : '' }}>Мужской</option>
                            <option value="женский" {{ old('gender') == 'женский' ? 'selected' : '' }}>Женский</option>
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
                               value="{{ old('medical_card') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="MC-123456"
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
                               value="{{ old('passport') }}"
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
                               value="{{ old('nationality') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                               placeholder="Российская Федерация">
                        @error('nationality')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Автодополнение адресов -->
                <div class="space-y-4">
                    <div>
                        <x-autocomplete
                            name="address"
                            placeholder="Начните вводить адрес..."
                            url="{{ route('address.suggest') }}"
                            label="Адрес регистрации по месту жительства"
                            icon="home"
                            value="{{ old('address') }}"
                        />
                    </div>

                    <div>
                        <x-autocomplete
                            name="register_place"
                            placeholder="Начните вводить адрес..."
                            url="{{ route('address.suggest.place') }}"
                            label="Адрес регистрации по месту пребывания"
                            icon="building"
                            value="{{ old('register_place') }}"
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
                               value="{{ old('snils') }}"
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
                               value="{{ old('polis') }}"
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
                               value="{{ old('phone_agent') }}"
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
                            <option value="полицией" {{ old('delivered') == 'полицией' ? 'selected' : '' }}>Полицией</option>
                            <option value="выездной бригадой скорой медицинской помощи" {{ old('delivered') == 'выездной бригадой скорой медицинской помощи' ? 'selected' : '' }}>Скорой помощью</option>
                            <option value="другой медицинской организацией" {{ old('delivered') == 'другой медицинской организацией' ? 'selected' : '' }}>Другой медорганизацией</option>
                            <option value="обратился самостоятельно" {{ old('delivered') == 'обратился самостоятельно' ? 'selected' : '' }}>Обратился самостоятельно</option>
                        </select>
                        @error('delivered')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Автодополнение МКБ -->
                <div class="space-y-4">
                    <div>
                        <x-mkd-autocomplete
                            name="state"
                            placeholder="Начните вводить диагноз..."
                            url="{{ route('mkd.suggestState') }}"
                            label="Диагноз заболевания (код по МКБ)"
                            icon="file-medical"
                            value="{{ old('state') }}"
                        />
                    </div>

                    <div>
                        <x-mkd-autocomplete
                            name="wound"
                            placeholder="Начните вводить диагноз..."
                            url="{{ route('mkd.suggestWound') }}"
                            label="Причина травмы/отравления (код по МКБ)"
                            icon="band-aid"
                            value="{{ old('wound') }}"
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
                               value="{{ old('fact_alcohol') }}"
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
                               value="{{ old('datetime_alcohol') }}"
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
                               value="{{ old('result_research') }}"
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
                               value="{{ old('section_medical') }}"
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
                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">При выписке</span>
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
                            <option value="выписан" {{ old('outcome') == 'выписан' ? 'selected' : '' }}>Выписан</option>
                            <option value="переведен в другую медицинскую организацию" {{ old('outcome') == 'переведен в другую медицинскую организацию' ? 'selected' : '' }}>Переведен в другую МО</option>
                            <option value="умер" {{ old('outcome') == 'умер' ? 'selected' : '' }}>Умер</option>
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
                               value="{{ old('datetime_discharge') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                        @error('datetime_discharge')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="medicalOrgField" class="{{ old('outcome') != 'переведен в другую медицинскую организацию' ? 'hidden' : '' }}">
                    <label for="section_transferred" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-hospital mr-1 text-yellow-600"></i>
                        Медицинская организация перевода
                    </label>
                    <input type="text"
                           id="section_transferred"
                           name="section_transferred"
                           value="{{ old('section_transferred') }}"
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
                           value="{{ old('datetime_inform') }}"
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
                            <option value="отказался пациент" {{ old('reason_refusal') == 'отказался пациент' ? 'selected' : '' }}>Отказался пациент</option>
                            <option value="отсутствие показаний" {{ old('reason_refusal') == 'отсутствие показаний' ? 'selected' : '' }}>Отсутствие показаний</option>
                            <option value="помощь оказана в приемном отделении медицинской организации" {{ old('reason_refusal') == 'помощь оказана в приемном отделении медицинской организации' ? 'selected' : '' }}>Помощь оказана в приемном отделении</option>
                            <option value="направлен в другую медицинскую организацию" {{ old('reason_refusal') == 'направлен в другую медицинскую организацию' ? 'selected' : '' }}>Направлен в другую МО</option>
                            <option value="иная причина" {{ old('reason_refusal') == 'иная причина' ? 'selected' : '' }}>Иная причина</option>
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
                               value="{{ old('name_medical_worker') }}"
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
                              placeholder="Дополнительная информация об отказе...">{{ old('add_info') }}</textarea>
                    @error('add_info')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Кнопки действий -->
        <div class="card mb-6">
            <div class="card-body">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Все поля отмеченные как "Обязательно" должны быть заполнены
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline">
                            <i class="fas fa-times mr-2"></i>
                            Отменить
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Сохранить запись
                        </button>
                        <button type="button" onclick="previewForm()" class="btn btn-secondary">
                            <i class="fas fa-eye mr-2"></i>
                            Предварительный просмотр
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Показать/скрыть поле медицинской организации при выборе перевода
        document.getElementById('outcome').addEventListener('change', function() {
            const medicalOrgField = document.getElementById('medicalOrgField');
            if (this.value === 'переведен в другую медицинскую организацию') {
                medicalOrgField.classList.remove('hidden');
                medicalOrgField.querySelector('input').required = true;
            } else {
                medicalOrgField.classList.add('hidden');
                medicalOrgField.querySelector('input').required = false;
            }
        });

        // Валидация формы
        function validateForm() {
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            return isValid;
        }

        // Предварительный просмотр
        function previewForm() {
            if (validateForm()) {
                // Здесь можно добавить логику предпросмотра
                alert('Форма заполнена корректно. Можно сохранять запись.');
            } else {
                alert('Пожалуйста, заполните все обязательные поля (помечены красной иконкой).');
            }
        }

        // Автоматический расчет возраста
        document.getElementById('birth_day').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            // Можно добавить отображение возраста рядом с полем
            const ageDisplay = document.getElementById('age-display') || (() => {
                const div = document.createElement('div');
                div.id = 'age-display';
                div.className = 'text-sm text-gray-600 mt-1';
                this.parentNode.appendChild(div);
                return div;
            })();

            ageDisplay.textContent = `Возраст пациента: ${age} лет`;
        });

        // Инициализация при загрузке
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация скрытых полей
            const outcomeSelect = document.getElementById('outcome');
            if (outcomeSelect) {
                outcomeSelect.dispatchEvent(new Event('change'));
            }

            // Подсказки для полей
            const tooltips = {
                'snils': 'Формат: XXX-XXX-XXX XX',
                'polis': 'Формат: XXXX XXXXXXXXXXXX',
                'phone_agent': 'Формат: +7 (XXX) XXX-XX-XX',
                'passport': 'Формат: XXXX XXXXXX'
            };

            Object.entries(tooltips).forEach(([id, text]) => {
                const input = document.getElementById(id);
                if (input) {
                    input.placeholder = text;
                }
            });
        });
    </script>

    <style>
        /* Стили для обязательных полей */
        label[for]:has(+ input[required]),
        label[for]:has(+ select[required]),
        label[for]:has(+ textarea[required]) {
            position: relative;
        }

        label[for]:has(+ input[required])::after,
        label[for]:has(+ select[required])::after,
        label[for]:has(+ textarea[required])::after {
            content: "*";
            color: #ef4444;
            margin-left: 4px;
        }

        /* Анимация фокуса */
        input:focus, select:focus, textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        /* Стили для ошибок */
        .border-red-500 {
            border-color: #ef4444 !important;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
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

            .flex-col.md\:flex-row {
                flex-direction: column;
                gap: 1rem;
            }

            .flex-col.md\:flex-row .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .grid-cols-1.md\:grid-cols-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Стили для прогресс-бара */
        .progress-step.active {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .progress-step.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
    </style>
</x-app-layout>
