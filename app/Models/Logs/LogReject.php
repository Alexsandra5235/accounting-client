<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $reason_refusal
 * @property string $name_medical_worker
 * @property string $add_info
 * @property int $id
 */
class LogReject extends Model
{
    /**
     * @var string
     */
    protected $table = 'log_rejects';
    /**
     * @var string[]
     */
    protected $fillable = [
        'reason_refusal',
        'name_medical_worker',
        'add_info',
    ];
}
