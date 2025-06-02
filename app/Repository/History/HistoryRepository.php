<?php

namespace App\Repository\History;

use App\DTO\History\HistoryDTO;
use App\Models\History;

class HistoryRepository
{
   public function create(HistoryDTO $historyDTO): History
   {
        return History::query()->create([
            'action_id' => $historyDTO->action->getAction()->id,
            'diff' => $historyDTO->diff,
            'user_id' => $historyDTO->user_id,
            'log_id' => $historyDTO->log,
        ]);
   }
}
