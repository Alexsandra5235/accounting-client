<?php

namespace App\Services;

use App\Models\Logs\LogReceipt;
use App\Repository\LogReceiptRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 *
 */
class LogReceiptService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Создание записи в базе данных.
     * @param Request $request
     * @return LogReceipt
     */
    public function create(Request $request): LogReceipt
    {
        return app(LogReceiptRepository::class)->create($request);
    }
}
