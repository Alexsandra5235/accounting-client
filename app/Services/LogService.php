<?php

namespace App\Services;

use App\Services\Api\ApiService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\ConnectionException;

class LogService
{
    /**
     * @throws ConnectionException
     */
    public function getLogsByDates(string $date1, string $date2): array
    {
        $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $date1 . ' 08:00:00');
        $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $date2 . ' 07:59:00');

        $logs = collect(app(ApiService::class)->getLogs(env('API_LOG_TOKEN')));
        $logsReceipt = $logs->filter(fn($log) => $this->isInReceiptRange($log, $date1, $date2));

        return [
            'receipt' => $logsReceipt->values()->toArray(),
            'discharge' => $logs->filter(fn($log) => $this->isInDischargeRange($log, $date1, $date2))->values()->toArray(),
            'before' => $logs->filter(fn($log) => $this->isReceiptBefore($log, $date1))->values()->toArray(),
            'birthday' => $logsReceipt->filter(fn($log) => $this->isChild($log))->values()->toArray(),
            'total' => $logs->filter(fn($log) => $this->isNotDischargedAndBefore($log, $date2))->values()->toArray(),
        ];
    }
    private function isInReceiptRange($log, Carbon $start, Carbon $end): bool
    {
        try {
            $dt = Carbon::createFromFormat('Y-m-d H:i', $log->log_receipt->date_receipt . ' ' . $log->log_receipt->time_receipt);
            return $dt->between($start, $end);
        } catch (Exception) {
            return false;
        }
    }

    private function isInDischargeRange($log, Carbon $start, Carbon $end): bool
    {
        if (empty($log->log_discharge?->datetime_discharge)) {
            return false;
        }

        try {
            $dt = Carbon::parse($log->log_discharge->datetime_discharge);
            return $dt->between($start, $end);
        } catch (Exception) {
            return false;
        }
    }

    private function isReceiptBefore($log, Carbon $date): bool
    {
        try {
            $dt = Carbon::createFromFormat('Y-m-d H:i', $log->log_receipt->date_receipt . ' ' . $log->log_receipt->time_receipt);
            return $dt->isBefore($date);
        } catch (Exception) {
            return false;
        }
    }

    private function isChild($log): bool
    {
        try {
            return Carbon::parse($log->patient->birth_day)->age <= 17;
        } catch (Exception) {
            return false;
        }
    }

    private function isNotDischargedAndBefore($log, Carbon $date): bool
    {
        if (!empty($log->log_discharge?->datetime_discharge)) {
            return false;
        }

        try {
            $dt = Carbon::createFromFormat('Y-m-d H:i', $log->log_receipt->date_receipt . ' ' . $log->log_receipt->time_receipt);
            return $dt->isBefore($date);
        } catch (Exception) {
            return false;
        }
    }
}
