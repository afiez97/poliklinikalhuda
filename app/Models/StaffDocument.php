<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'staff_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'expiry_date',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * Document types.
     */
    public const TYPES = [
        'ic' => 'MyKad',
        'passport' => 'Pasport',
        'photo' => 'Gambar',
        'degree' => 'Sijil Ijazah',
        'cert' => 'Sijil Profesional',
        'mmc' => 'Sijil MMC',
        'apc' => 'APC',
        'resume' => 'Resume',
        'contract' => 'Kontrak',
        'offer_letter' => 'Surat Tawaran',
        'other' => 'Lain-lain',
    ];

    /**
     * Get the staff.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the uploader.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get document type label.
     */
    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->document_type] ?? $this->document_type;
    }

    /**
     * Get formatted file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        if (! $this->file_size) {
            return '-';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Check if document is expiring soon.
     */
    public function isExpiringSoon(int $days = 30): bool
    {
        if (! $this->expiry_date) {
            return false;
        }

        return $this->expiry_date->lte(now()->addDays($days));
    }

    /**
     * Check if document is expired.
     */
    public function isExpired(): bool
    {
        if (! $this->expiry_date) {
            return false;
        }

        return $this->expiry_date->lt(now());
    }

    /**
     * Scope for expiring documents.
     */
    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>=', now());
    }

    /**
     * Scope for expired documents.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now());
    }
}
