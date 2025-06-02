<?php

namespace App\Services\History;

use App\DTO\History\HistoryDTO;
use App\Enum\FieldNameTranslator;
use App\Models\History;
use App\Repository\History\HistoryRepository;
use Carbon\Carbon;
use Exception;

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
                if($this->isCarbonDate($valueBefore) && $this->isCarbonDate($valueAfter)) {
                    $diff[FieldNameTranslator::translate($fullPath)] = [
                        'before' => Carbon::parse($valueAfter)->locale('ru')->translatedFormat('d.m.Y'),
                        'after' => Carbon::parse($valueBefore)->locale('ru')->translatedFormat('d.m.Y'),
                    ];
                } else {
                    $diff[FieldNameTranslator::translate($fullPath)] = [
                        'before' => $valueBefore,
                        'after' => $valueAfter,
                    ];
                }
            }
        }

        return $diff;
    }
    private function isCarbonDate($value): bool
    {
        try {
            return Carbon::parse($value) instanceof Carbon;
        } catch (Exception $e) {
            return false;
        }
    }
}
