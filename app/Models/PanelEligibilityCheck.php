<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanelEligibilityCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_id',
        'patient_id',
        'panel_employee_id',
        'guarantee_letter_id',
        'check_date',
        'check_method',
        'verifier_name',
        'is_eligible',
        'eligibility_details',
        'available_limit',
        'coverage_info',
        'exclusions_info',
        'notes',
        'checked_by',
    ];

    protected $casts = [
        'check_date' => 'datetime',
        'is_eligible' => 'boolean',
        'available_limit' => 'decimal:2',
        'coverage_info' => 'array',
        'exclusions_info' => 'array',
    ];

    public const METHOD_SYSTEM = 'system';

    public const METHOD_PHONE = 'phone';

    public const METHOD_EMAIL = 'email';

    public const METHOD_PORTAL = 'portal';

    public const METHODS = [
        self::METHOD_SYSTEM => 'Sistem',
        self::METHOD_PHONE => 'Telefon',
        self::METHOD_EMAIL => 'E-mel',
        self::METHOD_PORTAL => 'Portal',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(PanelEmployee::class, 'panel_employee_id');
    }

    public function guaranteeLetter(): BelongsTo
    {
        return $this->belongsTo(GuaranteeLetter::class);
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function scopeEligible($query)
    {
        return $query->where('is_eligible', true);
    }

    public function scopeByPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function getMethodNameAttribute(): string
    {
        return self::METHODS[$this->check_method] ?? $this->check_method;
    }
}
