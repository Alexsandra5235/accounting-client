<?php

namespace App\Models;

use App\Enum\ActionsEnum;
use App\Services\Api\ApiService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Client\ConnectionException;

/**
 * @property string $action_id
 * @property array $diff
 * @property string $user_id
 * @property string $log_id
 */
class History extends Model
{
    public $fillable = [
        'action_id',
        'diff',
        'user_id',
        'log_id',
    ];

    public $casts = [
        'diff' => 'array',
    ];

    /**
     * @throws ConnectionException
     */
    public function log() {
        return app(ApiService::class)->getLogById(env('API_LOG_TOKEN'), $this->log_id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
}
