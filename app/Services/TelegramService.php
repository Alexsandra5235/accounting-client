<?php

namespace App\Services;

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
    public function sendMessage(string $message): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'chat_id' => $this->chatId,
            'text'    => $message,
            'parse_mode' => 'HTML',
        ]);
    }
}
