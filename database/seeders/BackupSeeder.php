<?php

namespace Database\Seeders;

use App\Models\Backup;
use App\Models\User;
use Illuminate\Database\Seeder;

class BackupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = User::where('is_admin', true)->get();

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');

            return;
        }

        // Create sample backup records
        $backups = [
            [
                'filename' => 'backup_2025_01_15_120000.sql',
                'file_path' => 'storage/backups/backup_2025_01_15_120000.sql',
                'file_size' => 5242880, // 5 MB
                'created_by' => $adminUsers->random()->id,
                'created_at' => now()->subDays(5),
            ],
            [
                'filename' => 'backup_2025_01_10_180000.sql',
                'file_path' => 'storage/backups/backup_2025_01_10_180000.sql',
                'file_size' => 5242880, // 5 MB
                'created_by' => $adminUsers->random()->id,
                'created_at' => now()->subDays(10),
            ],
            [
                'filename' => 'backup_2025_01_05_060000.sql',
                'file_path' => 'storage/backups/backup_2025_01_05_060000.sql',
                'file_size' => 6291456, // 6 MB
                'created_by' => $adminUsers->random()->id,
                'created_at' => now()->subDays(15),
            ],
            [
                'filename' => 'backup_2024_12_30_120000.sql',
                'file_path' => 'storage/backups/backup_2024_12_30_120000.sql',
                'file_size' => 5242880, // 5 MB
                'created_by' => $adminUsers->random()->id,
                'created_at' => now()->subDays(20),
            ],
            [
                'filename' => 'backup_2024_12_25_180000.sql',
                'file_path' => 'storage/backups/backup_2024_12_25_180000.sql',
                'file_size' => 4194304, // 4 MB
                'created_by' => $adminUsers->random()->id,
                'created_at' => now()->subDays(25),
            ],
        ];

        foreach ($backups as $backupData) {
            Backup::create($backupData);
        }

        $this->command->info('Backups seeded successfully!');
        $this->command->info('Total backups: '.Backup::count());
    }
}
