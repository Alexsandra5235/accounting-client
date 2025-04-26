<?php

namespace App\Services;

use App\Models\Logs\Log;
use App\Models\Logs\LogDischarge;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Models\Patient\Patient;
use App\Repository\LogRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($request) {
            $patient = app(PatientService::class)->create($request);
            $logReceipt = app(LogReceiptService::class)->create($request);
            $logDischarge = app(LogDischargeService::class)->create($request);
            $logReject = app(LogRejectService::class)->create($request);
            return app(LogRepository::class)->create($logDischarge, $logReceipt, $logReject, $patient);
        });
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return app(LogRepository::class)->destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update(int $id, Request $request): bool
    {
        try {
            return app(LogRepository::class)->update($id, $request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function findAll(): Collection
    {
        return app(LogRepository::class)->findAll();
    }
    public function findByID(int $id): Log
    {
        return app(LogRepository::class)->findByID($id);
    }
}
