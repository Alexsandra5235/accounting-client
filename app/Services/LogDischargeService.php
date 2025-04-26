<?php

namespace App\Services;

use App\Models\Logs\LogDischarge;
use App\Repository\LogDischargeRepository;
use Exception;
use Illuminate\Http\Request;

class LogDischargeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function create(Request $request) : LogDischarge
    {
        return app(LogDischargeRepository::class)->create($request);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id) : bool
    {
        try {
            return app(LogDischargeRepository::class)->destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, int $id) : bool
    {
        try {
            return app(LogDischargeRepository::class)->update($id, $request);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
