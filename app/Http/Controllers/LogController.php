<?php

namespace App\Http\Controllers;

use App\Models\Logs\LogDischarge;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Services\LogDischargeService;
use App\Services\LogReceiptService;
use App\Services\LogRejectService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function store(Request $request): LogDischarge
    {
        return app(LogDischargeService::class)->create($request);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool|string
    {
        try {
            return app(LogDischargeService::class)->destroy($id);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
