<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Staff;
use App\Models\User;
use App\Models\VitalSign;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoEncounterSeeder extends Seeder
{
    /**
     * Seed demo encounters for testing and demonstration.
     */
    public function run(): void
    {
        // Get existing patients or create demo patients
        $patients = Patient::take(10)->get();

        if ($patients->isEmpty()) {
            $this->command->warn('No patients found. Creating demo patients...');
            $patients = $this->createDemoPatients();
        }

        // Get a doctor (staff with mmc_no - Malaysian Medical Council number)
        $doctor = Staff::doctors()->active()->first();

        if (! $doctor) {
            $this->command->warn('No doctor found. Creating demo doctor...');
            $doctor = $this->createDemoDoctor();
        }

        // Get common ICD-10 codes
        $icdCodes = DB::table('icd10_codes')
            ->whereIn('code', ['J06.9', 'I10', 'E11.9', 'K29.7', 'M54.5', 'R50.9', 'N39.0', 'L30.9'])
            ->get()
            ->keyBy('code');

        $encountersCreated = 0;

        foreach ($patients as $patient) {
            // Create 1-3 encounters per patient
            $encounterCount = rand(1, 3);

            for ($i = 0; $i < $encounterCount; $i++) {
                $encountersCreated += $this->createEncounter($patient, $doctor, $icdCodes);
            }
        }

        $this->command->info("Demo encounters seeded: {$encountersCreated} records");
    }

    private function createDemoPatients(): \Illuminate\Support\Collection
    {
        $demoPatients = [
            [
                'name' => 'Ahmad bin Abdullah',
                'ic_number' => '850315-01-5678',
                'gender' => 'male',
                'date_of_birth' => '1985-03-15',
                'phone' => '012-3456789',
                'address' => '123 Jalan Mawar, Taman Bunga',
                'city' => 'Shah Alam',
                'state' => 'Selangor',
                'postcode' => '40000',
            ],
            [
                'name' => 'Siti Aminah binti Hassan',
                'ic_number' => '900520-06-1234',
                'gender' => 'female',
                'date_of_birth' => '1990-05-20',
                'phone' => '013-9876543',
                'address' => '456 Jalan Melati, Taman Indah',
                'city' => 'Petaling Jaya',
                'state' => 'Selangor',
                'postcode' => '47800',
            ],
            [
                'name' => 'Tan Wei Ming',
                'ic_number' => '780812-10-9876',
                'gender' => 'male',
                'date_of_birth' => '1978-08-12',
                'phone' => '016-5551234',
                'address' => '789 Jalan Cempaka',
                'city' => 'Kuala Lumpur',
                'state' => 'W.P. Kuala Lumpur',
                'postcode' => '50000',
            ],
            [
                'name' => 'Priya a/p Subramaniam',
                'ic_number' => '951105-14-4567',
                'gender' => 'female',
                'date_of_birth' => '1995-11-05',
                'phone' => '017-8889999',
                'address' => '321 Jalan Kenanga',
                'city' => 'Klang',
                'state' => 'Selangor',
                'postcode' => '41000',
            ],
            [
                'name' => 'Mohd Faiz bin Ismail',
                'ic_number' => '880225-03-2345',
                'gender' => 'male',
                'date_of_birth' => '1988-02-25',
                'phone' => '019-7776666',
                'address' => '654 Jalan Dahlia',
                'city' => 'Subang Jaya',
                'state' => 'Selangor',
                'postcode' => '47500',
            ],
        ];

        $patients = collect();

        foreach ($demoPatients as $data) {
            $data['id_type'] = 'ic';
            $data['nationality'] = 'Malaysian';
            $data['status'] = 'active';
            $data['pdpa_consent'] = true;
            $data['pdpa_consent_at'] = now();
            $data['pdpa_consent_by'] = 'System';

            $patient = Patient::create($data);
            $patients->push($patient);
        }

        return $patients;
    }

    private function createDemoDoctor(): Staff
    {
        // First create a user for the doctor
        $user = User::firstOrCreate(
            ['email' => 'dr.demo@poliklinik.test'],
            [
                'name' => 'Dr. Demo',
                'username' => 'dr.demo',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        return Staff::firstOrCreate(
            ['user_id' => $user->id],
            [
                'staff_no' => Staff::generateStaffNo(),
                'name' => 'Dr. Demo',
                'ic_no' => '800101-01-0001',
                'gender' => 'male',
                'date_of_birth' => '1980-01-01',
                'status' => 'active',
                'join_date' => now()->subYears(2),
                'mmc_no' => 'MMC12345', // This makes them a doctor
                'apc_no' => 'APC2024-12345',
                'apc_expiry_date' => now()->addYear(),
                'specialty' => 'Perubatan Am',
            ]
        );
    }

    private function createEncounter(Patient $patient, Staff $doctor, $icdCodes): int
    {
        // Create a patient visit first
        $visitDate = now()->subDays(rand(0, 90));
        $queuePrefix = ['A', 'A', 'A', 'U'][rand(0, 3)];

        $lastQueue = PatientVisit::whereDate('visit_date', $visitDate->toDateString())
            ->where('queue_prefix', $queuePrefix)
            ->max('queue_number');

        $visit = PatientVisit::create([
            'patient_id' => $patient->id,
            'visit_date' => $visitDate->toDateString(),
            'check_in_time' => $visitDate->format('H:i:s'),
            'visit_type' => ['walk_in', 'appointment', 'follow_up'][rand(0, 2)],
            'priority' => $queuePrefix === 'U' ? 'urgent' : 'normal',
            'queue_prefix' => $queuePrefix,
            'queue_number' => ($lastQueue ?? 0) + 1,
            'status' => 'completed',
            'doctor_id' => $doctor->id,
            'chief_complaint' => $this->getRandomChiefComplaint(),
        ]);

        // Create the encounter
        $encounterNo = 'ENC'.date('Ymd', strtotime($visitDate)).$visit->id;
        $status = ['completed', 'completed', 'completed', 'in_progress'][rand(0, 3)];

        $soapData = $this->getRandomSoapData();

        $encounter = Encounter::create([
            'encounter_no' => $encounterNo,
            'patient_id' => $patient->id,
            'patient_visit_id' => $visit->id,
            'doctor_id' => $doctor->id,
            'encounter_date' => $visitDate,
            'status' => $status,
            'chief_complaint' => $visit->chief_complaint,
            'history_present_illness' => $soapData['history'],
            'subjective' => $soapData['subjective'],
            'objective' => $soapData['objective'],
            'assessment' => $soapData['assessment'],
            'plan' => $soapData['plan'],
            'started_at' => $visitDate,
            'completed_at' => $status === 'completed' ? $visitDate->addMinutes(rand(10, 30)) : null,
        ]);

        // Create vital signs
        $this->createVitalSigns($encounter, $patient, $visitDate);

        // Create diagnoses
        $this->createDiagnoses($encounter, $patient, $icdCodes, $soapData['diagnosis_code']);

        return 1;
    }

    private function createVitalSigns(Encounter $encounter, Patient $patient, $date): void
    {
        $age = $patient->date_of_birth ? now()->diffInYears($patient->date_of_birth) : 30;

        // Generate realistic vital signs based on age
        $systolic = rand(110, 140);
        $diastolic = rand(70, 90);

        // Higher BP for older patients sometimes
        if ($age > 50 && rand(0, 1)) {
            $systolic = rand(130, 160);
            $diastolic = rand(80, 100);
        }

        VitalSign::create([
            'encounter_id' => $encounter->id,
            'patient_id' => $patient->id,
            'recorded_at' => $date,
            'temperature' => round(rand(365, 385) / 10, 1),
            'pulse_rate' => rand(60, 100),
            'respiratory_rate' => rand(14, 20),
            'systolic_bp' => $systolic,
            'diastolic_bp' => $diastolic,
            'spo2' => rand(95, 100),
            'weight' => round(rand(450, 900) / 10, 1),
            'height' => rand(150, 180),
            'pain_score' => rand(0, 5),
        ]);
    }

    private function createDiagnoses(Encounter $encounter, Patient $patient, $icdCodes, string $code): void
    {
        $icd = $icdCodes->get($code);

        if ($icd) {
            Diagnosis::create([
                'encounter_id' => $encounter->id,
                'patient_id' => $patient->id,
                'icd10_id' => $icd->id,
                'icd10_code' => $icd->code,
                'diagnosis_text' => $icd->description,
                'type' => 'primary',
                'status' => 'active',
            ]);
        }

        // Sometimes add a secondary diagnosis
        if (rand(0, 1)) {
            $secondaryCodes = ['I10', 'E11.9', 'E78.5'];
            $secondaryCode = $secondaryCodes[array_rand($secondaryCodes)];
            $secondaryIcd = $icdCodes->get($secondaryCode);

            if ($secondaryIcd && $secondaryCode !== $code) {
                Diagnosis::create([
                    'encounter_id' => $encounter->id,
                    'patient_id' => $patient->id,
                    'icd10_id' => $secondaryIcd->id,
                    'icd10_code' => $secondaryIcd->code,
                    'diagnosis_text' => $secondaryIcd->description,
                    'type' => 'secondary',
                    'status' => 'chronic',
                ]);
            }
        }
    }

    private function getRandomChiefComplaint(): string
    {
        $complaints = [
            'Demam 3 hari, batuk dan selesema',
            'Sakit kepala sejak semalam',
            'Sakit perut dan loya',
            'Sakit belakang bawah',
            'Sakit tekak dan susah menelan',
            'Gatal-gatal dan ruam kulit',
            'Pening dan rasa lemah',
            'Batuk berdahak 1 minggu',
            'Cirit-birit 2 hari',
            'Sakit semasa kencing',
            'Lawatan susulan hipertensi',
            'Lawatan susulan kencing manis',
            'Sakit lutut bila berjalan',
            'Tidak lena tidur',
            'Pemeriksaan kesihatan am',
        ];

        return $complaints[array_rand($complaints)];
    }

    private function getRandomSoapData(): array
    {
        $soapTemplates = [
            [
                'diagnosis_code' => 'J06.9',
                'history' => 'Pesakit mengadu demam sejak 3 hari yang lalu, disertai batuk kering dan selesema. Tiada sesak nafas. Tiada sakit dada. Tiada kontak dengan pesakit COVID-19.',
                'subjective' => "Demam on and off 3 hari\nBatuk kering\nSelesema\nSakit tekak ringan\nTiada sesak nafas\nSelera makan kurang",
                'objective' => "Keadaan umum: Baik\nT: 37.8°C, PR: 88 bpm, BP: 120/80 mmHg\nTekak: Sedikit merah, tiada eksudat\nTonsil: Tidak bengkak\nPernafasan: Vesikuler bilateral, tiada ronki/wheezing\nAbdomen: Lembut, tidak tender",
                'assessment' => 'Acute upper respiratory tract infection (URTI)',
                'plan' => "1. Paracetamol 500mg 1/1 PRN demam\n2. Loratadine 10mg 0/0/1\n3. Bromhexine 8mg 1/1/1\n4. Nasihat minum air banyak, rehat\n5. MC 2 hari\n6. TCA PRN jika tidak baik",
            ],
            [
                'diagnosis_code' => 'I10',
                'history' => 'Lawatan susulan hipertensi. Pesakit didiagnos hipertensi 5 tahun lalu. Compliance ubat baik. Tiada sakit kepala, pening atau sesak nafas.',
                'subjective' => "Lawatan susulan bulanan\nCompliance ubat: Baik\nBP di rumah: 130-140/80-90 mmHg\nTiada sakit kepala\nTiada pening\nTiada sesak nafas",
                'objective' => "Keadaan umum: Baik\nBP: 138/88 mmHg (duduk), 135/85 mmHg (berdiri)\nPR: 72 bpm, regular\nBerat: 75 kg, BMI: 27.3\nKardiovaskular: S1S2 normal, tiada murmur\nPernafasan: Normal\nAnggota: Tiada edema",
                'assessment' => 'Essential hypertension - fairly controlled',
                'plan' => "1. Teruskan Amlodipine 5mg 0/0/1\n2. Teruskan Perindopril 4mg 1/0/0\n3. Nasihat diet rendah garam, senaman\n4. Pantau BP di rumah\n5. TCA 1 bulan",
            ],
            [
                'diagnosis_code' => 'E11.9',
                'history' => 'Lawatan susulan kencing manis Type 2. Didiagnos 8 tahun lalu. Pada rawatan Metformin. HbA1c terakhir 7.2% (3 bulan lalu).',
                'subjective' => "Lawatan susulan 3 bulan\nCompliance ubat: Baik\nTiada hipoglisemia\nTiada polyuria/polydipsia\nBacaan gula di rumah: 6-8 mmol/L (puasa)",
                'objective' => "Keadaan umum: Baik\nBP: 128/82 mmHg\nBerat: 78 kg, BMI: 28.1\nKaki: Tiada ulser, sensasi normal, denyutan ada\nFunduskopi: Tiada retinopati",
                'assessment' => 'Type 2 Diabetes Mellitus - controlled on OHA',
                'plan' => "1. Teruskan Metformin 1g BD\n2. Teruskan Gliclazide MR 60mg 1/0/0\n3. HbA1c, FBS, RFT, Lipid profile - ambil darah hari ini\n4. Nasihat diet, senaman\n5. TCA 3 bulan dengan keputusan darah",
            ],
            [
                'diagnosis_code' => 'K29.7',
                'history' => 'Pesakit mengadu sakit perut bahagian atas sejak 5 hari. Sakit seperti pedih, lebih teruk bila perut kosong. Ada rasa kembung dan pedih ulu hati.',
                'subjective' => "Sakit epigastrik 5 hari\nPedih bila perut kosong\nKembung selepas makan\nPedih ulu hati\nTiada muntah\nTiada najis hitam\nMakan tidak teratur, sering skip meal",
                'objective' => "Keadaan umum: Tidak selesa\nAbdomen: Lembut, tender di epigastrik\nTiada guarding, tiada rebound\nBising usus: Normal\nTiada hepatosplenomegali",
                'assessment' => 'Gastritis / Dyspepsia',
                'plan' => "1. Omeprazole 20mg 1/0/0 AC x 2 minggu\n2. Antacid 10ml PRN\n3. Buscopan 10mg PRN sakit\n4. Nasihat: Elak makanan pedas, masam, berminyak\n5. Makan ikut masa, kuantiti kecil tapi kerap\n6. TCA 2 minggu",
            ],
            [
                'diagnosis_code' => 'M54.5',
                'history' => 'Pesakit mengadu sakit belakang bawah sejak 1 minggu selepas mengangkat barang berat. Sakit memancar ke bahagian punggung kanan. Tiada kebas atau kelemahan kaki.',
                'subjective' => "Sakit belakang bawah 1 minggu\nOnset selepas angkat barang berat\nSakit ke punggung kanan\nTiada kebas kaki\nTiada kelemahan\nTiada gangguan kencing/buang air",
                'objective' => "Keadaan umum: Tidak selesa\nGait: Antalgic\nSpine: Tender L4-L5, spasm paravertebral\nROM: Terhad oleh sakit\nSLR: Negatif bilateral\nPower: 5/5 bilateral\nSensasi: Normal\nRefleks: Normal",
                'assessment' => 'Mechanical low back pain',
                'plan' => "1. Paracetamol 1g TDS x 5 hari\n2. Etoricoxib 90mg OD x 5 hari\n3. Orphenadrine 100mg BD x 5 hari\n4. Topical Diclofenac gel\n5. Nasihat: Elak angkat berat, postur betul\n6. MC 3 hari\n7. TCA 1 minggu jika tidak baik",
            ],
            [
                'diagnosis_code' => 'N39.0',
                'history' => 'Pesakit mengadu sakit semasa kencing dan kerap kencing sejak 2 hari. Kencing sedikit-sedikit. Tiada demam. Tiada sakit pinggang.',
                'subjective' => "Dysuria 2 hari\nFrequency: >10 kali/hari\nUrgency: Ada\nTiada hematuria\nTiada demam\nTiada sakit pinggang\nLMP: 2 minggu lepas",
                'objective' => "Keadaan umum: Baik\nT: 36.8°C\nAbdomen: Lembut, mild suprapubic tenderness\nRenal angle: Tidak tender\nUrine dipstick: Leukocytes ++, Nitrite +",
                'assessment' => 'Uncomplicated urinary tract infection',
                'plan' => "1. Nitrofurantoin 100mg BD x 5 hari\n2. Paracetamol PRN\n3. UPT: Negatif\n4. Nasihat: Minum air banyak, kencing selepas hubungan\n5. TCA jika tidak baik dalam 3 hari",
            ],
            [
                'diagnosis_code' => 'L30.9',
                'history' => 'Pesakit mengadu gatal dan ruam di badan sejak 1 minggu. Gatal lebih teruk pada waktu malam. Tiada trigger yang jelas. Tiada alahan makanan atau ubat yang diketahui.',
                'subjective' => "Ruam dan gatal 1 minggu\nSeluruh badan, lebih di lengan dan badan\nGatal lebih teruk waktu malam\nTiada trigger yang diketahui\nTiada ubat baru\nTiada makanan baru",
                'objective' => "Kulit: Papula erythematous scattered di trunk dan extremities\nLesi: 2-5mm, raised, erythematous\nEkskoriasi: Ada (kesan garukan)\nTiada vesikel atau pustula\nTiada distribusi dermatomal",
                'assessment' => 'Dermatitis / Allergic skin reaction',
                'plan' => "1. Cetirizine 10mg ON\n2. Hydrocortisone 1% cream BD untuk badan\n3. Aqueous cream sebagai moisturizer\n4. Nasihat: Elak garuk, sabun lembut, pakaian cotton\n5. TCA 1 minggu",
            ],
            [
                'diagnosis_code' => 'R50.9',
                'history' => 'Pesakit mengadu demam sejak semalam dengan sakit badan. Tiada gejala respiratori. Tiada cirit-birit. Tiada kontak dengan orang sakit.',
                'subjective' => "Demam 1 hari, suhu tinggi malam tadi\nSakit badan\nRasa lemah\nTiada batuk/selesema\nTiada sakit tekak\nTiada cirit-birit\nSelera kurang",
                'objective' => "Keadaan umum: Lemah\nT: 38.5°C, PR: 96 bpm, BP: 118/76 mmHg\nTidak dehidrasi\nTekak: Normal\nPernafasan: Normal\nAbdomen: Lembut\nTiada ruam",
                'assessment' => 'Fever, unspecified - likely viral',
                'plan' => "1. Paracetamol 1g QID PRN demam\n2. Minum air yang banyak\n3. Rehat\n4. FBC jika demam >3 hari\n5. Datang semula jika demam berterusan atau bertambah teruk\n6. MC 2 hari",
            ],
        ];

        return $soapTemplates[array_rand($soapTemplates)];
    }
}
