<?php

namespace App\Models;

use App\Enum\ActionsEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $value
 * @property int $id
 */
class Action extends Model
{
    protected $fillable = ['value'];

    public $timestamps = true;

    public function getEnum(): ?ActionsEnum
    {
        return ActionsEnum::tryFrom($this->value);
    }
}
