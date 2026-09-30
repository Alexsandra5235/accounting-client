<?php

namespace App\Services\Export;

use App\Services\Api\ApiService;
use Illuminate\Http\Client\ConnectionException;

class ExportToCsvService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    /** * Экспорт данных о пациентах в CSV. * * @throws ConnectionException */
    public function export(): string
    {
        $logs = app(ApiService::class)->getLogs(config('api.log_token'));
        $fileName = 'patients_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $directory = storage_path('app/imports'); // Создаём директорию, если её нет
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $filePath = $directory . '/' . $fileName;
        $file = fopen($filePath, 'w');

        // BOM для корректного открытия UTF-8 в Excel
        fwrite($file, "\xEF\xBB\xBF");
        // Заголовки CSV
        fputcsv($file, [
            'ФИО',
            'Дата рождения',
            'Пол',
            'Медицинская карта',
            'Паспорт',
            'Гражданство',
            'Адрес',
            'Адрес регистрации',
            'СНИЛС',
            'Полис',
            'Код диагноза',
            'Диагноз',
            'Код повреждения',
            'Повреждение',
            'Дата поступления',
            'Время поступления',
            'Дата и время выписки',
            'Исход',
            'Переведён в отделение',
            'Причина отказа'
            ], ';');

        foreach ($logs as $log) {
            $patient = $log->patient ?? null;
            $diagnosis = $patient?->diagnosis;
            $state = $diagnosis?->state;
            $wound = $diagnosis?->wound;
            $receipt = $log->log_receipt ?? null;
            $discharge = $log->log_discharge ?? null;
            $reject = $log->log_reject ?? null;
            fputcsv($file, [
                $patient?->name,
                $patient?->birth_day,
                $patient?->gender,
                $patient?->medical_card,
                $patient?->passport,
                $patient?->nationality,
                $patient?->address,
                $patient?->register_place,
                $patient?->snils,
                $patient?->polis,
                $state?->code,
                $state?->value,
                $wound?->code,
                $wound?->value,
                $receipt?->date_receipt,
                $receipt?->time_receipt,
                $discharge?->datetime_discharge,
                $discharge?->outcome,
                $discharge?->section_transferred,
                $reject?->reason_refusal],
        ';'
            );
        }
        fclose($file);
        return $filePath;
    }
}
