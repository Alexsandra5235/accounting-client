<?php

namespace App\Facades;

use App\Services\Export\ExportToExcel;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setDefaultSettings( \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $firstRow, int $endRow, int $firstCol, int $endCol, ?string $nameCol, string $alignment, bool $border, int $rotation, bool $underline, int $fontHeight, bool $bold, bool $borderBottom, bool $wrapText)
 */
class ExcelStyler extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ExportToExcel::class;
    }
}
