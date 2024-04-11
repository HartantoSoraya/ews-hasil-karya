<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $appname = config('app.name');

        User::create([
            'email' => 'admin@'.str_replace(' ', '', strtolower($appname)).'.co.id',
            'password' => bcrypt('password'),
        ])->assignRole(UserRoleEnum::DEV->value);

        User::create([
            'email' => 'client@'.str_replace(' ', '', strtolower($appname)).'.co.id',
            'password' => bcrypt('password'),
        ])->assignRole(UserRoleEnum::CLIENT->value);
    }
}
