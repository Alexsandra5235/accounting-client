<?php

namespace App\Interfaces;

use App\Models\Logs\Log;
use App\Models\Logs\LogDischarge;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Models\Patient\Patient;
use Illuminate\Http\Request;
use Monolog\LogRecord;

/**
 * Реализует добавление данных в таблицу Log.
 */
interface LogInterface
{
    /**
     * @param LogDischarge $logDischarge
     * @param LogReceipt $logReceipt
     * @param LogReject $logReject
     * @param Patient $patient
     * @return Log
     */
    public function create(LogDischarge $logDischarge, LogReceipt $logReceipt,
                           LogReject    $logReject, Patient $patient): Log;

    public function update(int $id, Request $request): bool;
}
