<?php

namespace App\Repository;

use App\Interfaces\ClassifiersInterface;
use App\Interfaces\DeleteInterface;
use App\Models\Patient\Classifiers;
use app\Traits\HasLog;
use Exception;
use Illuminate\Http\Request;

class ClassifiersRepository implements ClassifiersInterface, DeleteInterface
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
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return $this->destroyLog($id, Classifiers::class);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return Classifiers
     */
    public function createState(Request $request): Classifiers
    {
        return Classifiers::query()->create([
            'code' => $request->get('state_code'),
            'value' => $request->get('state_value'),
        ]);
    }

    /**
     * @param Request $request
     * @return Classifiers
     */
    public function createWound(Request $request): Classifiers
    {
        return Classifiers::query()->create([
            'code' => $request->get('wound_code'),
            'value' => $request->get('wound_value'),
        ]);
    }
}
