<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $date_receipt
 * @property string $time_receipt
 * @property string $datetime_alcohol
 * @property string $phone_agent
 * @property string $delivered
 * @property string $fact_alcohol
 * @property string $result_research
 * @property string $section_medical
 * @property int $id
 */
class LogReceipt extends Model
{
    /**
     * @var string
     */
    protected $table = 'log_receipts';
    /**
     * @var string[]
     */
    protected $fillable = [
        'date_receipt',
        'time_receipt',
        'datetime_alcohol',
        'phone_agent',
        'delivered',
        'fact_alcohol',
        'result_research',
        'section_medical',
    ];
    protected $dates = [
        'date_receipt',
        'datetime_alcohol',
    ];
}
