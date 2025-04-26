<?php

namespace App\Services;

use App\Models\Logs\Log;
use App\Models\Logs\LogDischarge;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Models\Patient\Patient;
use App\Repository\LogRepository;
use Exception;

class LogService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(LogReceipt $logReceipt, LogDischarge $logDischarge, LogReject $logReject,
                           Patient $patient): Log
    {
        return app(LogRepository::class)->create($logDischarge, $logReceipt, $logReject, $patient);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return app(LogRepository::class)->delete($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
