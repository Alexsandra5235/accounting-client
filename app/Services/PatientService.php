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
    public function create(Request $request, Diagnosis $diagnosis): Patient
    {
        return app(PatientRepository::class)->create($request, $diagnosis);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            return app(PatientRepository::class)->delete($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
