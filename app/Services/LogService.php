<?php

namespace App\Services;

use App\Models\Logs\Log;
use App\Models\Logs\LogDischarge;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Models\Patient\Patient;
use App\Repository\LogRepository;
use Exception;
use Illuminate\Http\Request;

class LogService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(Request $request): Log
    {
        $patient = app(PatientService::class)->create($request);
        $logReceipt = app(LogReceiptService::class)->create($request);
        $logDischarge = app(LogDischargeService::class)->create($request);
        $logReject = app(LogRejectService::class)->create($request);
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
