<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanelDependent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'panel_employee_id',
        'patient_id',
        'name',
        'ic_number',
        'date_of_birth',
        'relationship',
        'gender',
        'has_separate_limit',
        'separate_limit',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'has_separate_limit' => 'boolean',
        'separate_limit' => 'decimal:2',
    ];

    public const RELATIONSHIP_SPOUSE = 'spouse';

    public const RELATIONSHIP_CHILD = 'child';

    public const RELATIONSHIP_PARENT = 'parent';

    public const RELATIONSHIP_SIBLING = 'sibling';

    public const RELATIONSHIP_OTHER = 'other';

    public const RELATIONSHIPS = [
        self::RELATIONSHIP_SPOUSE => 'Pasangan',
        self::RELATIONSHIP_CHILD => 'Anak',
        self::RELATIONSHIP_PARENT => 'Ibu/Bapa',
        self::RELATIONSHIP_SIBLING => 'Adik-beradik',
        self::RELATIONSHIP_OTHER => 'Lain-lain',
    ];

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(PanelEmployee::class, 'panel_employee_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function guaranteeLetters(): HasMany
    {
        return $this->hasMany(GuaranteeLetter::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function getRelationshipNameAttribute(): string
    {
        return self::RELATIONSHIPS[$this->relationship] ?? $this->relationship;
    }

    public function getAgeAttribute(): ?int
    {
        if (! $this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    public function getGenderNameAttribute(): string
    {
        return match ($this->gender) {
            'male' => 'Lelaki',
            'female' => 'Perempuan',
            default => '-',
        };
    }
}
