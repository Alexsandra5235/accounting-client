<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;
use function Symfony\Component\Translation\t;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->updateOrCreate([
            'slug' => 'admin',
            'name' => 'Зав. отделением',
            'permissions' => [
                'history' => true,
                'statistic' => true,
                'access.add' => true,
                'access.edit' => true,
                'access.delete' => true,
                'report.create' => true,
                'report.history' => true,
                'platform.index' => true,
                'platform.systems.roles' => true,
                'platform.systems.users' => true,
            ]
        ]);

        Role::query()->updateOrCreate([
            'slug' => 'medical',
            'name' => 'Мед. сестра',
            'permissions' => [
                'history' => false,
                'statistic' => false,
                'access.add' => true,
                'access.edit' => true,
                'access.delete' => true,
                'report.create' => false,
                'report.history' => false,
                'platform.index' => false,
                'platform.systems.roles' => false,
                'platform.systems.users' => false,
            ]
        ]);

        Role::query()->updateOrCreate([
            'slug' => 'user',
            'name' => 'Обычный пользователь',
            'permissions' => [
                'history' => false,
                'statistic' => false,
                'access.add' => false,
                'access.edit' => false,
                'access.delete' => false,
                'report.create' => false,
                'report.history' => false,
                'platform.index' => false,
                'platform.systems.roles' => false,
                'platform.systems.users' => false,
            ]
        ]);
    }
}
