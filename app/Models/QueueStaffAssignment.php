<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueStaffAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'counter_id',
        'assignment_date',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the counter.
     */
    public function counter(): BelongsTo
    {
        return $this->belongsTo(QueueCounter::class, 'counter_id');
    }

    /**
     * Scope for today's assignments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('assignment_date', today());
    }

    /**
     * Scope for active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for a specific counter.
     */
    public function scopeForCounter($query, int $counterId)
    {
        return $query->where('counter_id', $counterId);
    }

    /**
     * Check if assignment is currently within working hours.
     */
    public function isWithinWorkingHours(): bool
    {
        $now = now()->format('H:i:s');
        $start = $this->start_time?->format('H:i:s');
        $end = $this->end_time?->format('H:i:s');

        if (! $start) {
            return true;
        }

        if (! $end) {
            return $now >= $start;
        }

        return $now >= $start && $now <= $end;
    }

    /**
     * Deactivate assignment.
     */
    public function deactivate(): void
    {
        $this->update([
            'is_active' => false,
            'end_time' => now()->format('H:i'),
        ]);
    }
}
