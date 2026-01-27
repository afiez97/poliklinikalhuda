<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'body_region',
        'category',
        'is_red_flag',
        'red_flag_conditions',
        'associated_diagnoses',
        'follow_up_questions',
        'is_active',
    ];

    protected $casts = [
        'is_red_flag' => 'boolean',
        'is_active' => 'boolean',
        'red_flag_conditions' => 'array',
        'associated_diagnoses' => 'array',
        'follow_up_questions' => 'array',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRedFlags($query)
    {
        return $query->where('is_red_flag', true);
    }

    public function scopeByRegion($query, string $region)
    {
        return $query->where('body_region', $region);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Body regions
    public static function bodyRegions(): array
    {
        return [
            'head' => 'Kepala',
            'eyes' => 'Mata',
            'ears' => 'Telinga',
            'nose' => 'Hidung',
            'throat' => 'Tekak',
            'neck' => 'Leher',
            'chest' => 'Dada',
            'abdomen' => 'Perut',
            'back' => 'Belakang',
            'upper_limbs' => 'Tangan',
            'lower_limbs' => 'Kaki',
            'skin' => 'Kulit',
            'general' => 'Umum',
        ];
    }

    // Categories
    public static function categories(): array
    {
        return [
            'pain' => 'Sakit',
            'fever' => 'Demam',
            'respiratory' => 'Pernafasan',
            'gastrointestinal' => 'Pencernaan',
            'cardiovascular' => 'Jantung',
            'neurological' => 'Saraf',
            'musculoskeletal' => 'Otot/Tulang',
            'dermatological' => 'Kulit',
            'urinary' => 'Kencing',
            'psychiatric' => 'Mental',
            'other' => 'Lain-lain',
        ];
    }
}
