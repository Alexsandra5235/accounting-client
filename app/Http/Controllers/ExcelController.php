<?php

namespace App\Http\Controllers;

use App\Services\Export\GenerateExcelService;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelController extends Controller
{
    public function getPageStore(): View
    {
        return view('excel.excel-store');
    }

    /**
     * @throws ConnectionException
     */
    public function downloadExcel(Request $request): StreamedResponse|RedirectResponse
    {
        $request->validate([
            'date1' => 'required',
            'date2' => 'required',
        ]);

        $date1 = $request->input('date1');
        $date2 = $request->input('date2');

        $writer = app(GenerateExcelService::class)->getWriter($date1, $date2);
        $fileName = app(GenerateExcelService::class)->getFileName($date1, $date2);

        if ($request->input('action') === 'open') {
            return $this->previewReport($writer, $fileName);
        }

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function previewReport(Xlsx $writer, string $fileName): RedirectResponse
    {
        $filePath = storage_path("app/public/{$fileName}");
        $writer->save($filePath);

        return redirect("/storage/{$fileName}");
    }

    /**
     * @throws ConnectionException
     */
    public function downloadExcelSummary(Request $request): StreamedResponse|RedirectResponse
    {
        $request->validate([
            'date1' => 'required',
            'date2' => 'required',
        ]);

        $date1 = $request->input('date1');
        $date2 = $request->input('date2');

        $writer = app(GenerateExcelService::class)->getWriterSummary($date1, $date2);
        $fileName = app(GenerateExcelService::class)->getFileNameSummary($date1, $date2);

        if ($request->input('action') === 'open') {
            return $this->previewReport($writer, $fileName);
        }

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
