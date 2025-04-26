<?php

namespace App\Services;

use App\Models\Patient\Classifiers;
use App\Models\Patient\Diagnosis;
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
    public function createState(Request $request): Classifiers
    {
        return app(ClassifiersRepository::class)->createState($request);
    }
    public function createWound(Request $request): Classifiers
    {
        return app(ClassifiersRepository::class)->createWound($request);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return app(ClassifiersRepository::class)->destroy($id);
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
            app(ClassifiersRepository::class)->updateWound($diagnosis, $request);
            app(ClassifiersRepository::class)->updateState($diagnosis, $request);
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
