<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_config_id',
        'snapshot_date',
        'value',
        'target',
        'status',
        'breakdown',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'value' => 'decimal:2',
        'target' => 'decimal:2',
        'breakdown' => 'array',
    ];

    public function kpiConfig(): BelongsTo
    {
        return $this->belongsTo(KpiConfig::class);
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('snapshot_date', $date);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('snapshot_date', [$startDate, $endDate]);
    }

    public function getVarianceAttribute(): float
    {
        if (!$this->target || $this->target == 0) return 0;
        return (($this->value - $this->target) / $this->target) * 100;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'good' => 'success',
            'warning' => 'warning',
            'critical' => 'danger',
            default => 'secondary',
        };
    }
}
