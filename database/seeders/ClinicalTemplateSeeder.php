<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicalTemplateSeeder extends Seeder
{
    /**
     * Seed clinical templates for common visit types.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Pemeriksaan Am',
                'category' => 'general',
                'chief_complaint_template' => "Aduan utama pesakit:\n- \n\nTempoh aduan:\n- ",
                'history_template' => "Sejarah penyakit sekarang:\n- Permulaan:\n- Perjalanan:\n- Faktor pencetus:\n- Faktor melegakan:\n\nSejarah perubatan lepas:\n- \n\nSejarah keluarga:\n- \n\nSejarah sosial:\n- Merokok: Ya/Tidak\n- Alkohol: Ya/Tidak\n- Pekerjaan: ",
                'examination_template' => "Pemeriksaan Fizikal:\n- Keadaan umum: Baik/Tidak Baik\n- Kesedaran: Alert/Drowsy\n\nKepala & Leher:\n- \n\nKardiovaskular:\n- Bunyi jantung: S1S2 normal\n- Murmur: Tiada\n\nPernafasan:\n- Bunyi nafas: Vesikuler bilateral\n- Ronki/Wheezing: Tiada\n\nAbdomen:\n- Lembut, tidak tender\n- Hepatosplenomegali: Tiada\n\nAnggota:\n- Edema: Tiada",
                'assessment_template' => "Diagnosis:\n1. \n\nDiagnosis Pembezaan:\n- ",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan:\n   - \n\n2. Nasihat:\n   - \n\n3. Temujanji susulan:\n   - ",
                'vital_sign_defaults' => json_encode([
                    'temperature' => true,
                    'pulse_rate' => true,
                    'blood_pressure' => true,
                    'respiratory_rate' => true,
                    'spo2' => true,
                    'weight' => true,
                    'height' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'J06.9' => 'Acute upper respiratory infection',
                    'R50.9' => 'Fever, unspecified',
                    'K30' => 'Functional dyspepsia',
                    'R51' => 'Headache',
                ]),
                'sort_order' => 1,
            ],
            [
                'name' => 'Demam & Selesema',
                'category' => 'general',
                'chief_complaint_template' => "Aduan utama:\n- Demam: Ya/Tidak, Tempoh: __ hari\n- Batuk: Ya/Tidak, Jenis: Kering/Berdahak\n- Selesema: Ya/Tidak\n- Sakit tekak: Ya/Tidak\n- Sakit badan: Ya/Tidak",
                'history_template' => "Sejarah penyakit sekarang:\n- Suhu tertinggi yang diukur: __°C\n- Menggigil: Ya/Tidak\n- Kontak dengan pesakit demam: Ya/Tidak\n- Perjalanan luar negara: Ya/Tidak\n\nGejala berkaitan:\n- Sakit kepala: Ya/Tidak\n- Loya/Muntah: Ya/Tidak\n- Cirit-birit: Ya/Tidak\n- Ruam kulit: Ya/Tidak\n\nUbat yang telah diambil:\n- ",
                'examination_template' => "Pemeriksaan Fizikal:\n- Keadaan umum: Baik/Lemah/Toksik\n- Suhu: __°C\n- Dehidrasi: Tiada/Ringan/Sederhana\n\nKepala & Leher:\n- Konjunktiva: Normal/Pucat/Kuning\n- Tekak: Normal/Merah/Eksudat\n- Tonsil: Normal/Bengkak\n- Limfadenopati: Tiada/Ada\n\nPernafasan:\n- Bunyi nafas: Vesikuler bilateral\n- Ronki: Tiada/Ada\n- Wheezing: Tiada/Ada\n\nAbdomen:\n- Lembut, tidak tender\n- Hepatomegali: Tiada/Ada",
                'assessment_template' => "Diagnosis:\n1. [ ] J06.9 - URTI\n2. [ ] J11.1 - Influenza\n3. [ ] A09 - Viral gastroenteritis\n4. [ ] R50.9 - Fever, unspecified",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan:\n   - Paracetamol 500mg 1/1 PRN (untuk demam >38°C)\n   - \n\n2. Nasihat:\n   - Minum air yang banyak\n   - Rehat yang cukup\n   - Datang semula jika demam berterusan >3 hari\n   - Datang segera jika sesak nafas/ruam/pendarahan\n\n3. MC: __ hari\n\n4. Temujanji susulan: TCA PRN / __ hari",
                'vital_sign_defaults' => json_encode([
                    'temperature' => true,
                    'pulse_rate' => true,
                    'blood_pressure' => true,
                    'respiratory_rate' => true,
                    'spo2' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'J06.9' => 'Acute upper respiratory infection',
                    'J11.1' => 'Influenza',
                    'J02.9' => 'Acute pharyngitis',
                    'J03.9' => 'Acute tonsillitis',
                    'A09' => 'Viral gastroenteritis',
                ]),
                'sort_order' => 2,
            ],
            [
                'name' => 'Sakit Perut / Gastritis',
                'category' => 'general',
                'chief_complaint_template' => "Aduan utama:\n- Sakit perut: Ya, Lokasi: Atas/Tengah/Bawah/Kiri/Kanan\n- Tempoh: __ hari/minggu\n- Sifat sakit: Pedih/Kejang/Melilit/Tajam\n- Keterukan (1-10): __",
                'history_template' => "Sejarah penyakit sekarang:\n- Onset: Tiba-tiba/Beransur\n- Faktor pencetus: Makanan pedas/Makan lewat/Stress/Ubatan\n- Faktor melegakan: Makan/Ubat antasid/Lain\n- Berkaitan dengan makan: Ya/Tidak\n\nGejala berkaitan:\n- Loya: Ya/Tidak\n- Muntah: Ya/Tidak, __ kali\n- Cirit-birit: Ya/Tidak, __ kali\n- Sembelit: Ya/Tidak\n- Pedih ulu hati: Ya/Tidak\n- Kembung: Ya/Tidak\n- Najis hitam/berdarah: Ya/Tidak\n- Penurunan berat badan: Ya/Tidak\n\nSejarah ubatan:\n- NSAID: Ya/Tidak\n- Steroid: Ya/Tidak",
                'examination_template' => "Pemeriksaan Fizikal:\n- Keadaan umum: Baik/Tidak selesa\n- Dehidrasi: Tiada/Ringan/Sederhana\n\nAbdomen:\n- Inspeksi: Normal/Distensi\n- Palpasi: Lembut/Tender\n- Lokasi tender: Epigastrik/RHC/LHC/RIF/LIF/Suprapubik\n- Guarding: Tiada/Ada\n- Rebound tenderness: Tiada/Ada\n- Hepatomegali: Tiada/Ada\n- Splenomegali: Tiada/Ada\n- Bising usus: Normal/Meningkat/Berkurang/Tiada\n\nPR (jika perlu):\n- ",
                'assessment_template' => "Diagnosis:\n1. [ ] K29.7 - Gastritis\n2. [ ] K30 - Dyspepsia\n3. [ ] K21.0 - GERD\n4. [ ] K52.9 - Non-infective gastroenteritis\n5. [ ] K58.9 - IBS",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan:\n   - Omeprazole 20mg 1/0 AC x 2 minggu\n   - Antacid 10ml 1/1/1 PRN\n   - \n\n2. Nasihat:\n   - Elakkan makanan pedas, masam, berminyak\n   - Elakkan kopi, teh pekat, alkohol\n   - Makan dalam kuantiti kecil tetapi kerap\n   - Jangan makan lewat malam\n   - Elakkan ubat NSAID\n\n3. Red flags - datang segera jika:\n   - Muntah darah/najis hitam\n   - Sakit teruk yang tidak hilang\n   - Penurunan berat badan\n   - Susah menelan\n\n4. Temujanji susulan: 2 minggu",
                'vital_sign_defaults' => json_encode([
                    'temperature' => true,
                    'pulse_rate' => true,
                    'blood_pressure' => true,
                    'weight' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'K29.7' => 'Gastritis, unspecified',
                    'K30' => 'Functional dyspepsia',
                    'K21.0' => 'GERD with oesophagitis',
                    'K52.9' => 'Non-infective gastroenteritis',
                    'R10.1' => 'Epigastric pain',
                ]),
                'sort_order' => 3,
            ],
            [
                'name' => 'Hipertensi (Follow-up)',
                'category' => 'chronic',
                'chief_complaint_template' => "Lawatan susulan hipertensi:\n- Compliance ubat: Baik/Kadang-kadang/Tidak\n- Kesan sampingan ubat: Ya/Tidak\n- Jika ya, nyatakan: ",
                'history_template' => "Sejarah hipertensi:\n- Tempoh diagnosis: __ tahun\n- Bacaan BP terakhir di rumah: __/__ mmHg\n- Ubat semasa: \n\nGejala berkaitan:\n- Sakit kepala: Ya/Tidak\n- Pening: Ya/Tidak\n- Rasa berdebar: Ya/Tidak\n- Penglihatan kabur: Ya/Tidak\n- Sakit dada: Ya/Tidak\n- Sesak nafas: Ya/Tidak\n- Bengkak kaki: Ya/Tidak\n\nGaya hidup:\n- Diet rendah garam: Ya/Tidak\n- Senaman: __ kali seminggu\n- Merokok: Ya/Tidak\n- Alkohol: Ya/Tidak\n- BMI: __",
                'examination_template' => "Pemeriksaan Fizikal:\n- BP (duduk): __/__ mmHg\n- BP (berdiri): __/__ mmHg\n- HR: __ bpm, Regular/Irregular\n\nKardiovaskular:\n- JVP: Normal/Meningkat\n- Bunyi jantung: S1S2 normal\n- Murmur: Tiada/Ada\n- S3/S4: Tiada/Ada\n\nPernafasan:\n- Bunyi nafas: Normal\n- Crepitations: Tiada/Ada\n\nAnggota:\n- Edema: Tiada/Ada, Gred: \n- Denyutan periferal: Ada/Lemah",
                'assessment_template' => "Diagnosis:\n1. [ ] I10 - Essential hypertension - Terkawal/Tidak terkawal\n\nKomplikasi:\n- [ ] Tiada\n- [ ] Hypertensive heart disease\n- [ ] CKD Stage: __\n- [ ] Retinopathy\n\nRisiko kardiovaskular: Rendah/Sederhana/Tinggi",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan (tiada perubahan/ubah):\n   - \n\n2. Sasaran BP: <140/90 mmHg (atau <130/80 jika DM/CKD)\n\n3. Pemantauan:\n   - BP di rumah: 2x sehari\n   - Rekodkan dalam buku\n\n4. Nasihat:\n   - Kurangkan garam (<5g/hari)\n   - Diet DASH\n   - Senaman aerobik 30 min x 5 kali/minggu\n   - Berhenti merokok\n   - Kurangkan alkohol\n   - Kawalan berat badan\n\n5. Investigasi:\n   - [ ] FBC, RFT, LFT\n   - [ ] Lipid profile\n   - [ ] FBS/HbA1c\n   - [ ] ECG\n   - [ ] Urine FEME\n\n6. Temujanji susulan: 1 bulan",
                'vital_sign_defaults' => json_encode([
                    'blood_pressure' => true,
                    'pulse_rate' => true,
                    'weight' => true,
                    'height' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'I10' => 'Essential hypertension',
                    'I11.9' => 'Hypertensive heart disease',
                    'E78.5' => 'Hyperlipidaemia',
                    'E66.9' => 'Obesity',
                ]),
                'sort_order' => 4,
            ],
            [
                'name' => 'Diabetes Mellitus (Follow-up)',
                'category' => 'chronic',
                'chief_complaint_template' => "Lawatan susulan kencing manis:\n- Compliance ubat: Baik/Kadang-kadang/Tidak\n- Kesan sampingan ubat: Ya/Tidak\n- Jika ya, nyatakan: \n- Hipoglisemia: Ya/Tidak, __ kali sebulan",
                'history_template' => "Sejarah diabetes:\n- Tempoh diagnosis: __ tahun\n- Jenis: Type 1/Type 2\n- Rawatan: OHA/Insulin/Kombinasi\n- HbA1c terakhir: __% (tarikh: )\n- Bacaan gula di rumah: __-__ mmol/L\n\nGejala berkaitan:\n- Poliuria: Ya/Tidak\n- Polidipsia: Ya/Tidak\n- Penurunan berat: Ya/Tidak\n- Penglihatan kabur: Ya/Tidak\n- Kebas/kesemutan kaki: Ya/Tidak\n- Luka lambat sembuh: Ya/Tidak\n\nPemeriksaan tahunan:\n- Mata (terakhir): \n- Kaki (terakhir): \n- Fungsi ginjal (terakhir): ",
                'examination_template' => "Pemeriksaan Fizikal:\n- BP: __/__ mmHg\n- BMI: __ kg/m²\n- Berat semasa: __ kg (perubahan: +/- __ kg)\n\nKaki (diabetic foot):\n- Inspeksi: Normal/Kalus/Ulser/Gangren\n- Denyutan dorsalis pedis: Ada/Lemah/Tiada\n- Denyutan tibialis posterior: Ada/Lemah/Tiada\n- Sensasi monofilamen: Normal/Berkurang\n- Sensasi getaran: Normal/Berkurang\n\nMata:\n- Funduskopi: Normal/Retinopati (gred: )",
                'assessment_template' => "Diagnosis:\n1. [ ] E11.9 - Type 2 DM - Terkawal/Tidak terkawal\n\nKomplikasi:\n- [ ] Tiada\n- [ ] Retinopati diabetik\n- [ ] Nefropati diabetik (CKD Stage: __)\n- [ ] Neuropati diabetik\n- [ ] Kaki diabetik\n\nKomorbiditi:\n- [ ] Hipertensi\n- [ ] Dislipidemia\n- [ ] Obesiti",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan:\n   - Metformin: __ mg BD/TDS\n   - \n\n2. Sasaran:\n   - HbA1c: <7% (atau individualized)\n   - FBS: 4-7 mmol/L\n   - BP: <130/80 mmHg\n   - LDL: <2.6 mmol/L\n\n3. Pemantauan gula di rumah:\n   - SMBG: __ kali sehari\n   - Target: __-__ mmol/L\n\n4. Nasihat diet:\n   - Karbohidrat kompleks\n   - Kurangkan gula/minuman manis\n   - Sayur-sayuran dan buah-buahan\n   - Makan mengikut jadual\n\n5. Investigasi:\n   - [ ] FBS/RBS\n   - [ ] HbA1c (setiap 3 bulan)\n   - [ ] RFT, urine ACR\n   - [ ] Lipid profile\n   - [ ] LFT\n\n6. Rujukan:\n   - [ ] Pakar mata - pemeriksaan tahunan\n   - [ ] Dietitian\n\n7. Temujanji susulan: 1-3 bulan",
                'vital_sign_defaults' => json_encode([
                    'blood_pressure' => true,
                    'pulse_rate' => true,
                    'weight' => true,
                    'height' => true,
                    'blood_glucose' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'E11.9' => 'Type 2 diabetes mellitus',
                    'E10.9' => 'Type 1 diabetes mellitus',
                    'I10' => 'Essential hypertension',
                    'E78.5' => 'Hyperlipidaemia',
                ]),
                'sort_order' => 5,
            ],
            [
                'name' => 'Sakit Belakang / Sakit Sendi',
                'category' => 'musculoskeletal',
                'chief_complaint_template' => "Aduan utama:\n- Lokasi sakit: Leher/Belakang atas/Belakang bawah/Lutut/Bahu/Lain: __\n- Tempoh: __ hari/minggu/bulan\n- Keterukan (1-10): __\n- Sifat: Sakit/Lenguh/Kebas/Tajam",
                'history_template' => "Sejarah penyakit sekarang:\n- Onset: Tiba-tiba/Beransur\n- Faktor pencetus: Angkat berat/Jatuh/Postur/Tiada\n- Faktor melegakan: Rehat/Ubat/Heat/Lain\n- Faktor memburukkan: Pergerakan/Berdiri lama/Duduk lama\n\nGejala berkaitan:\n- Kebas: Ya/Tidak, Lokasi: \n- Kelemahan: Ya/Tidak\n- Sakit memancar ke kaki: Ya/Tidak\n- Gangguan kencing/buang air: Ya/Tidak\n- Demam: Ya/Tidak\n- Penurunan berat: Ya/Tidak\n\nSejarah:\n- Trauma: Ya/Tidak\n- Kerja fizikal berat: Ya/Tidak\n- Kanser: Ya/Tidak",
                'examination_template' => "Pemeriksaan Fizikal:\n\nSpine (jika sakit belakang):\n- Inspeksi: Normal/Skoliosis/Kyphosis\n- Palpasi: Tender di L__-L__ / T__-T__\n- ROM: Fleksi __°, Ekstensi __°, Lateral bending __°\n- SLR: Negatif/Positif di __°\n- Neurology: Power __/5, Sensasi Normal/Berkurang, Reflex Normal/Berkurang\n\nSendi (jika sakit sendi):\n- Inspeksi: Normal/Bengkak/Kemerahan/Deformiti\n- Palpasi: Tender/Hangat/Efusi\n- ROM: Aktif __°, Pasif __°\n- Crepitus: Ya/Tidak\n- Stability: Stabil/Tidak stabil",
                'assessment_template' => "Diagnosis:\n1. [ ] M54.5 - Low back pain\n2. [ ] M54.2 - Cervicalgia\n3. [ ] M17.9 - Knee osteoarthritis\n4. [ ] M79.1 - Myalgia\n5. [ ] M51.9 - Intervertebral disc disorder\n\nRed flags:\n- [ ] Tiada\n- [ ] Cauda equina syndrome\n- [ ] Malignancy\n- [ ] Infection\n- [ ] Fracture",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan:\n   - Paracetamol 1g TDS x 5 hari\n   - NSAID (jika tiada kontraindikasi): \n   - Muscle relaxant: \n   - Topical: \n\n2. Fisioterapi:\n   - [ ] Heat/Cold therapy\n   - [ ] Rujuk fisioterapi\n\n3. Nasihat:\n   - Elakkan angkat berat\n   - Postur yang betul\n   - Senaman regangan\n   - Tidak bed rest berlebihan\n\n4. Red flags - datang segera jika:\n   - Kebas di kawasan perineum\n   - Inkontinens kencing/najis\n   - Kelemahan kaki progresif\n   - Demam dengan sakit belakang\n\n5. MC: __ hari\n\n6. Temujanji susulan: 1 minggu / PRN",
                'vital_sign_defaults' => json_encode([
                    'blood_pressure' => true,
                    'pulse_rate' => true,
                    'pain_score' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'M54.5' => 'Low back pain',
                    'M54.2' => 'Cervicalgia',
                    'M17.9' => 'Gonarthrosis',
                    'M79.1' => 'Myalgia',
                    'M25.5' => 'Pain in joint',
                ]),
                'sort_order' => 6,
            ],
            [
                'name' => 'Masalah Kulit',
                'category' => 'dermatology',
                'chief_complaint_template' => "Aduan utama:\n- Jenis masalah: Ruam/Gatal/Bengkak/Luka/Jerawat/Lain: __\n- Lokasi: \n- Tempoh: __ hari/minggu/bulan\n- Gatal: Ya/Tidak, Keterukan: __/10",
                'history_template' => "Sejarah penyakit sekarang:\n- Onset: Tiba-tiba/Beransur\n- Perkembangan: Merebak/Tetap/Berkurang\n- Faktor pencetus: Makanan/Ubat/Sabun/Cuaca/Stress/Tidak pasti\n- Rawatan yang telah dicuba: \n\nGejala berkaitan:\n- Demam: Ya/Tidak\n- Sakit sendi: Ya/Tidak\n- Lesi di mulut/mata: Ya/Tidak\n\nSejarah:\n- Alahan: \n- Atopi (asma/eczema/allergic rhinitis): Ya/Tidak\n- Ubat baru: Ya/Tidak\n- Kontak dengan pesakit serupa: Ya/Tidak",
                'examination_template' => "Pemeriksaan Kulit:\n\nLesi primer:\n- Jenis: Makula/Papula/Plak/Vesikel/Pustula/Nodula/Ulser\n- Saiz: __ mm/cm\n- Warna: Merah/Hiperpigmentasi/Hipopigmentasi\n- Bentuk: Bulat/Oval/Tidak sekata\n- Sempadan: Jelas/Tidak jelas\n- Distribusi: Setempat/Generalisasi/Dermatomal\n- Lokasi: \n\nLesi sekunder:\n- Skuama: Ya/Tidak\n- Krusta: Ya/Tidak\n- Ekskoriasi: Ya/Tidak\n- Likenifikasi: Ya/Tidak\n\nPemeriksaan lain:\n- Kuku: Normal/Abnormal\n- Rambut: Normal/Abnormal\n- Mukosa: Normal/Abnormal",
                'assessment_template' => "Diagnosis:\n1. [ ] L30.9 - Dermatitis\n2. [ ] L20.9 - Atopic dermatitis\n3. [ ] L50.9 - Urticaria\n4. [ ] L70.0 - Acne vulgaris\n5. [ ] B35.4 - Tinea corporis\n6. [ ] B86 - Scabies\n7. [ ] L02.9 - Abscess/Furuncle",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan:\n   - Topikal: \n   - Oral: \n   - Antihistamin: \n\n2. Penjagaan kulit:\n   - Sabun lembut/emollient\n   - Elakkan mencakar\n   - Moisturizer: \n\n3. Elakkan:\n   - Pencetus yang dikenal pasti\n   - Sabun keras\n   - Air panas\n\n4. Temujanji susulan: __ minggu\n\n5. Rujuk pakar kulit jika:\n   - Tidak respons kepada rawatan\n   - Jangkitan sekunder\n   - Lesi yang mencurigakan",
                'vital_sign_defaults' => json_encode([
                    'temperature' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'L30.9' => 'Dermatitis, unspecified',
                    'L20.9' => 'Atopic dermatitis',
                    'L50.9' => 'Urticaria',
                    'L70.0' => 'Acne vulgaris',
                    'B35.4' => 'Tinea corporis',
                ]),
                'sort_order' => 7,
            ],
            [
                'name' => 'Pediatrik - Demam',
                'category' => 'pediatric',
                'chief_complaint_template' => "Aduan utama:\n- Demam: Ya, Tempoh: __ hari\n- Suhu tertinggi: __°C\n- Batuk: Ya/Tidak\n- Selesema: Ya/Tidak\n- Muntah: Ya/Tidak, __ kali\n- Cirit-birit: Ya/Tidak, __ kali\n- Ruam: Ya/Tidak\n- Kurang aktif: Ya/Tidak\n- Kurang makan/minum: Ya/Tidak",
                'history_template' => "Sejarah penyakit sekarang:\n- Tempoh demam: __ hari\n- Pattern: Berterusan/Hilang timbul\n- Respons kepada Paracetamol: Baik/Tidak\n- Kontak dengan pesakit demam: Ya/Tidak\n- Sekolah/taska: Ada kes serupa? Ya/Tidak\n\nSejarah perubatan:\n- Kelahiran: Normal/Prematur (__minggu)\n- Imunisasi: Lengkap/Tidak lengkap\n- Alahan: \n- Penyakit kronik: \n\nRed flags:\n- Sawan: Ya/Tidak\n- Susah bernafas: Ya/Tidak\n- Tidak boleh minum: Ya/Tidak\n- Mengantuk berlebihan: Ya/Tidak\n- Ruam yang tidak hilang bila ditekan: Ya/Tidak",
                'examination_template' => "Pemeriksaan Fizikal:\n- Keadaan umum: Aktif/Kurang aktif/Letargi\n- Suhu: __°C\n- HR: __ bpm\n- RR: __ /min\n- SpO2: __%\n- Berat: __ kg (persentil: __)\n\nDehidrasi:\n- CRT: < 2 saat / > 2 saat\n- Turgor: Normal/Berkurang\n- Fontanel (jika bayi): Normal/Cekung\n- Mata: Normal/Cekung\n- Membran mukosa: Lembap/Kering\n\nKepala & Leher:\n- Tekak: Normal/Merah/Eksudat\n- Tonsil: Normal/Bengkak\n- Telinga: Normal/Merah/Bulging\n\nPernafasan:\n- Nafas cuping hidung: Ya/Tidak\n- Retraksi: Ya/Tidak\n- Bunyi nafas: Normal/Ronki/Wheezing\n\nAbdomen:\n- Lembut/Distensi\n- Hepatomegali: Ya/Tidak\n\nKulit:\n- Ruam: Tiada/Ada, Jenis: ",
                'assessment_template' => "Diagnosis:\n1. [ ] J06.9 - URTI\n2. [ ] H66.9 - Otitis media\n3. [ ] A09 - Viral gastroenteritis\n4. [ ] J18.9 - Pneumonia\n5. [ ] B34.9 - Viral infection\n\nKeterukan: Ringan/Sederhana/Teruk\nDehidrasi: Tiada/Ringan/Sederhana/Teruk",
                'plan_template' => "Pelan Rawatan:\n1. Ubat-ubatan (dos mengikut berat: __ kg):\n   - Paracetamol __mg (15mg/kg) setiap 4-6 jam PRN\n   - \n\n2. Rehidrasi:\n   - ORS: __ ml selepas setiap cirit-birit/muntah\n   - Teruskan penyusuan/susu formula\n\n3. Nasihat kepada ibu bapa:\n   - Tepid sponging jika demam tinggi\n   - Pakaian nipis\n   - Minum air yang banyak\n   - Pantau tanda-tanda bahaya\n\n4. Datang segera jika:\n   - Demam >3 hari\n   - Sawan\n   - Susah bernafas\n   - Tidak mahu minum\n   - Sangat mengantuk/sukar dikejutkan\n   - Ruam yang tidak hilang bila ditekan\n\n5. Temujanji susulan: __ hari / PRN",
                'vital_sign_defaults' => json_encode([
                    'temperature' => true,
                    'pulse_rate' => true,
                    'respiratory_rate' => true,
                    'spo2' => true,
                    'weight' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'J06.9' => 'Acute upper respiratory infection',
                    'H66.9' => 'Otitis media',
                    'A09' => 'Viral gastroenteritis',
                    'J18.9' => 'Pneumonia',
                    'B34.9' => 'Viral infection',
                ]),
                'sort_order' => 8,
            ],
            [
                'name' => 'Pemeriksaan Kesihatan / Medical Check-up',
                'category' => 'preventive',
                'chief_complaint_template' => "Tujuan lawatan:\n- [ ] Pemeriksaan kesihatan am\n- [ ] Pre-employment\n- [ ] Insurans\n- [ ] Lesen memandu\n- [ ] Lain-lain: __",
                'history_template' => "Sejarah perubatan:\n- Penyakit kronik: Tiada / \n- Pembedahan: Tiada / \n- Alahan: Tiada / \n- Ubat semasa: Tiada / \n\nSejarah keluarga:\n- Kencing manis: Ya/Tidak\n- Darah tinggi: Ya/Tidak\n- Penyakit jantung: Ya/Tidak\n- Kanser: Ya/Tidak\n- Stroke: Ya/Tidak\n\nGaya hidup:\n- Merokok: Tidak/Ya, __ batang/hari, __ tahun\n- Alkohol: Tidak/Ya, __ unit/minggu\n- Senaman: __ kali/minggu, __ minit\n- Diet: Seimbang/Tidak seimbang\n- Tidur: __ jam/malam\n- Stress: Rendah/Sederhana/Tinggi",
                'examination_template' => "Pemeriksaan Fizikal:\n\nAm:\n- Keadaan umum: Baik\n- BP: __/__ mmHg\n- HR: __ bpm\n- Berat: __ kg\n- Tinggi: __ cm\n- BMI: __ kg/m² (Normal/Underweight/Overweight/Obese)\n- Waist circumference: __ cm\n\nMata:\n- Penglihatan: R __/__ L __/__\n- Warna: Normal/Buta warna\n\nTelinga:\n- Pendengaran: Normal/Berkurang\n\nMulut:\n- Gigi: Baik/Karies/Hilang\n\nLeher:\n- Tiroid: Normal/Bengkak\n- Limfadenopati: Tiada\n\nKardiovaskular:\n- Bunyi jantung: S1S2 normal\n- Murmur: Tiada\n\nPernafasan:\n- Bunyi nafas: Normal bilateral\n\nAbdomen:\n- Lembut, tidak tender\n- Organomegali: Tiada\n\nAnggota:\n- Edema: Tiada\n- Varicose veins: Tiada\n\nNeurologi:\n- Reflex: Normal\n- Power: 5/5",
                'assessment_template' => "Kesimpulan:\n- [ ] Sihat untuk bekerja/tujuan yang dinyatakan\n- [ ] Memerlukan rawatan/susulan untuk: __\n\nFaktor risiko yang dikenal pasti:\n- [ ] Tiada\n- [ ] Hipertensi\n- [ ] Obesiti\n- [ ] Dislipidemia\n- [ ] Merokok\n- [ ] Sedentary lifestyle\n- [ ] Sejarah keluarga penyakit kronik",
                'plan_template' => "Pelan:\n1. Investigasi:\n   - [ ] FBC\n   - [ ] FBS / HbA1c\n   - [ ] Lipid profile\n   - [ ] RFT (Urea, Creatinine)\n   - [ ] LFT\n   - [ ] Urine FEME\n   - [ ] ECG\n   - [ ] Chest X-ray\n\n2. Nasihat kesihatan:\n   - Diet seimbang\n   - Senaman 150 min/minggu\n   - Berhenti merokok\n   - Kurangkan alkohol\n   - Tidur cukup 7-8 jam\n   - Pengurusan stress\n\n3. Vaksinasi (jika perlu):\n   - [ ] Influenza (tahunan)\n   - [ ] Hepatitis B\n   - [ ] Tetanus booster\n\n4. Saringan kanser (mengikut umur/risiko):\n   - [ ] Pap smear (wanita)\n   - [ ] Mammogram (wanita >40)\n   - [ ] PSA (lelaki >50)\n\n5. Temujanji untuk keputusan: __ hari",
                'vital_sign_defaults' => json_encode([
                    'blood_pressure' => true,
                    'pulse_rate' => true,
                    'weight' => true,
                    'height' => true,
                    'bmi' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'Z00.0' => 'General adult medical examination',
                    'Z02.1' => 'Pre-employment examination',
                    'I10' => 'Essential hypertension',
                    'E66.9' => 'Obesity',
                    'E78.5' => 'Hyperlipidaemia',
                ]),
                'sort_order' => 9,
            ],
            [
                'name' => 'UTI / Jangkitan Saluran Kencing',
                'category' => 'general',
                'chief_complaint_template' => "Aduan utama:\n- Sakit semasa kencing: Ya/Tidak\n- Kerap kencing: Ya/Tidak, __ kali/hari\n- Rasa tidak puas kencing: Ya/Tidak\n- Kencing berdarah: Ya/Tidak\n- Sakit pinggang/perut bawah: Ya/Tidak\n- Demam: Ya/Tidak",
                'history_template' => "Sejarah penyakit sekarang:\n- Tempoh gejala: __ hari\n- Episode UTI sebelum ini: Ya/Tidak, __ kali\n- Rawatan UTI terakhir: \n\nGejala berkaitan:\n- Loya/Muntah: Ya/Tidak\n- Menggigil: Ya/Tidak\n- Keputihan (wanita): Ya/Tidak\n\nFaktor risiko:\n- Kencing manis: Ya/Tidak\n- Batu karang: Ya/Tidak\n- Kehamilan: Ya/Tidak\n- Kateter: Ya/Tidak\n- Aktiviti seksual baru: Ya/Tidak\n- Menopause: Ya/Tidak\n\nUntuk wanita:\n- LMP: \n- Kehamilan: Mungkin/Tidak mungkin",
                'examination_template' => "Pemeriksaan Fizikal:\n- Suhu: __°C\n- BP: __/__ mmHg\n- HR: __ bpm\n\nAbdomen:\n- Suprapubic tenderness: Ya/Tidak\n- Renal angle tenderness: Ya/Tidak (Kiri/Kanan/Bilateral)\n- CVA tenderness: Ya/Tidak\n\nGenital (jika perlu):\n- Discharge: Ya/Tidak\n- Ulser: Ya/Tidak",
                'assessment_template' => "Diagnosis:\n1. [ ] N39.0 - UTI, site unspecified\n2. [ ] N30.0 - Acute cystitis\n3. [ ] N10 - Acute pyelonephritis\n\nKeterukan:\n- [ ] Uncomplicated UTI\n- [ ] Complicated UTI (faktor: )\n- [ ] Upper UTI (pyelonephritis)",
                'plan_template' => "Pelan Rawatan:\n\n1. Investigasi:\n   - Urine FEME: \n   - Urine C&S (jika complicated/recurrent)\n   - UPT (wanita usia subur)\n\n2. Ubat-ubatan:\n   Uncomplicated cystitis:\n   - Nitrofurantoin 100mg BD x 5 hari, ATAU\n   - Trimethoprim 200mg BD x 3 hari, ATAU\n   - Fosfomycin 3g single dose\n\n   Pyelonephritis (rawatan luar):\n   - Ciprofloxacin 500mg BD x 7-14 hari\n\n   Analgesia:\n   - Paracetamol PRN\n\n3. Nasihat:\n   - Minum air sekurang-kurangnya 8 gelas/hari\n   - Kencing selepas hubungan seksual\n   - Lap dari depan ke belakang\n   - Jangan tahan kencing\n   - Habiskan antibiotik\n\n4. Indikasi kemasukan hospital:\n   - Muntah berterusan\n   - Sepsis\n   - Kehamilan\n   - Tidak respons kepada rawatan oral\n\n5. Temujanji susulan:\n   - 3-5 hari jika gejala tidak berkurang\n   - Repeat urine C&S jika recurrent",
                'vital_sign_defaults' => json_encode([
                    'temperature' => true,
                    'blood_pressure' => true,
                    'pulse_rate' => true,
                ]),
                'common_diagnoses' => json_encode([
                    'N39.0' => 'Urinary tract infection',
                    'N30.0' => 'Acute cystitis',
                    'N10' => 'Acute pyelonephritis',
                    'N30.9' => 'Cystitis, unspecified',
                ]),
                'sort_order' => 10,
            ],
        ];

        $now = now();

        foreach ($templates as $template) {
            DB::table('clinical_templates')->insert([
                'name' => $template['name'],
                'category' => $template['category'],
                'chief_complaint_template' => $template['chief_complaint_template'],
                'history_template' => $template['history_template'],
                'examination_template' => $template['examination_template'],
                'assessment_template' => $template['assessment_template'],
                'plan_template' => $template['plan_template'],
                'vital_sign_defaults' => $template['vital_sign_defaults'],
                'common_diagnoses' => $template['common_diagnoses'],
                'created_by' => null,
                'is_active' => true,
                'sort_order' => $template['sort_order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('Clinical templates seeded: '.count($templates).' records');
    }
}
