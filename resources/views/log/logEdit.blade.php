<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Редактирование информации') }}
        </h2>
    </x-slot>

    @error('error_update')
    <div class="pt-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div style="padding: 24px 0 0 24px" class="flex items-center text-gray-900 dark:text-gray-100">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Ошибка сохранения записи') }}
                    </h2>
                </div>
                <div class="flex items-center p-6 text-gray-900 dark:text-gray-100">
                    <svg class="w-6 h-6 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9z" />
                    </svg>
                    {{ $message }}
                </div>
            </div>
        </div>
    </div>
    @enderror

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Информация о записи</h3>
                    <p>Дата и время создания записи: <strong style="color: #2563eb">{{ \Carbon\Carbon::parse($log->created_at)->addHours(7)->locale('ru')->translatedFormat('D, d M Y H:i') }}</strong></p>
                    <p>Дата и время последнего редактирования: <strong style="color: #2563eb">{{ \Carbon\Carbon::parse($log->updated_at)->addHours(7)->locale('ru')->translatedFormat('D, d M Y H:i') }}</strong></p>
                    <form action="{{ route('log.destroy', ['id' => $log->id]) }}" method="post" class="mt-2" onsubmit="return confirmDeletion({{ json_encode($log->patient->name) }})">
                        @csrf
                        @method('delete')
                        <x-danger-button>Удалить запись</x-danger-button>
                    </form>
                </div>
            </div>
            <form action="{{ route('log.update', ['id' => $log->id]) }}" method="post">
                @csrf
                @method('put')
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
                    <div>
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Информация о пациенте') }}
                                </h2>
                            </header>
                            <div class="mt-6 space-y-6">
                                <div>
                                    <x-input-label for="date_receipt" :value="__('Дата поступления')" />
                                    <x-text-input id="date_receipt" name="date_receipt" type="date" class="mt-1 block w-full" :value="\Carbon\Carbon::parse($log->log_receipt->date_receipt)->locale('ru')->translatedFormat('Y-m-d')"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('date_receipt')" />
                                </div>

                                <div>
                                    <x-input-label for="time_receipt" :value="__('Время поступления')" />
                                    <x-text-input id="time_receipt" name="time_receipt" type="time" class="mt-1 block w-full" :value="\Carbon\Carbon::parse($log->log_receipt->time_receipt)->locale('ru')->translatedFormat('H:i')"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('time_receipt')" />
                                </div>

                                <div>
                                    <x-input-label for="name" :value="__('Фамилия, имя, отчество (при наличии)')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="$log->patient->name"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="birth_day" :value="__('Дата рождения (число, месяц, год)')" />
                                    <x-text-input id="birth_day" name="birth_day" type="date" class="mt-1 block w-full" :value="$log->patient->birth_day" />
                                    <x-input-error class="mt-2" :messages="$errors->get('birth_day')" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Пол (мужской, женский)')"/>
                                    <x-select id="gender" name="gender" class="mt-1 block w-full">
                                        <option value="" style="font-weight: bold">Пожалуйста, сделайте выбор</option>
                                        <option value="мужской" {{ $log->patient->gender == 'мужской' ? 'selected' : '' }}>мужской</option>
                                        <option value="женский" {{ $log->patient->gender == 'женский' ? 'selected' : '' }}>женский</option>
                                    </x-select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                </div>

                                <div>
                                    <x-input-label for="medical_card" :value="__('Номер медицинской карты')" />
                                    <x-text-input id="medical_card" name="medical_card" type="text" class="mt-1 block w-full" :value="$log->patient->medical_card" />
                                    <x-input-error class="mt-2" :messages="$errors->get('medical_card')" />
                                </div>

                                <div>
                                    <x-input-label for="passport" :value="__('Серия и номер паспорта или иного документа, удостоверяющего личность (при наличии)')" />
                                    <x-text-input id="passport" name="passport" type="text" class="mt-1 block w-full" :value="$log->patient->passport" />
                                    <x-input-error class="mt-2" :messages="$errors->get('passport')" />
                                </div>

                                <div>
                                    <x-input-label for="nationality" :value="__('Гражданство')" />
                                    <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full" :value="$log->patient->nationality" />
                                    <x-input-error class="mt-2" :messages="$errors->get('nationality')" />
                                </div>

                                <x-autocomplete
                                    name="address"
                                    placeholder="Начните вводить адрес..."
                                    url="{{ route('address.suggest') }}"
                                    value="Регистрация по месту жительства"
                                    initial="{{ old('address', $log->patient->address) }}"
                                />

                                <x-autocomplete
                                    name="register_place"
                                    placeholder="Начните вводить адрес..."
                                    url="{{ route('address.suggest.place') }}"
                                    value="Регистрация по месту пребывания"
                                    initial="{{ old('register_place', $log->patient->register_place) }}"
                                />

                                <div>
                                    <x-input-label for="snils" :value="__('СНИСЛ (при наличии)')" />
                                    <x-text-input id="snils" name="snils" type="text" class="mt-1 block w-full" :value="$log->patient->snils" />
                                    <x-input-error class="mt-2" :messages="$errors->get('snils')" />
                                </div>

                                <div>
                                    <x-input-label for="polis" :value="__('Полис обязательного медицинского страхования (при наличии)')" />
                                    <x-text-input id="polis" name="polis" type="text" class="mt-1 block w-full" :value="$log->patient->polis" />
                                    <x-input-error class="mt-2" :messages="$errors->get('polis')" />
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
                    <div>
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Дополнительная информация') }}
                                </h2>
                            </header>
                            <div class="mt-6 space-y-6">
                                <div>
                                    <x-input-label for="phone_agent" :value="__('Номер телефона законного представителя, лица, которому может быть передана информация о состоянии здоровья пациента')" />
                                    <x-text-input id="phone_agent" name="phone_agent" type="text" class="mt-1 block w-full" :value="$log->log_receipt->phone_agent" />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone_agent')" />
                                </div>

                                <div>
                                    <x-input-label for="delivered" :value="__('Пациент доставлен (направлен)')" />
                                    <x-select id="delivered" name="delivered" class="mt-1 block w-full">
                                        <option value="" style="font-weight: bold">Пожалуйста, сделайте выбор</option>
                                        <option value="полицией" {{ $log->log_receipt->delivered == 'полицией' ? 'selected' : '' }}>полицией</option>
                                        <option value="выездной бригадой скорой медицинской помощи" {{ $log->log_receipt->delivered == 'выездной бригадой скорой медицинской помощи' ? 'selected' : '' }}>выездной бригадой скорой медицинской помощи</option>
                                        <option value="другой медицинской организацией" {{ $log->log_receipt->delivered == 'другой медицинской организацией' ? 'selected' : '' }}>другой медицинской организацией</option>
                                        <option value="обратился самостоятельно" {{ $log->log_receipt->delivered == 'обратился самостоятельно' ? 'selected' : '' }}>обратился самостоятельно</option>
                                    </x-select>
                                    <x-input-error class="mt-2" :messages="$errors->get('delivered')" />
                                </div>

                                <x-mkd-autocomplete
                                    name="state"
                                    placeholder="Начните вводить диагноз..."
                                    url="{{ route('mkd.suggestState') }}"
                                    value="Диагноз заболевания (состояния), поставленный направившей медицинской организацией (код по МКБ)"
                                    initial="{{ old('state_code', $log->patient->diagnosis->state->code) }}"
                                    hidden="{{ old('state_value', $log->patient->diagnosis->state->value) }}"
                                />

                                <x-mkd-autocomplete
                                    name="wound"
                                    placeholder="Начните вводить диагноз..."
                                    url="{{ route('mkd.suggestWound') }}"
                                    value="Причина и обстоятельства травмы (в том числе при дорожно-транспортных происшествиях), отравления (код по МКБ)"
                                    initial="{{ old('wound_code', $log->patient->diagnosis->wound->code) }}"
                                    hidden="{{ old('wound_value', $log->patient->diagnosis->wound->value) }}"
                                />

                                <div>
                                    <x-input-label for="fact_alcohol" :value="__('Факт употребления алкоголя и иных психоактивных веществ, установление наличия или отсутствия признаков состояния опьянения при поступлении пациента в медицинскую организацию')" />
                                    <x-text-input id="fact_alcohol" name="fact_alcohol" type="text" class="mt-1 block w-full" :value="$log->log_receipt->fact_alcohol" />
                                    <x-input-error class="mt-2" :messages="$errors->get('fact_alcohol')" />
                                </div>

                                <div>
                                    <x-input-label for="datetime_alcohol" :value="__('Дата и время взятия пробы')" />
                                    <x-text-input id="datetime_alcohol" name="datetime_alcohol" type="datetime-local" class="mt-1 block w-full" :value="$log->log_receipt->datetime_alcohol" />
                                    <x-input-error class="mt-2" :messages="$errors->get('datetime_alcohol')" />
                                </div>

                                <div>
                                    <x-input-label for="result_research" :value="__('Результаты лабораторных исследований')" />
                                    <x-text-input id="result_research" name="result_research" type="text" class="mt-1 block w-full" :value="$log->log_receipt->result_research" />
                                    <x-input-error class="mt-2" :messages="$errors->get('result_research')" />
                                </div>

                                <div>
                                    <x-input-label for="section_medical" :value="__('Отделение медицинской организации, в которое направлен пациент')" />
                                    <x-text-input id="section_medical" name="section_medical" type="text" class="mt-1 block w-full" :value="$log->log_receipt->section_medical" />
                                    <x-input-error class="mt-2" :messages="$errors->get('section_medical')" />
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
                    <div>
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Выписка пациента') }}
                                </h2>
                            </header>
                            <div class="mt-6 space-y-6">
                                <div>
                                    <x-input-label for="outcome" :value="__('Исход госпитализации')" />
                                    <x-select id="outcome" name="outcome" class="mt-1 block w-full">
                                        <option value="" style="font-weight: bold">Пожалуйста, сделайте выбор</option>
                                        <option value="выписан" {{ $log->log_discharge->outcome == 'выписан' ? 'selected' : '' }}>выписан</option>
                                        <option value="переведен в другую медицинскую организацию" {{ $log->log_discharge->outcome == 'переведен в другую медицинскую организацию' ? 'selected' : '' }}>переведен в другую медицинскую организацию</option>
                                        <option value="умер" {{ $log->log_discharge->outcome == 'умер' ? 'selected' : '' }}>умер</option>
                                    </x-select>
                                    <x-input-error class="mt-2" :messages="$errors->get('outcome')" />
                                </div>

                                <div>
                                    <x-input-label for="datetime_discharge" :value="__('Дата и время исхода')" />
                                    <x-text-input id="datetime_discharge" name="datetime_discharge" type="datetime-local" class="mt-1 block w-full" :value="$log->log_discharge->datetime_discharge" />
                                    <x-input-error class="mt-2" :messages="$errors->get('datetime_discharge')" />
                                </div>

                                <div id="medicalOrgField">
                                    <x-input-label for="section_transferred" :value="__('Наименование медицинской организации, куда переведен пациент')" />
                                    <x-text-input id="section_transferred" name="section_transferred" type="text" class="mt-1 block w-full" :value="$log->log_discharge->section_transferred" />
                                    <x-input-error class="mt-2" :messages="$errors->get('section_transferred')" />
                                </div>

                                <div>
                                    <x-input-label for="datetime_inform" :value="__('Дата и время сообщения законному представителю, иному лицу или медицинской организации, направившей пациента, о госпитализации (отказе в госпитализации) пациента, ее исходе')" />
                                    <x-text-input id="datetime_inform" name="datetime_inform" type="datetime-local" class="mt-1 block w-full" :value="$log->log_discharge->datetime_inform" />
                                    <x-input-error class="mt-2" :messages="$errors->get('datetime_inform')" />
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mb-6">
                    <div>
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Отказ в госпитализации') }}
                                </h2>
                            </header>
                            <div class="mt-6 space-y-6">
                                <div>
                                    <x-input-label for="reason_refusal" :value="__('Причина отказа в госпитализации')" />
                                    <x-select id="reason_refusal" name="reason_refusal" class="mt-1 block w-full">
                                        <option value="" selected style="font-weight: bold">Пожалуйста, сделайте выбор...</option>
                                        <option value="отказался пациент" {{ $log->log_reject->reason_refusal == 'отказался пациент' ? 'selected' : '' }}>отказался пациент</option>
                                        <option value="отсутствие показаний" {{ $log->log_reject->reason_refusal == 'отсутствие показаний' ? 'selected' : '' }}>отсутствие показаний</option>
                                        <option value="помощь оказана в приемном отделении медицинской организации" {{ $log->log_reject->reason_refusal == 'помощь оказана в приемном отделении медицинской организации' ? 'selected' : '' }}>помощь оказана в приемном отделении медицинской организации</option>
                                        <option value="направлен в другую медицинскую организацию" {{ $log->log_reject->reason_refusal == 'направлен в другую медицинскую организацию' ? 'selected' : '' }}>направлен в другую медицинскую организацию</option>
                                        <option value="иная причина" {{ $log->log_reject->reason_refusal == 'иная причина' ? 'selected' : '' }}>иная причина</option>
                                    </x-select>
                                    <x-input-error class="mt-2" :messages="$errors->get('reason_refusal')" />
                                </div>

                                <div>
                                    <x-input-label for="name_medical_worker" :value="__('Фамилия, имя, отчество (при наличии) медицинского работника, зафиксировавшего причину отказа в госпитализации')" />
                                    <x-text-input id="name_medical_worker" name="name_medical_worker" type="text" class="mt-1 block w-full" :value="$log->log_reject->name_medical_worker" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name_medical_worker')" />
                                </div>

                                <div>
                                    <x-input-label for="add_info" :value="__('Дополнительные сведения')" />
                                    <x-text-input id="add_info" name="add_info" type="text" class="mt-1 block w-full" :value="$log->log_reject->add_info" />
                                    <x-input-error class="mt-2" :messages="$errors->get('add_info')" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
