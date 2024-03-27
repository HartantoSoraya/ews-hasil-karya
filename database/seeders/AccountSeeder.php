<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ])->assignRole(UserRoleEnum::DEV->value);
    }
}
