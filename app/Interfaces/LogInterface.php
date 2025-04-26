<?php

namespace App\Interfaces;

use App\Models\Logs\Log;
use App\Models\Logs\LogDischarge;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Models\Patient\Patient;
use Monolog\LogRecord;

interface LogInterface
{
    public function create(LogDischarge $logDischarge, LogReceipt $logReceipt,
                           LogReject $logReject, Patient $patient): Log;
    public function destroy(int $id): bool;
}
