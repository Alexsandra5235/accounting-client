<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function index(): View
    {
        $perPage = 5; // Количество отчетов на странице

        $reports = Report::query()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('report.report', [
            'reports' => $reports
        ]);
    }

    /**
     * Скачать ранее сохранённый отчёт из базы данных.
     *
     * @param  int  $id
     * @return Response|RedirectResponse
     */
    public function downloadSaved(int $id): Response|RedirectResponse
    {
        try {
            $report = Report::query()->find($id);

            if (!$report) {
                return redirect()->back()->withErrors(['report_error' => 'Отчет не найден.']);
            }

            $filename = $report->filename;
            $content  = $report->file;

            return response($content, 200, [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Content-Length'      => strlen($content),
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->withErrors(['report_error' => $exception->getMessage()]);
        }
    }
}
