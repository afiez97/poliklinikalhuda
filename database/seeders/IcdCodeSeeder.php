<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IcdCodeSeeder extends Seeder
{
    /**
     * Seed ICD-10 codes commonly used in primary care clinics.
     */
    public function run(): void
    {
        $codes = [
            // Chapter I: Infectious and Parasitic Diseases
            ['code' => 'A09', 'description' => 'Infectious gastroenteritis and colitis, unspecified', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'A38', 'description' => 'Scarlet fever', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'A63.0', 'description' => 'Anogenital (venereal) warts', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B00.1', 'description' => 'Herpesviral vesicular dermatitis', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B00.9', 'description' => 'Herpesviral infection, unspecified', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B01.9', 'description' => 'Varicella without complication', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B02.9', 'description' => 'Zoster without complication', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B05.9', 'description' => 'Measles without complication', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B06.9', 'description' => 'Rubella without complication', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B26.9', 'description' => 'Mumps without complication', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B34.9', 'description' => 'Viral infection, unspecified', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B35.0', 'description' => 'Tinea barbae and tinea capitis', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B35.1', 'description' => 'Tinea unguium (nail fungus)', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B35.3', 'description' => 'Tinea pedis (athlete\'s foot)', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B35.4', 'description' => 'Tinea corporis (ringworm)', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B36.0', 'description' => 'Pityriasis versicolor', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B37.0', 'description' => 'Candidal stomatitis (oral thrush)', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B37.3', 'description' => 'Candidiasis of vulva and vagina', 'category' => 'Infectious diseases', 'chapter' => 'I'],
            ['code' => 'B86', 'description' => 'Scabies', 'category' => 'Infectious diseases', 'chapter' => 'I'],

            // Chapter IV: Endocrine, nutritional and metabolic diseases
            ['code' => 'E03.9', 'description' => 'Hypothyroidism, unspecified', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E05.9', 'description' => 'Thyrotoxicosis, unspecified', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E10.9', 'description' => 'Type 1 diabetes mellitus without complications', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E11.9', 'description' => 'Type 2 diabetes mellitus without complications', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E66.9', 'description' => 'Obesity, unspecified', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E78.0', 'description' => 'Pure hypercholesterolaemia', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E78.1', 'description' => 'Pure hyperglyceridaemia', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E78.5', 'description' => 'Hyperlipidaemia, unspecified', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],
            ['code' => 'E79.0', 'description' => 'Hyperuricaemia (gout diathesis)', 'category' => 'Endocrine disorders', 'chapter' => 'IV'],

            // Chapter V: Mental and behavioural disorders
            ['code' => 'F10.1', 'description' => 'Mental and behavioural disorders due to alcohol, harmful use', 'category' => 'Mental disorders', 'chapter' => 'V'],
            ['code' => 'F17.1', 'description' => 'Mental and behavioural disorders due to tobacco, harmful use', 'category' => 'Mental disorders', 'chapter' => 'V'],
            ['code' => 'F32.0', 'description' => 'Mild depressive episode', 'category' => 'Mental disorders', 'chapter' => 'V'],
            ['code' => 'F32.1', 'description' => 'Moderate depressive episode', 'category' => 'Mental disorders', 'chapter' => 'V'],
            ['code' => 'F32.9', 'description' => 'Depressive episode, unspecified', 'category' => 'Mental disorders', 'chapter' => 'V'],
            ['code' => 'F41.0', 'description' => 'Panic disorder', 'category' => 'Mental disorders', 'chapter' => 'V'],
            ['code' => 'F41.1', 'description' => 'Generalized anxiety disorder', 'category' => 'Mental disorders', 'chapter' => 'V'],
            ['code' => 'F51.0', 'description' => 'Insomnia not due to a substance or known physiological condition', 'category' => 'Mental disorders', 'chapter' => 'V'],

            // Chapter VI: Diseases of the nervous system
            ['code' => 'G43.9', 'description' => 'Migraine, unspecified', 'category' => 'Nervous system', 'chapter' => 'VI'],
            ['code' => 'G44.2', 'description' => 'Tension-type headache', 'category' => 'Nervous system', 'chapter' => 'VI'],
            ['code' => 'G47.0', 'description' => 'Insomnia', 'category' => 'Nervous system', 'chapter' => 'VI'],
            ['code' => 'G50.0', 'description' => 'Trigeminal neuralgia', 'category' => 'Nervous system', 'chapter' => 'VI'],
            ['code' => 'G51.0', 'description' => 'Bell\'s palsy', 'category' => 'Nervous system', 'chapter' => 'VI'],

            // Chapter VII: Diseases of the eye
            ['code' => 'H10.9', 'description' => 'Conjunctivitis, unspecified', 'category' => 'Eye diseases', 'chapter' => 'VII'],
            ['code' => 'H60.9', 'description' => 'Otitis externa, unspecified', 'category' => 'Eye diseases', 'chapter' => 'VII'],
            ['code' => 'H65.9', 'description' => 'Nonsuppurative otitis media, unspecified', 'category' => 'Ear diseases', 'chapter' => 'VIII'],
            ['code' => 'H66.9', 'description' => 'Otitis media, unspecified', 'category' => 'Ear diseases', 'chapter' => 'VIII'],

            // Chapter IX: Diseases of the circulatory system
            ['code' => 'I10', 'description' => 'Essential (primary) hypertension', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I11.9', 'description' => 'Hypertensive heart disease without heart failure', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I20.9', 'description' => 'Angina pectoris, unspecified', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I25.9', 'description' => 'Chronic ischaemic heart disease, unspecified', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I50.9', 'description' => 'Heart failure, unspecified', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I63.9', 'description' => 'Cerebral infarction, unspecified', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I64', 'description' => 'Stroke, not specified as haemorrhage or infarction', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I80.9', 'description' => 'Phlebitis and thrombophlebitis, unspecified', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I83.9', 'description' => 'Varicose veins of lower extremities', 'category' => 'Circulatory system', 'chapter' => 'IX'],
            ['code' => 'I84.9', 'description' => 'Haemorrhoids, unspecified', 'category' => 'Circulatory system', 'chapter' => 'IX'],

            // Chapter X: Diseases of the respiratory system
            ['code' => 'J00', 'description' => 'Acute nasopharyngitis (common cold)', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J01.9', 'description' => 'Acute sinusitis, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J02.9', 'description' => 'Acute pharyngitis, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J03.9', 'description' => 'Acute tonsillitis, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J04.0', 'description' => 'Acute laryngitis', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J06.9', 'description' => 'Acute upper respiratory infection, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J11.1', 'description' => 'Influenza with other respiratory manifestations', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J18.9', 'description' => 'Pneumonia, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J20.9', 'description' => 'Acute bronchitis, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J30.1', 'description' => 'Allergic rhinitis due to pollen', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J30.4', 'description' => 'Allergic rhinitis, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J31.0', 'description' => 'Chronic rhinitis', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J32.9', 'description' => 'Chronic sinusitis, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J40', 'description' => 'Bronchitis, not specified as acute or chronic', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J42', 'description' => 'Unspecified chronic bronchitis', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J44.9', 'description' => 'Chronic obstructive pulmonary disease, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],
            ['code' => 'J45.9', 'description' => 'Asthma, unspecified', 'category' => 'Respiratory system', 'chapter' => 'X'],

            // Chapter XI: Diseases of the digestive system
            ['code' => 'K02.9', 'description' => 'Dental caries, unspecified', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K04.7', 'description' => 'Periapical abscess without sinus', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K05.0', 'description' => 'Acute gingivitis', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K12.0', 'description' => 'Recurrent oral aphthae (mouth ulcer)', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K21.0', 'description' => 'Gastro-oesophageal reflux disease with oesophagitis', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K25.9', 'description' => 'Gastric ulcer, unspecified', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K29.7', 'description' => 'Gastritis, unspecified', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K30', 'description' => 'Functional dyspepsia', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K52.9', 'description' => 'Noninfective gastroenteritis and colitis, unspecified', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K58.9', 'description' => 'Irritable bowel syndrome without diarrhoea', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K59.0', 'description' => 'Constipation', 'category' => 'Digestive system', 'chapter' => 'XI'],
            ['code' => 'K76.0', 'description' => 'Fatty (change of) liver, not elsewhere classified', 'category' => 'Digestive system', 'chapter' => 'XI'],

            // Chapter XII: Diseases of the skin
            ['code' => 'L02.9', 'description' => 'Cutaneous abscess, furuncle and carbuncle, unspecified', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L03.9', 'description' => 'Cellulitis, unspecified', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L08.0', 'description' => 'Pyoderma', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L20.9', 'description' => 'Atopic dermatitis, unspecified', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L23.9', 'description' => 'Allergic contact dermatitis, unspecified cause', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L24.9', 'description' => 'Irritant contact dermatitis, unspecified cause', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L30.9', 'description' => 'Dermatitis, unspecified', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L40.9', 'description' => 'Psoriasis, unspecified', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L50.9', 'description' => 'Urticaria, unspecified', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L70.0', 'description' => 'Acne vulgaris', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L72.0', 'description' => 'Epidermal cyst', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L73.9', 'description' => 'Follicular disorder, unspecified', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L81.0', 'description' => 'Postinflammatory hyperpigmentation', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L82', 'description' => 'Seborrhoeic keratosis', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L84', 'description' => 'Corns and callosities', 'category' => 'Skin diseases', 'chapter' => 'XII'],
            ['code' => 'L91.0', 'description' => 'Hypertrophic scar (keloid)', 'category' => 'Skin diseases', 'chapter' => 'XII'],

            // Chapter XIII: Diseases of the musculoskeletal system
            ['code' => 'M06.9', 'description' => 'Rheumatoid arthritis, unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M10.9', 'description' => 'Gout, unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M17.9', 'description' => 'Gonarthrosis (knee osteoarthritis), unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M19.9', 'description' => 'Arthrosis, unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M25.5', 'description' => 'Pain in joint', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M51.9', 'description' => 'Intervertebral disc disorder, unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M54.2', 'description' => 'Cervicalgia (neck pain)', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M54.5', 'description' => 'Low back pain', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M54.9', 'description' => 'Dorsalgia, unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M62.8', 'description' => 'Other specified disorders of muscle', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M65.9', 'description' => 'Synovitis and tenosynovitis, unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M70.5', 'description' => 'Other bursitis of knee', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M75.1', 'description' => 'Rotator cuff syndrome', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M77.0', 'description' => 'Medial epicondylitis', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M77.1', 'description' => 'Lateral epicondylitis (tennis elbow)', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M79.1', 'description' => 'Myalgia', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],
            ['code' => 'M79.3', 'description' => 'Panniculitis, unspecified', 'category' => 'Musculoskeletal', 'chapter' => 'XIII'],

            // Chapter XIV: Diseases of the genitourinary system
            ['code' => 'N10', 'description' => 'Acute tubulo-interstitial nephritis', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N12', 'description' => 'Tubulo-interstitial nephritis, not specified as acute or chronic', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N18.9', 'description' => 'Chronic kidney disease, unspecified', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N20.0', 'description' => 'Calculus of kidney (kidney stone)', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N23', 'description' => 'Unspecified renal colic', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N30.0', 'description' => 'Acute cystitis', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N30.9', 'description' => 'Cystitis, unspecified', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N39.0', 'description' => 'Urinary tract infection, site not specified', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N41.0', 'description' => 'Acute prostatitis', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N76.0', 'description' => 'Acute vaginitis', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N89.8', 'description' => 'Other specified noninflammatory disorders of vagina', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N91.2', 'description' => 'Amenorrhoea, unspecified', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N92.0', 'description' => 'Excessive and frequent menstruation with regular cycle', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N94.4', 'description' => 'Primary dysmenorrhoea', 'category' => 'Genitourinary', 'chapter' => 'XIV'],
            ['code' => 'N94.6', 'description' => 'Dysmenorrhoea, unspecified', 'category' => 'Genitourinary', 'chapter' => 'XIV'],

            // Chapter XVIII: Symptoms, signs and abnormal findings
            ['code' => 'R00.0', 'description' => 'Tachycardia, unspecified', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R00.2', 'description' => 'Palpitations', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R05', 'description' => 'Cough', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R06.0', 'description' => 'Dyspnoea', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R07.0', 'description' => 'Pain in throat', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R10.1', 'description' => 'Pain localized to upper abdomen', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R10.3', 'description' => 'Pain localized to other parts of lower abdomen', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R10.4', 'description' => 'Other and unspecified abdominal pain', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R11', 'description' => 'Nausea and vomiting', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R12', 'description' => 'Heartburn', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R14', 'description' => 'Flatulence and related conditions', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R19.4', 'description' => 'Change in bowel habit', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R21', 'description' => 'Rash and other nonspecific skin eruption', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R42', 'description' => 'Dizziness and giddiness', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R50.9', 'description' => 'Fever, unspecified', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R51', 'description' => 'Headache', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],
            ['code' => 'R53', 'description' => 'Malaise and fatigue', 'category' => 'Symptoms/Signs', 'chapter' => 'XVIII'],

            // Chapter XIX: Injury and poisoning
            ['code' => 'S00.9', 'description' => 'Superficial injury of head, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S01.9', 'description' => 'Open wound of head, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S09.9', 'description' => 'Unspecified injury of head', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S20.9', 'description' => 'Superficial injury of thorax, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S30.9', 'description' => 'Superficial injury of abdomen, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S40.9', 'description' => 'Superficial injury of shoulder and upper arm, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S50.9', 'description' => 'Superficial injury of elbow and forearm, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S60.9', 'description' => 'Superficial injury of wrist and hand, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S61.9', 'description' => 'Open wound of wrist and hand, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S70.9', 'description' => 'Superficial injury of hip and thigh, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S80.9', 'description' => 'Superficial injury of lower leg, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S90.9', 'description' => 'Superficial injury of ankle and foot, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'S93.4', 'description' => 'Sprain and strain of ankle', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'T14.0', 'description' => 'Superficial injury of unspecified body region', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'T14.1', 'description' => 'Open wound of unspecified body region', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'T15.9', 'description' => 'Foreign body on external eye, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'T63.9', 'description' => 'Toxic effect of contact with venomous animal, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],
            ['code' => 'T78.4', 'description' => 'Allergy, unspecified', 'category' => 'Injuries', 'chapter' => 'XIX'],

            // Chapter XXI: Factors influencing health status (Z codes)
            ['code' => 'Z00.0', 'description' => 'General adult medical examination', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z01.0', 'description' => 'Examination of eyes and vision', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z02.0', 'description' => 'Examination for admission to educational institution', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z02.1', 'description' => 'Pre-employment examination', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z23', 'description' => 'Need for immunization against single bacterial diseases', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z30.0', 'description' => 'General counseling and advice on contraception', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z32.0', 'description' => 'Pregnancy examination or test, not (yet) confirmed', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z34.9', 'description' => 'Supervision of normal pregnancy, unspecified', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z71.1', 'description' => 'Person with feared complaint in whom no diagnosis is made', 'category' => 'Health status factors', 'chapter' => 'XXI'],
            ['code' => 'Z76.0', 'description' => 'Issue of repeat prescription', 'category' => 'Health status factors', 'chapter' => 'XXI'],
        ];

        $now = now();

        foreach ($codes as &$code) {
            $code['is_active'] = true;
            $code['created_at'] = $now;
            $code['updated_at'] = $now;
        }

        DB::table('icd10_codes')->insert($codes);

        $this->command->info('ICD-10 codes seeded: '.count($codes).' records');
    }
}
