<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = UserRoleEnum::toArrayValue();

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
