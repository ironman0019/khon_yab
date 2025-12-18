<?php

namespace App\Services\Admin;

use App\Models\Backup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackupService
{
    /**
     * Create a database backup.
     */
    public function createBackup(int $userId): Backup
    {
        $filename = 'backup_'.date('Y-m-d_His').'_'.Str::random(6).'.sql';
        $backupPath = 'backups/'.$filename;

        // Create backups directory if it doesn't exist
        if (! Storage::exists('backups')) {
            Storage::makeDirectory('backups');
        }

        $fullPath = Storage::path($backupPath);

        // Get database configuration
        $connection = DB::connection();
        $config = $connection->getConfig();

        // Create backup using mysqldump or appropriate method
        $this->executeBackup($config, $fullPath);

        $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;

        // Save backup record
        return Backup::create([
            'filename' => $filename,
            'file_path' => $backupPath,
            'file_size' => $fileSize,
            'created_by' => $userId,
        ]);
    }

    /**
     * Execute database backup command.
     */
    protected function executeBackup(array $config, string $outputPath): void
    {
        $driver = $config['driver'] ?? 'mysql';

        if ($driver === 'mysql') {
            // Build mysqldump command with proper escaping
            $host = escapeshellarg($config['host'] ?? '127.0.0.1');
            $port = escapeshellarg($config['port'] ?? '3306');
            $username = escapeshellarg($config['username'] ?? 'root');
            $password = escapeshellarg($config['password'] ?? '');
            $database = escapeshellarg($config['database'] ?? '');
            $output = escapeshellarg($outputPath);

            // Use MYSQL_PWD environment variable for password to avoid command line exposure
            $env = "MYSQL_PWD={$password}";
            $command = "{$env} mysqldump -h {$host} -P {$port} -u {$username} {$database} > {$output}";

            exec($command.' 2>&1', $output, $returnCode);

            if ($returnCode !== 0) {
                throw new \RuntimeException('Backup failed: '.implode("\n", $output));
            }
        } else {
            // For other database drivers, implement appropriate backup method
            throw new \RuntimeException("Backup not supported for driver: {$driver}");
        }
    }

    /**
     * Get all backups.
     */
    public function getAllBackups()
    {
        return Backup::with('createdBy')->latest()->get();
    }

    /**
     * Get backup file path.
     */
    public function getBackupPath(Backup $backup): string
    {
        return Storage::path($backup->file_path);
    }

    /**
     * Delete backup file and record.
     */
    public function deleteBackup(Backup $backup): bool
    {
        // Delete file if exists
        if (Storage::exists($backup->file_path)) {
            Storage::delete($backup->file_path);
        }

        // Delete record
        return $backup->delete();
    }

    /**
     * Clean old backups (older than specified days).
     */
    public function cleanOldBackups(int $days = 30): int
    {
        $cutoffDate = now()->subDays($days);
        $oldBackups = Backup::where('created_at', '<', $cutoffDate)->get();

        $deleted = 0;
        foreach ($oldBackups as $backup) {
            if ($this->deleteBackup($backup)) {
                $deleted++;
            }
        }

        return $deleted;
    }
}
