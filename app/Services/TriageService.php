<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Symptom;
use App\Models\TriageAssessment;

class TriageService
{
    // Red flag symptoms that require immediate attention
    protected array $redFlagSymptoms = [
        'chest_pain',
        'difficulty_breathing',
        'severe_bleeding',
        'unconscious',
        'seizure',
        'stroke_symptoms',
        'severe_allergic_reaction',
        'severe_abdominal_pain',
        'high_fever_infant',
        'suicidal_thoughts',
    ];

    // Vital signs abnormal thresholds
    protected array $vitalThresholds = [
        'bp_systolic' => ['low' => 90, 'high' => 180],
        'bp_diastolic' => ['low' => 60, 'high' => 110],
        'heart_rate' => ['low' => 50, 'high' => 120],
        'temperature' => ['low' => 35.0, 'high' => 39.0],
        'respiratory_rate' => ['low' => 12, 'high' => 25],
        'spo2' => ['low' => 94, 'high' => 100],
    ];

    /**
     * Perform triage assessment
     */
    public function assessTriage(array $data, Patient $patient): array
    {
        $symptoms = $data['symptoms'] ?? [];
        $vitals = $data['vital_signs'] ?? [];
        $chiefComplaint = $data['chief_complaint'] ?? '';
        $painScore = (int) ($data['pain_score'] ?? 0);

        // Analyze symptoms
        $symptomAnalysis = $this->analyzeSymptoms($symptoms);

        // Analyze vital signs
        $vitalsAnalysis = $this->analyzeVitals($vitals);

        // Check for red flags
        $redFlags = $this->detectRedFlags($symptoms, $vitals, $painScore);

        // Calculate severity score
        $severityScore = $this->calculateSeverityScore(
            $symptomAnalysis,
            $vitalsAnalysis,
            $redFlags,
            $painScore
        );

        // Determine severity level
        $severityLevel = $this->determineSeverityLevel($severityScore, $redFlags);

        // Generate reasoning
        $reasoning = $this->generateReasoning(
            $symptomAnalysis,
            $vitalsAnalysis,
            $redFlags,
            $severityScore
        );

        // Generate differential diagnoses
        $differentialDiagnoses = $this->suggestDifferentialDiagnoses(
            $symptoms,
            $chiefComplaint,
            $patient
        );

        // Generate recommended actions
        $recommendedActions = $this->generateRecommendedActions($severityLevel, $redFlags);

        // Calculate confidence
        $confidence = $this->calculateConfidence($symptoms, $vitals, $symptomAnalysis);

        return [
            'severity_level' => $severityLevel,
            'severity_score' => $severityScore,
            'ai_reasoning' => $reasoning,
            'red_flags_detected' => $redFlags,
            'differential_diagnoses' => $differentialDiagnoses,
            'recommended_actions' => $recommendedActions,
            'ai_confidence' => $confidence,
        ];
    }

    /**
     * Analyze symptoms
     */
    protected function analyzeSymptoms(array $symptoms): array
    {
        $analysis = [
            'total_symptoms' => count($symptoms),
            'severe_symptoms' => 0,
            'moderate_symptoms' => 0,
            'mild_symptoms' => 0,
            'symptom_categories' => [],
            'body_regions' => [],
        ];

        foreach ($symptoms as $symptom) {
            $severity = $symptom['severity'] ?? 'mild';

            if ($severity === 'severe') {
                $analysis['severe_symptoms']++;
            } elseif ($severity === 'moderate') {
                $analysis['moderate_symptoms']++;
            } else {
                $analysis['mild_symptoms']++;
            }

            if (isset($symptom['category'])) {
                $analysis['symptom_categories'][$symptom['category']] =
                    ($analysis['symptom_categories'][$symptom['category']] ?? 0) + 1;
            }

            if (isset($symptom['body_region'])) {
                $analysis['body_regions'][$symptom['body_region']] =
                    ($analysis['body_regions'][$symptom['body_region']] ?? 0) + 1;
            }
        }

        return $analysis;
    }

    /**
     * Analyze vital signs
     */
    protected function analyzeVitals(array $vitals): array
    {
        $analysis = [
            'abnormal_vitals' => [],
            'critical_vitals' => [],
            'normal' => true,
        ];

        foreach ($this->vitalThresholds as $vital => $thresholds) {
            if (! isset($vitals[$vital])) {
                continue;
            }

            $value = (float) $vitals[$vital];

            if ($value < $thresholds['low'] || $value > $thresholds['high']) {
                $analysis['abnormal_vitals'][] = [
                    'vital' => $vital,
                    'value' => $value,
                    'status' => $value < $thresholds['low'] ? 'low' : 'high',
                ];
                $analysis['normal'] = false;

                // Check if critical (far outside range)
                $criticalLow = $thresholds['low'] * 0.85;
                $criticalHigh = $thresholds['high'] * 1.15;

                if ($value < $criticalLow || $value > $criticalHigh) {
                    $analysis['critical_vitals'][] = $vital;
                }
            }
        }

        return $analysis;
    }

    /**
     * Detect red flags
     */
    protected function detectRedFlags(array $symptoms, array $vitals, int $painScore): array
    {
        $redFlags = [];

        // Check symptoms for red flags
        foreach ($symptoms as $symptom) {
            $code = $symptom['code'] ?? '';

            if (in_array($code, $this->redFlagSymptoms)) {
                $redFlags[] = [
                    'type' => 'symptom',
                    'code' => $code,
                    'name' => $symptom['name'] ?? $code,
                    'severity' => 'critical',
                    'action' => 'Perlu perhatian segera',
                ];
            }
        }

        // Check vital signs for red flags
        if (isset($vitals['spo2']) && $vitals['spo2'] < 90) {
            $redFlags[] = [
                'type' => 'vital',
                'code' => 'low_spo2',
                'name' => 'Paras oksigen rendah (SpO2 < 90%)',
                'severity' => 'critical',
                'action' => 'Berikan oksigen segera',
            ];
        }

        if (isset($vitals['bp_systolic']) && $vitals['bp_systolic'] < 80) {
            $redFlags[] = [
                'type' => 'vital',
                'code' => 'hypotension',
                'name' => 'Tekanan darah sangat rendah',
                'severity' => 'critical',
                'action' => 'Perlu resusitasi',
            ];
        }

        if (isset($vitals['temperature']) && $vitals['temperature'] > 40.5) {
            $redFlags[] = [
                'type' => 'vital',
                'code' => 'hyperthermia',
                'name' => 'Demam sangat tinggi (>40.5°C)',
                'severity' => 'critical',
                'action' => 'Rawatan demam segera',
            ];
        }

        // Severe pain
        if ($painScore >= 9) {
            $redFlags[] = [
                'type' => 'pain',
                'code' => 'severe_pain',
                'name' => 'Sakit sangat teruk (9-10/10)',
                'severity' => 'urgent',
                'action' => 'Pertimbangkan analgesia segera',
            ];
        }

        return $redFlags;
    }

    /**
     * Calculate severity score (0-100)
     */
    protected function calculateSeverityScore(
        array $symptomAnalysis,
        array $vitalsAnalysis,
        array $redFlags,
        int $painScore
    ): int {
        $score = 30; // Base score

        // Red flags add significant score
        $score += count($redFlags) * 15;

        // Severe symptoms
        $score += $symptomAnalysis['severe_symptoms'] * 10;
        $score += $symptomAnalysis['moderate_symptoms'] * 5;

        // Abnormal vitals
        $score += count($vitalsAnalysis['abnormal_vitals']) * 8;
        $score += count($vitalsAnalysis['critical_vitals']) * 15;

        // Pain score contribution
        $score += ($painScore / 10) * 15;

        // Multiple symptom categories indicate complexity
        if (count($symptomAnalysis['symptom_categories']) > 2) {
            $score += 10;
        }

        return min(100, max(0, $score));
    }

    /**
     * Determine severity level based on score and red flags
     */
    protected function determineSeverityLevel(int $score, array $redFlags): string
    {
        // Critical red flags always emergency
        $criticalFlags = array_filter($redFlags, fn ($f) => $f['severity'] === 'critical');
        if (count($criticalFlags) > 0) {
            return 'emergency';
        }

        if ($score >= 85) {
            return 'emergency';
        }
        if ($score >= 70) {
            return 'urgent';
        }
        if ($score >= 50) {
            return 'semi_urgent';
        }
        if ($score >= 30) {
            return 'standard';
        }

        return 'non_urgent';
    }

    /**
     * Generate reasoning explanation
     */
    protected function generateReasoning(
        array $symptomAnalysis,
        array $vitalsAnalysis,
        array $redFlags,
        int $severityScore
    ): array {
        $reasons = [];

        if (count($redFlags) > 0) {
            $flagNames = array_column($redFlags, 'name');
            $reasons[] = [
                'factor' => 'Red Flags',
                'description' => 'Simptom kritikal dikesan: '.implode(', ', $flagNames),
                'weight' => 'high',
            ];
        }

        if ($symptomAnalysis['severe_symptoms'] > 0) {
            $reasons[] = [
                'factor' => 'Simptom Teruk',
                'description' => "{$symptomAnalysis['severe_symptoms']} simptom teruk direkodkan",
                'weight' => 'medium',
            ];
        }

        if (! $vitalsAnalysis['normal']) {
            $abnormalCount = count($vitalsAnalysis['abnormal_vitals']);
            $reasons[] = [
                'factor' => 'Vital Signs',
                'description' => "{$abnormalCount} bacaan vital tidak normal",
                'weight' => 'medium',
            ];
        }

        if (empty($reasons)) {
            $reasons[] = [
                'factor' => 'Penilaian Am',
                'description' => 'Tiada simptom kritikal dikesan, triage standard',
                'weight' => 'low',
            ];
        }

        return [
            'reasons' => $reasons,
            'score_breakdown' => [
                'base' => 30,
                'symptoms' => $symptomAnalysis['severe_symptoms'] * 10 + $symptomAnalysis['moderate_symptoms'] * 5,
                'vitals' => count($vitalsAnalysis['abnormal_vitals']) * 8,
                'red_flags' => count($redFlags) * 15,
            ],
        ];
    }

    /**
     * Suggest differential diagnoses based on symptoms
     */
    protected function suggestDifferentialDiagnoses(
        array $symptoms,
        string $chiefComplaint,
        Patient $patient
    ): array {
        // Basic rule-based differential diagnosis
        // In production, this would use ML models or knowledge base

        $diagnoses = [];
        $symptomCodes = array_column($symptoms, 'code');

        // Check for common symptom patterns
        if (in_array('fever', $symptomCodes) && in_array('cough', $symptomCodes)) {
            $diagnoses[] = [
                'code' => 'J06.9',
                'name' => 'Jangkitan saluran pernafasan atas (URTI)',
                'confidence' => 75,
                'supporting_evidence' => ['Demam', 'Batuk'],
            ];

            if (in_array('sore_throat', $symptomCodes)) {
                $diagnoses[] = [
                    'code' => 'J02.9',
                    'name' => 'Faringitis akut',
                    'confidence' => 70,
                    'supporting_evidence' => ['Demam', 'Sakit tekak'],
                ];
            }
        }

        if (in_array('headache', $symptomCodes)) {
            $diagnoses[] = [
                'code' => 'G43.9',
                'name' => 'Migrain',
                'confidence' => 60,
                'supporting_evidence' => ['Sakit kepala'],
            ];
        }

        if (in_array('abdominal_pain', $symptomCodes)) {
            $diagnoses[] = [
                'code' => 'K30',
                'name' => 'Dyspepsia',
                'confidence' => 55,
                'supporting_evidence' => ['Sakit perut'],
            ];

            if (in_array('vomiting', $symptomCodes) || in_array('diarrhea', $symptomCodes)) {
                $diagnoses[] = [
                    'code' => 'A09',
                    'name' => 'Gastroenteritis akut',
                    'confidence' => 70,
                    'supporting_evidence' => ['Sakit perut', 'Muntah/Cirit-birit'],
                ];
            }
        }

        // Sort by confidence
        usort($diagnoses, fn ($a, $b) => $b['confidence'] <=> $a['confidence']);

        return array_slice($diagnoses, 0, 5);
    }

    /**
     * Generate recommended actions
     */
    protected function generateRecommendedActions(string $severityLevel, array $redFlags): array
    {
        $actions = [];
        $levelInfo = TriageAssessment::severityLevels()[$severityLevel];

        // Base action
        $actions[] = [
            'priority' => 1,
            'action' => $levelInfo['action'],
            'category' => 'routing',
        ];

        // Add actions based on red flags
        foreach ($redFlags as $flag) {
            if (isset($flag['action'])) {
                $actions[] = [
                    'priority' => $flag['severity'] === 'critical' ? 0 : 2,
                    'action' => $flag['action'],
                    'category' => 'intervention',
                    'related_to' => $flag['name'],
                ];
            }
        }

        // General actions based on severity
        if (in_array($severityLevel, ['emergency', 'urgent'])) {
            $actions[] = [
                'priority' => 1,
                'action' => 'Maklumkan doktor segera',
                'category' => 'notification',
            ];
        }

        // Sort by priority
        usort($actions, fn ($a, $b) => $a['priority'] <=> $b['priority']);

        return $actions;
    }

    /**
     * Calculate AI confidence score
     */
    protected function calculateConfidence(array $symptoms, array $vitals, array $symptomAnalysis): int
    {
        $confidence = 50; // Base confidence

        // More symptoms = better assessment
        $confidence += min(20, count($symptoms) * 5);

        // Having vitals increases confidence
        if (! empty($vitals)) {
            $confidence += 15;
        }

        // Clear symptom patterns
        if ($symptomAnalysis['severe_symptoms'] > 0 || $symptomAnalysis['total_symptoms'] >= 3) {
            $confidence += 10;
        }

        return min(95, max(30, $confidence));
    }

    /**
     * Get common symptoms list
     */
    public function getCommonSymptoms(): array
    {
        return [
            ['code' => 'fever', 'name' => 'Demam', 'category' => 'fever', 'body_region' => 'general'],
            ['code' => 'cough', 'name' => 'Batuk', 'category' => 'respiratory', 'body_region' => 'chest'],
            ['code' => 'sore_throat', 'name' => 'Sakit Tekak', 'category' => 'respiratory', 'body_region' => 'throat'],
            ['code' => 'headache', 'name' => 'Sakit Kepala', 'category' => 'pain', 'body_region' => 'head'],
            ['code' => 'runny_nose', 'name' => 'Hidung Berair', 'category' => 'respiratory', 'body_region' => 'nose'],
            ['code' => 'body_ache', 'name' => 'Sakit Badan', 'category' => 'pain', 'body_region' => 'general'],
            ['code' => 'fatigue', 'name' => 'Keletihan', 'category' => 'other', 'body_region' => 'general'],
            ['code' => 'nausea', 'name' => 'Loya', 'category' => 'gastrointestinal', 'body_region' => 'abdomen'],
            ['code' => 'vomiting', 'name' => 'Muntah', 'category' => 'gastrointestinal', 'body_region' => 'abdomen'],
            ['code' => 'diarrhea', 'name' => 'Cirit-birit', 'category' => 'gastrointestinal', 'body_region' => 'abdomen'],
            ['code' => 'abdominal_pain', 'name' => 'Sakit Perut', 'category' => 'gastrointestinal', 'body_region' => 'abdomen'],
            ['code' => 'chest_pain', 'name' => 'Sakit Dada', 'category' => 'cardiovascular', 'body_region' => 'chest', 'is_red_flag' => true],
            ['code' => 'difficulty_breathing', 'name' => 'Sesak Nafas', 'category' => 'respiratory', 'body_region' => 'chest', 'is_red_flag' => true],
            ['code' => 'dizziness', 'name' => 'Pening', 'category' => 'neurological', 'body_region' => 'head'],
            ['code' => 'rash', 'name' => 'Ruam Kulit', 'category' => 'dermatological', 'body_region' => 'skin'],
            ['code' => 'joint_pain', 'name' => 'Sakit Sendi', 'category' => 'musculoskeletal', 'body_region' => 'general'],
            ['code' => 'back_pain', 'name' => 'Sakit Belakang', 'category' => 'musculoskeletal', 'body_region' => 'back'],
            ['code' => 'urinary_symptoms', 'name' => 'Masalah Kencing', 'category' => 'urinary', 'body_region' => 'abdomen'],
        ];
    }
}
