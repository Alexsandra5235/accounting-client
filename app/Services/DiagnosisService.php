<?php

namespace App\Services;

use App\Interfaces\DiagnosisInterface;
use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;
use App\Models\Patient\Patient;
use App\Repository\DiagnosisRepository;
use Exception;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @return Diagnosis
     */
    public function create(Request $request): Diagnosis
    {
        $state = app(ClassifiersService::class)->createState($request);
        $wound = app(ClassifiersService::class)->createWound($request);
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

    /**
     * @throws Exception
     */
    public function update(Diagnosis $diagnosis, Request $request): bool
    {
        try {
            return app(DiagnosisRepository::class)->update($diagnosis, $request);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
