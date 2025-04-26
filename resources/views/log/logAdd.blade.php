<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Добавление новой записи') }}
        </h2>
    </x-slot>

    @error('error_store')
    <div style="padding-top: 48px">
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('log.store') }}" method="post">
                @csrf
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
                                    <x-text-input id="date_receipt" name="date_receipt" type="date" class="mt-1 block w-full" :value="old('date_receipt', \Carbon\Carbon::parse(now())->locale('ru')->translatedFormat('Y-m-d'))"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('date_receipt')" />
                                </div>

                                <div>
                                    <x-input-label for="time_receipt" :value="__('Время поступления')" />
                                    <x-text-input id="time_receipt" name="time_receipt" type="time" class="mt-1 block w-full" :value="old('time_receipt', now()->format('H:i'))"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('time_receipt')" />
                                </div>

                                <div>
                                    <x-input-label for="name" :value="__('Фамилия, имя, отчество (при наличии)')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>

                                <div>
                                    <x-input-label for="birth_day" :value="__('Дата рождения (число, месяц, год)')" />
                                    <x-text-input id="birth_day" name="birth_day" type="date" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('birth_day')" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Пол (мужской, женский)')"/>
                                    <select id="gender" name="gender" class="mt-1 block w-full dark:bg-gray-800 text-white rounded-lg border border-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="" style="font-weight: bold">Пожалуйста, сделайте выбор</option>
                                        <option value="мужской">мужской</option>
                                        <option value="женский">женский</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                </div>

                                <div>
                                    <x-input-label for="medical_card" :value="__('Номер медицинской карты')" />
                                    <x-text-input id="medical_card" name="medical_card" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('medical_card')" />
                                </div>

                                <div>
                                    <x-input-label for="passport" :value="__('Серия и номер паспорта или иного документа, удостоверяющего личность (при наличии)')" />
                                    <x-text-input id="passport" name="passport" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('passport')" />
                                </div>

                                <div>
                                    <x-input-label for="nationality" :value="__('Гражданство')" />
                                    <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('nationality')" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Регистрация по месту жительства')" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"  autocomplete="off" />
                                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                </div>

                                <div>
                                    <x-input-label for="snils" :value="__('СНИСЛ (при наличии)')" />
                                    <x-text-input id="snils" name="snils" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('snils')" />
                                </div>

                                <div>
                                    <x-input-label for="polis" :value="__('Полис обязательного медицинского страхования (при наличии)')" />
                                    <x-text-input id="polis" name="polis" type="text" class="mt-1 block w-full"  />
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
                                    <x-text-input id="phone_agent" name="phone_agent" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('phone_agent')" />
                                </div>

                                <div>
                                    <x-input-label for="delivered" :value="__('Пациент доставлен (направлен)')" />
                                    <select id="delivered" name="delivered" class="mt-1 block w-full dark:bg-gray-800 text-white rounded-lg border border-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="" style="font-weight: bold">Пожалуйста, сделайте выбор</option>
                                        <option value="полицией" >полицией</option>
                                        <option value="выездной бригадой скорой медицинской помощи">выездной бригадой скорой медицинской помощи</option>
                                        <option value="другой медицинской организацией">другой медицинской организацией</option>
                                        <option value="обратился самостоятельно">обратился самостоятельно</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('delivered')" />
                                </div>

                                <div>
                                    <x-input-label for="state_code" :value="__('Диагноз заболевания (состояния), поставленный направившей медицинской организацией (код по МКБ)')" />
                                    <x-text-input id="state_code" name="state_code" type="text" class="mt-1 block w-full"  />
                                    <input type="text" id="state_value" name="state_value" hidden="hidden" value="">
                                    <x-input-error class="mt-2" :messages="$errors->get('state_code')" />
                                </div>

                                <div>
                                    <x-input-label for="wound_code" :value="__('Причина и обстоятельства травмы (в том числе при дорожно-транспортных происшествиях), отравления (код по МКБ)')" />
                                    <x-text-input id="wound_code" name="wound_code" type="text" class="mt-1 block w-full"  />
                                    <input type="text" id="wound_value" name="wound_value" hidden="hidden" value="">
                                    <x-input-error class="mt-2" :messages="$errors->get('wound_code')" />
                                </div>

                                <div>
                                    <x-input-label for="fact_alcohol" :value="__('Факт употребления алкоголя и иных психоактивных веществ, установление наличия или отсутствия признаков состояния опьянения при поступлении пациента в медицинскую организацию')" />
                                    <x-text-input id="fact_alcohol" name="fact_alcohol" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('fact_alcohol')" />
                                </div>

                                <div>
                                    <x-input-label for="datetime_alcohol" :value="__('Дата и время взятия пробы')" />
                                    <x-text-input id="datetime_alcohol" name="datetime_alcohol" type="datetime-local" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('datetime_alcohol')" />
                                </div>

                                <div>
                                    <x-input-label for="result_research" :value="__('Результаты лабораторных исследований')" />
                                    <x-text-input id="result_research" name="result_research" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('result_research')" />
                                </div>

                                <div>
                                    <x-input-label for="section_medical" :value="__('Отделение медицинской организации, в которое направлен пациент')" />
                                    <x-text-input id="section_medical" name="section_medical" type="text" class="mt-1 block w-full"  />
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
                                    <select id="outcome" name="outcome" class="mt-1 block w-full dark:bg-gray-800 text-white rounded-lg border border-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="" style="font-weight: bold">Пожалуйста, сделайте выбор</option>
                                        <option value="выписан" >выписан</option>
                                        <option value="переведен в другую медицинскую организацию" >переведен в другую медицинскую организацию</option>
                                        <option value="умер">умер</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('outcome')" />
                                </div>

                                <div>
                                    <x-input-label for="datetime_discharge" :value="__('Дата и время исхода')" />
                                    <x-text-input id="datetime_discharge" name="datetime_discharge" type="datetime-local" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('datetime_discharge')" />
                                </div>

                                <div id="medicalOrgField">
                                    <x-input-label for="section_transferred" :value="__('Наименование медицинской организации, куда переведен пациент')" />
                                    <x-text-input id="section_transferred" name="section_transferred" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('section_transferred')" />
                                </div>

                                <div>
                                    <x-input-label for="datetime_inform" :value="__('Дата и время сообщения законному представителю, иному лицу или медицинской организации, направившей пациента, о госпитализации (отказе в госпитализации) пациента, ее исходе')" />
                                    <x-text-input id="datetime_inform" name="datetime_inform" type="datetime-local" class="mt-1 block w-full"  />
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
                                    <select id="reason_refusal" name="reason_refusal" class="mt-1 block w-full dark:bg-gray-800 text-white rounded-lg border border-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="" selected style="font-weight: bold">Пожалуйста, сделайте выбор...</option>
                                        <option value="отказался пациент" >отказался пациент</option>
                                        <option value="отсутствие показаний">отсутствие показаний</option>
                                        <option value="помощь оказана в приемном отделении медицинской организации">помощь оказана в приемном отделении медицинской организации</option>
                                        <option value="направлен в другую медицинскую организацию" >направлен в другую медицинскую организацию</option>
                                        <option value="иная причина">иная причина</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('reason_refusal')" />
                                </div>

                                <div>
                                    <x-input-label for="name_medical_worker" :value="__('Фамилия, имя, отчество (при наличии) медицинского работника, зафиксировавшего причину отказа в госпитализации')" />
                                    <x-text-input id="name_medical_worker" name="name_medical_worker" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('name_medical_worker')" />
                                </div>

                                <div>
                                    <x-input-label for="add_info" :value="__('Дополнительные сведения')" />
                                    <x-text-input id="add_info" name="add_info" type="text" class="mt-1 block w-full"  />
                                    <x-input-error class="mt-2" :messages="$errors->get('add_info')" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Добавить') }}</x-primary-button>
                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
