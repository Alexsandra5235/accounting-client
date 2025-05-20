<?php

namespace App\Services\Export;

use App\Facades\ExcelStyler;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenerateExcelService
{
    private array $headsLeft = [
        'Заголовок 1',
        'Заголовок 2',
        'Заголовок 3',
        'Заголовок 4',
        'Заголовок 5', // <- элемент с индексом 4
    ];

    private int $fontHeightHead = 12; // аналог переменной `fontHeightHead` в Java

    public function createHeadLeft(Spreadsheet $spreadsheet, Worksheet $sheet): void
    {
        for ($i = 0; $i < 5; $i++) {
            ExcelStyler::setDefaultSettings(
                $spreadsheet,
                $sheet,
                $i, $i,           // firstRow, endRow
                0, 4,             // firstCol, endCol (объединить 5 ячеек по горизонтали)
                $this->headsLeft[$i],
                $i === 4 ? 'center' : 'left',
                false,            // border
                0,                // rotation
                $i === 4,         // underline только для последнего
                $this->fontHeightHead,
                false,            // bold
                false,            // borderBottom
                true              // wrapText
            );
        }
    }


}
