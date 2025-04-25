<?php

namespace App\Repository;

use app\Interfaces\LogInterface;
use App\Models\Logs\LogReceipt;
use Illuminate\Http\Request;

/**
 *
 */
class LogReceiptRepository implements LogInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Создание записи в базе данных
     * @param Request $request
     * @return LogReceipt
     */
    public function create(Request $request): LogReceipt
    {
        return LogReceipt::query()->create([
            'date_receipt' => $request->input('date_receipt'),
            'time_receipt' => $request->input('time_receipt'),
        ]);
    }
}
