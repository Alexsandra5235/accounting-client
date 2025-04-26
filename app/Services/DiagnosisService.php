<?php

namespace App\Services;

use App\Interfaces\DiagnosisInterface;
use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;
use App\Repository\DiagnosisRepository;
use Exception;

class DiagnosisService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Classifiers $wound
     * @param Classifiers $state
     * @return Diagnosis
     */
    public function create(Classifiers $wound, Classifiers $state): Diagnosis
    {
        return app(DiagnosisRepository::class)->create($wound, $state);
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return app(DiagnosisRepository::class)->destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
