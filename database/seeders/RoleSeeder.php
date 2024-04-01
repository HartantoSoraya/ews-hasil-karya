<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    private $permissions = [
        'dashboard',

        'ews-device-list',
        'ews-device-create',
        'ews-device-edit',
        'ews-device-delete',

        'client-list',
        'client-create',
        'client-edit',
        'client-delete',

        'ews-device-measurement-list',
        'ews-device-measurement-create',
        'ews-device-measurement-edit',
        'ews-device-measurement-delete',
    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = UserRoleEnum::toArrayValue();

        foreach ($roles as $role) {
            $role = Role::create(['name' => $role]);

            $permissions = Permission::pluck('id', 'id')->all();
            if ($role->name === UserRoleEnum::DEV->value) {
                $role->syncPermissions($permissions);
            } elseif ($role->name === UserRoleEnum::CLIENT->value) {
                $role->syncPermissions([
                    'dashboard',
                    'ews-device-list',
                    'ews-device-measurement-list',
                ]);
            }
        }
    }
}
