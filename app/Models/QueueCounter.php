<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QueueCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_type_id',
        'code',
        'name',
        'name_en',
        'name_zh',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the queue type.
     */
    public function queueType(): BelongsTo
    {
        return $this->belongsTo(QueueType::class);
    }

    /**
     * Get tickets served at this counter.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(QueueTicket::class, 'current_counter_id');
    }

    /**
     * Get calls made from this counter.
     */
    public function calls(): HasMany
    {
        return $this->hasMany(QueueCall::class, 'counter_id');
    }

    /**
     * Get staff assignments for this counter.
     */
    public function staffAssignments(): HasMany
    {
        return $this->hasMany(QueueStaffAssignment::class, 'counter_id');
    }

    /**
     * Scope for active counters.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the current active staff assignment.
     */
    public function getCurrentStaffAttribute()
    {
        return $this->staffAssignments()
            ->where('assignment_date', today())
            ->where('is_active', true)
            ->with('user')
            ->first();
    }

    /**
     * Check if counter has an assigned staff today.
     */
    public function hasAssignedStaff(): bool
    {
        return $this->current_staff !== null;
    }

    /**
     * Get localized name.
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        return match ($locale) {
            'en' => $this->name_en ?? $this->name,
            'zh' => $this->name_zh ?? $this->name,
            default => $this->name,
        };
    }

    /**
     * Get display name with code.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /**
     * Get today's served count.
     */
    public function getTodayServedCountAttribute(): int
    {
        return $this->calls()
            ->whereDate('called_at', today())
            ->where('responded', true)
            ->count();
    }

    /**
     * Get current ticket being served.
     */
    public function getCurrentServingTicket(): ?QueueTicket
    {
        return QueueTicket::where('current_counter_id', $this->id)
            ->where('status', 'serving')
            ->whereDate('queue_date', today())
            ->first();
    }
}
