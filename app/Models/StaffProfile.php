<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'ic_number',
        'date_of_birth',
        'gender',
        'marital_status',
        'address',
        'city',
        'state',
        'postcode',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'department',
        'position',
        'employment_type',
        'join_date',
        'confirmation_date',
        'resignation_date',
        'employment_status',
        'basic_salary',
        'bank_name',
        'bank_account_number',
        'epf_number',
        'socso_number',
        'eis_number',
        'tax_number',
        'epf_employee_rate',
        'epf_employer_rate',
        'mmc_number',
        'apc_number',
        'apc_expiry_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'confirmation_date' => 'date',
        'resignation_date' => 'date',
        'apc_expiry_date' => 'date',
        'basic_salary' => 'decimal:2',
        'epf_employee_rate' => 'decimal:2',
        'epf_employer_rate' => 'decimal:2',
    ];

    public const EMPLOYMENT_TYPES = [
        'permanent' => 'Tetap',
        'contract' => 'Kontrak',
        'part_time' => 'Separuh Masa',
        'locum' => 'Locum',
        'intern' => 'Pelatih',
    ];

    public const EMPLOYMENT_STATUSES = [
        'active' => 'Aktif',
        'probation' => 'Percubaan',
        'resigned' => 'Berhenti',
        'terminated' => 'Diberhentikan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function allowances(): HasMany
    {
        return $this->hasMany(StaffAllowance::class, 'user_id', 'user_id');
    }

    public function leaveEntitlements(): HasMany
    {
        return $this->hasMany(LeaveEntitlement::class, 'user_id', 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    public function getYearsOfServiceAttribute(): float
    {
        if (!$this->join_date) return 0;
        return $this->join_date->diffInYears(now());
    }

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address,
            $this->city,
            $this->postcode,
            $this->state,
        ])->filter()->implode(', ');
    }

    public static function generateEmployeeCode(): string
    {
        $prefix = 'EMP';
        $date = now()->format('Ymd');
        $last = self::whereDate('created_at', now())->orderByDesc('id')->first();
        $seq = $last ? ((int) substr($last->employee_code, -4)) + 1 : 1;
        return $prefix . '-' . $date . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
