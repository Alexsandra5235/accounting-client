<?php

namespace App\Interfaces;

use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;
use Illuminate\Http\Request;

/**
 * Реализуй добавление данных в таблицу Diagnosis.
 */
interface DiagnosisInterface
{
    /**
     * @param Classifiers $wound
     * @param Classifiers $state
     * @return Diagnosis
     */
    public function create(Classifiers $wound, Classifiers $state) : Diagnosis;
    public function update(Diagnosis $diagnosis, Request $request) : bool;

}
