<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
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

    public function downloadExcel(): StreamedResponse
    {
        // Создаем пустую таблицу
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1'); // можно опустить

        // Создаем объект Writer
        $writer = new Xlsx($spreadsheet);

        // Возвращаем в виде скачиваемого файла
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'empty_sheet.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
