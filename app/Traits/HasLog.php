<?php

namespace app\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HasLog
{
    /**
     * Создание записи в базу данных.
     * @param Request $request
     * @param class-string<Model> $modelClass
     * @return mixed
     */
    public function createLog(Request $request, string $modelClass): Model
    {
        return $modelClass::query()->create($request->all());
    }
}
