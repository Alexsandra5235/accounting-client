<?php

namespace App\Interfaces;

use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;
use Illuminate\Http\Request;

/**
 * Реализует добавление данных state и wound.
 */
interface ClassifiersInterface
{
    /**
     * @param Request $request
     * @return Classifiers
     */
    public function createState(Request $request): Classifiers;

    /**
     * @param Request $request
     * @return Classifiers
     */
    public function createWound(Request $request): Classifiers;
    public function updateState(Diagnosis $diagnosis, Request $request): bool;
    public function updateWound(Diagnosis $diagnosis, Request $request): bool;
}
