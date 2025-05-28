<?php

namespace App\Console\Commands;

use App\Services\Export\GenerateExcelService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class TelegramLongPoll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запускает Long Polling для получения обновлений от Telegram';

    // Храним последний offset, чтобы не обрабатывать одни и те же апдейты повторно.
    protected $offset = 0;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Telegram Long Polling запущен. Нажмите CTRL+C для остановки.');

        while (true) {
            try {
                $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

                $params = [
                    'offset' => $this->offset,
                    'timeout' => 30,
                    'allowed_updates' => ['message'],
                ];

                $updates = $telegram->getUpdates($params);

                foreach ($updates as $update) {
                    $this->processUpdate($update);
                    $this->offset = $update->getUpdateId() + 1;
                }
            } catch (Exception $e) {
                Log::error('Ошибка LongPolling: ' . $e->getMessage());
                sleep(5);
            }
        }
    }

    /**
     * Обрабатывает один Update от Telegram
     *
     * @param Update $update
     * @throws ConnectionException
     */
    protected function processUpdate(Update $update): void
    {
        // Проверяем, что это сообщение с текстом
        if (!$update->has('message') || !$update->getMessage()->has('text')) {
            return;
        }

        $message = $update->getMessage();
        $text = trim($message->getText());
        $chatId = $message->getChat()->getId();

        Log::info("New message from {$chatId}: {$text}");

        // Если текст начинается с '/' — это команда
        if (str_starts_with($text, '/')) {
            $this->handleCommand($chatId, $text);
        } else {
            // Иначе можно реагировать на «свободный текст»
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => "🤔 Хмм...\nТакой команды не найдено!\nНапишите /help, чтобы увидеть список команд.",
            ]);
        }
    }

    /**
     * Разбираем команду, обрабатываем /report YYYY-MM-DD
     *
     * @param int $chatId
     * @param string $text
     */
    protected function handleCommand(int $chatId, string $text): void
    {
        // Уберём ведущий слеш и разобьём на команду и аргумент
        $parts = explode(' ', mb_substr($text, 1), 2);
        $command = mb_strtolower($parts[0]);

        switch ($command) {
            case 'report':
                if (preg_match('/report\s+type=(\d)\s+(\d{2}\.\d{2}\.\d{4})\s+(\d{2}\.\d{2}\.\d{4})/i', $text, $matches)) {
                    $type = (int)$matches[1];
                    $date1 = Carbon::createFromFormat('d.m.Y', $matches[2])->format('Y-m-d');
                    $date2 = Carbon::createFromFormat('d.m.Y', $matches[3])->format('Y-m-d');

                    if ($type === 1) {
                        $this->sendExcelReportToTelegram($chatId, $date1, $date2);
                    } elseif ($type === 2) {
                        $this->sendExcelReportToTelegramSummary($chatId, $date1, $date2);
                    } else {
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text' => "Неизвестный тип отчета. Допустимые: type=1 или type=2",
                        ]);
                    }
                } else {
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => "Не хватает аргументов.\nДля генерации отчета напишите отчетный период\nНапример: /report type=1 22.03.2025 23.03.2025",
                    ]);
                }
                break;

            case 'start':
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text'    => "🙋‍♀️ Привет!\n\n📄 Этот бот занимается формированием отчетной документации по движению пациентов санатория 'Журавлик'.\n\n Чтобы получить Excel-отчёт, напиши:\n/report type=1 22.05.2025 23.05.2025",
                ]);
                break;

            case 'help':
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text'    => "Доступные команды:\n\n"
                        ."/start — стартовое сообщение\n\n"
                        ."/report type=1 DD-MM-YYYY DD-MM-YYYY — получить лист ежедневного учета Excel за указанный отчетный период\n\n"
                        ."/report type=2 DD-MM-YYYY DD-MM-YYYY — получить сводную ведомость учета Excel за указанный отчетный период\n\n"
                        ."/help - увидеть список команд",
                ]);
                break;

            default:
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text'    => "Неизвестная команда: /{$command}\n"
                        ."Напишите /help, чтобы увидеть список команд.",
                ]);
                break;
        }
    }

    public function sendExcelReportToTelegram(string $chatId, string $date1, string $date2): void
    {
        try {
            // Получаем Xlsx writer
            $generateService = app(GenerateExcelService::class);
            $writer = $generateService->getWriter($date1, $date2);
            $fileName = $generateService->getFileName($date1, $date2);
            $filePath = storage_path("app/public/{$fileName}");

            // Сохраняем файл
            $writer->save($filePath);

            // Отправляем документ в Telegram
            Telegram::sendDocument([
                'chat_id' => $chatId,
                'document' => InputFile::create($filePath),
                'caption' => "📊 Отчет с {$date1} по {$date2}",
            ]);
        } catch (Exception $e) {
            Log::error("ошибка экспорта: {$e->getMessage()}" );
        }
    }
    public function sendExcelReportToTelegramSummary(string $chatId, string $date1, string $date2): void
    {
        try {
            // Получаем Xlsx writer
            $generateService = app(GenerateExcelService::class);
            $writer = $generateService->getWriterSummary($date1, $date2);
            $fileName = $generateService->getFileNameSummary($date1, $date2);
            $filePath = storage_path("app/public/{$fileName}");

            // Сохраняем файл
            $writer->save($filePath);

            // Отправляем документ в Telegram
            Telegram::sendDocument([
                'chat_id' => $chatId,
                'document' => InputFile::create($filePath),
                'caption' => "📊 Отчет с {$date1} по {$date2}",
            ]);
        } catch (Exception $e) {
            Log::error("ошибка экспорта: {$e->getMessage()}" );
        }
    }
}
