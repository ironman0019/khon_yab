<?php

namespace App\Http\Controllers\Admin\BackupManagement;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Services\Admin\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BackupController extends Controller
{
    public function __construct(
        protected BackupService $backupService
    ) {}

    /**
     * Display a listing of backups.
     */
    public function index(): View
    {
        $backups = $this->backupService->getAllBackups();

        return view('admin.backup-management.index', compact('backups'));
    }

    /**
     * Create a new backup.
     */
    public function create(): RedirectResponse
    {
        try {
            $backup = $this->backupService->createBackup(auth()->id());

            return redirect()->route('admin.backup-management.index')
                ->with('success', 'Backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.backup-management.index')
                ->with('error', 'Failed to create backup: '.$e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function download(Backup $backup): Response
    {
        if (! Storage::exists($backup->file_path)) {
            abort(404, 'Backup file not found.');
        }

        return Storage::download($backup->file_path, $backup->filename);
    }

    /**
     * Remove the specified backup.
     */
    public function destroy(Backup $backup): RedirectResponse
    {
        try {
            $this->backupService->deleteBackup($backup);

            return redirect()->route('admin.backup-management.index')
                ->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.backup-management.index')
                ->with('error', 'Failed to delete backup: '.$e->getMessage());
        }
    }

    /**
     * Clean old backups.
     */
    public function cleanOld(): RedirectResponse
    {
        try {
            $deleted = $this->backupService->cleanOldBackups(30);

            return redirect()->route('admin.backup-management.index')
                ->with('success', "{$deleted} old backup(s) deleted successfully.");
        } catch (\Exception $e) {
            return redirect()->route('admin.backup-management.index')
                ->with('error', 'Failed to clean old backups: '.$e->getMessage());
        }
    }
}
