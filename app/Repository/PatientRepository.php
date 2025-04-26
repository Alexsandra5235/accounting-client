<?php

namespace App\Repository;

use App\Interfaces\DeleteInterface;
use App\Interfaces\LogModelInterface;
use App\Interfaces\PatientInterface;
use App\Models\Patient\Diagnosis;
use App\Models\Patient\Patient;
use app\Traits\HasLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PatientRepository implements PatientInterface, DeleteInterface
{
    use HasLog;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    /**
     * @param Request $request
     * @param Diagnosis $diagnosis
     * @return Patient
     */
    public function create(Request $request, Diagnosis $diagnosis): Patient
    {
        $data = $request->all();
        $data['diagnosis_id'] = $diagnosis->id;
        return Patient::query()->create($data);
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            $patient = $this->findByIdLog($id, Patient::class);
            $patient->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
