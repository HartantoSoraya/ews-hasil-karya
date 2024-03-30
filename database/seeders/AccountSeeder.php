<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ])->assignRole(UserRoleEnum::DEV->value);

        User::create([
            'email' => 'client@admin.com',
            'password' => bcrypt('password'),
        ]);
    }
}
