<?php

namespace App\Http\Controllers;


use App\Models\Logs\Log;
use App\Services\LogDischargeService;
use App\Services\LogService;
use Exception;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function store(Request $request): Log
    {
        validator($request->all(), [
            'date_receipt' => ['required'],
            'time_receipt' => ['required'],
            'name' => ['required'],
            'gender' => ['required'],
            'birth_day' => ['required'],
            'medical_card' => ['required'],
        ]);
        return app(LogService::class)->create($request);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool|string
    {
        try {
            return app(LogDischargeService::class)->destroy($id);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
