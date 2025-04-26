<?php

namespace App\Interfaces;

use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;

interface DiagnosisInterface
{
    public function create(Classifiers $wound, Classifiers $state) : Diagnosis;
    public function destroy(int $id) : bool;
}
