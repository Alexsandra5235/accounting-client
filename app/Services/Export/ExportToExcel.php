<?php

namespace App\Services\Export;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font as SpreadsheetFont;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportToExcel
{
    public function setDefaultSettings(
        Worksheet $sheet,
        int $firstRow,
        int $endRow,
        int $firstCol,
        int $endCol,
        ?string $nameCol,
        string $alignment,
        bool $border,
        int $rotation,
        bool $underline,
        int $fontHeight,
        bool $bold,
        bool $borderBottom,
        bool $wrapText
    ): void {
        // Преобразуем числовые индексы в буквы для объединения ячеек
        $firstColLetter = Coordinate::stringFromColumnIndex($firstCol + 1);
        $endColLetter = Coordinate::stringFromColumnIndex($endCol + 1);
        $mergeRange = "{$firstColLetter}" . ($firstRow + 1) . ":{$endColLetter}" . ($endRow + 1);

        // Стиль
        $styleArray = [
            'alignment' => [
                'horizontal' => constant(Alignment::class . '::HORIZONTAL_' . strtoupper($alignment)),
                'vertical' => Alignment::VERTICAL_CENTER,
                'textRotation' => $rotation,
                'wrapText' => $wrapText,
            ],
            'font' => [
                'name' => 'Times New Roman',
                'size' => $fontHeight,
                'bold' => $bold,
                'underline' => $underline ? SpreadsheetFont::UNDERLINE_SINGLE : SpreadsheetFont::UNDERLINE_NONE,
            ],
            'borders' => [
                'top' => ['borderStyle' => $border ? Border::BORDER_THIN : Border::BORDER_NONE],
                'right' => ['borderStyle' => $border ? Border::BORDER_THIN : Border::BORDER_NONE],
                'bottom' => ['borderStyle' => $border || $borderBottom ? Border::BORDER_THIN : Border::BORDER_NONE],
                'left' => ['borderStyle' => $border ? Border::BORDER_THIN : Border::BORDER_NONE],
            ],
        ];

        // Объединение ячеек
        if ($firstRow !== $endRow || $firstCol !== $endCol) {
            $sheet->mergeCells($mergeRange);
        }

        // Установка значения в верхнюю левую ячейку
        $targetCell = Coordinate::stringFromColumnIndex($firstCol + 1) . ($firstRow + 1);
        if ($nameCol !== null) {
            $sheet->setCellValue($targetCell, $nameCol);
        }

        // Применение стиля ко всем ячейкам в диапазоне
        for ($row = $firstRow; $row <= $endRow; $row++) {
            for ($col = $firstCol; $col <= $endCol; $col++) {
                $cellCoordinate = Coordinate::stringFromColumnIndex($col + 1) . ($row + 1);
                $sheet->getStyle($cellCoordinate)->applyFromArray($styleArray);
            }
        }
    }
}
