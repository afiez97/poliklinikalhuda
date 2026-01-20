<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@poliklinikalhuda.my'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('Admin@123456'),
                'status' => 'active',
                'email_verified_at' => now(),
                'password_changed_at' => now(),
                'mfa_required' => true,
            ]
        );

        $superAdmin->assignRole('super-admin');

        $this->command->info('Super admin user created: admin@poliklinikalhuda.my / Admin@123456');

        // Create demo users for each role
        $demoUsers = [
            [
                'name' => 'Dr. Ahmad',
                'username' => 'dr.ahmad',
                'email' => 'doktor@poliklinikalhuda.my',
                'role' => 'doktor',
            ],
            [
                'name' => 'Jururawat Fatimah',
                'username' => 'fatimah',
                'email' => 'jururawat@poliklinikalhuda.my',
                'role' => 'jururawat',
            ],
            [
                'name' => 'Kerani Aminah',
                'username' => 'aminah',
                'email' => 'kerani@poliklinikalhuda.my',
                'role' => 'kerani',
            ],
            [
                'name' => 'Farmasi Rahman',
                'username' => 'rahman',
                'email' => 'farmasi@poliklinikalhuda.my',
                'role' => 'farmasi',
            ],
        ];

        foreach ($demoUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'password' => Hash::make('User@123456'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'password_changed_at' => now(),
                ]
            );

            $user->assignRole($userData['role']);

            $this->command->info("Demo user created: {$userData['email']} / User@123456 ({$userData['role']})");
        }
    }
}
