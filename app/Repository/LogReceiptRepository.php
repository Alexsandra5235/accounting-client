<?php

namespace App\Repository;

use app\Interfaces\LogInterface;
use App\Models\Logs\LogReceipt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            $logReceipt = $this->findById($id);
            $logReceipt->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Поиск записи по id
     * @param int $id
     * @return LogReceipt
     * Если запись была найдена она будет возвращена,
     * в противном случае возвращается исключение.
     */
    public function findById(int $id): LogReceipt
    {
        try {
            return LogReceipt::query()->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException("Log Receipt with id = {$id} not found");
        }
    }
}
