<?php

namespace App\Http\Controllers;


use App\DTO\History\HistoryDTO;
use App\Enum\ActionsEnum;
use App\Jobs\SendTelegramNotification;
use App\Models\User;
use App\Services\Api\ApiService;
use App\Services\History\HistoryService;
use App\Services\LogService;
use App\Services\TelegramService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            $response = app(ApiService::class)->createLog($request, config('api.log_token'));
            if ($response->badRequest()){
                return redirect()->back()->withErrors(['error_store' => $response->getBody()]);
            }

            $response = json_decode($response->getBody()->getContents());

            app(HistoryService::class)->store(
                new HistoryDTO(
                    action: ActionsEnum::ADD,
                    user_id: Auth::user()->id,
                    log: $response->id,
                )
            );

            $message = app(TelegramService::class)->generateMessageStore($response);

            SendTelegramNotification::dispatch($message);

            return redirect()->route('dashboard')->with('toast', 'Запись успешно добавлена!');
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
            $log = app(ApiService::class)->getLogById(config('api.log_token'), $id);
            if ($log->badRequest()){
                return redirect()->route('dashboard')->withErrors(['error_delete' => json_decode($log->getBody())]);
            }

            $log = json_decode($log->getBody());

            $response = app(ApiService::class)->deleteLog(config('api.log_token'), $id);
            if($response->badRequest()){
                return redirect()->route('dashboard')->withErrors(['error_delete' => $response->getBody()]);
            }

            app(HistoryService::class)->delete($id);

            app(HistoryService::class)->store(
                new HistoryDTO(
                    action: ActionsEnum::DELETE,
                    user_id: Auth::user()->id,
                )
            );

            $message = app(TelegramService::class)->generateMessageDestroy($log);
            SendTelegramNotification::dispatch($message);
            return redirect()->route('dashboard')->with('toast', "Запись о пациенте '{$log->patient->name}' успешно удалена!");
        } catch (Exception $exception) {
            return redirect()->route('dashboard')->withErrors(['error_delete' => $exception->getMessage()]);
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
            $logBefore = app(ApiService::class)->getLogById(config('api.log_token'), $id);
            $response = app(ApiService::class)->updateLog($request, config('api.log_token'), $id);
            if ($response->badRequest()){
                return redirect()->back()->withErrors(['error_update' => $response->getBody()]);
            }

            $log = app(ApiService::class)->getLogById(config('api.log_token'), $id);
            if ($log->badRequest()){
                return redirect()->route('dashboard')->withErrors(['error_show' => json_decode($log->getBody())]);
            }

            app(HistoryService::class)->store(
                new HistoryDTO(
                    action: ActionsEnum::EDIT,
                    user_id: Auth::user()->id,
                    diff: app(HistoryService::class)
                        ->getDiff(json_decode($log->getBody(), true), json_decode($logBefore->getBody(), true)),
                    log: $id
                )
            );

            $log = json_decode($log->getBody());

            $message = app(TelegramService::class)->generateMessageUpdate($log);
            SendTelegramNotification::dispatch($message);
            return redirect()->route('dashboard')->with('toast', "Запись о пациенте '{$log->patient->name}' успешно обновлена!");
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error_update' => $e->getMessage()]);
        }
    }
    public function findById(int $id): View|RedirectResponse
    {
        try {
            $log = app(ApiService::class)->getLogById(config('api.log_token'), $id);
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
            $log = app(ApiService::class)->getLogById(config('api.log_token'), $id);
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
        $response = app(ApiService::class)->getLogByName(config('api.log_token'), $request);
        $logs = json_decode($response->getBody());
        usort($logs, function($a, $b) {
            $datetimeA = $a->log_receipt->date_receipt . ' ' . $a->log_receipt->time_receipt;
            $datetimeB = $b->log_receipt->date_receipt . ' ' . $b->log_receipt->time_receipt;

            $timestampA = strtotime($datetimeA);
            $timestampB = strtotime($datetimeB);

            return $timestampB <=> $timestampA;
        });

        // Разделяем на текущих и выписанных
        $currentPatients = [];
        $dischargedPatients = [];

        foreach ($logs as $log) {
            if (!empty($log->log_discharge->datetime_discharge)) {
                $dischargedPatients[] = $log;
            } else {
                $currentPatients[] = $log;
            }
        }
        $search_name = $request->input('search_name');
        return view('dashboard', compact('logs', 'search_name', 'currentPatients', 'dischargedPatients'));
    }
}
