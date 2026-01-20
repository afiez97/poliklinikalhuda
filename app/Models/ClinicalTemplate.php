<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'chief_complaint_template',
        'history_template',
        'examination_template',
        'assessment_template',
        'plan_template',
        'vital_sign_defaults',
        'common_diagnoses',
        'created_by',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'vital_sign_defaults' => 'array',
        'common_diagnoses' => 'array',
        'is_active' => 'boolean',
    ];

    public const CATEGORIES = [
        'general' => 'Umum',
        'pediatric' => 'Pediatrik',
        'ob_gyn' => 'O&G',
        'dental' => 'Pergigian',
        'minor_surgery' => 'Pembedahan Kecil',
        'chronic' => 'Penyakit Kronik',
        'emergency' => 'Kecemasan',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
