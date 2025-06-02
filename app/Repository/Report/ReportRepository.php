<?php

namespace App\Repository\Report;

use App\Models\Report;

class ReportRepository
{
    public function create(string $fileName, string $content): Report
    {
        return Report::query()->create([
            'filename' => $fileName,
            'file'     => $content,
        ]);
    }
}
