<?php

namespace App\Repository;

use App\Interfaces\LogModelInterface;
use App\Models\Logs\LogReceipt;
use App\Models\Logs\LogReject;
use app\Traits\HasLog;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LogRejectRepository implements LogModelInterface
{
    use HasLog;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(Request $request): LogReject
    {
        return $this->createLog($request, LogReject::class);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        try {
            return $this->destroyLog($id, LogReject::class);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
