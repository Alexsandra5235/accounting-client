<?php

namespace App\Http\Controllers\Import;

use App\DTO\History\HistoryDTO;
use App\Enum\ActionsEnum;
use App\Http\Controllers\Controller;
use App\Services\Api\ApiService;
use App\Services\History\HistoryService;
use App\Services\Import\OCRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    private OCRService $ocrService;

    public function __construct(OCRService $ocrService)
    {
        $this->ocrService = $ocrService;
    }

    public function index()
    {
        return view('import.index');
    }

    /**
     * Сохранение распознанных данных пациентов
     */
    public function save(Request $request)
    {
        $saved = 0;
        $errors = [];
        $apiService = app(ApiService::class);
        $historyService = app(HistoryService::class);

        // Получаем данные пациентов из JSON строки
        $patients = json_decode($request->patients, true);

        if (!is_array($patients)) {
            return redirect()->route('import.index')->with('toast', 'Ошибка: неверный формат данных');
        }

        foreach ($patients as $index => $patientData) {
            try {
                // Формируем структуру для API
                $apiData = $this->prepareApiData($patientData);

                $response = $apiService->createLog($apiData, config('api.log_token'));

                if ($response->badRequest()) {
                    $errors[] = [
                        'index' => $index,
                        'name' => $patientData['name'] ?? 'Неизвестно',
                        'error' => 'Ошибка API: ' . $response->getBody(),
                    ];
                    continue;
                }

                // Получаем ID созданной записи
                $responseData = json_decode($response->getBody()->getContents());

                if (empty($responseData->id)) {
                    $errors[] = [
                        'index' => $index,
                        'name' => $patientData['name'] ?? 'Неизвестно',
                        'error' => 'API не вернул ID записи',
                    ];
                    continue;
                }

                // Записываем в историю
                $historyService->store(
                    new HistoryDTO(
                        action: ActionsEnum::ADD,
                        user_id: Auth::user()->id,
                        log: $responseData->id,
                    )
                );

                $saved++;

            } catch (\Exception $e) {
                Log::error('Save patient error: ' . $e->getMessage(), [
                    'index' => $index,
                    'name' => $patientData['name'] ?? 'unknown',
                    'trace' => $e->getTraceAsString(),
                ]);

                $errors[] = [
                    'index' => $index,
                    'name' => $patientData['name'] ?? 'Неизвестно',
                    'error' => 'Ошибка сохранения: ' . $e->getMessage(),
                ];
            }
        }

        // Формируем сообщение
        $message = $this->formatSaveMessage($saved, count($errors), count($patients));
        Log::info('Errors: ' . json_encode($errors, JSON_UNESCAPED_UNICODE));

        return redirect()->route('import.index')->with('toast', $message);
    }

    /**
     * Подготовка данных для API
     */
    private function prepareApiData(array $patientData): array
    {
        // Преобразуем поля в формат, ожидаемый API
        return [
            'name' => $patientData['name'] ?? null,
            'birth_day' => $patientData['birth_day'] ?? null,
            'gender' => $patientData['gender'] ?? null,
            'medical_card' => $patientData['medical_card'] ?? null,
            'passport' => $patientData['passport'] ?? null,
            'nationality' => $patientData['nationality'] ?? 'РФ',
            'address' => $patientData['address'] ?? null,
            'register_place' => $patientData['register_place'] ?? null,
            'phone_agent' => $patientData['phone_agent'] ?? null,
            'snils' => $patientData['snils'] ?? null,
            'polis' => $patientData['polis'] ?? null,
            'diagnosis_state' => $patientData['diagnosis_state'] ?? null,
            'diagnosis_wound' => $patientData['diagnosis_wound'] ?? null,
            'date_receipt' => $patientData['date_receipt'] ?? null,
            'time_receipt' => $patientData['time_receipt'] ?? null,
            'log_receipt' => $patientData['log_receipt'] ?? null,
        ];
    }

    /**
     * Форматирование сообщения о результате сохранения
     */
    private function formatSaveMessage(int $saved, int $errorsCount, int $total): string
    {
        if ($saved === $total && $total > 0) {
            return "Успешно сохранено: {$saved} пациентов";
        }

        if ($saved === 0) {
            return 'Не удалось сохранить ни одного пациента. Проверьте ошибки.';
        }

        return "Сохранено: {$saved} из {$total}. Ошибок: {$errorsCount}";
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'files' => 'required|array|min:1',
                'files.*' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации файлов',
                'errors' => $e->errors()
            ], 422);
        }

        $files = $request->file('files');
        $results = [];
        $errors = [];

        foreach ($files as $file) {
            try {
                // Сохраняем временно
                $tempPath = $file->store('temp/ocr', 'local');
                $fullPath = Storage::path($tempPath);

                Log::info('Processing file: ' . $file->getClientOriginalName());

                // Распознаём
                $patientData = $this->ocrService->recognizePatientData($fullPath);

                // Добавляем данные о файле
                $patientData['id'] = 'pat_' . uniqid();
                $patientData['source_file'] = $file->getClientOriginalName();

                // Создаём preview
                $mimeType = $file->getMimeType();
                $fileContent = file_get_contents($fullPath);
                $patientData['preview_url'] = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

                $results[] = $patientData;

                Log::info('OCR success for file: ' . $file->getClientOriginalName(), [
                    'confidence' => $patientData['confidence'] ?? 0
                ]);

                // Удаляем временный файл
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }

            } catch (\Exception $e) {
                Log::error('Upload error: ' . $e->getMessage(), [
                    'file' => $file->getClientOriginalName(),
                    'trace' => $e->getTraceAsString()
                ]);

                $errors[] = [
                    'filename' => $file->getClientOriginalName(),
                    'error' => 'Ошибка обработки файла: ' . $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'errors' => $errors,
            'total' => count($files),
            'processed' => count($results),
            'failed' => count($errors),
        ]);
    }
}
