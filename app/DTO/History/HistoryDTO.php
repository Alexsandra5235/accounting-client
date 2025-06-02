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
        public ?int $user_id,
        public ?array $diff = null,
        public ?int $log = null,
    ) { }
}
