<?php

namespace app\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface LogInterface
{
    public function create(Request $request): Model;
    public function destroy(int $id): bool;
}
