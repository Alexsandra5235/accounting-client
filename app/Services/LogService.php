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
        $logs = app(ApiService::class)->getLogs(env('API_LOG_TOKEN'));
        $filteredLogs = collect($logs)->filter(function ($log) use ($date1, $date2) {
            try {
                $receiptDateTime = Carbon::createFromFormat('Y-m-d H:i', $log->log_receipt->date_receipt . ' ' . $log->log_receipt->time_receipt);
            } catch (Exception $e) {
                return false;
            }

            return $receiptDateTime->between($date1, $date2);
        });

        $filteredLogsDischarge = collect($logs)->filter(function ($log) use ($date1, $date2) {
            if (empty($log->log_discharge?->datetime_discharge)) {
                return false;
            }
            try {
                $dischargeDateTime = Carbon::parse($log->log_discharge->datetime_discharge);
            } catch (Exception $e) {
                return false;
            }

            return $dischargeDateTime->between($date1, $date2);
        });

        return [
            'receipt' => $filteredLogs->values()->toArray(),
            'discharge' => $filteredLogsDischarge->values()->toArray(),
        ];
    }
}
