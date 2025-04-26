<?php

namespace App\Repository;

use App\Interfaces\DeleteInterface;
use App\Interfaces\LogModelInterface;
use App\Models\Logs\LogReceipt;
use app\Traits\HasLog;
use Exception;
use Illuminate\Http\Request;

/**
 *
 */
class LogReceiptRepository implements LogModelInterface, DeleteInterface
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
     * Создание записи в базе данных
     * @param Request $request
     * @return LogReceipt
     */
    public function create(Request $request): LogReceipt
    {
        return $this->createLog($request, LogReceipt::class);
    }

    /**
     * Удаление записи из БД
     * @param int $id
     * @return bool
     * Если удаление успешно, то вернет true,
     * если нет, то исключения.
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return $this->destroyLog($id, LogReceipt::class);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

}
