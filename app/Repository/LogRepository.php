<?php

namespace App\Repository;

use App\Interfaces\DeleteInterface;
use App\Interfaces\LogInterface;
use App\Models\Logs\Log;
use App\Models\Logs\LogDischarge;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Models\Patient\Patient;
use App\Services\LogDischargeService;
use App\Services\LogReceiptService;
use App\Services\LogRejectService;
use App\Services\LogService;
use App\Services\PatientService;
use app\Traits\HasLog;
use Exception;
use Illuminate\Support\Facades\DB;

class LogRepository implements LogInterface, DeleteInterface
{
    use HasLog;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param LogDischarge $logDischarge
     * @param LogReceipt $logReceipt
     * @param LogReject $logReject
     * @param Patient $patient
     * @return Log
     */
    public function create(LogDischarge $logDischarge, LogReceipt $logReceipt, LogReject $logReject,
                           Patient $patient): Log
    {
        return Log::query()->create([
            'log_receipt_id' => $logReceipt->id,
            'log_discharge_id' => $logDischarge->id,
            'log_reject_id' => $logReject->id,
            'patient_id' => $patient->id,
        ]);
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            DB::transaction(function () use ($id) {
                $log = $this->findByIdLog($id, Log::class);
                if ($log instanceof Log) {
                    app(LogReceiptService::class)->destroy($log->log_receipt->id);
                    app(LogRejectService::class)->destroy($log->log_reject->id);
                    app(LogDischargeService::class)->destroy($log->log_discharge->id);
                    app(PatientService::class)->destroy($log->patient->id);
                }
                $log->delete();
            });
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
