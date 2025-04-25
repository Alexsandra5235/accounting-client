<?php

namespace App\Models\Logs;

use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $log_receipt_id
 * @property int $log_discharge_id
 * @property int $log_reject_id
 * @property int $patient_id
 */
class Log extends Model
{
    /**
     * @var string
     */
    protected $table = 'logs';
    /**
     * @var string[]
     */
    protected $fillable = [
        'log_receipt_id',
        'log_discharge_id',
        'log_reject_id',
        'patient_id',
    ];

    /**
     * @return BelongsTo
     */
    public function log_receipt() : BelongsTo
    {
        return $this->belongsTo(LogReceipt::class);
    }

    /**
     * @return BelongsTo
     */
    public function log_discharge() : BelongsTo
    {
        return $this->belongsTo(LogDischarge::class);
    }

    /**
     * @return BelongsTo
     */
    public function log_reject() : BelongsTo
    {
        return $this->belongsTo(LogReject::class);
    }

    /**
     * @return BelongsTo
     */
    public function patient() : BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
