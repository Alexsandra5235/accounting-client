<?php

namespace App\Repository;

use App\Interfaces\DeleteInterface;
use App\Interfaces\DiagnosisInterface;
use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;
use App\Services\ClassifiersService;
use app\Traits\HasLog;
use Exception;

class DiagnosisRepository implements DiagnosisInterface, DeleteInterface
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
     * @param Classifiers $wound
     * @param Classifiers $state
     * @return Diagnosis
     */
    public function create(Classifiers $wound, Classifiers $state): Diagnosis
    {
        return Diagnosis::query()->create([
            'wound_id' => $wound->id,
            'state_id' => $state->id,
        ]);
    }

    /**
     * Удаление данных из таблицы Diagnosis и связанные с ними записи
     * в таблице Classifiers
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            $diagnosis = $this->findByIdLog($id, Diagnosis::class);
            if ($diagnosis instanceof Diagnosis) {
                app(ClassifiersService::class)->destroy($diagnosis->wound->id);
                app(ClassifiersService::class)->destroy($diagnosis->state->id);
            }
            $diagnosis->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
