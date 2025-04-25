<?php

namespace App\Services;

use App\Models\Logs\LogReject;
use App\Repository\LogRejectRepository;
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
}
