<?php

namespace App\Services;

use App\Models\Patient\Diagnosis;
use App\Models\Patient\Patient;
use App\Repository\PatientRepository;
use Exception;
use Illuminate\Http\Request;

class PatientService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(Request $request): Patient
    {
        $diagnosis = app(DiagnosisService::class)->create($request);
        return app(PatientRepository::class)->create($request, $diagnosis);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return app(PatientRepository::class)->destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id): bool
    {
        try {
            return app(PatientRepository::class)->update($id, $request);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
