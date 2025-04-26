<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface LogModelInterface
{
    public function create(Request $request): Model;
    public function destroy(int $id): bool;
}
