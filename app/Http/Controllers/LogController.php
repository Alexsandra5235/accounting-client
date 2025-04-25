<?php

namespace App\Http\Controllers;

use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use App\Services\LogReceiptService;
use App\Services\LogRejectService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function store(Request $request): LogReject
    {
        return app(LogRejectService::class)->create($request);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool|string
    {
        try {
            return app(LogRejectService::class)->destroy($id);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
