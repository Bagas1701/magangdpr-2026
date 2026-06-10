<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@magangdpr.test',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@magangdpr.test',
                'role' => 'admin',
            ],
            [
                'name' => 'Anggota Dewan',
                'email' => 'anggota@magangdpr.test',
                'role' => 'anggota_dewan',
            ],
            [
                'name' => 'Tenaga Ahli',
                'email' => 'tenagaahli@magangdpr.test',
                'role' => 'tenaga_ahli',
            ],
            [
                'name' => 'Staf',
                'email' => 'staf@magangdpr.test',
                'role' => 'staf',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                ]
            );

            $user->syncRoles([$userData['role']]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}