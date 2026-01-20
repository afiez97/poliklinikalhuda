<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_code',
        'year',
        'month',
        'start_date',
        'end_date',
        'payment_date',
        'status',
        'total_staff',
        'total_gross',
        'total_deductions',
        'total_net',
        'processed_by',
        'processed_at',
        'finalized_by',
        'finalized_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'payment_date' => 'date',
        'processed_at' => 'datetime',
        'finalized_at' => 'datetime',
        'total_gross' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net' => 'decimal:2',
    ];

    /**
     * Status options.
     */
    public const STATUSES = [
        'draft' => 'Draf',
        'processing' => 'Dalam Proses',
        'finalized' => 'Dimuktamadkan',
        'paid' => 'Dibayar',
    ];

    /**
     * Get the processor.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the finalizer.
     */
    public function finalizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    /**
     * Get payroll records.
     */
    public function payrollRecords(): HasMany
    {
        return $this->hasMany(PayrollRecord::class);
    }

    /**
     * Get commission records.
     */
    public function commissionRecords(): HasMany
    {
        return $this->hasMany(CommissionRecord::class);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get period display name.
     */
    public function getPeriodNameAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Mac', 4 => 'April',
            5 => 'Mei', 6 => 'Jun', 7 => 'Julai', 8 => 'Ogos',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Disember',
        ];

        return ($months[$this->month] ?? $this->month).' '.$this->year;
    }

    /**
     * Check if period is editable.
     */
    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'processing']);
    }

    /**
     * Update totals from payroll records.
     */
    public function updateTotals(): void
    {
        $this->total_staff = $this->payrollRecords()->count();
        $this->total_gross = $this->payrollRecords()->sum('gross_salary');
        $this->total_deductions = $this->payrollRecords()->sum('total_deductions');
        $this->total_net = $this->payrollRecords()->sum('net_salary');
        $this->save();
    }

    /**
     * Generate period code.
     */
    public static function generatePeriodCode(int $year, int $month): string
    {
        return sprintf('%d-%02d', $year, $month);
    }

    /**
     * Scope for year.
     */
    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
