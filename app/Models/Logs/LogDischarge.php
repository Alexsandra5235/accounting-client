<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $datetime_discharge
 * @property string $datetime_inform
 * @property string $outcome
 * @property string $section_transferred
 */
class LogDischarge extends Model
{
    /**
     * @var string
     */
    protected $table = 'log_discharges';
    /**
     * @var string[]
     */
    protected $fillable = [
        'datetime_discharge',
        'datetime_inform',
        'outcome',
        'section_transferred',
    ];
    protected array $dates = [
        'datetime_discharge',
        'datetime_inform',
    ];
}
