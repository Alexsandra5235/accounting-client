<?php

namespace App\Interfaces;

use App\Models\Patient\Diagnosis;
use App\Models\Patient\Patient;
use Illuminate\Http\Request;

/**
 * Реализует добавление данных в таблицу Patient.
 */
interface PatientInterface
{
    /**
     * @param Request $request
     * @param Diagnosis $diagnosis
     * @return Patient
     */
    public function create(Request $request, Diagnosis $diagnosis): Patient;
    public function update(int $id, Request $request): bool;

}
