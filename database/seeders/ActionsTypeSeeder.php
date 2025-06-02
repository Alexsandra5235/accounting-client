<?php

namespace Database\Seeders;

use App\Enum\ActionsEnum;
use App\Models\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\ConnectionException;

class ActionsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ActionsEnum::cases() as $actionType) {
            Action::query()->updateOrCreate(
                ['value' => $actionType->value],
            );
        }
    }
}
