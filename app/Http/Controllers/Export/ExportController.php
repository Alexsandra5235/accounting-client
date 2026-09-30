<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Services\Export\ExportToCsvService;
use Illuminate\Http\Client\ConnectionException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function exportToCsv(): BinaryFileResponse
    {
        $filePath = app(ExportToCsvService::class)->export();
        return response()->download(
            $filePath,
            basename($filePath),
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        )->deleteFileAfterSend();
    }
}
