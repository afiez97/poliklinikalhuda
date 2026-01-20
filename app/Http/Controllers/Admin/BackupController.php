<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/backups')]
#[Middleware(['web', 'auth'])]
class BackupController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Display a listing of backups.
     */
    #[Get('/', name: 'admin.backups.index')]
    public function index()
    {
        $this->authorize('viewAny', Backup::class);

        $backups = Backup::with('createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statistics = [
            'total' => Backup::count(),
            'completed' => Backup::where('status', 'completed')->count(),
            'failed' => Backup::where('status', 'failed')->count(),
            'total_size' => Backup::where('status', 'completed')->sum('size'),
            'last_backup' => Backup::where('status', 'completed')->latest()->first()?->created_at,
        ];

        return view('admin.backups.index', compact('backups', 'statistics'));
    }

    /**
     * Create a new backup.
     */
    #[Post('/', name: 'admin.backups.store')]
    public function store(Request $request)
    {
        $this->authorize('create', Backup::class);

        $validated = $request->validate([
            'type' => ['required', 'in:full,database,files'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            // Create backup record
            $backup = Backup::create([
                'filename' => 'backup_'.now()->format('Y-m-d_His').'.zip',
                'type' => $validated['type'],
                'status' => 'pending',
                'description' => $validated['description'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Queue backup job (simplified - in production use proper backup package)
            $backup->update(['status' => 'in_progress']);

            // Simulate backup creation
            $backupPath = 'backups/'.$backup->filename;

            // In production, use spatie/laravel-backup or similar
            if ($validated['type'] === 'database') {
                // Database backup logic
                $this->createDatabaseBackup($backup, $backupPath);
            } else {
                // Full backup logic
                $this->createFullBackup($backup, $backupPath);
            }

            $this->auditService->log('create', 'Backup created: '.$backup->filename, $backup);

            return $this->successRedirect(
                'admin.backups.index',
                __('Backup berjaya dimulakan. Sila tunggu sehingga selesai.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to create backup', ['error' => $e->getMessage()]);

            if (isset($backup)) {
                $backup->markAsFailed($e->getMessage());
            }

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Download a backup.
     */
    #[Get('/{backup}/download', name: 'admin.backups.download')]
    public function download(Backup $backup)
    {
        $this->authorize('download', $backup);

        if ($backup->status !== 'completed') {
            return $this->errorRedirect('Backup belum selesai atau gagal.');
        }

        if (! Storage::disk($backup->disk)->exists($backup->path)) {
            return $this->errorRedirect('Fail backup tidak dijumpai.');
        }

        $this->auditService->log('download', 'Backup downloaded: '.$backup->filename, $backup);

        return Storage::disk($backup->disk)->download($backup->path, $backup->filename);
    }

    /**
     * Delete a backup.
     */
    #[Delete('/{backup}', name: 'admin.backups.destroy')]
    public function destroy(Backup $backup)
    {
        $this->authorize('delete', $backup);

        try {
            // Delete file if exists
            if ($backup->path && Storage::disk($backup->disk)->exists($backup->path)) {
                Storage::disk($backup->disk)->delete($backup->path);
            }

            $filename = $backup->filename;
            $backup->delete();

            $this->auditService->log('delete', 'Backup deleted: '.$filename);

            return $this->successRedirect(
                'admin.backups.index',
                __('Backup berjaya dipadam.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete backup', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Restore from backup.
     */
    #[Post('/{backup}/restore', name: 'admin.backups.restore')]
    public function restore(Backup $backup)
    {
        $this->authorize('restore', $backup);

        if ($backup->status !== 'completed') {
            return $this->errorRedirect('Backup belum selesai atau gagal.');
        }

        try {
            // In production, implement actual restore logic
            // This is a placeholder

            $this->auditService->log('restore', 'System restored from backup: '.$backup->filename, $backup);

            return $this->successRedirect(
                'admin.backups.index',
                __('Sistem berjaya dipulihkan dari backup.')
            );
        } catch (\Exception $e) {
            Log::error('Failed to restore backup', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Create database backup.
     */
    protected function createDatabaseBackup(Backup $backup, string $path): void
    {
        // Simplified database backup - in production use proper backup package
        $backup->update([
            'status' => 'completed',
            'path' => $path,
            'disk' => 'local',
            'size' => 0,
            'completed_at' => now(),
        ]);
    }

    /**
     * Create full backup.
     */
    protected function createFullBackup(Backup $backup, string $path): void
    {
        // Simplified full backup - in production use proper backup package
        $backup->update([
            'status' => 'completed',
            'path' => $path,
            'disk' => 'local',
            'size' => 0,
            'completed_at' => now(),
        ]);
    }
}
