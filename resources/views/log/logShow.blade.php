<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Просмотр записи') }}
        </h2>
        <p class="font-normal text-gray-800 dark:text-gray-200 leading-tight">
            На данной странице отображается полная информация по пациенту санатория.
        </p>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Информация о записи</h3>
                    <p>Дата и время создания записи: <strong style="color: #2563eb">{{ \Carbon\Carbon::parse($log->created_at)->addHours(7)->locale('ru')->translatedFormat('D, d M Y H:i') }}</strong></p>
                    <p>Дата и время последнего редактирования: <strong style="color: #2563eb">{{ \Carbon\Carbon::parse($log->updated_at)->addHours(7)->locale('ru')->translatedFormat('D, d M Y H:i') }}</strong></p>
                    <x-link-primary-button class="mt-2" href="{{ route('log.edit', ['id' => $log->id]) }}">Редактировать запись</x-link-primary-button>
                    <form action="{{ route('log.destroy', ['id' => $log->id]) }}" method="post" class="mt-2" onsubmit="return confirmDeletion({{ json_encode($log->patient->name) }})">
                        @csrf
                        @method('delete')
                        <x-danger-button>Удалить запись</x-danger-button>
                    </form>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Информация о пациенте</h3>
                    <div class="space-y-2">
                        <p>ФИО пациента: <strong style="color: #2563eb">{{ $log->patient->name }}</strong></p>
                        <p>Дата и время поступления: <strong style="color: #2563eb">{{ \Carbon\Carbon::parse($log->log_receipt->date_receipt)->locale('ru')->translatedFormat('D, d M Y') }} {{ \Carbon\Carbon::parse($log->log_receipt->time_receipt)->locale('ru')->translatedFormat('H:i') }}</strong></p>
                        <p>Фамилия, имя, отчество (при наличии): <strong style="color: #2563eb">{{ $log->patient->name }}</strong></p>
                        <p>Дата рождения: <strong style="color: #2563eb">{{ $log->patient->birth_day }}</strong></p>
                        <p>Пол: <strong style="color: #2563eb">{{ $log->patient->gender }}</strong></p>
                        <p>Номер медицинской карты: <strong style="color: #2563eb">{{ $log->patient->medical_card }}</strong></p>
                        <p>Серия и номер паспорта: <strong style="color: #2563eb">{{ $log->patient->passport }}</strong></p>
                        <p>Гражданство: <strong style="color: #2563eb">{{ $log->patient->nationality }}</strong></p>
                        <p>Регистрация по месту жительства: <strong style="color: #2563eb">{{ $log->patient->address }}</strong></p>
                        <p>Регистрация по месту пребывания: <strong style="color: #2563eb">{{ $log->patient->register_place }}</strong></p>
                        <p>СНИСЛ: <strong style="color: #2563eb">{{ $log->patient->snils }}</strong></p>
                        <p>Полис ОМС: <strong style="color: #2563eb">{{ $log->patient->polis }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Дополнительная информация</h3>
                    <div class="space-y-2">
                        <p>Телефон законного представителя: <strong style="color: #2563eb">{{ $log->log_receipt->phone_agent }}</strong></p>
                        <p>Пациент доставлен: <strong style="color: #2563eb">{{ $log->log_receipt->delivered }}</strong></p>
                        <p>Диагноз: <strong style="color: #2563eb">{{ $log->patient->diagnosis->state->code }} - {{ $log->patient->diagnosis->state->value }}</strong></p>
                        <p>Причина и обстоятельства травмы: <strong style="color: #2563eb">{{ $log->patient->diagnosis->wound->code }} - {{ $log->patient->diagnosis->wound->value }}</strong></p>
                        <p>Факт употребления алкоголя: <strong style="color: #2563eb">{{ $log->log_receipt->fact_alcohol }}</strong></p>
                        <p>Дата и время взятия пробы: <strong style="color: #2563eb">{{ $log->log_receipt->datetime_alcohol }}</strong></p>
                        <p>Результаты исследований: <strong style="color: #2563eb">{{ $log->log_receipt->result_research }}</strong></p>
                        <p>Отделение, в которое направлен пациент: <strong style="color: #2563eb">{{ $log->log_receipt->section_medical }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Информация по выписке пациента</h3>
                    <div class="space-y-2">
                        <p>Исход госпитализации: <strong style="color: #2563eb">{{ $log->log_discharge->outcome }}</strong></p>
                        <p>Дата и время исхода: <strong style="color: #2563eb">{{ $log->log_discharge->datetime_discharge }}</strong></p>
                        <p>Наименование медицинской организации: <strong style="color: #2563eb">{{ $log->log_discharge->section_transferred }}</strong></p>
                        <p>Дата и время сообщения: <strong style="color: #2563eb">{{ $log->log_discharge->datetime_inform }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Отказ в госпитализации</h3>
                    <div class="space-y-2">
                        <p>Причина отказа: <strong style="color: #2563eb">{{ $log->log_reject->reason_refusal }}</strong></p>
                        <p>Медицинский работник: <strong style="color: #2563eb">{{ $log->log_reject->name_medical_worker }}</strong></p>
                        <p>Дополнительные сведения: <strong style="color: #2563eb">{{ $log->log_reject->add_info }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
