<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'staff_no',
        'user_id',
        'department_id',
        'position_id',
        'name',
        'ic_no',
        'gender',
        'date_of_birth',
        'marital_status',
        'nationality',
        'race',
        'religion',
        'phone',
        'phone_emergency',
        'emergency_contact_name',
        'emergency_contact_relation',
        'email',
        'address',
        'postcode',
        'city',
        'state',
        'employment_type',
        'join_date',
        'confirmation_date',
        'resignation_date',
        'last_working_date',
        'status',
        'basic_salary',
        'bank_name',
        'bank_account_no',
        'epf_no',
        'socso_no',
        'eis_no',
        'income_tax_no',
        'mmc_no',
        'apc_no',
        'apc_expiry_date',
        'specialty',
        'photo',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'confirmation_date' => 'date',
        'resignation_date' => 'date',
        'last_working_date' => 'date',
        'apc_expiry_date' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    /**
     * Get the user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the position.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get staff documents.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StaffDocument::class);
    }

    /**
     * Get rosters.
     */
    public function rosters(): HasMany
    {
        return $this->hasMany(Roster::class);
    }

    /**
     * Get attendances.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get leave balances.
     */
    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Get leave requests.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
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
     * Get doctor commission settings.
     */
    public function doctorCommissions(): HasMany
    {
        return $this->hasMany(DoctorCommission::class);
    }

    /**
     * Scope for active staff.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for doctors.
     */
    public function scopeDoctors($query)
    {
        return $query->whereNotNull('mmc_no');
    }

    /**
     * Get age attribute.
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    /**
     * Get service years.
     */
    public function getServiceYearsAttribute(): float
    {
        if (! $this->join_date) {
            return 0;
        }

        $endDate = $this->last_working_date ?? Carbon::now();

        return round($this->join_date->diffInYears($endDate), 1);
    }

    /**
     * Get full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->postcode,
            $this->city,
            $this->state,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Check if APC is expiring soon.
     */
    public function isApcExpiringSoon(int $days = 30): bool
    {
        if (! $this->apc_expiry_date) {
            return false;
        }

        return $this->apc_expiry_date->lte(Carbon::now()->addDays($days));
    }

    /**
     * Check if staff is doctor.
     */
    public function isDoctor(): bool
    {
        return ! empty($this->mmc_no);
    }

    /**
     * Generate next staff number.
     */
    public static function generateStaffNo(): string
    {
        $year = date('Y');
        $lastStaff = self::withTrashed()
            ->where('staff_no', 'like', "STF{$year}%")
            ->orderBy('staff_no', 'desc')
            ->first();

        if ($lastStaff) {
            $lastNumber = (int) substr($lastStaff->staff_no, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('STF%s%04d', $year, $nextNumber);
    }
}
