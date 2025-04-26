<?php

namespace App\Http\Controllers;


use App\Models\Logs\Log;
use App\Services\LogDischargeService;
use App\Services\LogService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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
            return app(LogService::class)->destroy($id);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
    public function update(int $id, Request $request): bool|string
    {
        try {
            validator($request->all(), [
                'date_receipt' => ['required'],
                'time_receipt' => ['required'],
                'name' => ['required'],
                'gender' => ['required'],
                'birth_day' => ['required'],
                'medical_card' => ['required'],
            ]);
            return app(LogService::class)->update($id, $request);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::info('controller');
            return $e->getMessage();
        }
    }
    public function findAll(): Collection
    {
        return app(LogService::class)->findAll();
    }
}
