<div class="mb-4">
    <p class="text-gray-600">
        В таблице представлена информация о выписанных пациентах.
    </p>
</div>

<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <div class="flex items-center gap-1">
                    <i class="fas fa-calendar-check"></i>
                    Дата выписки
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
                    <i class="fas fa-calendar-alt"></i>
                    Период лечения
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

                $datetimeDischarge = is_array($log)
                    ? ($log['log_discharge']['datetime_discharge'] ?? $log->log_discharge->datetime_discharge ?? null)
                    : ($log->log_discharge->datetime_discharge ?? null);

                $logId = is_array($log) ? ($log['id'] ?? $log->id) : $log->id;

                $age = $patientBirthDay ? \Carbon\Carbon::parse($patientBirthDay)->age : 'N/A';

                // Вычисляем период лечения
                if ($dateReceipt && $datetimeDischarge) {
                    $startDate = \Carbon\Carbon::parse($dateReceipt);
                    $endDate = \Carbon\Carbon::parse($datetimeDischarge);
                    $daysDiff = $startDate->diffInDays($endDate);

                    if ($daysDiff == 0) {
                        $treatmentPeriod = 'менее дня';
                    } elseif ($daysDiff == 1) {
                        $treatmentPeriod = '1 день';
                    } elseif ($daysDiff < 5) {
                        $treatmentPeriod = (int)$daysDiff . ' дня';
                    } else {
                        $treatmentPeriod = (int)$daysDiff . ' дней';
                    }
                }
            @endphp

            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-900">
                            @if($datetimeDischarge)
                                {{ \Carbon\Carbon::parse($datetimeDischarge)->translatedFormat('d M Y') }}
                            @else
                                Не указана
                            @endif
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($datetimeDischarge)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-clock mr-1"></i>
                            {{ \Carbon\Carbon::parse($datetimeDischarge)->format('H:i') }}
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
                <td class="px-6 py-4 whitespace-nowrap">
                    @if(isset($treatmentPeriod))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $treatmentPeriod }}
                        </span>
                    @else
                        <span class="text-gray-400 text-sm">Не указано</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('log.find', ['id' => $logId]) }}"
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <i class="fas fa-eye mr-1"></i>
                            Просмотр
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
            Отображено {{ count($logs) }} записей
            @if(request()->has('search_name') && !empty($search_name))
                по запросу "<span class="semibold">{{ $search_name }}</span>"
            @endif
        </div>
        <div class="text-sm text-gray-600">
            <i class="fas fa-database mr-2"></i>
            Обновлено: {{ now()->translatedFormat('d M Y, H:i') }}
        </div>
    </div>
</div>
