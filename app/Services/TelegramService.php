<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Response;
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
    public function generateMessage($response): string
    {
        $datetime_receipt = Carbon::parse($response->log_receipt->date_receipt)->locale('ru')->translatedFormat('D, d M Y') . ' ' . $response->log_receipt->time_receipt;
        $birth_day = Carbon::parse($response->patient->birth_day)->locale('ru')->translatedFormat('D, d M Y');
        return "👤 Новый пациент добавлен:\n\n<b>Дата и время посупления:</b> {$datetime_receipt}\n<b>ФИО:</b> {$response->patient->name}\n<b>Дата рождения:</b> {$birth_day}\n<b>Номер мед.карты:</b> {$response->patient->medical_card}";
    }
    public function sendMessage(string $message): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'chat_id' => $this->chatId,
            'text'    => $message,
            'parse_mode' => 'HTML',
        ]);
    }
}
