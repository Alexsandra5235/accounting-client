<?php

namespace App\Interfaces;

use App\Models\Patient\Diagnosis;
use App\Models\Patient\Patient;
use Illuminate\Http\Request;

interface PatientInterface
{
    public function create(Request $request, Diagnosis $diagnosis): Patient;
    public function destroy(int $id): bool;
}
