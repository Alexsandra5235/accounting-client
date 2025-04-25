<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $code
 * @property string $value
 * @property int $id
 */
class Classifiers extends Model
{
    /**
     * @var string
     */
    protected $table = 'classifiers';
    /**
     * @var string[]
     */
    protected $fillable = [
        'code',
        'value',
    ];
}
