<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procedure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'encounter_id',
        'patient_id',
        'performed_by',
        'procedure_code',
        'procedure_name',
        'description',
        'performed_at',
        'duration_minutes',
        'findings',
        'complications',
        'charge_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'charge_amount' => 'decimal:2',
    ];

    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        'scheduled' => 'Dijadualkan',
        'in_progress' => 'Sedang Dijalankan',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'performed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
