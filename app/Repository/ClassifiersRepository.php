<?php

namespace App\Repository;

use app\Interfaces\LogInterface;
use App\Models\Patient\Classifiers;
use app\Traits\HasLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ClassifiersRepository implements LogInterface
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
     * @return Classifiers
     */
    public function create(Request $request): Classifiers
    {
        return $this->createLog($request, Classifiers::class);
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
}
