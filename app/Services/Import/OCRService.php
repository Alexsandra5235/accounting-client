<?php

namespace App\Services\Import;

use App\Services\MKD\MkdService;
use Illuminate\Support\Facades\Log;

class OCRService
{
    private string $pythonPath;
    private string $scriptPath;
    private bool $useMock = true; // ← переключить на true для заглушки

    public function __construct()
    {
        $this->pythonPath = '/home/make/PycharmProjects/proccessImage/.venv/bin/python3';
        $this->scriptPath = '/home/make/PycharmProjects/proccessImage/ocr-gemini.py';
    }

    /**
     * Распознать данные пациента с изображения
     * @throws \Exception
     */
    public function recognizePatientData(string $imagePath): array
    {
        if ($this->useMock) {
            return $this->parsePatientData($this->mockResponse());
        }

        $rawText = $this->extractTextFromImage($imagePath);

        Log::info('OCR raw text:', ['text' => $rawText]);

        if (empty(trim($rawText))) {
            return $this->emptyPatientData();
        }

        return $this->parsePatientData($rawText);
    }

    /**
     * Вызов Python-скрипта
     */
    private function extractTextFromImage(string $imagePath): string
    {
        $command = sprintf(
            '%s %s %s 2>&1',
            escapeshellcmd($this->pythonPath),
            escapeshellcmd($this->scriptPath),
            escapeshellarg($imagePath)
        );

        $output = shell_exec($command);

        if (empty($output)) {
            Log::error('OCR: empty output');
            return '';
        }

        $result = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('OCR: JSON parse error', ['output' => $output]);
            return '';
        }

        if (!empty($result['error'])) {
            Log::error('OCR error: ' . $result['error']);
            return '';
        }

        return $result['text'] ?? '';
    }

    /**
     * Заглушка для тестирования
     */
    private function mockResponse(): string
    {
        return <<<TEXT
        Учетная форма №: 025/у
        Номер медицинской карты: 123
        Дата заполнения медицинской карты: 26.10.2020
        Фамилия, имя, отчество: Петров Иван Иванович
        Пол: муж.
        Дата рождения: 12.12.1990
        Место регистрации: субъект Российской Федерации: Москва
        Место регистрации: район: Ленинский
        Место регистрации: город: Москва
        Местность: городская
        Адрес: г. Москва, ул. Ленинский проспект, д. 3, кв. 16
        Телефон: 8(495)777-77-77
        СНИЛС: 123-456-789 00
        Дата начала диспансерного наблюдения: 26.10.2020
        Диагноз: Гипертиреоз неуточненный (для оказания услуг по эпиляции)
        Код по МКБ-10: L68.9
        Врач: ФИО
        TEXT;
    }

    // ===== Парсинг =====

    /**
     * @throws \Exception
     */
    private function parsePatientData(string $rawText): array
    {
        $patient = [
            'name' => null,
            'birth_day' => null,
            'gender' => null,
            'medical_card' => null,
            'passport' => null,
            'nationality' => 'РФ',
            'address' => null,
            'register_place' => null,
            'snils' => null,
            'polis' => null,
            'diagnosis' => null,
            'log_receipt' => null,
            'phone_agent' => null,
            'confidence' => 0,
        ];

        // ФИО
        if (preg_match('/(?:Фамилия[,:\s]*имя[,:\s]*отчество|ФИО)\s*[:;]?\s*(.+)/ui', $rawText, $m)) {
            $patient['name'] = trim($m[1]);
        }

        // Дата рождения
        if (preg_match('/Дата\s*рождения\s*[:;]?\s*(\d{2}\.\d{2}\.\d{4})/ui', $rawText, $m)) {
            $patient['birth_day'] = $m[1];
        }

        // Пол
        if (preg_match('/Пол\s*[:;]?\s*(муж|жен)/ui', $rawText, $m)) {
            $patient['gender'] = mb_strtolower($m[1]) === 'муж' ? 'male' : 'female';
        }

        // Номер медкарты
        if (preg_match('/(?:Номер\s*медицинской\s*карты|Медицинская\s*карта\s*№)\s*[:;]?\s*(\d+)/ui', $rawText, $m)) {
            $patient['medical_card'] = $m[1];
        }

        // СНИЛС
        if (preg_match('/СНИЛС\s*[:;]?\s*(\d{3}[.\-\s]\d{3}[.\-\s]\d{3}[.\-\s]\d{2})/ui', $rawText, $m)) {
            $patient['snils'] = str_replace('.', '-', $m[1]);
        }

        // Адрес
        if (preg_match('/Адрес\s*[:;]?\s*(.+)/ui', $rawText, $m)) {
            $patient['address'] = trim($m[1]);
        }

        // Телефон агента
        if (preg_match('/Телефон\s*[:;]?\s*(.+)/ui', $rawText, $m)) {
            $patient['phone_agent'] = trim($m[1]);
        }

        if (empty($patient['phone_agent'])) {
            if (preg_match('/Телефон\s*[:;]?\s*(.+)/ui', $rawText, $m)) {
                $patient['phone_agent'] = trim($m[1]);
            }
        }

        // Место регистрации (город)
        if (preg_match('/Место\s*регистрации[^:]*город\s*[:;]?\s*(.+)/ui', $rawText, $m)) {
            $patient['register_place'] = trim($m[1]);
        }

        // Диагноз
        if (preg_match('/(?:Код\s*по\s*МКБ[-\s]*10|МКБ[-\s]*10)\s*[:;]?\s*([A-Za-z]\d{2}[.\d]*)/ui', $rawText, $m)) {
            $mkbCode = trim($m[1]);
            Log::warning('диагнох ' . $mkbCode);
            $result = $this->searchMkbCodes($mkbCode);
            $patient['diagnosis_state'] = !empty($result)
                ? $result[0]['value']
                : $mkbCode;
        }

        // Поиск кода для причины травмы
        if (preg_match('/(?:Причина\s*травмы|Травма)\s*[:;]?\s*(?:Код\s*МКБ[-\s]*10)?\s*[:;]?\s*([A-Za-z]\d{2}[.\d]*)/ui', $rawText, $m)) {
            $mkbCode = trim($m[1]);
            Log::warning('причина ' . $mkbCode);
            $result = $this->searchMkbCodes($mkbCode);
            $patient['diagnosis_wound'] = !empty($result)
                ? $result[0]['value']
                : $mkbCode;
        }

        // Дата поступления
        if (preg_match('/(?:Дата\s*(?:заполнения|поступления))\D*(\d{2}\.\d{2}\.\d{4})/ui', $rawText, $m)) {
            $patient['log_receipt']['date_receipt'] = $m[1];
        }

        $patient['confidence'] = $this->calculateConfidence($patient);

        Log::warning(json_encode($patient,JSON_UNESCAPED_UNICODE));
        return $patient;
    }

    /**
     * Поиск кодов МКБ по вхождению
     * @throws \Exception
     */
    private function searchMkbCodes(string $query): array
    {
        if (empty($query) || strlen($query) < 2) {
            return [];
        }

        $codes = app(MkdService::class)->search($query);

        Log::info('MKB codes: ' . json_encode($codes, JSON_UNESCAPED_UNICODE));

        $suggestions = [];
        foreach ($codes as $code) {
            Log::info('MKB codes: ' . json_encode($code, JSON_UNESCAPED_UNICODE));
            $suggestions[] = [
                'value' => $code['code'],
                'label' => $code['code'] . ' - ' . $code['value'],
                'name' => $code['value'],
            ];
        }

        // Если не найдено, возвращаем исходный запрос как вариант
        if (empty($suggestions)) {
            $suggestions[] = [
                'value' => $query,
                'label' => $query . ' - (не найден в справочнике)',
                'name' => null,
            ];
        }

        return $suggestions;
    }

    private function calculateConfidence(array $patient): int
    {
        $fields = ['name', 'birth_day', 'gender', 'medical_card', 'snils', 'diagnosis'];
        $filled = count(array_filter($fields, fn($f) => !empty($patient[$f])));
        return (int) round(($filled / count($fields)) * 100);
    }

    private function emptyPatientData(): array
    {
        return [
            'name' => null, 'birth_day' => null, 'gender' => null,
            'medical_card' => null, 'passport' => null, 'nationality' => 'РФ',
            'address' => null, 'register_place' => null, 'snils' => null,
            'polis' => null, 'diagnosis' => null, 'log_receipt' => null,
            'confidence' => 0,
        ];
    }
}
