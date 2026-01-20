<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Backup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'disk',
        'path',
        'size',
        'type',
        'status',
        'error_message',
        'is_encrypted',
        'encryption_algorithm',
        'checksum',
        'started_at',
        'completed_at',
        'expires_at',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'is_encrypted' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user who created this backup.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include completed backups.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed backups.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include non-expired backups.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-secondary">Menunggu</span>',
            'running' => '<span class="badge bg-info">Sedang Berjalan</span>',
            'completed' => '<span class="badge bg-success">Selesai</span>',
            'failed' => '<span class="badge bg-danger">Gagal</span>',
            default => '<span class="badge bg-secondary">'.ucfirst($this->status).'</span>',
        };
    }

    /**
     * Get type badge HTML.
     */
    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'full' => '<span class="badge bg-primary">Penuh</span>',
            'incremental' => '<span class="badge bg-info">Tambahan</span>',
            'database' => '<span class="badge bg-warning">Pangkalan Data</span>',
            default => '<span class="badge bg-secondary">'.ucfirst($this->type).'</span>',
        };
    }

    /**
     * Check if backup file exists.
     */
    public function fileExists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    /**
     * Get full path to backup file.
     */
    public function getFullPathAttribute(): string
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    /**
     * Delete backup file from storage.
     */
    public function deleteFile(): bool
    {
        if ($this->fileExists()) {
            return Storage::disk($this->disk)->delete($this->path);
        }

        return true;
    }

    /**
     * Mark backup as running.
     */
    public function markAsRunning(): void
    {
        $this->update([
            'status' => 'running',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark backup as completed.
     */
    public function markAsCompleted(int $size, string $checksum): void
    {
        $this->update([
            'status' => 'completed',
            'size' => $size,
            'checksum' => $checksum,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark backup as failed.
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
            'completed_at' => now(),
        ]);
    }

    /**
     * Check if backup is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get duration of backup process.
     */
    public function getDurationAttribute(): ?string
    {
        if (! $this->started_at || ! $this->completed_at) {
            return null;
        }

        $diff = $this->started_at->diff($this->completed_at);

        if ($diff->h > 0) {
            return $diff->format('%h jam %i minit %s saat');
        }
        if ($diff->i > 0) {
            return $diff->format('%i minit %s saat');
        }

        return $diff->format('%s saat');
    }
}
