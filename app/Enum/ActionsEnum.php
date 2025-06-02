<?php

namespace App\Enum;

use App\Models\Action;
use App\Services\Api\ApiService;
use Illuminate\Http\Client\ConnectionException;

enum ActionsEnum: string
{
    case ADD = 'add';
    case EDIT = 'edit';
    case DELETE = 'delete';

    /**
     * @throws ConnectionException
     */
    public function message(int $log_id = null, string $patientName = null): string
    {
        return match ($this) {
            self::ADD => $this->getAddMessage($log_id),
            self::EDIT => $this->getEditMessage($log_id),
            self::DELETE => $this->getDeleteMessage($patientName),
        };
    }

    /**
     * @throws ConnectionException
     */
    private function getAddMessage(int $log_id): string
    {
        $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $log_id);
        $log = json_decode($log->getBody(), true);
        return "Запись о пациенте {$log->patient->name} была добавлена.";
    }

    /**
     * @throws ConnectionException
     */
    private function getEditMessage(int $log_id): string
    {
        $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $log_id);
        $log = json_decode($log->getBody(), true);
        return "Запись о пациенте {$log->patient->name} была изменена.";
    }
    private function getDeleteMessage(string $patientName): string
    {
        return "Запись о пациенте {$patientName} была удалена.";
    }

    public function getAction(): Action
    {
        return Action::query()->where('value', '=', $this->value)->first();
    }
}
