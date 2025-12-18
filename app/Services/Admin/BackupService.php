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
            $host = $config['host'] ?? '127.0.0.1';
            $port = $config['port'] ?? '3306';
            $username = $config['username'] ?? 'root';
            $password = $config['password'] ?? '';
            $database = $config['database'] ?? '';

            // Ensure the directory exists
            $directory = dirname($outputPath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Create a temporary config file for password (more secure)
            $tempConfigFile = tempnam(sys_get_temp_dir(), 'mysql_backup_');
            $configContent = "[client]\n";
            $configContent .= "host={$host}\n";
            $configContent .= "port={$port}\n";
            $configContent .= "user={$username}\n";
            $configContent .= "password={$password}\n";
            file_put_contents($tempConfigFile, $configContent);
            chmod($tempConfigFile, 0600); // Read/write for owner only

            try {
                // Build mysqldump command using the config file
                $command = sprintf(
                    'mysqldump --defaults-extra-file=%s %s > %s 2>&1',
                    escapeshellarg($tempConfigFile),
                    escapeshellarg($database),
                    escapeshellarg($outputPath)
                );

                $commandOutput = [];
                $returnCode = 0;
                exec($command, $commandOutput, $returnCode);

                // Clean up temp file
                @unlink($tempConfigFile);

                if ($returnCode !== 0) {
                    $errorMessage = ! empty($commandOutput) ? implode("\n", $commandOutput) : 'mysqldump command failed';
                    throw new \RuntimeException('Backup failed: '.$errorMessage);
                }

                if (! file_exists($outputPath)) {
                    throw new \RuntimeException('Backup failed: Output file was not created');
                }

                if (filesize($outputPath) === 0) {
                    throw new \RuntimeException('Backup failed: Output file is empty');
                }
            } catch (\Exception $e) {
                // Clean up temp file on error
                @unlink($tempConfigFile);
                throw $e;
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
        // Ensure the backup has an ID
        if (empty($backup->id)) {
            throw new \RuntimeException('Cannot delete backup: Backup model does not have an ID. Make sure the backup exists in the database.');
        }

        $backupId = (int) $backup->id;
        $filePath = $backup->file_path;

        // Delete file if exists and file_path is not null
        if ($filePath && Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // Delete record using query builder directly (bypass Eloquent)
        $deleted = DB::table('backups')->where('id', $backupId)->delete();

        // Verify the record was actually deleted
        if ($deleted === 0) {
            throw new \RuntimeException("Failed to delete backup record with ID: {$backupId}. No rows were deleted.");
        }

        // Double-check the record no longer exists
        $stillExists = DB::table('backups')->where('id', $backupId)->exists();
        if ($stillExists) {
            throw new \RuntimeException("Failed to delete backup record with ID: {$backupId}. The record still exists after deletion attempt.");
        }

        return true;
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
