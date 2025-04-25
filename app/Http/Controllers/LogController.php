<?php

namespace App\Http\Controllers;

use App\Models\Logs\LogReceipt;
use App\Services\LogReceiptService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function store(Request $request): LogReceipt
    {
        validator($request->all(), [
            'date_receipt' => ['required'],
            'time_receipt' => ['required'],
        ]);
        return app(LogReceiptService::class)->create($request);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool|string
    {
        try {
            return app(LogReceiptService::class)->destroy($id);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
