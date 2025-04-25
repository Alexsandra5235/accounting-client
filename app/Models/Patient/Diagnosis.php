<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NunoMaduro\Collision\Adapters\Phpunit\State;

/**
 * @property int $id
 * @property int $state_id
 * @property int $wound_id
 */
class Diagnosis extends Model
{
    /**
     * @var string
     */
    protected $table = 'diagnosis';
    /**
     * @var string[]
     */
    protected $fillable = [
      'state_id',
      'wound_id',
    ];

    /**
     * @return BelongsTo
     */
    public function state() : BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }

    /**
     * @return BelongsTo
     */
    public function wound() : BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }
}
