<?php

namespace App\Services;

use App\Models\Patient\Classifiers;
use App\Repository\ClassifiersRepository;
use Exception;
use Illuminate\Http\Request;

class ClassifiersService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(Request $request): Classifiers
    {
        return app(ClassifiersRepository::class)->create($request);
    }

    /**
     * @throws Exception
     */
    public function destroyLog(int $id): bool
    {
        try {
            return app(ClassifiersRepository::class)->destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
