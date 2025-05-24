<?php

namespace App\Services\Export;

use App\Constants\TableColumnsName;
use App\Facades\ExcelStyler;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GenerateExcelService
{
    public function createHeadLeft(Worksheet $sheet): void
    {
        for ($i = 0; $i < 5; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                $i, $i,
                0, 4,
                TableColumnsName::LEFT_COLUMNS[$i],
                $i === 4 ? 'center' : 'left',
                false,
                0,
                $i === 4,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true
            );
        }
    }

    public function createHeadRight(Worksheet $sheet): void
    {
        for ($i = 2; $i < 6; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                $i,
                $i,
                19,
                24,
                TableColumnsName::RIGHT_COLUMNS[$i-2],
                Alignment::HORIZONTAL_CENTER,
                false,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true
            );
        }

        ExcelStyler::setDefaultSettings(
            $sheet,
            0,
            0,
            19,
            21,
            TableColumnsName::RIGHT_COLUMN_CODE,
            Alignment::HORIZONTAL_RIGHT,
            false,
            0,
            false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            false,
            false,
            true
        );

        ExcelStyler::setDefaultSettings(
            $sheet,
            0,
            0,
            22,
            24,
            null,
            Alignment::HORIZONTAL_CENTER,
            false,
            0,
            false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            false,
            true,
            true
        );
    }

    public function setSizeColumn(Worksheet $sheet): void
    {
        $sheet->getColumnDimensionByColumn(1)->setWidth(34);

        for ($i = 14; $i < 18; $i++) {
            $row = $sheet->getRowDimension($i + 1);
            if ($i !== 17) {
                $row->setRowHeight(32);
            } else {
                $row->setRowHeight(200);
            }
        }
    }

    public function createTitle(Worksheet $sheet, string $date1, string $date2): void
    {
        $date1 = Carbon::parse($date1)->format('d.m.Y');
        $date2 = Carbon::parse($date2)->format('d.m.Y');
        $titles = TableColumnsName::generateCenterColumns($date1, $date2);

        for ($i = 8; $i < 12; $i++) {
            $text = $titles[$i - 8];

            if ($i === 11) {
                ExcelStyler::setDefaultSettings(
                    $sheet,
                    $i,
                    $i,
                    0,
                    24,
                    $text,
                    Alignment::HORIZONTAL_CENTER,
                    false,
                    0,
                    false,
                    TableColumnsName::FONT_HEIGHT_TEXT,
                    false,
                    false,
                    true,
                );
            } else {
                ExcelStyler::setDefaultSettings(
                    $sheet,
                    $i, $i,
                    0, 24,
                    $text,
                    'center',
                    false,
                    0,
                    false,
                    TableColumnsName::FONT_HEIGHT_HEAD,
                    true,
                    false,
                    true
                );
            }
        }
    }

    public function createTitleSummary(Worksheet $sheet, string $date1, string $date2): void
    {
        $date1 = Carbon::parse($date1)->format('d.m.Y');
        $date2 = Carbon::parse($date2)->format('d.m.Y');
        $titles = TableColumnsName::generateCenterColumnsSummary($date1, $date2);

        for ($i = 8; $i < 13; $i++) {
            $text = $titles[$i - 8];

            if ($i === 12) {
                ExcelStyler::setDefaultSettings(
                    $sheet,
                    $i,
                    $i,
                    0,
                    24,
                    $text,
                    Alignment::HORIZONTAL_CENTER,
                    false,
                    0,
                    false,
                    TableColumnsName::FONT_HEIGHT_TEXT,
                    false,
                    false,
                    true,
                );
            } else {
                ExcelStyler::setDefaultSettings(
                    $sheet,
                    $i, $i,
                    0, 24,
                    $text,
                    'center',
                    false,
                    0,
                    false,
                    TableColumnsName::FONT_HEIGHT_HEAD,
                    true,
                    false,
                    true
                );
            }
        }
    }

    public function createColumnVertical(Worksheet $sheet): void
    {
        for ($i = 1; $i < 25; $i++) {

            ExcelStyler::setDefaultSettings(
                $sheet,
                TableColumnsName::CELLS_FIRST_COLUMN_VERTICAL[$i - 1],
                17,
                $i,
                $i,
                TableColumnsName::NAME_COLUMN_VERTICAL[$i - 1],
                Alignment::HORIZONTAL_CENTER,
                true,
                90,
                false,
                TableColumnsName::FONT_HEIGHT_HEAD,
                false,
                false,
                true
            );
        }
    }

    public function createColumnVerticalSummary(Worksheet $sheet): void
    {
        for ($i = 1; $i < 24; $i++) {

            ExcelStyler::setDefaultSettings(
                $sheet,
                TableColumnsName::CELLS_FIRST_COLUMN_VERTICAL_SUMMARY[$i - 1],
                18,
                $i,
                $i,
                TableColumnsName::NAME_COLUMN_VERTICAL_SUMMARY[$i - 1],
                Alignment::HORIZONTAL_CENTER,
                true,
                90,
                false,
                TableColumnsName::FONT_HEIGHT_HEAD,
                false,
                false,
                true
            );
        }
    }

    public function createColumnHorizontal(Worksheet $sheet): void
    {
        for ($i = 0; $i < 12; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                TableColumnsName::ROW_FIRST_COLUMN_HORIZONTAL[$i],
                TableColumnsName::ROW_END_COLUMN_HORIZONTAL[$i],
                TableColumnsName::CELLS_FIRST_COLUMN_HORIZONTAL[$i],
                TableColumnsName::CELLS_END_COLUMN_HORIZONTAL[$i],
                TableColumnsName::NAME_COLUMN_HORIZONTAL[$i],
                Alignment::HORIZONTAL_CENTER,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true
            );
        }
    }
    public function createColumnHorizontalSummary(Worksheet $sheet): void
    {
        for ($i = 0; $i < 12; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                TableColumnsName::ROW_FIRST_COLUMN_HORIZONTAL_SUMMARY[$i],
                TableColumnsName::ROW_END_COLUMN_HORIZONTAL_SUMMARY[$i],
                TableColumnsName::CELLS_FIRST_COLUMN_HORIZONTAL_SUMMARY[$i],
                TableColumnsName::CELLS_END_COLUMN_HORIZONTAL_SUMMARY[$i],
                TableColumnsName::NAME_COLUMN_HORIZONTAL_SUMMARY[$i],
                Alignment::HORIZONTAL_CENTER,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true
            );
        }
    }

    public function createNumsRows(Worksheet $sheet): void
    {
        for ($i = 0; $i < 10; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                18,
                18,
                $i,
                $i,
                $i+1,
                Alignment::HORIZONTAL_CENTER,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true,
            );
        }
        for ($i = 13; $i < 25; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                18,
                18,
                $i,
                $i,
                $i-1,
                Alignment::HORIZONTAL_CENTER,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true,
            );
        }
        for ($i = 10; $i < 13; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                18,
                18,
                $i,
                $i,
                TableColumnsName::NUMS_COLUMN[$i-10],
                Alignment::HORIZONTAL_CENTER,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true,
            );
        }
    }
    public function createNumsRowsSummary(Worksheet $sheet): void
    {
        for ($i = 0; $i < 24; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                19,
                19,
                $i,
                $i,
                $i+1,
                Alignment::HORIZONTAL_CENTER,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true,
            );
        }
    }

    public function createColumnReport(Worksheet $sheet): void
    {
        $nameRowTotal = TableColumnsName::NAME_ROW_TOTAL;
        $nameRowDayHospital = TableColumnsName::NAME_ROW_DAY_HOSPITAL;
        $nameRowsReport = TableColumnsName::NAME_ROWS_REPORT;
        $endRow = TableColumnsName::SIGNATURE;

        ExcelStyler::setDefaultSettings(
            $sheet,
            19, 19, 0, 0,
            $nameRowTotal,
            Alignment::HORIZONTAL_RIGHT,
            true, 0, false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            true, false,
            true
        );

        ExcelStyler::setDefaultSettings(
            $sheet,
            20, 20, 0, 0,
            $nameRowDayHospital,
            Alignment::HORIZONTAL_LEFT,
            true, 0, false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            true, false,
            true
        );

        for ($i = 21; $i < 23; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                $i, $i, 0, 0,
                $nameRowsReport[$i - 21],
                Alignment::HORIZONTAL_RIGHT,
                true, 0, false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false, false,
                false
            );
        }

        ExcelStyler::setDefaultSettings(
            $sheet,
            25, 25, 16, 18,
            $endRow,
            Alignment::HORIZONTAL_RIGHT,
            false, 0, false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            false, false,
            true
        );

        ExcelStyler::setDefaultSettings(
            $sheet,
            25, 25, 19, 21,
            null,
            Alignment::HORIZONTAL_CENTER,
            false, 0, false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            false, true,
            false
        );

        $this->createBorder($sheet);
    }
    public function createColumnReportSummary(Worksheet $sheet): void
    {
        $nameRowTotal = TableColumnsName::NAME_ROW_TOTAL;
        $nameRowDayHospital = TableColumnsName::NAME_ROW_DAY_HOSPITAL;
        $nameRowsReport = TableColumnsName::NAME_ROWS_REPORT;

        ExcelStyler::setDefaultSettings(
            $sheet,
            20, 20, 0, 0,
            $nameRowTotal,
            Alignment::HORIZONTAL_RIGHT,
            true, 0, false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            true, false,
            true
        );

        ExcelStyler::setDefaultSettings(
            $sheet,
            21, 21, 0, 0,
            $nameRowDayHospital,
            Alignment::HORIZONTAL_LEFT,
            true, 0, false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            true, false,
            true
        );

        for ($i = 22; $i < 24; $i++) {
            ExcelStyler::setDefaultSettings(
                $sheet,
                $i, $i, 0, 0,
                $nameRowsReport[$i - 22],
                Alignment::HORIZONTAL_RIGHT,
                true, 0, false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false, false,
                false
            );
        }

        ExcelStyler::setDefaultSettings(
            $sheet,
            26, 26, 19, 21,
            null,
            Alignment::HORIZONTAL_CENTER,
            false, 0, false,
            TableColumnsName::FONT_HEIGHT_TEXT,
            false, true,
            false
        );

        $this->createBorderSummary($sheet);
    }

    public function createBorder(Worksheet $sheet): void
    {
        $fontHeightHead = TableColumnsName::FONT_HEIGHT_HEAD;

        for ($row = 19; $row <= 22; $row++) {
            for ($col = 1; $col <= 24; $col++) {
                ExcelStyler::setDefaultSettings(
                    $sheet,
                    $row, $row,
                    $col, $col,
                    null,
                    Alignment::HORIZONTAL_CENTER,
                    true,
                    0,
                    false,
                    $fontHeightHead,
                    false,
                    false,
                    true
                );
            }
        }
    }
    public function createBorderSummary(Worksheet $sheet): void
    {
        $fontHeightHead = TableColumnsName::FONT_HEIGHT_HEAD;

        for ($row = 20; $row <= 23; $row++) {
            for ($col = 1; $col <= 23; $col++) {
                ExcelStyler::setDefaultSettings(
                    $sheet,
                    $row, $row,
                    $col, $col,
                    null,
                    Alignment::HORIZONTAL_CENTER,
                    true,
                    0,
                    false,
                    $fontHeightHead,
                    false,
                    false,
                    true
                );
            }
        }
    }

    public function setReportData(Worksheet $sheet, array $logs): void
    {
        $logCount = count($logs['receipt']);
        $logDischargeCount = count($logs['discharge']);
        $logBefore = count($logs['before']);
        $logChild = count($logs['birthday']);
        $logTotal = count($logs['total']);

        $fontHeightHead = TableColumnsName::FONT_HEIGHT_HEAD;
        $fontHeightTableReport = TableColumnsName::FONT_HEIGHT_TEXT;

        ExcelStyler::setDefaultSettings($sheet, 19, 19, 4, 4, (string)$logCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 19, 19, 20, 20, (string)$logTotal,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 4, 4, (string)$logCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightTableReport, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 19, 19, 13, 13, (string)$logDischargeCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 13, 13, (string)$logDischargeCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightTableReport, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 22, 22, 13, 13, (string)$logDischargeCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 20, 20, (string)$logTotal,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightTableReport, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 22, 22, 4, 4, (string)$logCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 22, 22, 20, 20, (string)$logTotal,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 19, 19, 3, 3, (string)$logBefore,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 3, 3, (string)$logBefore,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 22, 22, 3, 3, (string)$logBefore,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 19, 19, 7, 7, (string)$logChild,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 7, 7, (string)$logChild,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);

        ExcelStyler::setDefaultSettings($sheet, 22, 22, 7, 7, (string)$logChild,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, false, false, true);
    }
    public function setReportDataSummary(Worksheet $sheet, array $logs): void
    {
        $logBeforeCount = count($logs['before']);
        $logReceiptCount = count($logs['receipt']);
        $logChildCount = count($logs['birthday']);
        $logDischargeCount = count($logs['discharge']);
        $logTotalCount = count($logs['total']);

        $fontHeightHead = TableColumnsName::FONT_HEIGHT_HEAD;
        $fontHeightTableReport = TableColumnsName::FONT_HEIGHT_TEXT;

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 4, 4, (string)$logBeforeCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 21, 21, 4, 4, (string)$logBeforeCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 23, 23, 4, 4, (string)$logBeforeCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightTableReport, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 5, 5, (string)$logReceiptCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 8, 8, (string)$logChildCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 12, 12, (string)$logDischargeCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 20, 20, 19, 19, (string)$logTotalCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 21, 21, 19, 19, (string)$logTotalCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightHead, true, false, true);

        ExcelStyler::setDefaultSettings($sheet, 23, 23, 19, 19, (string)$logTotalCount,
            Alignment::HORIZONTAL_CENTER, true, 0, false, $fontHeightTableReport, true, false, true);
    }

    public function createColumnsSheetPatient(Worksheet $sheet): void
    {
        $rowFirstSheetPatient = TableColumnsName::ROW_FIRST_SHEET_2; // массив int
        $rowEndSheetPatient = TableColumnsName::ROW_END_SHEET_2;
        $colFirstSheetPatient = TableColumnsName::CELLS_FIRST_SHEET_2;
        $colEndSheetPatient = TableColumnsName::CELLS_END_SHEET_2;

        for ($i = 0; $i < count($rowFirstSheetPatient); $i++) {
            $firstRow = $rowFirstSheetPatient[$i];
            $rowEnd = $rowEndSheetPatient[$i];
            $colFirst = $colFirstSheetPatient[$i];
            $colEnd = $colEndSheetPatient[$i];

            if ($i < 6) {
                $colStartNum = TableColumnsName::CELLS_FIRST_SHEET_2_NUMS[$i];
                $colEndNum = TableColumnsName::CELLS_END_SHEET_2_NUMS[$i];

                ExcelStyler::setDefaultSettings(
                    $sheet,
                    6, 6,
                    $colStartNum, $colEndNum,
                    (string) ($i + 1),
                    Alignment::HORIZONTAL_CENTER,
                    true, 0, false,
                    TableColumnsName::FONT_HEIGHT_TEXT,
                    false, false, true
                );
            }

            ExcelStyler::setDefaultSettings(
                $sheet,
                $firstRow, $rowEnd,
                $colFirst, $colEnd,
                TableColumnsName::NAME_COLUMNS_SHEET_2[$i],
                Alignment::HORIZONTAL_CENTER,
                true, 0, false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false, false, true
            );
        }

        ExcelStyler::setDefaultSettings(
            $sheet,
            7, 7,
            0, 18,
            TableColumnsName::NAME_ROW_DAY_HOSPITAL,
            Alignment::HORIZONTAL_CENTER,
            true, 0, false,
            TableColumnsName::FONT_HEIGHT_REPORT,
            true, false, true
        );
    }

    public function setReportDataSheetTwo(Worksheet $sheet, array $logs): void {
        $size = max(count($logs['receipt']), count($logs['discharge']));

        for ($i = 0; $i < $size; $i++) {
            $rowIndex = $i + 9; // строки начинаются с 8 (то есть Excel 9-я строка)
            $sheet->getRowDimension($rowIndex)->setRowHeight(45);

            foreach (TableColumnsName::CELLS_FIRST_SHEET_2_NUMS as $j => $colStartNum) {
                $colEndNum = TableColumnsName::CELLS_END_SHEET_2_NUMS[$j];

                ExcelStyler::setDefaultSettings(
                    $sheet,
                    $i+8,
                    $i+8,
                    $colStartNum,
                    $colEndNum,
                    null,
                    Alignment::HORIZONTAL_CENTER,
                    true,
                    0,
                    false,
                    TableColumnsName::FONT_HEIGHT_TEXT,
                    false,
                    false,
                    true
                );
            }
        }

        // Вставка данных пациентов (logs)
        foreach ($logs['receipt'] as $i => $log) {
            $rowIndex = $i + 8;
            $fullName = $log->patient->name ?? '';
            $card = $log->patient->medical_card ?? '';
            $text = "{$fullName} \n№ {$card}";

            ExcelStyler::setDefaultSettings(
                $sheet,
                $rowIndex,
                $rowIndex,
                0,
                0,
                $text,
                Alignment::HORIZONTAL_LEFT,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true
            );
        }

        // Вставка данных пациентов (logsDischarge)
        foreach ($logs['discharge'] as $i => $log) {
            $rowIndex = $i + 8;
            $fullName = $log->patient->name ?? '';
            $card = $log->patient->medical_card ?? '';
            $text = "{$fullName} \n№ {$card}";

            ExcelStyler::setDefaultSettings(
                $sheet,
                $rowIndex,
                $rowIndex,
                6,
                6,
                $text,
                Alignment::HORIZONTAL_LEFT,
                true,
                0,
                false,
                TableColumnsName::FONT_HEIGHT_TEXT,
                false,
                false,
                true
            );
        }
    }

    public function generateExcel(Worksheet $sheet1, Worksheet $sheet2, string $date1,
                                  string $date2, array $logs): void
    {
        $this->setSizeColumn($sheet1);
        $this->createHeadLeft($sheet1);
        $this->createHeadRight($sheet1);
        $this->createTitle($sheet1, $date1, $date2);

        $this->createColumnVertical($sheet1);
        $this->createColumnHorizontal($sheet1);
        $this->createNumsRows($sheet1);

        $this->createColumnReport($sheet1);
        $this->setReportData($sheet1, $logs);

        $this->createColumnsSheetPatient($sheet2);
        $this->setReportDataSheetTwo($sheet2, $logs);
    }

    public function generateExcelSummary(Worksheet $sheet1, string $date1,
                                  string $date2, array $logs): void
    {
        $this->setSizeColumn($sheet1);
        $this->createHeadLeft($sheet1);
        $this->createHeadRight($sheet1);
        $this->createTitleSummary($sheet1, $date1, $date2);

        $this->createColumnVerticalSummary($sheet1);
        $this->createColumnHorizontalSummary($sheet1);
        $this->createNumsRowsSummary($sheet1);

        $this->createColumnReportSummary($sheet1);
        $this->setReportDataSummary($sheet1, $logs);
    }

    /**
     * @throws ConnectionException
     */
    public function getWriter(string $date1, string $date2): Xlsx
    {
        $spreadsheet = new Spreadsheet();
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Результат');

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('стр.2');

        $logs = app(LogService::class)->getLogsByDates($date1, $date2);
        $this->generateExcel(
            $sheet1, $sheet2, $date1, $date2, $logs
        );

        $spreadsheet->setActiveSheetIndexByName($sheet1->getTitle());
        return new Xlsx($spreadsheet);
    }

    /**
     * @throws ConnectionException
     */
    public function getWriterSummary(string $date1, string $date2): Xlsx
    {
        $spreadsheet = new Spreadsheet();
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Результат');


        $logs = app(LogService::class)->getLogsByDates($date1, $date2);
        $this->generateExcelSummary(
            $sheet1, $date1, $date2, $logs
        );

        $spreadsheet->setActiveSheetIndexByName($sheet1->getTitle());
        return new Xlsx($spreadsheet);
    }

    public function getFileName(string $date1, string $date2): string
    {
        $date1 = Carbon::parse($date1)->format('d.m.Y');
        $date2 = Carbon::parse($date2)->format('d.m.Y');
        return "Лист ежедневного учета {$date1} - {$date2}.xlsx";
    }

    public function getFileNameSummary(string $date1, string $date2): string
    {
        $date1 = Carbon::parse($date1)->format('d.m.Y');
        $date2 = Carbon::parse($date2)->format('d.m.Y');
        return "Сводная ведомость учета {$date1} - {$date2}.xlsx";
    }
}
