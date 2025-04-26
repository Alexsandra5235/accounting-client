<?php

namespace App\Interfaces;

use App\Models\Patient\Classifiers;
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
}
