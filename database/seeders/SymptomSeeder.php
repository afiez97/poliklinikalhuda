<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    public function run(): void
    {
        $symptoms = [
            // General
            ['code' => 'fever', 'name' => 'Demam', 'name_en' => 'Fever', 'body_region' => 'general', 'category' => 'fever', 'is_red_flag' => false],
            ['code' => 'fatigue', 'name' => 'Keletihan', 'name_en' => 'Fatigue', 'body_region' => 'general', 'category' => 'other', 'is_red_flag' => false],
            ['code' => 'weakness', 'name' => 'Kelemahan', 'name_en' => 'Weakness', 'body_region' => 'general', 'category' => 'other', 'is_red_flag' => false],
            ['code' => 'body_ache', 'name' => 'Sakit Badan', 'name_en' => 'Body Ache', 'body_region' => 'general', 'category' => 'pain', 'is_red_flag' => false],
            ['code' => 'chills', 'name' => 'Menggigil', 'name_en' => 'Chills', 'body_region' => 'general', 'category' => 'fever', 'is_red_flag' => false],
            ['code' => 'night_sweats', 'name' => 'Berpeluh Malam', 'name_en' => 'Night Sweats', 'body_region' => 'general', 'category' => 'other', 'is_red_flag' => false],
            ['code' => 'weight_loss', 'name' => 'Berat Badan Turun', 'name_en' => 'Weight Loss', 'body_region' => 'general', 'category' => 'other', 'is_red_flag' => false],

            // Head
            ['code' => 'headache', 'name' => 'Sakit Kepala', 'name_en' => 'Headache', 'body_region' => 'head', 'category' => 'pain', 'is_red_flag' => false],
            ['code' => 'dizziness', 'name' => 'Pening', 'name_en' => 'Dizziness', 'body_region' => 'head', 'category' => 'neurological', 'is_red_flag' => false],
            ['code' => 'vertigo', 'name' => 'Vertigo', 'name_en' => 'Vertigo', 'body_region' => 'head', 'category' => 'neurological', 'is_red_flag' => false],

            // Eyes
            ['code' => 'eye_pain', 'name' => 'Sakit Mata', 'name_en' => 'Eye Pain', 'body_region' => 'eyes', 'category' => 'pain', 'is_red_flag' => false],
            ['code' => 'blurred_vision', 'name' => 'Penglihatan Kabur', 'name_en' => 'Blurred Vision', 'body_region' => 'eyes', 'category' => 'neurological', 'is_red_flag' => false],
            ['code' => 'red_eyes', 'name' => 'Mata Merah', 'name_en' => 'Red Eyes', 'body_region' => 'eyes', 'category' => 'other', 'is_red_flag' => false],

            // Ears
            ['code' => 'ear_pain', 'name' => 'Sakit Telinga', 'name_en' => 'Ear Pain', 'body_region' => 'ears', 'category' => 'pain', 'is_red_flag' => false],
            ['code' => 'hearing_loss', 'name' => 'Masalah Pendengaran', 'name_en' => 'Hearing Loss', 'body_region' => 'ears', 'category' => 'neurological', 'is_red_flag' => false],
            ['code' => 'tinnitus', 'name' => 'Bunyi Telinga', 'name_en' => 'Tinnitus', 'body_region' => 'ears', 'category' => 'other', 'is_red_flag' => false],

            // Nose & Throat
            ['code' => 'runny_nose', 'name' => 'Hidung Berair', 'name_en' => 'Runny Nose', 'body_region' => 'nose', 'category' => 'respiratory', 'is_red_flag' => false],
            ['code' => 'blocked_nose', 'name' => 'Hidung Tersumbat', 'name_en' => 'Blocked Nose', 'body_region' => 'nose', 'category' => 'respiratory', 'is_red_flag' => false],
            ['code' => 'sneezing', 'name' => 'Bersin', 'name_en' => 'Sneezing', 'body_region' => 'nose', 'category' => 'respiratory', 'is_red_flag' => false],
            ['code' => 'sore_throat', 'name' => 'Sakit Tekak', 'name_en' => 'Sore Throat', 'body_region' => 'throat', 'category' => 'respiratory', 'is_red_flag' => false],
            ['code' => 'difficulty_swallowing', 'name' => 'Sukar Menelan', 'name_en' => 'Difficulty Swallowing', 'body_region' => 'throat', 'category' => 'respiratory', 'is_red_flag' => false],

            // Chest & Respiratory
            ['code' => 'cough', 'name' => 'Batuk', 'name_en' => 'Cough', 'body_region' => 'chest', 'category' => 'respiratory', 'is_red_flag' => false],
            ['code' => 'productive_cough', 'name' => 'Batuk Berdahak', 'name_en' => 'Productive Cough', 'body_region' => 'chest', 'category' => 'respiratory', 'is_red_flag' => false],
            ['code' => 'chest_pain', 'name' => 'Sakit Dada', 'name_en' => 'Chest Pain', 'body_region' => 'chest', 'category' => 'cardiovascular', 'is_red_flag' => true],
            ['code' => 'difficulty_breathing', 'name' => 'Sesak Nafas', 'name_en' => 'Difficulty Breathing', 'body_region' => 'chest', 'category' => 'respiratory', 'is_red_flag' => true],
            ['code' => 'wheezing', 'name' => 'Nafas Berbunyi', 'name_en' => 'Wheezing', 'body_region' => 'chest', 'category' => 'respiratory', 'is_red_flag' => false],
            ['code' => 'palpitations', 'name' => 'Jantung Berdebar', 'name_en' => 'Palpitations', 'body_region' => 'chest', 'category' => 'cardiovascular', 'is_red_flag' => false],

            // Abdomen
            ['code' => 'abdominal_pain', 'name' => 'Sakit Perut', 'name_en' => 'Abdominal Pain', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],
            ['code' => 'severe_abdominal_pain', 'name' => 'Sakit Perut Teruk', 'name_en' => 'Severe Abdominal Pain', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => true],
            ['code' => 'nausea', 'name' => 'Loya', 'name_en' => 'Nausea', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],
            ['code' => 'vomiting', 'name' => 'Muntah', 'name_en' => 'Vomiting', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],
            ['code' => 'diarrhea', 'name' => 'Cirit-birit', 'name_en' => 'Diarrhea', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],
            ['code' => 'constipation', 'name' => 'Sembelit', 'name_en' => 'Constipation', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],
            ['code' => 'bloating', 'name' => 'Perut Kembung', 'name_en' => 'Bloating', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],
            ['code' => 'blood_in_stool', 'name' => 'Darah dalam Najis', 'name_en' => 'Blood in Stool', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => true],
            ['code' => 'heartburn', 'name' => 'Pedih Ulu Hati', 'name_en' => 'Heartburn', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],
            ['code' => 'loss_of_appetite', 'name' => 'Hilang Selera', 'name_en' => 'Loss of Appetite', 'body_region' => 'abdomen', 'category' => 'gastrointestinal', 'is_red_flag' => false],

            // Urinary
            ['code' => 'painful_urination', 'name' => 'Sakit Semasa Kencing', 'name_en' => 'Painful Urination', 'body_region' => 'abdomen', 'category' => 'urinary', 'is_red_flag' => false],
            ['code' => 'frequent_urination', 'name' => 'Kerap Kencing', 'name_en' => 'Frequent Urination', 'body_region' => 'abdomen', 'category' => 'urinary', 'is_red_flag' => false],
            ['code' => 'blood_in_urine', 'name' => 'Darah dalam Air Kencing', 'name_en' => 'Blood in Urine', 'body_region' => 'abdomen', 'category' => 'urinary', 'is_red_flag' => true],

            // Back
            ['code' => 'back_pain', 'name' => 'Sakit Belakang', 'name_en' => 'Back Pain', 'body_region' => 'back', 'category' => 'musculoskeletal', 'is_red_flag' => false],
            ['code' => 'lower_back_pain', 'name' => 'Sakit Pinggang', 'name_en' => 'Lower Back Pain', 'body_region' => 'back', 'category' => 'musculoskeletal', 'is_red_flag' => false],

            // Limbs
            ['code' => 'joint_pain', 'name' => 'Sakit Sendi', 'name_en' => 'Joint Pain', 'body_region' => 'general', 'category' => 'musculoskeletal', 'is_red_flag' => false],
            ['code' => 'muscle_pain', 'name' => 'Sakit Otot', 'name_en' => 'Muscle Pain', 'body_region' => 'general', 'category' => 'musculoskeletal', 'is_red_flag' => false],
            ['code' => 'swelling', 'name' => 'Bengkak', 'name_en' => 'Swelling', 'body_region' => 'general', 'category' => 'other', 'is_red_flag' => false],
            ['code' => 'numbness', 'name' => 'Kebas', 'name_en' => 'Numbness', 'body_region' => 'general', 'category' => 'neurological', 'is_red_flag' => false],

            // Skin
            ['code' => 'rash', 'name' => 'Ruam Kulit', 'name_en' => 'Rash', 'body_region' => 'skin', 'category' => 'dermatological', 'is_red_flag' => false],
            ['code' => 'itching', 'name' => 'Gatal', 'name_en' => 'Itching', 'body_region' => 'skin', 'category' => 'dermatological', 'is_red_flag' => false],
            ['code' => 'skin_lesion', 'name' => 'Luka Kulit', 'name_en' => 'Skin Lesion', 'body_region' => 'skin', 'category' => 'dermatological', 'is_red_flag' => false],

            // Critical/Emergency (Red Flags)
            ['code' => 'unconscious', 'name' => 'Tidak Sedarkan Diri', 'name_en' => 'Unconscious', 'body_region' => 'general', 'category' => 'neurological', 'is_red_flag' => true],
            ['code' => 'seizure', 'name' => 'Sawan', 'name_en' => 'Seizure', 'body_region' => 'general', 'category' => 'neurological', 'is_red_flag' => true],
            ['code' => 'severe_bleeding', 'name' => 'Pendarahan Teruk', 'name_en' => 'Severe Bleeding', 'body_region' => 'general', 'category' => 'other', 'is_red_flag' => true],
            ['code' => 'stroke_symptoms', 'name' => 'Simptom Strok', 'name_en' => 'Stroke Symptoms', 'body_region' => 'general', 'category' => 'neurological', 'is_red_flag' => true],
            ['code' => 'severe_allergic_reaction', 'name' => 'Reaksi Alahan Teruk', 'name_en' => 'Severe Allergic Reaction', 'body_region' => 'general', 'category' => 'other', 'is_red_flag' => true],
            ['code' => 'high_fever_infant', 'name' => 'Demam Tinggi (Bayi)', 'name_en' => 'High Fever (Infant)', 'body_region' => 'general', 'category' => 'fever', 'is_red_flag' => true],
            ['code' => 'suicidal_thoughts', 'name' => 'Fikiran Membunuh Diri', 'name_en' => 'Suicidal Thoughts', 'body_region' => 'general', 'category' => 'psychiatric', 'is_red_flag' => true],

            // Mental Health
            ['code' => 'anxiety', 'name' => 'Keresahan', 'name_en' => 'Anxiety', 'body_region' => 'general', 'category' => 'psychiatric', 'is_red_flag' => false],
            ['code' => 'depression', 'name' => 'Kemurungan', 'name_en' => 'Depression', 'body_region' => 'general', 'category' => 'psychiatric', 'is_red_flag' => false],
            ['code' => 'insomnia', 'name' => 'Tidak Dapat Tidur', 'name_en' => 'Insomnia', 'body_region' => 'general', 'category' => 'psychiatric', 'is_red_flag' => false],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::firstOrCreate(
                ['code' => $symptom['code']],
                $symptom
            );
        }
    }
}
