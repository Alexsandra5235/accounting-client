<?php

namespace App\Http\Controllers;


use App\Jobs\SendTelegramNotification;
use App\Services\Api\ApiService;
use App\Services\TelegramService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use stdClass;
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
            $response = app(ApiService::class)->createLog($request, env('API_LOG_TOKEN'));
            if ($response->badRequest()){
                return redirect()->back()->withErrors(['error_store' => $response->getBody()]);
            }

            $response = json_decode($response->getBody()->getContents());

            $message = app(TelegramService::class)->generateMessageStore($response);

            SendTelegramNotification::dispatch($message);

            return redirect()->route('dashboard')->with('toast', 'Запись успешно добавлена!');;
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
            $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $id);
            if ($log->badRequest()){
                return redirect()->route('dashboard')->withErrors(['error_delete' => json_decode($log->getBody())]);
            }

            $log = json_decode($log->getBody());

            $response = app(ApiService::class)->deleteLog(env('API_LOG_TOKEN'), $id);
            if($response->badRequest()){
                return redirect()->back()->withErrors(['error_delete' => $response->getBody()]);
            }

            $message = app(TelegramService::class)->generateMessageDestroy($log);
            SendTelegramNotification::dispatch($message);
            return redirect()->back()->with('toast', "Запись о пациенте '{$log->patient->name}' успешно удалена!");
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

            $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $id);
            if ($log->badRequest()){
                return redirect()->route('dashboard')->withErrors(['error_show' => json_decode($log->getBody())]);
            }

            $log = json_decode($log->getBody());

            $message = app(TelegramService::class)->generateMessageUpdate($log);
            SendTelegramNotification::dispatch($message);
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error_update' => $e->getMessage()]);
        }
    }
    public function findById(int $id): View|RedirectResponse
    {
        try {
            $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $id);
            if ($log->badRequest()){
                return redirect()->route('dashboard')->withErrors(['error_show' => json_decode($log->getBody())]);
            }
            $log = json_decode($log->getBody());
            return view('log.logShow', compact('log'));
        } catch (Exception $exception){
            return redirect()->route('dashboard')->withErrors(['error_show' => $exception->getMessage()]);
        }
    }
    public function edit(int $id): View|RedirectResponse
    {
        try {
            $log = app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $id);
            if ($log->badRequest()){
                return redirect()->route('dashboard')->withErrors(['error_show' => json_decode($log->getBody())]);
            }
            $log = json_decode($log->getBody());
            return view('log.logEdit', compact('log'));
        } catch (Exception $exception){
            return redirect()->route('dashboard')->withErrors(['error_edit' => $exception->getMessage()]);
        }
    }
    public function add() : View
    {
        return view('log.logAdd');
    }

    /**
     * @throws ConnectionException
     */
    public function getLogByName(Request $request): View
    {
        $response = app(ApiService::class)->getLogByName(env('API_LOG_TOKEN'), $request);
        $logs = json_decode($response->getBody());
        $search_name = $request->input('search_name');
        return view('dashboard', compact(['logs', 'search_name']));
    }

    public function getGrouping(Request $request): stdClass|string
    {
        try {
            $group = app(ApiService::class)->getGrouping(env('API_LOG_TOKEN'), $request);
            if ($group->badRequest()){
                return $group->getBody();
            }
            return json_decode($group->getBody());
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
