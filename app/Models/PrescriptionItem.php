<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'medicine_name',
        'strength',
        'form',
        'dosage',
        'frequency',
        'route',
        'duration',
        'quantity',
        'unit',
        'instructions',
        'special_instructions',
        'status',
        'quantity_dispensed',
        'dispensed_at',
        'unit_price',
        'total_price',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'dispensed_at' => 'datetime',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public const ROUTES = [
        'oral' => 'Oral',
        'topical' => 'Topikal',
        'inhalation' => 'Penyedutan',
        'injection' => 'Suntikan',
        'sublingual' => 'Sublingual',
        'rectal' => 'Rektal',
        'ophthalmic' => 'Mata',
        'otic' => 'Telinga',
        'nasal' => 'Hidung',
        'transdermal' => 'Transdermal',
    ];

    public const FREQUENCIES = [
        'OD' => 'Sekali sehari',
        'BD' => 'Dua kali sehari',
        'TDS' => 'Tiga kali sehari',
        'QID' => 'Empat kali sehari',
        'PRN' => 'Apabila perlu',
        'STAT' => 'Segera',
        'ON' => 'Waktu malam',
        'OM' => 'Waktu pagi',
        'AC' => 'Sebelum makan',
        'PC' => 'Selepas makan',
        'Q4H' => 'Setiap 4 jam',
        'Q6H' => 'Setiap 6 jam',
        'Q8H' => 'Setiap 8 jam',
        'Q12H' => 'Setiap 12 jam',
        'WEEKLY' => 'Seminggu sekali',
    ];

    public const FORMS = [
        'tablet' => 'Tablet',
        'capsule' => 'Kapsul',
        'syrup' => 'Sirap',
        'suspension' => 'Suspensi',
        'cream' => 'Krim',
        'ointment' => 'Salap',
        'drops' => 'Titisan',
        'injection' => 'Suntikan',
        'inhaler' => 'Penyedut',
        'suppository' => 'Supositoria',
        'patch' => 'Tampalan',
        'powder' => 'Serbuk',
        'gel' => 'Gel',
        'lotion' => 'Losyen',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Auto-calculate total price
            if ($item->unit_price && $item->quantity) {
                $item->total_price = $item->unit_price * $item->quantity;
            }
        });
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function getRouteLabel(): string
    {
        return self::ROUTES[$this->route] ?? $this->route;
    }

    public function getFrequencyLabel(): string
    {
        return self::FREQUENCIES[$this->frequency] ?? $this->frequency;
    }

    public function getFormLabel(): string
    {
        return self::FORMS[$this->form] ?? $this->form;
    }

    public function getFullMedicineNameAttribute(): string
    {
        $name = $this->medicine_name;
        if ($this->strength) {
            $name .= " {$this->strength}";
        }
        if ($this->form) {
            $name .= " ({$this->getFormLabel()})";
        }

        return $name;
    }

    public function getDosageInstructionsAttribute(): string
    {
        $parts = [];
        $parts[] = $this->dosage;
        $parts[] = $this->getFrequencyLabel();

        if ($this->duration) {
            $parts[] = "x {$this->duration}";
        }

        return implode(' ', $parts);
    }

    public function isDispensed(): bool
    {
        return $this->status === 'dispensed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
