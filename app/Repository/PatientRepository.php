<?php

namespace App\Repository;

use app\Interfaces\LogInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PatientRepository implements LogInterface
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
     * @return Model
     */
    public function create(Request $request): Model
    {
        // TODO: Implement create() method.
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        // TODO: Implement destroy() method.
    }
}
