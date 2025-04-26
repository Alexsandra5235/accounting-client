<?php

namespace App\Services;

use App\Models\Logs\Log;
use App\Models\Logs\LogReceipt;
use App\Repository\LogReceiptRepository;
use Exception;
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

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return app(LogReceiptRepository::class)->destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id): bool
    {
        try {
            return app(LogReceiptRepository::class)->update($id, $request);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
