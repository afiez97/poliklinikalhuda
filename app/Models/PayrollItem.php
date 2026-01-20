<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_record_id',
        'type',
        'code',
        'description',
        'amount',
        'quantity',
        'rate',
        'is_taxable',
        'is_epf_applicable',
        'is_socso_applicable',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_epf_applicable' => 'boolean',
        'is_socso_applicable' => 'boolean',
    ];

    /**
     * Common earning codes.
     */
    public const EARNING_CODES = [
        'OT' => 'Kerja Lebih Masa',
        'ALW_MEAL' => 'Elaun Makan',
        'ALW_TRANSPORT' => 'Elaun Pengangkutan',
        'ALW_PHONE' => 'Elaun Telefon',
        'ALW_SPECIAL' => 'Elaun Khas',
        'COM' => 'Komisen',
        'BON' => 'Bonus',
        'BON_13' => 'Bonus Gaji ke-13',
        'CLAIM' => 'Tuntutan',
    ];

    /**
     * Common deduction codes.
     */
    public const DEDUCTION_CODES = [
        'EPF' => 'KWSP',
        'SOCSO' => 'PERKESO',
        'EIS' => 'SIP',
        'PCB' => 'PCB',
        'UPL' => 'Potongan Cuti Tanpa Gaji',
        'LOAN' => 'Pinjaman',
        'ADV' => 'Pendahuluan Gaji',
        'OTHER' => 'Potongan Lain',
    ];

    /**
     * Get the payroll record.
     */
    public function payrollRecord(): BelongsTo
    {
        return $this->belongsTo(PayrollRecord::class);
    }

    /**
     * Get code name.
     */
    public function getCodeNameAttribute(): string
    {
        if ($this->type === 'earning') {
            return self::EARNING_CODES[$this->code] ?? $this->description;
        }

        return self::DEDUCTION_CODES[$this->code] ?? $this->description;
    }

    /**
     * Scope for earnings.
     */
    public function scopeEarnings($query)
    {
        return $query->where('type', 'earning');
    }

    /**
     * Scope for deductions.
     */
    public function scopeDeductions($query)
    {
        return $query->where('type', 'deduction');
    }
}
