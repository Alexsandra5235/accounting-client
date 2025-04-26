<?php

namespace App\Interfaces;

use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;

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

}
