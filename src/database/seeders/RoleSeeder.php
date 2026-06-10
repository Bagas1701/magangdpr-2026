<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'super_admin',
            'admin',
            'staf',
            'tenaga_ahli',
            'anggota_dewan',
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                [
                    'name' => $role,
                    'guard_name' => 'web',
                ],
                [
                    'name' => $role,
                    'guard_name' => 'web',
                ]
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}