<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $name
 * @property string $birth_day
 * @property string $gender
 * @property string $medical_card
 * @property string $passport
 * @property string $nationality
 * @property string $address
 * @property string $register_place
 * @property string $snils
 * @property string $polis
 * @property int $diagnosis_id
 */
class Patient extends Model
{
    /**
     * @var string
     */
    protected $table = 'patients';
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'birth_day',
        'gender',
        'medical_card',
        'diagnosis_id',
        'passport',
        'nationality',
        'address',
        'register_place',
        'snils',
        'polis',
    ];
    protected $dates = [
        'birth_day',
    ];

    /**
     * @return BelongsTo
     */
    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(Diagnosis::class);
    }
}
