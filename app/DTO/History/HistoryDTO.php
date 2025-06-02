<?php

namespace App\DTO\History;

use App\Enum\ActionsEnum;

class HistoryDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ActionsEnum $action,
        public ?array $diff,
        public ?int $log,
        public ?int $user_id,
    ) { }
}
