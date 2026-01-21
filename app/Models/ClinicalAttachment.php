<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'encounter_id',
        'patient_id',
        'attachment_type',
        'title',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'description',
        'uploaded_by',
    ];

    public const TYPE_LAB_RESULT = 'lab_result';
    public const TYPE_IMAGING = 'imaging';
    public const TYPE_REFERRAL_LETTER = 'referral_letter';
    public const TYPE_CONSENT_FORM = 'consent_form';
    public const TYPE_PRESCRIPTION = 'prescription';
    public const TYPE_MEDICAL_CERTIFICATE = 'medical_certificate';
    public const TYPE_OTHER = 'other';

    public const TYPES = [
        'lab_result' => 'Keputusan Makmal',
        'imaging' => 'Pengimejan',
        'referral_letter' => 'Surat Rujukan',
        'consent_form' => 'Borang Persetujuan',
        'prescription' => 'Preskripsi',
        'medical_certificate' => 'Sijil Sakit',
        'other' => 'Lain-lain',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->attachment_type] ?? $this->attachment_type;
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}
