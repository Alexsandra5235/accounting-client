<?php

namespace App\Interfaces;

use App\Models\Patient\Classifiers;
use Illuminate\Http\Request;

interface ClassifiersInterface
{
    public function createState(Request $request): Classifiers;
    public function createWound(Request $request): Classifiers;
    public function destroy(int $id): bool;
}
