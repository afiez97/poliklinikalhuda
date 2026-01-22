<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'document_type',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_confidential',
        'uploaded_by',
    ];

    protected $casts = [
        'is_confidential' => 'boolean',
        'file_size' => 'integer',
    ];

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the uploader.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
