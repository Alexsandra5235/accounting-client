<?php

namespace App\Http\Controllers;


use App\Models\Logs\Log;
use App\Services\Api\ApiService;
use App\Services\LogDischargeService;
use App\Services\LogService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\String\Exception\ExceptionInterface;

class LogController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date_receipt' => ['required'],
            'time_receipt' => ['required'],
            'name' => ['required'],
            'gender' => ['required'],
            'birth_day' => ['required'],
            'medical_card' => ['required'],
        ]);
        try {
            app(LogService::class)->create($request);
            return redirect()->route('dashboard');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors(['error_store' => $exception->getMessage()]);
        }

    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): bool|string
    {
        try {
            app(LogService::class)->destroy($id);
            return redirect()->back();
        } catch (Exception $exception) {
            return redirect()->back()->withErrors(['error_delete' => $exception->getMessage()]);
        }
    }
    public function update(int $id, Request $request): RedirectResponse
    {
        $request->validate([
            'date_receipt' => ['required'],
            'time_receipt' => ['required'],
            'name' => ['required'],
            'gender' => ['required'],
            'birth_day' => ['required'],
            'medical_card' => ['required'],
        ]);
        try {
            $response = app(ApiService::class)->updateLog($request, env('API_LOG_TOKEN') ,$id);
            if ($response->badRequest()){
                return redirect()->back()->withErrors(['error_update' => $response->getBody()]);
            }
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error_update' => $e->getMessage()]);
        }
    }
    public function findAll(): Collection
    {
        return app(LogService::class)->findAll();
    }
    public function findById(int $id): View|RedirectResponse
    {
        try {
            $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $id);
            return view('log.logShow', compact('log'));
        } catch (Exception $exception){
            return redirect()->route('dashboard')->withErrors(['error_show' => $exception->getMessage()]);
        }
    }
    public function edit(int $id): View|RedirectResponse
    {
        try {
            $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $id);
            return view('log.logEdit', compact('log'));
        } catch (Exception $exception){
            return redirect()->route('dashboard')->withErrors(['error_edit' => $exception->getMessage()]);
        }
    }
    public function add() : View
    {
        return view('log.logAdd');
    }
}
