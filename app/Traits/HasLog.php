<?php

namespace app\Traits;

use App\Models\Logs\LogReceipt;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

trait HasLog
{
    /**
     * Создание записи в базе данных.
     * @param Request $request
     * @param class-string<Model> $modelClass
     * @return mixed
     */
    public function createLog(Request $request, string $modelClass): Model
    {
        return $modelClass::query()->create($request->all());
    }

    /**
     * Поиск записи по id
     * @param int $id
     * @param class-string<Model> $modelClass
     * @return LogReceipt
     * Если запись была найдена она будет возвращена,
     * в противном случае возвращается исключение.
     */
    public function findByIdLog(int $id, string $modelClass): Model
    {
        try {
            return $modelClass::query()->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException("Record with id = {$id} not found");
        }
    }

    /**
     * Удаление записи из БД
     * @param int $id
     * @param class-string<Model> $modelClass
     * @return bool
     * Если удаление успешно, то вернет true,
     * если нет, то исключения.
     * @throws Exception
     */
    public function destroyLog(int $id, string $modelClass): bool
    {
        try {
            $model = $this->findByIdLog($id, $modelClass);
            $model->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
