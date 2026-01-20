<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_period_id',
        'staff_id',
        'basic_salary',
        'working_days',
        'days_worked',
        'overtime_pay',
        'allowances',
        'commission',
        'bonus',
        'other_earnings',
        'gross_salary',
        'epf_employee',
        'epf_employer',
        'socso_employee',
        'socso_employer',
        'eis_employee',
        'eis_employer',
        'pcb',
        'unpaid_leave_deduction',
        'loan_deduction',
        'other_deductions',
        'total_deductions',
        'net_salary',
        'bank_name',
        'bank_account_no',
        'payment_status',
        'paid_at',
        'payment_reference',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'basic_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances' => 'decimal:2',
        'commission' => 'decimal:2',
        'bonus' => 'decimal:2',
        'other_earnings' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'epf_employee' => 'decimal:2',
        'epf_employer' => 'decimal:2',
        'socso_employee' => 'decimal:2',
        'socso_employer' => 'decimal:2',
        'eis_employee' => 'decimal:2',
        'eis_employer' => 'decimal:2',
        'pcb' => 'decimal:2',
        'unpaid_leave_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    /**
     * Payment status options.
     */
    public const PAYMENT_STATUSES = [
        'pending' => 'Belum Dibayar',
        'paid' => 'Dibayar',
        'failed' => 'Gagal',
    ];

    /**
     * Get the payroll period.
     */
    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    /**
     * Get the staff.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get payroll items.
     */
    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    /**
     * Get earnings items.
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(PayrollItem::class)->where('type', 'earning');
    }

    /**
     * Get deduction items.
     */
    public function deductions(): HasMany
    {
        return $this->hasMany(PayrollItem::class)->where('type', 'deduction');
    }

    /**
     * Get payment status label.
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Calculate totals from items.
     */
    public function calculateTotals(): void
    {
        // Calculate earnings
        $this->overtime_pay = $this->earnings()->where('code', 'OT')->sum('amount');
        $this->allowances = $this->earnings()->where('code', 'like', 'ALW%')->sum('amount');
        $this->commission = $this->earnings()->where('code', 'COM')->sum('amount');
        $this->bonus = $this->earnings()->where('code', 'BON')->sum('amount');
        $this->other_earnings = $this->earnings()
            ->whereNotIn('code', ['OT', 'BON', 'COM'])
            ->where('code', 'not like', 'ALW%')
            ->sum('amount');

        $this->gross_salary = $this->basic_salary +
            $this->overtime_pay +
            $this->allowances +
            $this->commission +
            $this->bonus +
            $this->other_earnings;

        // Calculate deductions
        $this->other_deductions = $this->deductions()
            ->whereNotIn('code', ['EPF', 'SOCSO', 'EIS', 'PCB', 'UPL', 'LOAN'])
            ->sum('amount');

        $this->total_deductions = $this->epf_employee +
            $this->socso_employee +
            $this->eis_employee +
            $this->pcb +
            $this->unpaid_leave_deduction +
            $this->loan_deduction +
            $this->other_deductions;

        $this->net_salary = $this->gross_salary - $this->total_deductions;

        $this->save();
    }

    /**
     * Get total employer contributions.
     */
    public function getTotalEmployerContributionsAttribute(): float
    {
        return $this->epf_employer + $this->socso_employer + $this->eis_employer;
    }
}
