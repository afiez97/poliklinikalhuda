<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_claim_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public const TYPE_GL_COPY = 'gl_copy';

    public const TYPE_INVOICE = 'invoice';

    public const TYPE_MEDICAL_CERTIFICATE = 'medical_certificate';

    public const TYPE_LAB_REPORT = 'lab_report';

    public const TYPE_PRESCRIPTION = 'prescription';

    public const TYPE_PA_APPROVAL = 'pa_approval';

    public const TYPE_REFERRAL_LETTER = 'referral_letter';

    public const TYPE_OTHER = 'other';

    public const TYPES = [
        self::TYPE_GL_COPY => 'Salinan GL',
        self::TYPE_INVOICE => 'Invois',
        self::TYPE_MEDICAL_CERTIFICATE => 'Sijil Sakit (MC)',
        self::TYPE_LAB_REPORT => 'Laporan Makmal',
        self::TYPE_PRESCRIPTION => 'Preskripsi',
        self::TYPE_PA_APPROVAL => 'Kelulusan PA',
        self::TYPE_REFERRAL_LETTER => 'Surat Rujukan',
        self::TYPE_OTHER => 'Lain-lain',
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(PanelClaim::class, 'panel_claim_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getTypeNameAttribute(): string
    {
        return self::TYPES[$this->document_type] ?? $this->document_type;
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' bytes';
    }
}
