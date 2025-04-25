<?php

namespace App\Http\Controllers;

use App\Models\Logs\LogReceipt;
use App\Services\LogReceiptService;
use Illuminate\Http\Request;

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
}
