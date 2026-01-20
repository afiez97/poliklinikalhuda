<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mrn',
        'ic_number',
        'passport_number',
        'id_type',
        'name',
        'date_of_birth',
        'gender',
        'nationality',
        'race',
        'religion',
        'marital_status',
        'occupation',
        'phone',
        'phone_alt',
        'email',
        'address',
        'postcode',
        'city',
        'state',
        'country',
        'emergency_name',
        'emergency_phone',
        'emergency_relationship',
        'blood_type',
        'allergies',
        'chronic_diseases',
        'current_medications',
        'has_panel',
        'panel_company',
        'panel_member_id',
        'panel_expiry_date',
        'pdpa_consent',
        'pdpa_consent_at',
        'pdpa_consent_by',
        'status',
        'notes',
        'registered_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'panel_expiry_date' => 'date',
        'pdpa_consent' => 'boolean',
        'pdpa_consent_at' => 'datetime',
        'has_panel' => 'boolean',
    ];

    /**
     * ID Types.
     */
    public const ID_TYPES = [
        'ic' => 'Kad Pengenalan',
        'passport' => 'Pasport',
        'military' => 'Kad Tentera',
        'police' => 'Kad Polis',
        'birth_cert' => 'Sijil Lahir',
        'other' => 'Lain-lain',
    ];

    /**
     * Statuses.
     */
    public const STATUSES = [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        'deceased' => 'Meninggal',
        'transferred' => 'Dipindahkan',
    ];

    /**
     * Generate new MRN.
     */
    public static function generateMrn(): string
    {
        $prefix = 'PAH'; // Poliklinik Al-Huda
        $year = date('y');

        $lastPatient = self::withTrashed()
            ->where('mrn', 'like', "{$prefix}{$year}%")
            ->orderBy('mrn', 'desc')
            ->first();

        if ($lastPatient) {
            $lastNumber = (int) substr($lastPatient->mrn, 5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s%s%05d', $prefix, $year, $newNumber);
    }

    /**
     * Boot method.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            if (empty($patient->mrn)) {
                $patient->mrn = self::generateMrn();
            }
        });
    }

    /**
     * Get age from date of birth.
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth?->age ?? 0;
    }

    /**
     * Get age in months for infants.
     */
    public function getAgeInMonthsAttribute(): int
    {
        return $this->date_of_birth?->diffInMonths(now()) ?? 0;
    }

    /**
     * Get formatted age.
     */
    public function getFormattedAgeAttribute(): string
    {
        if (! $this->date_of_birth) {
            return '-';
        }

        $age = $this->age;
        if ($age < 1) {
            $months = $this->age_in_months;

            return $months.' bulan';
        }

        return $age.' tahun';
    }

    /**
     * Get gender label.
     */
    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'male' ? 'Lelaki' : 'Perempuan';
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get ID type label.
     */
    public function getIdTypeLabelAttribute(): string
    {
        return self::ID_TYPES[$this->id_type] ?? $this->id_type;
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
            $this->country !== 'Malaysia' ? $this->country : null,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Check if panel is valid.
     */
    public function isPanelValid(): bool
    {
        if (! $this->has_panel) {
            return false;
        }

        if (! $this->panel_expiry_date) {
            return true;
        }

        return $this->panel_expiry_date->isFuture();
    }

    /**
     * Get the registrar.
     */
    public function registrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get visits.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(PatientVisit::class);
    }

    /**
     * Get documents.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PatientDocument::class);
    }

    /**
     * Get latest visit.
     */
    public function latestVisit()
    {
        return $this->hasOne(PatientVisit::class)->latest('visit_date');
    }

    /**
     * Scope for search.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('mrn', 'like', "%{$term}%")
                ->orWhere('ic_number', 'like', "%{$term}%")
                ->orWhere('name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    /**
     * Scope for active patients.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for panel patients.
     */
    public function scopePanel($query)
    {
        return $query->where('has_panel', true);
    }
}
