<?php

namespace App\Repository;

use App\Interfaces\LogModelInterface;
use App\Models\Logs\LogDischarge;
use app\Traits\HasLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LogDischargeRepository implements LogModelInterface
{
    use HasLog;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request): LogDischarge
    {
        return $this->createLog($request, LogDischarge::class);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return $this->destroyLog($id, LogDischarge::class);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
