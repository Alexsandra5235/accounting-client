<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected string $token;
    protected string $chatId;

    public function __construct()
    {
        $this->token = env('TELEGRAM_TOKEN');
        $this->chatId = env('TELEGRAM_CHAT_ID');
    }
    public function generateMessageStore($response): string
    {
        $user = Auth::user();
        $url = "http://127.0.0.1:8000/log/{$response->id}";
        $datetime_receipt = Carbon::parse($response->log_receipt->date_receipt)->locale('ru')->translatedFormat('D, d M Y') . ' ' . $response->log_receipt->time_receipt;
        $birth_day = Carbon::parse($response->patient->birth_day)->locale('ru')->translatedFormat('D, d M Y');
        return "👤 Новый пациент добавлен:\n\n<b>Дата и время посупления:</b> {$datetime_receipt}\n<b>ФИО:</b> {$response->patient->name}\n<b>Дата рождения:</b> {$birth_day}\n<b>Номер мед.карты:</b> {$response->patient->medical_card}\n<a href='{$url}'>Кликни, чтобы перейти</a>\n\n👨‍⚕️👩‍⚕️ Информация о сотруднике.\n\n<b>Имя сотрудника: </b>{$user->name}\n<b>Почта сотрудника: </b>{$user->email}";
    }
    public function generateMessageDestroy($log): string
    {
        $user = Auth::user();
        return "🙅🏻‍♀️ Запись была удалена.\n\n<b>Имя пациента: </b>{$log->patient->name}\n<b>Номер мед.карты пациента: </b>{$log->patient->medical_card}\n\n👨‍⚕️👩‍⚕️ Информация о сотруднике.\n\n<b>Имя сотрудника: </b>{$user->name}\n<b>Почта сотрудника: </b>{$user->email}";
    }
    public function generateMessageUpdate($log): string
    {
        $user = Auth::user();
        $url = "http://127.0.0.1:8000/log/{$log->id}";
        $datetime_receipt = Carbon::parse($log->log_receipt->date_receipt)->locale('ru')->translatedFormat('D, d M Y') . ' ' . $log->log_receipt->time_receipt;
        $birth_day = Carbon::parse($log->patient->birth_day)->locale('ru')->translatedFormat('D, d M Y');
        return "🙊 Запись была обновлена:\n\n<b>Дата и время посупления:</b> {$datetime_receipt}\n<b>ФИО:</b> {$log->patient->name}\n<b>Дата рождения:</b> {$birth_day}\n<b>Номер мед.карты:</b> {$log->patient->medical_card}\n<a href='{$url}'>Кликни, чтобы перейти</a>\n\n👨‍⚕️👩‍⚕️ Информация о сотруднике.\n\n<b>Имя сотрудника: </b>{$user->name}\n<b>Почта сотрудника: </b>{$user->email}";
    }
    public function sendMessage(string $message): void
    {
        try {
            Log::info("Попытка отправить сообщение в Telegram: " . $this->chatId . ' ' .$this->token. "...");

            // Отключаем SSL проверку для этого запроса
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
            ])->post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $this->chatId,
                'text'    => $message,
                'parse_mode' => 'HTML',
            ]);

            // Проверяем ответ
            if ($response->successful()) {
                Log::info("Сообщение успешно отправлено в Telegram");
            } else {
                Log::error("Ошибка Telegram API: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Ошибка отправки сообщения ТГ бота: " . $e->getMessage());
        }
    }
}
