<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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
                'name' => 'Anggota',
                'email' => 'anggota@magangdpr.test',
                'role' => 'anggota',
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
    }
}