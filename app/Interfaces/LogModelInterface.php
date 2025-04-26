<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Реализует добавление данных в таблицу, не имеющую составных ключей.
 */
interface LogModelInterface
{
    /**
     * @param Request $request
     * @return Model
     */
    public function create(Request $request): Model;

}
