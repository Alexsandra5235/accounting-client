<?php

namespace App\Services;

use App\Models\Logs\LogReject;
use App\Repository\LogRejectRepository;
use Exception;
use Illuminate\Http\Request;

class LogRejectService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(Request $request): LogReject
    {
        return app(LogRejectRepository::class)->create($request);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return app(LogRejectRepository::class)->destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
