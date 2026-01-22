<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClaimRejection extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_claim_id',
        'rejection_date',
        'rejection_code',
        'rejection_reason',
        'rejected_amount',
        'partial_approved_amount',
        'panel_remarks',
        'is_appealable',
        'recorded_by',
    ];

    protected $casts = [
        'rejection_date' => 'date',
        'rejected_amount' => 'decimal:2',
        'partial_approved_amount' => 'decimal:2',
        'is_appealable' => 'boolean',
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(PanelClaim::class, 'panel_claim_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function appeals(): HasMany
    {
        return $this->hasMany(ClaimAppeal::class);
    }

    public function hasAppeal(): bool
    {
        return $this->appeals()->exists();
    }

    public function canAppeal(): bool
    {
        return $this->is_appealable && ! $this->hasAppeal();
    }
}
