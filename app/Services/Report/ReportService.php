<?php

namespace App\Services\Report;

use App\Models\Report;
use App\Repository\Report\ReportRepository;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportService
{
    public function store(Xlsx $writer, string $filename): Report
    {
        $stream = fopen('php://temp', 'r+');
        $writer->save($stream);
        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);
        return app(ReportRepository::class)->create($filename, $content);
    }
}
