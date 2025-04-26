<?php

namespace App\Repository;

use App\Interfaces\DeleteInterface;
use App\Interfaces\LogModelInterface;
use App\Models\Logs\Log;
use App\Models\Logs\LogDischarge;
use app\Traits\HasLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LogDischargeRepository implements LogModelInterface, DeleteInterface
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

    /**
     * @param int $id
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function update(int $id, Request $request): bool
    {
        try {
            $log = $this->findByIdLog($id, Log::class);
            if ($log instanceof Log) {
                $log->log_discharge->update($request->all());
            }
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
