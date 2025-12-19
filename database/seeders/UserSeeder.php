<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin users with multilingual names
        $adminUsers = [
            [
                'full_name' => 'Admin User',
                'email' => 'admin@khonyab.af',
                'password' => Hash::make('password'),
                'user_type' => UserType::User->value,
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'مدیر سیستم',
                'email' => 'admin-fa@khonyab.af',
                'password' => Hash::make('password'),
                'user_type' => UserType::User->value,
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'د سیسټم مدیر',
                'email' => 'admin-ps@khonyab.af',
                'password' => Hash::make('password'),
                'user_type' => UserType::User->value,
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($adminUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Test users for different user types
        $testUsers = [
            // Regular users
            [
                'full_name' => 'John Smith',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
                'user_type' => UserType::User->value,
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'احمد محمدی',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
                'user_type' => UserType::User->value,
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
            [
                'full_name' => 'احمد خان',
                'email' => 'user3@example.com',
                'password' => Hash::make('password'),
                'user_type' => UserType::User->value,
                'is_admin' => false,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($testUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Total users: '.User::count());
    }
}

