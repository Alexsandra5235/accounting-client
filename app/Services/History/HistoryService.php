<?php

namespace App\Services\History;

use App\DTO\History\HistoryDTO;
use App\Models\History;
use App\Repository\History\HistoryRepository;

class HistoryService
{
    public function store(HistoryDTO $historyDTO): History
    {
        return app(HistoryRepository::class)->create($historyDTO);
    }

    public function getDiff(array $logBefore, array $logAfter, string $path = ''): array
    {
        $diff = [];

        $allKeys = array_unique(array_merge(array_keys($logBefore), array_keys($logAfter)));

        foreach ($allKeys as $key) {
            $fullPath = $path === '' ? $key : "$path.$key";

            $valueBefore = $logBefore[$key] ?? null;
            $valueAfter = $logAfter[$key] ?? null;

            if (is_array($valueBefore) && is_array($valueAfter)) {
                $nestedDiff = $this->getDiff($valueBefore, $valueAfter, $fullPath);
                $diff = array_merge($diff, $nestedDiff);
            } elseif (($valueBefore !== $valueAfter) && $key !== 'updated_at') {
                $diff[$fullPath] = [
                    'before' => $valueBefore,
                    'after' => $valueAfter,
                ];
            }
        }

        return $diff;
    }
}
