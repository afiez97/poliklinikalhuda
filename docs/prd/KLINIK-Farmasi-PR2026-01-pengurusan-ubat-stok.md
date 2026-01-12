# PRD: Modul Farmasi & Stok Ubat - Pengurusan Ubat & Stok

**Kod PRD:** KLINIK-Farmasi-PR2026-01-pengurusan-ubat-stok
**Modul:** Farmasi & Stok Ubat
**Submodul:** Pengurusan Ubat
**Tarikh Dicipta:** 2026-01-13
**Versi:** 1.0
**Pemilik Produk:** Pengurusan Klinik
**Stakeholder:** Ahli Farmasi, Doktor, Jururawat, Pengurus Klinik

---

## 1. Ringkasan Eksekutif

### 1.1 Objektif
Sistem Pengurusan Farmasi & Stok Ubat bertujuan untuk mengautomasikan proses penerimaan preskripsi, pendispensan ubat, pengurusan stok ubat secara real-time, dan pematuhan Akta Racun 1952 bagi memastikan keselamatan pesakit dan kecekapan operasi farmasi di Poliklinik Al-Huda.

### 1.2 Skop
- Penerimaan preskripsi elektronik daripada modul EMR dan preskripsi manual
- Pendispensan ubat dengan semakan interaksi ubat (drug-drug, drug-allergy)
- Pengurusan stok ubat dengan pengesanan real-time dan FEFO/FIFO
- Pengurusan batch number dan tarikh luput ubat
- Pematuhan Akta Racun 1952 (Poison Register untuk Kumpulan A)
- Integrasi dengan modul EMR (terima preskripsi) dan Billing (auto-generate bil)
- Pengurusan pembekal dan pesanan pembelian (Purchase Order)
- Rekod Ubat Pesakit (Patient Medication Record - PMR)
- Pelaporan komprehensif (jualan, stok, luput, poison register)

### 1.3 Out of Scope
- Pengurusan klinik veterinar atau hospital besar (fokus: klinik swasta kecil-sederhana)
- Pengurusan ubat kawalan (narcotic) peringkat tinggi (fokus: poison schedule asas)
- E-commerce atau jualan online ubat
- Integrasi dengan sistem kerajaan (QUEST, myHEALTH)

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa
1. **Stok tidak dikemaskini real-time:** Stok ubat dikemaskini manual, menyebabkan ketidaktepatan stok dan risiko dispensing ubat yang tidak mencukupi
2. **Tiada alert ubat luput:** Ubat yang hampir luput tidak dikesan awal, menyebabkan kerugian dan risiko dispensing ubat luput
3. **Tiada semakan interaksi ubat:** Tiada sistem automatik untuk menyemak drug-drug interaction atau drug-allergy interaction
4. **Pematuhan Akta Racun manual:** Poison Register untuk ubat Kumpulan A ditulis manual, memakan masa dan terdedah kepada kesilapan
5. **Preskripsi tidak terintegrasi:** Preskripsi daripada doktor perlu ditaip semula atau dicari manual
6. **Tiada Patient Medication Record:** Sejarah ubat pesakit tidak terpusat, menyukarkan semakan ubat terdahulu

### 2.2 Impak
- Risiko keselamatan pesakit (adverse drug reaction, medication error)
- Kerugian kewangan akibat ubat luput atau stok tidak tepat
- Ketidakpatuhan undang-undang (Akta Racun 1952)
- Kelewatan dalam dispensing ubat
- Beban kerja tinggi untuk ahli farmasi

---

## 3. User Stories

### 3.1 User Stories Utama

1. **Sebagai Ahli Farmasi**, **saya mahu** menerima preskripsi elektronik terus daripada modul EMR **supaya** saya tidak perlu menaip semula preskripsi dan mengurangkan risiko kesilapan **bila** doktor finalize preskripsi di EMR **saya sepatutnya** dapat melihat preskripsi baru dalam senarai tugasan saya dengan status 'pending review'

2. **Sebagai Ahli Farmasi**, **saya mahu** merekod preskripsi manual daripada pesakit walk-in atau rujukan luar **supaya** semua preskripsi direkodkan dalam sistem walaupun tidak datang daripada EMR **bila** pesakit datang dengan preskripsi luar **saya sepatutnya** boleh input preskripsi manual dengan scan atau taip

3. **Sebagai Ahli Farmasi**, **saya mahu** menyemak stok ubat secara real-time semasa review preskripsi **supaya** saya tahu sama ada stok mencukupi sebelum dispensing **bila** saya buka preskripsi **saya sepatutnya** nampak status stok (Available/Low Stock/Out of Stock) bagi setiap ubat

4. **Sebagai Ahli Farmasi**, **saya mahu** sistem auto-check drug-drug interaction dan drug-allergy interaction **supaya** saya dapat mengelak adverse drug reaction **bila** saya review preskripsi **saya sepatutnya** dapat alert jika ada interaction dan cadangan alternatif

5. **Sebagai Ahli Farmasi**, **saya mahu** memilih batch number ubat berdasarkan FEFO (First Expiry First Out) **supaya** ubat yang hampir luput digunakan dahulu **bila** saya dispensing ubat **saya sepatutnya** sistem auto-suggest batch number yang paling hampir luput

6. **Sebagai Ahli Farmasi**, **saya mahu** sistem auto-deduct stok selepas dispensing **supaya** stok sentiasa tepat tanpa perlu update manual **bila** saya klik 'Dispense' **saya sepatutnya** stok berkurangan secara automatik mengikut kuantiti yang didispense

7. **Sebagai Ahli Farmasi**, **saya mahu** mencetak label ubat dengan arahan penggunaan dan amaran **supaya** pesakit tahu cara guna ubat dengan betul **bila** saya dispensing ubat **saya sepatutnya** boleh print label dengan nama pesakit, nama ubat, dos, frekuensi, dan amaran

8. **Sebagai Ahli Farmasi**, **saya mahu** merekod nasihat farmasi (pharmaceutical care) yang diberikan kepada pesakit **supaya** dokumentasi lengkap untuk audit dan kesinambungan rawatan **bila** saya beri counseling **saya sepatutnya** boleh rekod nasihat dalam sistem dan ia disimpan dalam PMR pesakit

9. **Sebagai Ahli Farmasi**, **saya mahu** melihat Patient Medication Record (PMR) pesakit **supaya** saya tahu sejarah ubat pesakit termasuk alergi dan ubat terdahulu **bila** saya review preskripsi **saya sepatutnya** nampak senarai ubat semasa, sejarah ubat, dan alergi pesakit

10. **Sebagai Ahli Farmasi**, **saya mahu** dapat alert apabila stok ubat mencapai paras minimum **supaya** saya boleh buat reorder awal sebelum stok habis **bila** stok ubat kurang daripada minimum level **saya sepatutnya** dapat notification dan cadangan kuantiti reorder

11. **Sebagai Ahli Farmasi**, **saya mahu** dapat alert ubat yang akan luput dalam 3 bulan **supaya** saya boleh plan untuk guna dahulu atau return kepada pembekal **bila** ubat hampir luput **saya sepatutnya** nampak senarai ubat expiring soon dalam dashboard

12. **Sebagai Ahli Farmasi**, **saya mahu** sistem auto-maintain Poison Register untuk ubat Kumpulan A **supaya** saya patuh Akta Racun 1952 tanpa perlu tulis manual **bila** saya dispensing ubat poison **saya sepatutnya** sistem auto-rekod dalam poison register dengan tarikh, nama pesakit, kuantiti, dan balance

13. **Sebagai Ahli Farmasi**, **saya mahu** buat stock adjustment untuk ubat rosak atau expired **supaya** stok sistem sama dengan stok fizikal **bila** saya ada ubat rosak atau return **saya sepatutnya** boleh adjust stok dengan sebab dan approval

14. **Sebagai Ahli Farmasi**, **saya mahu** buat stocktake berkala **supaya** saya boleh verify stok fizikal dengan stok sistem **bila** end of month **saya sepatutnya** boleh print senarai stok dan input stok fizikal untuk variance report

15. **Sebagai Pengurus Klinik**, **saya mahu** melihat laporan jualan ubat, stok semasa, dan ubat luput **supaya** saya boleh buat keputusan perniagaan dan procurement **bila** saya buka modul laporan **saya sepatutnya** boleh generate laporan dengan filter tarikh, kategori ubat, atau pembekal

### 3.2 Edge Cases

1. **Sebagai Ahli Farmasi**, **saya mahu** override interaction alert dengan justifikasi **supaya** saya boleh dispensing ubat walaupun ada alert jika doktor confirm **bila** ada interaction alert tetapi doktor dah confirm **saya sepatutnya** boleh override dengan rekod justifikasi dan doktor yang approve

2. **Sebagai Ahli Farmasi**, **saya mahu** handle partial dispensing **supaya** saya boleh dispensing sebahagian ubat jika stok tidak mencukupi **bila** stok kurang daripada kuantiti preskripsi **saya sepatutnya** boleh dispensing partial dengan rekod balance owed

3. **Sebagai Ahli Farmasi**, **saya mahu** void atau cancel dispensing dengan sebab **supaya** saya boleh reverse dispensing yang salah **bila** saya dispensing salah ubat **saya sepatutnya** boleh void dengan justifikasi dan stok auto-restore

4. **Sebagai Ahli Farmasi**, **saya mahu** handle ubat extemporaneous (compounding) **supaya** saya boleh rekod ubat yang dibuat in-house **bila** klinik buat compounding **saya sepatutnya** boleh rekod formula, bahan mentah, dan batch number compounded product

5. **Sebagai Doktor**, **saya mahu** dapat notification status preskripsi **supaya** saya tahu sama ada preskripsi sudah didispense atau ada masalah **bila** saya prescribed ubat **saya sepatutnya** dapat update status (Pending Review/Ready to Dispense/Dispensed/Issue) dalam EMR

---

## 4. Keperluan Fungsian

### 4.1 Penerimaan Preskripsi

**FR-1:** Sistem mesti boleh menerima preskripsi elektronik daripada modul EMR secara automatik apabila doktor finalize preskripsi

**FR-2:** Sistem mesti menyokong input preskripsi manual untuk pesakit walk-in atau rujukan luar dengan:
- Pilihan scan preskripsi kertas (PDF/Image)
- Input manual dengan search ubat
- Input maklumat doktor luar (nama, MMC number, klinik/hospital)

**FR-3:** Sistem mesti memaparkan senarai preskripsi dengan filter status:
- Pending Review
- Ready to Dispense
- Dispensed
- Cancelled
- On Hold (partial/stock issue)

### 4.2 Pengurusan Ubat (Drug Database)

**FR-4:** Sistem mesti mempunyai drug database dengan maklumat berikut:
- Nama ubat (generic + brand name)
- Dosage form (tablet, capsule, syrup, cream, etc.)
- Strength (e.g., 500mg, 10mg/ml)
- Poison Schedule Classification (Akta Racun 1952):
  - Kumpulan A (Group A Poisons)
  - Kumpulan B (Group B Poisons)
  - Kumpulan C (Group C Poisons)
  - First Schedule Poisons
  - Second Schedule Poisons
  - Non-poison
- Kategori ubat (Antibiotic, Analgesic, Antihypertensive, dll.)
- Route of administration (oral, topical, injection)
- Storage condition (room temp, refrigerated)
- Interaction database (drug-drug, drug-allergy)
- Pregnancy category
- Sokongan import dari MIMS atau NDFM (optional CSV import)

**FR-5:** Sistem mesti support custom drug entry untuk ubat compounding atau ubat baru

### 4.3 Workflow Dispensing

**FR-6:** Sistem mesti menyokong full dispensing workflow:

1. **Review Preskripsi:**
   - Lihat preskripsi + patient info + medical history
   - Semak PMR (Patient Medication Record)

2. **Check Stok:**
   - Auto-check stok availability
   - Alert jika low stock atau out of stock

3. **Check Interaction:**
   - Auto-check drug-drug interaction
   - Auto-check drug-allergy interaction (daripada patient allergies dalam EMR)
   - Display alert dengan severity level (Contraindicated/Major/Moderate/Minor)
   - Cadangan alternatif jika ada interaction

4. **Dispensing:**
   - Pilih batch number (auto-suggest FEFO)
   - Confirm kuantiti dispensing
   - Auto-deduct stok
   - Rekod pharmacist yang dispensing

5. **Label Printing:**
   - Print label ubat dengan:
     - Nama pesakit + IC/Passport
     - Nama ubat (generic + brand)
     - Dos dan frekuensi
     - Arahan penggunaan
     - Amaran (e.g., "Ambil selepas makan", "Elak alkohol")
     - Tarikh dispensing + expiry date

6. **Pharmaceutical Care (Counseling):**
   - Rekod nasihat farmasi
   - Tanda checklist counseling points (e.g., cara guna, side effects, storage)
   - Signature pesakit (optional)

**FR-7:** Sistem mesti support partial dispensing:
- Dispensing sebahagian ubat jika stok tidak mencukupi
- Rekod balance owed
- Alert untuk fulfill balance apabila stok masuk

**FR-8:** Sistem mesti allow override interaction alert dengan:
- Justifikasi (free text)
- Doktor yang approve override
- Rekod audit trail

**FR-9:** Sistem mesti allow void/cancel dispensing dengan:
- Sebab (salah ubat, salah dos, pesakit tak ambil, dll.)
- Auto-restore stok
- Rekod audit trail

### 4.4 Pengurusan Stok Ubat

**FR-10:** Sistem mesti track stok ubat dengan:
- Batch number (compulsory untuk poison schedule drugs)
- Tarikh luput
- Supplier
- Cost price per unit
- Selling price per unit
- Stok semasa (real-time)
- Minimum stock level (reorder point)
- Maximum stock level

**FR-11:** Sistem mesti auto-deduct stok selepas dispensing dengan:
- FEFO (First Expiry First Out) atau FIFO (First In First Out)
- Track stok per batch number

**FR-12:** Sistem mesti provide low stock alert:
- Notification apabila stok ≤ minimum level
- Cadangan kuantiti reorder berdasarkan average usage
- Generate reorder list

**FR-13:** Sistem mesti provide expiry alert:
- Alert ubat yang akan luput dalam 3 bulan
- Senarai ubat expired yang perlu dispose
- Track ubat expired dalam laporan

**FR-14:** Sistem mesti support stock adjustment:
- Reason: Damaged, Expired, Return to Supplier, Lost, Theft, Correction
- Approval workflow (jika adjust > threshold, perlu approval Pengurus)
- Rekod audit trail

**FR-15:** Sistem mesti support periodic stocktake:
- Print stock list untuk physical count
- Input physical count
- Generate variance report (System stock vs Physical stock)
- Auto-adjust stok selepas approval

### 4.5 Patient Medication Record (PMR)

**FR-16:** Sistem mesti menyimpan PMR untuk setiap pesakit dengan:
- Ubat semasa (current medications)
- Sejarah ubat (dispensing history dengan tarikh)
- Alergi ubat (daripada EMR)
- Adverse drug reaction (ADR) history
- Pharmaceutical care notes

**FR-17:** Sistem mesti auto-update PMR selepas setiap dispensing

### 4.6 Pematuhan Akta Racun 1952 (Poison Register)

**FR-18:** Sistem mesti auto-maintain Poison Register untuk ubat Kumpulan A (Group A Poisons) dengan rekod:
- Tarikh dispensing
- Nama ubat (poison)
- Kuantiti dispensed
- Nama pesakit + IC/Passport
- Alamat pesakit
- Nama doktor + MMC number
- Balance stok selepas dispensing
- Nama ahli farmasi

**FR-19:** Sistem mesti allow print Poison Register untuk inspection oleh Kementerian Kesihatan

**FR-20:** Sistem mesti alert ahli farmasi jika ubat adalah poison schedule untuk ensure proper handling

### 4.7 Pengurusan Pembekal & Purchase Order

**FR-21:** Sistem mesti support pengurusan pembekal (supplier) dengan:
- Nama pembekal
- Contact person + phone + email
- Alamat
- Payment terms (COD, 30 days, 60 days)
- Status (Active/Inactive)

**FR-22:** Sistem mesti support Purchase Order (PO) dengan:
- Auto-generate PO berdasarkan reorder list
- Manual create PO
- Track PO status (Draft/Sent/Partial Received/Fully Received/Cancelled)
- Print PO untuk email/fax kepada supplier

**FR-23:** Sistem mesti support Goods Receipt:
- Receive stok berdasarkan PO atau receive stok tanpa PO
- Input batch number, expiry date, kuantiti received
- Auto-update stok selepas goods receipt
- Generate Goods Receipt Note (GRN)

**FR-24:** Sistem mesti support return to supplier:
- Reason: Damaged, Expired, Wrong item
- Track return status
- Auto-adjust stok

### 4.8 Integrasi Dengan Sistem Lain

**FR-25:** Integrasi dengan Modul EMR:
- Auto-receive preskripsi elektronik daripada EMR apabila doktor finalize
- Send status update preskripsi kembali ke EMR (Pending Review/Dispensed/Issue)
- Auto-populate drug allergy daripada EMR patient profile

**FR-26:** Integrasi dengan Modul Billing:
- Auto-generate bil untuk ubat yang didispense
- Hantar data: Nama ubat, kuantiti, harga per unit, total harga
- Support GST/Sales tax calculation (jika applicable)

### 4.9 Pelaporan

**FR-27:** Sistem mesti provide laporan berikut:

1. **Sales Report:**
   - Jualan ubat by period (daily/weekly/monthly)
   - Breakdown by kategori ubat
   - Top selling drugs
   - Revenue by drug

2. **Stock Report:**
   - Current stock level (semua ubat atau by kategori)
   - Stock movement (in/out) by period
   - Slow moving drugs
   - Stock value (total cost)

3. **Expiry Report:**
   - Ubat yang akan luput dalam 1/3/6 bulan
   - Ubat yang sudah luput (belum dispose)

4. **Poison Register Report:**
   - Senarai dispensing ubat poison (Kumpulan A) by period
   - Format follow Akta Racun 1952 requirement

5. **Dispensing Report:**
   - Number of prescriptions dispensed by period
   - Dispensing by pharmacist
   - Average dispensing time
   - Partial dispensing report
   - Void/cancelled dispensing report

6. **Supplier Report:**
   - Purchase by supplier
   - Outstanding PO
   - Goods receipt by supplier

7. **Variance Report (Stocktake):**
   - System stock vs Physical stock
   - Variance by drug
   - Variance value

**FR-28:** Semua laporan mesti boleh export ke PDF dan Excel

---

## 5. Keperluan Teknikal

### 5.1 Arkitektur Sistem

**Framework:** Laravel 12
**Frontend:** Blade Templates + Bootstrap 5 + CoreUI
**Database:** MySQL 8.0
**Pattern:** Service Layer + Repository Pattern
**Validation:** FormRequest
**Routing:** Spatie Route Attributes

### 5.2 Struktur Database

**Jadual: `ubat` (Drug Database)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_ubat` | varchar(50) UNIQUE NOT NULL | Auto-generated (UBT-00001) |
| `nama_generik` | varchar(255) NOT NULL | Generic name |
| `nama_brand` | varchar(255) NULL | Brand name |
| `dosage_form` | varchar(100) NOT NULL | Tablet, Capsule, Syrup, etc. |
| `strength` | varchar(100) NOT NULL | e.g., 500mg, 10mg/ml |
| `route` | varchar(100) NOT NULL | Oral, Topical, Injection, etc. |
| `poison_schedule` | enum('group_a','group_b','group_c','first_schedule','second_schedule','non_poison') NOT NULL | Poison classification |
| `kategori` | varchar(100) NOT NULL | Antibiotic, Analgesic, etc. |
| `unit` | varchar(50) NOT NULL | Tablet, Bottle, Tube, etc. |
| `storage_condition` | varchar(255) NULL | Room temp, Refrigerated |
| `pregnancy_category` | char(1) NULL | A, B, C, D, X |
| `harga_kos` | decimal(10,2) NOT NULL DEFAULT 0 | Cost price per unit |
| `harga_jual` | decimal(10,2) NOT NULL DEFAULT 0 | Selling price per unit |
| `min_stok` | int NOT NULL DEFAULT 0 | Minimum stock level (reorder point) |
| `max_stok` | int NOT NULL DEFAULT 0 | Maximum stock level |
| `status` | enum('active','discontinued') NOT NULL DEFAULT 'active' | Status |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `stok_ubat` (Drug Stock)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `batch_number` | varchar(100) NOT NULL | Batch number (compulsory for poison) |
| `tarikh_luput` | date NOT NULL | Expiry date |
| `supplier_id` | bigint UNSIGNED NULL | FK → supplier.id |
| `grn_id` | bigint UNSIGNED NULL | FK → goods_receipt.id (reference) |
| `kuantiti_awal` | int NOT NULL | Initial quantity received |
| `kuantiti_semasa` | int NOT NULL | Current quantity available |
| `harga_kos` | decimal(10,2) NOT NULL | Cost price per unit (at time of purchase) |
| `tarikh_terima` | date NOT NULL | Date received |
| `status` | enum('available','expired','damaged','returned') NOT NULL DEFAULT 'available' | Status |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Index:** UNIQUE(ubat_id, batch_number, tarikh_luput)
**Note:** Satu ubat boleh ada multiple batch dengan tarikh luput berbeza

**Jadual: `preskripsi` (Prescriptions)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_preskripsi` | varchar(50) UNIQUE NOT NULL | Auto-generated (RX-YYYYMMDD-0001) |
| `jenis` | enum('electronic','manual') NOT NULL | Source type |
| `emr_id` | bigint UNSIGNED NULL | FK → emr.id (jika electronic) |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `doktor_id` | bigint UNSIGNED NULL | FK → users.id (jika electronic) |
| `doktor_luar_nama` | varchar(255) NULL | External doctor name (jika manual) |
| `doktor_luar_mmc` | varchar(50) NULL | External doctor MMC number |
| `doktor_luar_klinik` | varchar(255) NULL | External clinic/hospital |
| `tarikh_preskripsi` | date NOT NULL | Prescription date |
| `scan_preskripsi` | varchar(255) NULL | Path to scanned prescription (jika manual) |
| `status` | enum('pending_review','ready_to_dispense','partially_dispensed','dispensed','cancelled','on_hold') NOT NULL DEFAULT 'pending_review' | Status |
| `reviewed_by` | bigint UNSIGNED NULL | FK → users.id (ahli farmasi yang review) |
| `reviewed_at` | timestamp NULL | Review timestamp |
| `catatan` | text NULL | Notes from pharmacist |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `preskripsi_item` (Prescription Items)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `preskripsi_id` | bigint UNSIGNED NOT NULL | FK → preskripsi.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `kuantiti_prescribed` | int NOT NULL | Quantity prescribed |
| `dos` | varchar(255) NOT NULL | e.g., "500mg" |
| `frekuensi` | varchar(255) NOT NULL | e.g., "3 kali sehari" |
| `tempoh` | varchar(100) NULL | Duration, e.g., "7 hari" |
| `arahan` | text NULL | Instructions, e.g., "Ambil selepas makan" |
| `kuantiti_dispensed` | int NOT NULL DEFAULT 0 | Total dispensed so far |
| `kuantiti_balance` | int NOT NULL DEFAULT 0 | Balance owed (for partial) |
| `status` | enum('pending','dispensed','partial','cancelled') NOT NULL DEFAULT 'pending' | Item status |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `dispensing` (Dispensing Records)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_dispensing` | varchar(50) UNIQUE NOT NULL | Auto-generated (DSP-YYYYMMDD-0001) |
| `preskripsi_id` | bigint UNSIGNED NOT NULL | FK → preskripsi.id |
| `preskripsi_item_id` | bigint UNSIGNED NOT NULL | FK → preskripsi_item.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `stok_ubat_id` | bigint UNSIGNED NOT NULL | FK → stok_ubat.id (batch selected) |
| `batch_number` | varchar(100) NOT NULL | Batch number dispensed |
| `kuantiti_dispensed` | int NOT NULL | Quantity dispensed |
| `harga_jual_unit` | decimal(10,2) NOT NULL | Selling price per unit |
| `jumlah_harga` | decimal(10,2) NOT NULL | Total price |
| `tarikh_dispensing` | date NOT NULL | Dispensing date |
| `dispensed_by` | bigint UNSIGNED NOT NULL | FK → users.id (ahli farmasi) |
| `pharmaceutical_care` | text NULL | Counseling notes |
| `status` | enum('dispensed','voided') NOT NULL DEFAULT 'dispensed' | Status |
| `void_reason` | text NULL | Reason if voided |
| `voided_by` | bigint UNSIGNED NULL | FK → users.id |
| `voided_at` | timestamp NULL | Void timestamp |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `drug_interactions` (Drug Interaction Database)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `ubat_a_id` | bigint UNSIGNED NOT NULL | FK → ubat.id (first drug) |
| `ubat_b_id` | bigint UNSIGNED NOT NULL | FK → ubat.id (second drug) |
| `severity` | enum('contraindicated','major','moderate','minor') NOT NULL | Severity level |
| `description` | text NOT NULL | Interaction description |
| `recommendation` | text NULL | Clinical recommendation |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Index:** UNIQUE(ubat_a_id, ubat_b_id)

**Jadual: `patient_medication_record` (PMR)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `dispensing_id` | bigint UNSIGNED NOT NULL | FK → dispensing.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `tarikh_dispensing` | date NOT NULL | Dispensing date |
| `dos` | varchar(255) NOT NULL | Dosage |
| `frekuensi` | varchar(255) NOT NULL | Frequency |
| `kuantiti` | int NOT NULL | Quantity dispensed |
| `pharmaceutical_care` | text NULL | Counseling notes |
| `dispensed_by` | bigint UNSIGNED NOT NULL | FK → users.id (ahli farmasi) |
| `created_at` | timestamp | Created timestamp |

**Note:** PMR adalah historical record, tidak akan di-update atau delete

**Jadual: `poison_register` (Akta Racun 1952 Compliance)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `dispensing_id` | bigint UNSIGNED NOT NULL | FK → dispensing.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id (must be poison schedule) |
| `tarikh_dispensing` | date NOT NULL | Dispensing date |
| `nama_ubat` | varchar(255) NOT NULL | Drug name |
| `batch_number` | varchar(100) NOT NULL | Batch number |
| `kuantiti_dispensed` | int NOT NULL | Quantity dispensed |
| `balance_stok` | int NOT NULL | Stock balance after dispensing |
| `pesakit_nama` | varchar(255) NOT NULL | Patient name |
| `pesakit_ic` | varchar(50) NOT NULL | Patient IC/Passport |
| `pesakit_alamat` | text NOT NULL | Patient address |
| `doktor_nama` | varchar(255) NOT NULL | Doctor name |
| `doktor_mmc` | varchar(50) NULL | Doctor MMC number |
| `ahli_farmasi` | varchar(255) NOT NULL | Pharmacist name |
| `created_at` | timestamp | Created timestamp |

**Note:** Auto-created for Group A poisons only

**Jadual: `supplier` (Suppliers)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_supplier` | varchar(50) UNIQUE NOT NULL | Auto-generated (SUP-00001) |
| `nama` | varchar(255) NOT NULL | Supplier name |
| `contact_person` | varchar(255) NULL | Contact person |
| `telefon` | varchar(50) NULL | Phone number |
| `email` | varchar(255) NULL | Email |
| `alamat` | text NULL | Address |
| `payment_terms` | varchar(100) NULL | e.g., COD, 30 days, 60 days |
| `status` | enum('active','inactive') NOT NULL DEFAULT 'active' | Status |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `purchase_order` (Purchase Orders)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_po` | varchar(50) UNIQUE NOT NULL | Auto-generated (PO-YYYYMMDD-0001) |
| `supplier_id` | bigint UNSIGNED NOT NULL | FK → supplier.id |
| `tarikh_po` | date NOT NULL | PO date |
| `tarikh_jangka_terima` | date NULL | Expected delivery date |
| `jumlah_kos` | decimal(10,2) NOT NULL DEFAULT 0 | Total cost |
| `status` | enum('draft','sent','partial_received','fully_received','cancelled') NOT NULL DEFAULT 'draft' | Status |
| `catatan` | text NULL | Notes |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `approved_by` | bigint UNSIGNED NULL | FK → users.id |
| `approved_at` | timestamp NULL | Approval timestamp |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `purchase_order_item` (PO Items)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `po_id` | bigint UNSIGNED NOT NULL | FK → purchase_order.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `kuantiti_order` | int NOT NULL | Quantity ordered |
| `kuantiti_received` | int NOT NULL DEFAULT 0 | Quantity received so far |
| `harga_kos_unit` | decimal(10,2) NOT NULL | Cost price per unit |
| `jumlah_kos` | decimal(10,2) NOT NULL | Total cost for this item |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `goods_receipt` (Goods Receipt)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_grn` | varchar(50) UNIQUE NOT NULL | Auto-generated (GRN-YYYYMMDD-0001) |
| `po_id` | bigint UNSIGNED NULL | FK → purchase_order.id (NULL jika receive tanpa PO) |
| `supplier_id` | bigint UNSIGNED NOT NULL | FK → supplier.id |
| `tarikh_terima` | date NOT NULL | Receipt date |
| `invoice_number` | varchar(100) NULL | Supplier invoice number |
| `catatan` | text NULL | Notes |
| `received_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `goods_receipt_item` (GRN Items)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `grn_id` | bigint UNSIGNED NOT NULL | FK → goods_receipt.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `batch_number` | varchar(100) NOT NULL | Batch number |
| `tarikh_luput` | date NOT NULL | Expiry date |
| `kuantiti_received` | int NOT NULL | Quantity received |
| `harga_kos_unit` | decimal(10,2) NOT NULL | Cost price per unit |
| `jumlah_kos` | decimal(10,2) NOT NULL | Total cost |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Note:** Setiap GRN item akan create entry dalam `stok_ubat`

**Jadual: `stock_adjustment` (Stock Adjustments)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_adjustment` | varchar(50) UNIQUE NOT NULL | Auto-generated (ADJ-YYYYMMDD-0001) |
| `stok_ubat_id` | bigint UNSIGNED NOT NULL | FK → stok_ubat.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `batch_number` | varchar(100) NOT NULL | Batch number |
| `jenis` | enum('in','out') NOT NULL | Adjustment type (increase/decrease) |
| `kuantiti` | int NOT NULL | Quantity adjusted |
| `sebab` | enum('damaged','expired','return_to_supplier','lost','theft','correction','other') NOT NULL | Reason |
| `catatan` | text NULL | Notes |
| `tarikh_adjustment` | date NOT NULL | Adjustment date |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `approved_by` | bigint UNSIGNED NULL | FK → users.id (jika perlu approval) |
| `approved_at` | timestamp NULL | Approval timestamp |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `stocktake` (Physical Stocktake)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_stocktake` | varchar(50) UNIQUE NOT NULL | Auto-generated (STK-YYYYMM-01) |
| `tarikh_stocktake` | date NOT NULL | Stocktake date |
| `status` | enum('draft','completed','approved') NOT NULL DEFAULT 'draft' | Status |
| `total_variance_value` | decimal(10,2) NOT NULL DEFAULT 0 | Total variance value |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `approved_by` | bigint UNSIGNED NULL | FK → users.id |
| `approved_at` | timestamp NULL | Approval timestamp |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `stocktake_item` (Stocktake Items)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `stocktake_id` | bigint UNSIGNED NOT NULL | FK → stocktake.id |
| `ubat_id` | bigint UNSIGNED NOT NULL | FK → ubat.id |
| `stok_ubat_id` | bigint UNSIGNED NOT NULL | FK → stok_ubat.id |
| `batch_number` | varchar(100) NOT NULL | Batch number |
| `system_qty` | int NOT NULL | System quantity |
| `physical_qty` | int NULL | Physical count (input by user) |
| `variance_qty` | int NOT NULL DEFAULT 0 | Variance (physical - system) |
| `variance_value` | decimal(10,2) NOT NULL DEFAULT 0 | Variance value (variance_qty × cost) |
| `catatan` | text NULL | Notes for variance |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `interaction_alert_override` (Override Interaction Alerts)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `dispensing_id` | bigint UNSIGNED NOT NULL | FK → dispensing.id |
| `interaction_id` | bigint UNSIGNED NOT NULL | FK → drug_interactions.id |
| `justification` | text NOT NULL | Reason for override |
| `approved_by_doktor_id` | bigint UNSIGNED NULL | FK → users.id (doktor yang approve) |
| `overridden_by` | bigint UNSIGNED NOT NULL | FK → users.id (ahli farmasi) |
| `created_at` | timestamp | Created timestamp |

### 5.3 Models (Eloquent)

**Models yang perlu dicipta:**
- `Ubat` (Drug)
- `StokUbat` (DrugStock)
- `Preskripsi` (Prescription)
- `PreskripsiItem` (PrescriptionItem)
- `Dispensing`
- `DrugInteraction`
- `PatientMedicationRecord`
- `PoisonRegister`
- `Supplier`
- `PurchaseOrder`
- `PurchaseOrderItem`
- `GoodsReceipt`
- `GoodsReceiptItem`
- `StockAdjustment`
- `Stocktake`
- `StocktakeItem`
- `InteractionAlertOverride`

**Relationships:**
- `Ubat` hasMany `StokUbat`
- `Ubat` hasMany `Dispensing`
- `Preskripsi` hasMany `PreskripsiItem`
- `Preskripsi` belongsTo `Pesakit`
- `Preskripsi` belongsTo `EMR` (optional)
- `PreskripsiItem` belongsTo `Ubat`
- `Dispensing` belongsTo `Preskripsi`
- `Dispensing` belongsTo `StokUbat`
- `Dispensing` belongsTo `User` (ahli farmasi)
- `PurchaseOrder` hasMany `PurchaseOrderItem`
- `GoodsReceipt` hasMany `GoodsReceiptItem`

### 5.4 Services & Repositories

**Services:**
- `PreskripsiService` - Handle preskripsi workflow
- `DispensingService` - Handle dispensing workflow (check stock, check interaction, dispense, update stock)
- `StokUbatService` - Handle stock management (receive, adjust, stocktake)
- `DrugInteractionService` - Check drug-drug and drug-allergy interactions
- `PoisonRegisterService` - Auto-maintain poison register
- `PurchaseOrderService` - Handle PO workflow
- `ReportService` - Generate all reports

**Repositories:**
- `UbatRepository`
- `StokUbatRepository`
- `PreskripsiRepository`
- `DispensingRepository`
- `SupplierRepository`
- `PurchaseOrderRepository`

### 5.5 FormRequests (Validation)

- `StoreUbatRequest`
- `StorePreskripsiRequest`
- `DispensingRequest`
- `StockAdjustmentRequest`
- `StorePurchaseOrderRequest`
- `GoodsReceiptRequest`

---

## 6. Workflow

### 6.1 Workflow Penerimaan Preskripsi Elektronik

```
Doktor finalize preskripsi di EMR
    ↓
Sistem auto-create entry dalam jadual `preskripsi` (jenis=electronic, status=pending_review)
    ↓
Sistem auto-create entry dalam jadual `preskripsi_item` untuk setiap ubat
    ↓
Sistem send notification kepada Farmasi
    ↓
Ahli Farmasi dapat alert preskripsi baru dalam senarai tugasan
```

### 6.2 Workflow Dispensing Ubat

```
1. REVIEW PRESKRIPSI
   Ahli Farmasi buka preskripsi (status: pending_review)
       ↓
   Lihat maklumat pesakit + preskripsi + PMR + alergi
       ↓
   Klik "Review"

2. CHECK STOK
   Sistem auto-check stok untuk semua ubat dalam preskripsi
       ↓
   Display status: Available (hijau) / Low Stock (kuning) / Out of Stock (merah)
       ↓
   Jika out of stock → Alert ahli farmasi, option: Cancel item / Partial dispense

3. CHECK INTERACTION
   Sistem auto-check drug-drug interaction (antara ubat dalam preskripsi)
       ↓
   Sistem auto-check drug-allergy interaction (dengan patient allergies)
       ↓
   Jika ada interaction → Display alert dengan severity + description
       ↓
   Ahli Farmasi boleh:
       - Hubungi doktor untuk clarification
       - Override dengan justifikasi (rekod dalam `interaction_alert_override`)
       - Suggest alternative drug

4. DISPENSING
   Ahli Farmasi klik "Dispense" untuk setiap item
       ↓
   Sistem auto-suggest batch number (FEFO - earliest expiry first)
       ↓
   Ahli Farmasi confirm batch number + kuantiti dispensing
       ↓
   Sistem create entry dalam jadual `dispensing`
       ↓
   Sistem auto-deduct stok (`stok_ubat.kuantiti_semasa`)
       ↓
   Sistem update `preskripsi_item.kuantiti_dispensed` dan `kuantiti_balance`
       ↓
   Jika ubat adalah Group A Poison → Sistem auto-create entry dalam `poison_register`

5. LABEL PRINTING
   Ahli Farmasi klik "Print Label"
       ↓
   Sistem generate label dengan:
       - Nama pesakit + IC
       - Nama ubat (generic + brand)
       - Dos + frekuensi
       - Arahan penggunaan
       - Amaran
       - Tarikh dispensing + expiry date
       ↓
   Print label untuk tampal pada ubat

6. PHARMACEUTICAL CARE (COUNSELING)
   Ahli Farmasi rekod nasihat farmasi dalam `dispensing.pharmaceutical_care`
       ↓
   Checklist counseling points (optional):
       - Cara guna ubat
       - Side effects
       - Storage condition
       - Masa makan (before/after meal)
       ↓
   Signature pesakit (optional)

7. FINALIZE
   Selepas semua item dispensed → Status preskripsi update ke "dispensed"
       ↓
   Sistem auto-create entry dalam `patient_medication_record` (PMR)
       ↓
   Sistem send data to Billing module untuk auto-generate bil
       ↓
   Sistem send status update ke EMR (status: Dispensed)
```

### 6.3 Workflow Partial Dispensing

```
Ahli Farmasi review preskripsi
    ↓
Stok tidak mencukupi untuk sesuatu ubat
    ↓
Ahli Farmasi pilih "Partial Dispense"
    ↓
Input kuantiti partial (e.g., prescribed 30, stok only 15, dispense 15)
    ↓
Sistem update:
    - preskripsi_item.kuantiti_dispensed = 15
    - preskripsi_item.kuantiti_balance = 15
    - preskripsi_item.status = 'partial'
    - preskripsi.status = 'partially_dispensed'
    ↓
Sistem alert ahli farmasi bila stok masuk untuk fulfill balance
    ↓
Bila stok dah ada, ahli farmasi dispensing balance (15 lagi)
    ↓
Sistem update:
    - preskripsi_item.kuantiti_dispensed = 30
    - preskripsi_item.kuantiti_balance = 0
    - preskripsi_item.status = 'dispensed'
    - preskripsi.status = 'dispensed' (jika semua item dah full)
```

### 6.4 Workflow Goods Receipt (Terima Stok)

```
1. CREATE PURCHASE ORDER (PO)
   Pengurus/Ahli Farmasi create PO
       ↓
   Pilih supplier
       ↓
   Add ubat + kuantiti order + harga kos
       ↓
   Status PO: Draft
       ↓
   Submit untuk approval (optional)
       ↓
   Selepas approved → Status PO: Sent
       ↓
   Print/Email PO kepada supplier

2. TERIMA STOK
   Stok sampai dari supplier
       ↓
   Ahli Farmasi create Goods Receipt Note (GRN)
       ↓
   Link to PO (atau tanpa PO jika urgent purchase)
       ↓
   Input untuk setiap ubat:
       - Batch number
       - Tarikh luput
       - Kuantiti received
       - Verify harga kos
       ↓
   Sistem create entry dalam jadual `goods_receipt_item`
       ↓
   Sistem auto-create entry dalam jadual `stok_ubat`:
       - ubat_id
       - batch_number
       - tarikh_luput
       - kuantiti_awal = kuantiti_received
       - kuantiti_semasa = kuantiti_received
       - supplier_id
       - grn_id
       - tarikh_terima
       - status = 'available'
       ↓
   Sistem update `purchase_order_item.kuantiti_received`
       ↓
   Jika semua items fully received → Status PO: Fully Received
       ↓
   Sistem check jika ada pending partial dispensing untuk ubat ini → Alert ahli farmasi
```

### 6.5 Workflow Stock Adjustment

```
Ahli Farmasi dapati ubat rosak/expired/lost
    ↓
Create Stock Adjustment
    ↓
Pilih ubat + batch number
    ↓
Pilih jenis: In (increase) atau Out (decrease)
    ↓
Input kuantiti adjustment
    ↓
Pilih sebab: Damaged / Expired / Return to Supplier / Lost / Theft / Correction / Other
    ↓
Input catatan (justification)
    ↓
Jika adjustment > threshold (e.g., > RM500 atau > 100 unit) → Perlu approval Pengurus
    ↓
Selepas approved → Sistem update `stok_ubat.kuantiti_semasa`
    ↓
Rekod audit trail dalam `stock_adjustment`
```

### 6.6 Workflow Stocktake

```
End of month / periodic stocktake
    ↓
Pengurus create Stocktake (status: draft)
    ↓
Sistem generate stocktake items dengan semua stok_ubat (status: available)
    ↓
Sistem populate `system_qty` dari `stok_ubat.kuantiti_semasa`
    ↓
Print stock list untuk physical count
    ↓
Ahli Farmasi/Staff kira stok fizikal
    ↓
Input `physical_qty` untuk setiap item
    ↓
Sistem auto-calculate:
    - variance_qty = physical_qty - system_qty
    - variance_value = variance_qty × harga_kos
    ↓
Sistem calculate `total_variance_value` (sum of all variance_value)
    ↓
Submit stocktake untuk approval
    ↓
Pengurus review variance report
    ↓
Jika variance acceptable → Approve stocktake
    ↓
Sistem auto-adjust stok:
    - Update `stok_ubat.kuantiti_semasa` = physical_qty
    - Create entry dalam `stock_adjustment` untuk setiap variance (sebab: Correction, with reference to stocktake_id)
    ↓
Status stocktake: Approved
```

---

## 7. Keperluan UI/UX

### 7.1 Design System
- **Framework:** Bootstrap 5 + CoreUI
- **Icons:** CoreUI Icons / Font Awesome
- **Color Scheme:** Professional medical color palette (blue/green tones)
- **Responsive:** Mobile-first design (support tablet untuk farmasi counter)

### 7.2 Key Pages & Components

**1. Dashboard Farmasi**
- Summary cards:
  - Pending Prescriptions (count)
  - Low Stock Alerts (count)
  - Expiring Soon (count)
  - Today's Dispensing (count)
- Quick action buttons: New Manual Prescription, View Prescriptions, Check Stock
- Alerts/Notifications panel (low stock, expiring drugs, partial dispense ready)
- Recent dispensing list (last 10)

**2. Senarai Preskripsi**
- Table dengan columns: Kod Preskripsi, Pesakit, Doktor, Tarikh, Status, Actions
- Filter: Status (Pending/Ready/Dispensed/Cancelled), Tarikh, Doktor
- Search: Kod preskripsi, nama pesakit, IC pesakit
- Status badge dengan color coding:
  - Pending Review: badge-warning
  - Ready to Dispense: badge-info
  - Dispensed: badge-success
  - Cancelled: badge-danger
  - Partially Dispensed: badge-secondary
- Actions: View/Review, Dispense, Cancel

**3. Review & Dispensing Page**
- Section 1: Patient Info
  - Nama, IC, Umur, Jantina
  - Alergi ubat (highlight dalam red box jika ada)
  - Button: View PMR
- Section 2: Prescription Details
  - Doktor, Tarikh preskripsi
  - Preskripsi items table:
    - Nama ubat, Dos, Frekuensi, Kuantiti, Status stok (icon: ✓/⚠/✗), Actions
- Section 3: Interaction Alerts (jika ada)
  - Alert box dengan severity color:
    - Contraindicated: danger (red)
    - Major: warning (orange)
    - Moderate: info (yellow)
    - Minor: secondary (grey)
  - Description + Recommendation
  - Button: Override (with justification modal)
- Section 4: Dispensing Form (untuk setiap item)
  - Select batch number (dropdown dengan FEFO suggestion, show expiry date)
  - Input kuantiti dispensing
  - Display selling price + total
  - Button: Dispense / Partial Dispense
- Section 5: Pharmaceutical Care
  - Textarea: Counseling notes
  - Checklist: Counseling points (optional)
- Section 6: Actions
  - Button: Print Label, Finalize & Send to Billing, Cancel Prescription

**4. Drug Database Management**
- Table dengan columns: Kod Ubat, Nama Generik, Brand, Strength, Poison Schedule, Kategori, Stok Semasa, Min Stok, Actions
- Filter: Kategori, Poison Schedule, Status
- Search: Nama ubat, kod ubat
- Actions: Edit, View Stock, View History
- Button: Add New Drug, Import from CSV

**5. Stock Management**
- Tab 1: Current Stock
  - Table: Kod Ubat, Nama Ubat, Batch Number, Tarikh Luput, Kuantiti, Min Stok, Status
  - Color coding:
    - Low stock (kuantiti ≤ min_stok): background-warning
    - Expiring soon (< 3 months): background-info
    - Expired: background-danger
  - Filter: Status (Available/Low/Expired), Kategori, Poison Schedule
  - Actions: Adjust Stock, View History
- Tab 2: Goods Receipt
  - Button: New GRN, New PO
  - Table: Kod GRN, Supplier, Tarikh Terima, Total, Status, Actions
- Tab 3: Stock Adjustment
  - Button: New Adjustment
  - Table: Kod Adjustment, Ubat, Batch, Sebab, Kuantiti, Status, Actions
- Tab 4: Stocktake
  - Button: New Stocktake
  - Table: Kod Stocktake, Tarikh, Status, Total Variance, Actions

**6. Patient Medication Record (PMR)**
- Modal/Separate page
- Section 1: Patient Info + Allergies
- Section 2: Current Medications (active prescriptions)
- Section 3: Medication History
  - Timeline view (chronological)
  - Each entry: Tarikh, Ubat, Dos, Frekuensi, Kuantiti, Pharmacist, Counseling notes
- Section 4: ADR History (if any)

**7. Purchase Order Management**
- Table: Kod PO, Supplier, Tarikh PO, Tarikh Jangka Terima, Jumlah Kos, Status, Actions
- Filter: Status (Draft/Sent/Partial/Fully Received/Cancelled), Tarikh, Supplier
- Actions: View, Edit (jika Draft), Receive Goods, Print, Cancel
- Form: Create/Edit PO
  - Select Supplier
  - Add items (search ubat, input kuantiti order, harga kos)
  - Auto-calculate total
  - Button: Save as Draft, Submit for Approval, Send to Supplier

**8. Reporting**
- Select report type (dropdown)
- Date range picker
- Additional filters (kategori, supplier, pharmacist, etc.)
- Button: Generate Report, Export PDF, Export Excel
- Display report in table/chart format

**9. Poison Register**
- Read-only table (auto-maintained by sistem)
- Columns: Tarikh, Nama Ubat, Batch, Kuantiti Dispensed, Balance Stok, Pesakit (Nama + IC + Alamat), Doktor (Nama + MMC), Ahli Farmasi
- Filter: Tarikh range, Nama ubat
- Button: Print Poison Register (format for inspection)

### 7.3 Interaction Design

**Real-time Stock Check:**
- Apabila ahli farmasi review preskripsi, sistem auto-check stok real-time
- Display stock status icon:
  - ✓ (hijau): Stok mencukupi
  - ⚠ (kuning): Low stock (stok ada tetapi ≤ min_stok)
  - ✗ (merah): Out of stock

**Drug Interaction Alert:**
- Modal popup dengan severity color
- Display interaction description + clinical recommendation
- Options:
  - Contact Doctor (akan update status preskripsi ke "On Hold")
  - Override (dengan justification textarea + doktor approval)
  - Cancel item

**Batch Selection (FEFO):**
- Dropdown dengan batch numbers sorted by expiry date (earliest first)
- Each option format: "Batch: ABC123 | Exp: 2026-06-30 | Qty: 500"
- Highlight batch yang recommended (paling hampir luput tetapi masih > 3 months)

**Label Printing:**
- Preview label sebelum print
- Support print multiple labels (jika dispensing multiple items)
- Label template customizable (optional)

**Counseling Checklist (Optional Enhancement):**
- Pre-defined checklist based on drug category:
  - Antibiotic: "Habiskan dos walaupun dah sihat"
  - Antihypertensive: "Jangan stop sendiri"
  - Insulin: "Cara inject, storage dalam fridge"
  - etc.
- Ahli farmasi tick checklist + add free text notes

---

## 8. Keperluan Keselamatan

### 8.1 PDPA Compliance
- Semua maklumat pesakit (nama, IC, alamat, medication history) adalah confidential
- Access control: Hanya ahli farmasi yang bertugas boleh access PMR
- Audit trail: Log semua akses kepada PMR (siapa, bila, apa)

### 8.2 Role-Based Access Control (RBAC)

**Role: Ahli Farmasi**
- Full access: Review preskripsi, dispensing, stock management, PMR, reports
- Boleh override interaction alert (dengan justification + doktor approval)
- Boleh adjust stok (dengan approval jika > threshold)

**Role: Pengurus Klinik**
- View access: Semua data
- Approval: PO, stock adjustment (> threshold), stocktake
- Full access: Reports

**Role: Admin**
- Full access: Semua modul termasuk drug database management, supplier management

### 8.3 Audit Trail
- Log semua critical actions:
  - Dispensing ubat (siapa, bila, apa ubat, kuantiti, batch)
  - Stock adjustment (siapa, bila, sebab, kuantiti)
  - Interaction alert override (siapa, bila, justifikasi)
  - Void dispensing (siapa, bila, sebab)
  - Access PMR (siapa, bila, pesakit)
- Audit log tidak boleh di-edit atau delete

### 8.4 Data Integrity
- Stok tidak boleh negative (validation)
- Batch number + expiry date compulsory untuk poison schedule drugs
- Dispensing hanya boleh jika preskripsi status "ready_to_dispense"
- Void dispensing auto-restore stok (immutable record)

### 8.5 Akta Racun 1952 Compliance
- Poison Register auto-maintained untuk Group A drugs
- Rekod wajib: Tarikh, nama ubat, kuantiti, balance stok, pesakit details, doktor details, ahli farmasi
- Poison Register tidak boleh di-edit (immutable)
- Support print untuk inspection oleh Kementerian Kesihatan

---

## 9. Keperluan Prestasi

### 9.1 Response Time
- Preskripsi listing page: ≤ 1 saat
- Review preskripsi (with stock check + interaction check): ≤ 2 saat
- Dispensing action: ≤ 1 saat
- Stock listing page: ≤ 1 saat
- Report generation: ≤ 5 saat (untuk monthly report)

### 9.2 Scalability
- Support concurrent dispensing: 5-10 ahli farmasi at the same time
- Database dioptimumkan dengan proper indexing:
  - Index pada `ubat.kod_ubat`, `ubat.nama_generik`
  - Index pada `stok_ubat.ubat_id`, `stok_ubat.batch_number`, `stok_ubat.tarikh_luput`
  - Index pada `preskripsi.status`, `preskripsi.pesakit_id`
  - Index pada `dispensing.tarikh_dispensing`, `dispensing.dispensed_by`
- Caching untuk drug database dan interaction database (jarang berubah)

### 9.3 Data Volume
- Expected drug database: 500-1000 ubat
- Expected stock entries: 2000-5000 batch entries (multiple batches per drug)
- Expected dispensing transactions: 100-500 per day
- Expected PMR entries: 1000-5000 per month

---

## 10. Keperluan Ujian

### 10.1 Unit Testing
- Service methods testing:
  - `DispensingService::checkStockAvailability()`
  - `DispensingService::checkDrugInteraction()`
  - `DispensingService::dispense()`
  - `StokUbatService::deductStock()`
  - `StokUbatService::adjustStock()`
  - `PoisonRegisterService::createPoisonRegisterEntry()`

### 10.2 Feature Testing
- Preskripsi workflow:
  - Test auto-receive preskripsi from EMR
  - Test create manual preskripsi
  - Test review preskripsi
- Dispensing workflow:
  - Test dispensing with sufficient stock
  - Test dispensing with low stock (alert)
  - Test dispensing with out of stock (prevent)
  - Test partial dispensing
  - Test drug-drug interaction alert
  - Test drug-allergy interaction alert
  - Test override interaction alert
  - Test void dispensing (stok restore)
  - Test poison register auto-creation (Group A drugs)
- Stock management:
  - Test goods receipt (auto-create stok_ubat entry)
  - Test stock adjustment (increase/decrease)
  - Test stocktake workflow
  - Test low stock alert
  - Test expiry alert

### 10.3 Integration Testing
- Integration dengan EMR:
  - Test auto-receive preskripsi daripada EMR
  - Test send status update kembali ke EMR (Dispensed/Issue)
- Integration dengan Billing:
  - Test auto-generate bil selepas dispensing
  - Test bil details (ubat nama, kuantiti, harga)

### 10.4 User Acceptance Testing (UAT)
- Ahli Farmasi test full dispensing workflow
- Pengurus test reporting dan stocktake
- Test FEFO batch selection (earliest expiry first)
- Test label printing
- Test poison register compliance

---

## 11. Langkah Implementasi

### Fasa 1: Setup & Drug Database (1 minggu)
- [ ] Setup database schema (13 jadual)
- [ ] Create migrations
- [ ] Create models dengan relationships
- [ ] Seed drug database (import dari MIMS/NDFM atau manual)
- [ ] Seed interaction database (basic interactions)
- [ ] Create supplier dummy data

### Fasa 2: Preskripsi Management (1 minggu)
- [ ] Create `PreskripsiController`, `PreskripsiService`, `PreskripsiRepository`
- [ ] Create preskripsi listing page (with filters)
- [ ] Create manual preskripsi form (input preskripsi luar)
- [ ] Implement auto-receive preskripsi from EMR (integration)
- [ ] Create review preskripsi page (view patient info + items)
- [ ] Unit testing + feature testing

### Fasa 3: Dispensing Workflow - Part 1 (1 minggu)
- [ ] Create `DispensingService` dengan methods:
  - `checkStockAvailability()`
  - `getBatchesForDrug()` (FEFO sorting)
- [ ] Implement stock check real-time pada review page
- [ ] Implement batch selection dropdown (FEFO)
- [ ] Implement dispensing action (create dispensing record + deduct stock)
- [ ] Implement partial dispensing
- [ ] Unit testing + feature testing

### Fasa 4: Dispensing Workflow - Part 2 (Drug Interaction) (1 minggu)
- [ ] Create `DrugInteractionService` dengan methods:
  - `checkDrugDrugInteraction()`
  - `checkDrugAllergyInteraction()`
- [ ] Implement interaction check pada review page
- [ ] Implement interaction alert modal (dengan severity + description)
- [ ] Implement override interaction alert (dengan justification)
- [ ] Create `InteractionAlertOverride` record
- [ ] Unit testing + feature testing

### Fasa 5: PMR & Pharmaceutical Care (0.5 minggu)
- [ ] Create PMR page/modal (view medication history)
- [ ] Auto-create PMR entry selepas dispensing
- [ ] Implement counseling notes textarea
- [ ] Implement counseling checklist (optional)
- [ ] Implement label printing (template + preview)

### Fasa 6: Poison Register & Compliance (0.5 minggu)
- [ ] Create `PoisonRegisterService::createPoisonRegisterEntry()`
- [ ] Auto-trigger poison register entry for Group A drugs after dispensing
- [ ] Create poison register view page (read-only table)
- [ ] Implement print poison register (formatted for inspection)
- [ ] Testing dengan sample Group A drugs

### Fasa 7: Stock Management - Goods Receipt (1 minggu)
- [ ] Create `PurchaseOrderController`, `PurchaseOrderService`
- [ ] Create PO listing + create/edit form
- [ ] Implement PO approval workflow (optional)
- [ ] Create `GoodsReceiptController`, `GoodsReceiptService`
- [ ] Create GRN form (input batch number, expiry, kuantiti)
- [ ] Implement auto-create `stok_ubat` entry after GRN
- [ ] Implement auto-update PO status (partial/fully received)
- [ ] Unit testing + feature testing

### Fasa 8: Stock Management - Adjustment & Stocktake (1 minggu)
- [ ] Create stock adjustment form (reason, kuantiti, approval)
- [ ] Implement auto-update stok after adjustment
- [ ] Create stocktake workflow:
  - Create stocktake (generate items with system_qty)
  - Input physical_qty
  - Calculate variance
  - Approval + auto-adjust stok
- [ ] Implement low stock alert (notification)
- [ ] Implement expiry alert (notification + listing)
- [ ] Unit testing + feature testing

### Fasa 9: Integration dengan EMR & Billing (1 minggu)
- [ ] Implement listener untuk EMR event `PrescriptionFinalized` → auto-create preskripsi
- [ ] Implement event `PrescriptionDispensed` → send to EMR (update status)
- [ ] Implement event `PrescriptionDispensed` → send to Billing (auto-generate bil)
- [ ] Testing integration end-to-end:
  - Doktor finalize preskripsi → Farmasi receive → Dispensing → Billing generate

### Fasa 10: Reporting (1 minggu)
- [ ] Create `ReportService` dengan methods untuk setiap report:
  - `generateSalesReport()`
  - `generateStockReport()`
  - `generateExpiryReport()`
  - `generatePoisonRegisterReport()`
  - `generateDispensingReport()`
  - `generateSupplierReport()`
  - `generateVarianceReport()`
- [ ] Create reporting page (select report type + filters + date range)
- [ ] Implement export to PDF (using DomPDF atau Snappy)
- [ ] Implement export to Excel (using Laravel Excel)
- [ ] Testing semua reports

### Fasa 11: UAT, Bug Fixes & Deployment (1 minggu)
- [ ] UAT dengan ahli farmasi dan pengurus klinik
- [ ] Bug fixes berdasarkan UAT feedback
- [ ] Performance optimization (indexing, caching)
- [ ] Documentation (user manual untuk ahli farmasi)
- [ ] Deployment to production
- [ ] Training untuk ahli farmasi

**Anggaran Masa:** 10.5 minggu (2.5 bulan)

---

## 12. Kriteria Kejayaan

### 12.1 Metrics

1. **Stock Accuracy:**
   - Target: ≥ 98% (system stock vs physical stock after monthly stocktake)
   - Measurement: Variance report

2. **Dispensing Efficiency:**
   - Target: Average dispensing time ≤ 5 minit per preskripsi
   - Measurement: Dispensing timestamp (from review to finalize)

3. **Interaction Alert Detection:**
   - Target: 100% detection untuk contraindicated interactions
   - Measurement: Audit log interaction alerts vs manual pharmacist check

4. **Low Stock Prevention:**
   - Target: Zero stock-out untuk ubat kritikal (high usage drugs)
   - Measurement: Low stock alert effectiveness (reorder before stock-out)

5. **Expiry Waste Reduction:**
   - Target: ≤ 2% ubat luput (by value)
   - Measurement: Expiry report vs total stock value

6. **FEFO Compliance:**
   - Target: ≥ 95% dispensing menggunakan batch dengan earliest expiry
   - Measurement: Dispensing report (batch selected vs earliest available)

7. **Poison Register Compliance:**
   - Target: 100% auto-maintained (no manual entry)
   - Measurement: Poison register audit (completeness + accuracy)

8. **User Satisfaction:**
   - Target: ≥ 80% ahli farmasi berpuas hati dengan sistem
   - Measurement: UAT feedback form + post-implementation survey

### 12.2 Acceptance Criteria

**Preskripsi Management:**
- ✅ Sistem boleh auto-receive preskripsi elektronik daripada EMR
- ✅ Ahli farmasi boleh input preskripsi manual untuk pesakit walk-in
- ✅ Senarai preskripsi dengan filter dan search berfungsi dengan baik
- ✅ Status preskripsi dikemaskini real-time

**Dispensing Workflow:**
- ✅ Stock check real-time berfungsi (Available/Low Stock/Out of Stock)
- ✅ Drug-drug interaction alert muncul dengan betul (severity + description)
- ✅ Drug-allergy interaction alert muncul jika pesakit ada alergi
- ✅ Ahli farmasi boleh override interaction alert dengan justifikasi
- ✅ Batch selection auto-suggest FEFO (earliest expiry first)
- ✅ Stok auto-deduct selepas dispensing
- ✅ Partial dispensing boleh direkod dan fulfill kemudian
- ✅ Label ubat boleh dicetak dengan maklumat lengkap
- ✅ Pharmaceutical care notes boleh direkod

**PMR:**
- ✅ PMR auto-update selepas dispensing
- ✅ Ahli farmasi boleh view medication history pesakit (chronological)
- ✅ Alergi pesakit dipamerkan dengan jelas

**Poison Register:**
- ✅ Poison register auto-maintained untuk Group A drugs
- ✅ Rekod lengkap (tarikh, ubat, kuantiti, balance, pesakit, doktor, ahli farmasi)
- ✅ Poison register boleh dicetak untuk inspection

**Stock Management:**
- ✅ Goods receipt auto-create stok_ubat entry dengan batch number + expiry
- ✅ Low stock alert berfungsi (notification bila ≤ min_stok)
- ✅ Expiry alert berfungsi (notification untuk ubat < 3 bulan expiry)
- ✅ Stock adjustment dengan approval workflow (jika > threshold)
- ✅ Stocktake workflow lengkap (generate items → input physical → variance report → auto-adjust)

**Integration:**
- ✅ Preskripsi daripada EMR diterima dengan betul (all items + patient info)
- ✅ Status preskripsi dihantar kembali ke EMR (Dispensed/Issue)
- ✅ Bil auto-generate di Billing module selepas dispensing (ubat + harga + kuantiti)

**Reporting:**
- ✅ Semua 7 reports boleh generate dengan betul
- ✅ Export to PDF dan Excel berfungsi
- ✅ Report data tepat dan sesuai untuk audit

---

## 13. Risks & Mitigation

### 13.1 Risks

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Drug interaction database tidak lengkap | HIGH | MEDIUM | Import dari sumber terpercaya (MIMS, NDFM); allow ahli farmasi tambah custom interaction; periodic update |
| Stok tidak sync (double dispensing pada masa sama) | HIGH | LOW | Implement database transaction + row locking semasa dispensing; optimistic locking |
| FEFO tidak diikuti (ahli farmasi pilih batch salah) | MEDIUM | MEDIUM | Auto-suggest batch earliest expiry; warning jika pilih batch bukan earliest; audit report FEFO compliance |
| Slow performance bila drug database besar | MEDIUM | LOW | Proper indexing; caching drug database; pagination pada listing |
| Integration dengan EMR fail | HIGH | LOW | Implement retry mechanism; fallback to manual preskripsi input; alert admin jika integration fail |
| Ahli farmasi override interaction alert tanpa sebab kukuh | MEDIUM | MEDIUM | Compulsory justification field; require doktor approval; audit log untuk review |
| Ubat luput tidak dikesan | MEDIUM | LOW | Automated daily job untuk check expiry; email alert kepada pengurus; dashboard highlight expiring drugs |
| Poison register tidak complete (missing entries) | HIGH | LOW | Auto-triggered after dispensing (no manual entry); validation tidak boleh dispensing Group A tanpa patient details; audit check |

### 13.2 Dependencies

**Internal:**
- Modul EMR mesti siap dan stabil (untuk auto-receive preskripsi)
- Modul Billing mesti ada API endpoint untuk receive dispensing data
- Patient database (`pesakit`) mesti ada (dengan IC, alamat, alergi)

**External:**
- Drug database source (MIMS atau NDFM) untuk import data ubat
- Interaction database source untuk import interaction data
- Printer support untuk label printing (thermal printer recommended)

**Technical:**
- Laravel 12 framework
- MySQL 8.0 database
- PHP 8.2+
- Server resources untuk support 5-10 concurrent users

---

## 14. Acceptance Criteria

### 14.1 Functional Acceptance

**Preskripsi Management:**
1. Sistem boleh auto-receive preskripsi elektronik daripada EMR dengan maklumat lengkap (pesakit, doktor, ubat items, dos, frekuensi)
2. Ahli farmasi boleh input preskripsi manual dengan scan atau manual entry
3. Senarai preskripsi memaparkan semua preskripsi dengan filter dan search yang berfungsi
4. Status preskripsi dikemaskini dengan betul (Pending → Ready → Dispensed/Cancelled)

**Dispensing Workflow:**
1. Stock check real-time berfungsi dan display status yang betul (Available/Low/Out of Stock)
2. Drug-drug interaction alert muncul dengan betul bila ada interaction dalam preskripsi
3. Drug-allergy interaction alert muncul bila ubat dalam preskripsi match dengan patient allergy
4. Ahli farmasi boleh override interaction alert dengan input justifikasi dan doktor approval
5. Batch selection auto-suggest FEFO (batch dengan earliest expiry muncul first)
6. Dispensing action auto-deduct stok dengan betul (kuantiti_semasa berkurangan)
7. Partial dispensing boleh direkod dan balance boleh fulfill kemudian
8. Void dispensing auto-restore stok dengan betul
9. Label ubat boleh dicetak dengan maklumat lengkap dan format yang sesuai
10. Pharmaceutical care notes boleh direkod dan disimpan dalam PMR

**PMR & Poison Register:**
1. PMR auto-update selepas setiap dispensing
2. PMR memaparkan medication history chronological dengan maklumat lengkap
3. Poison register auto-create entry untuk Group A drugs selepas dispensing
4. Poison register boleh dicetak dengan format yang sesuai untuk inspection

**Stock Management:**
1. Goods receipt auto-create stok_ubat entry dengan batch number dan expiry date
2. Low stock alert muncul bila stok ≤ minimum level
3. Expiry alert muncul untuk ubat yang akan luput dalam 3 bulan
4. Stock adjustment dengan approval workflow berfungsi
5. Stocktake workflow lengkap (generate → input physical → variance → auto-adjust)

**Integration:**
1. Preskripsi daripada EMR diterima dengan betul (real-time atau near real-time)
2. Status preskripsi dihantar kembali ke EMR (Dispensed/Issue)
3. Bil auto-generate di Billing module dengan data yang betul (ubat, kuantiti, harga)

**Reporting:**
1. Semua 7 reports generate dengan data yang tepat
2. Export to PDF dan Excel berfungsi tanpa error
3. Report performance: ≤ 5 saat untuk monthly report

### 14.2 Non-Functional Acceptance

**Performance:**
- Preskripsi listing page load ≤ 1 saat
- Review preskripsi (with stock + interaction check) ≤ 2 saat
- Dispensing action complete ≤ 1 saat
- Support 5-10 concurrent users tanpa lag

**Security:**
- PDPA compliant: PMR hanya accessible oleh authorized pharmacist
- Audit trail lengkap untuk semua critical actions
- Poison register immutable (tidak boleh edit atau delete)

**Usability:**
- Ahli farmasi boleh complete dispensing dalam ≤ 5 minit (UAT feedback)
- Interface intuitive dan mudah difahami (UAT feedback ≥ 80% satisfied)
- Label printing berfungsi dengan printer thermal atau standard printer

**Reliability:**
- Zero data loss (stok, dispensing, PMR)
- Transaction integrity (stok tidak negative, dispensing tidak double)
- Integration dengan EMR dan Billing resilient (retry mechanism)

---

## 15. Lampiran

### 15.1 Contoh Preskripsi Elektronik (JSON Format from EMR)

```json
{
  "emr_id": 123,
  "pesakit_id": 456,
  "doktor_id": 789,
  "tarikh_preskripsi": "2026-01-13",
  "items": [
    {
      "ubat_id": 101,
      "nama_ubat": "Amoxicillin 500mg",
      "kuantiti": 21,
      "dos": "500mg",
      "frekuensi": "3 kali sehari",
      "tempoh": "7 hari",
      "arahan": "Ambil selepas makan. Habiskan dos walaupun dah sihat."
    },
    {
      "ubat_id": 102,
      "nama_ubat": "Paracetamol 500mg",
      "kuantiti": 30,
      "dos": "500mg",
      "frekuensi": "3-4 kali sehari bila perlu",
      "tempoh": "bila perlu",
      "arahan": "Untuk sakit atau demam. Jangan melebihi 8 tablet sehari."
    }
  ]
}
```

### 15.2 Contoh Drug Interaction Alert

**Scenario:** Pesakit prescribed Warfarin (anticoagulant) + Aspirin (NSAID)

**Interaction:**
- **Severity:** Major
- **Description:** Concurrent use of Warfarin and Aspirin may increase the risk of bleeding due to additive anticoagulant effects.
- **Recommendation:** Monitor INR closely. Consider alternative analgesic (e.g., Paracetamol). If combination necessary, educate patient on bleeding signs (bruising, black stools, etc.).

**Display:**
```
⚠️ MAJOR INTERACTION DETECTED

Warfarin + Aspirin

Risk: Increased bleeding risk (additive anticoagulant effects)

Recommendation:
- Monitor INR closely
- Consider alternative: Paracetamol for pain
- If combination necessary, educate patient on bleeding signs

Actions:
[Contact Doktor] [Override (requires justification)] [Cancel Aspirin]
```

### 15.3 Contoh Poison Register Entry

| Tarikh | Nama Ubat | Batch No. | Kuantiti Dispensed | Balance Stok | Pesakit | IC/Passport | Alamat | Doktor | MMC | Ahli Farmasi |
|--------|-----------|-----------|--------------------|--------------|---------|-----------|---------| -------|----|--------------|
| 2026-01-13 | Codeine Phosphate 30mg (Group A Poison) | BTH-2024-001 | 20 tablets | 480 tablets | Ahmad bin Ali | 850101-01-1234 | No 12, Jalan Merdeka, 50000 KL | Dr. Siti Aminah | MMC 12345 | Nurul Hidayah |
| 2026-01-13 | Morphine Sulphate 10mg (Group A Poison) | BTH-2024-015 | 10 tablets | 190 tablets | Fatimah binti Hassan | 900202-14-5678 | No 45, Jalan Raja, 41000 Klang | Dr. Ahmad Zaki | MMC 67890 | Nurul Hidayah |

### 15.4 Contoh Label Ubat

```
═══════════════════════════════════════
POLIKLINIK AL-HUDA
═══════════════════════════════════════

Pesakit: AHMAD BIN ALI
IC: 850101-01-1234
Tarikh: 13/01/2026

-------------------------------------------
AMOXICILLIN 500MG CAPSULE
-------------------------------------------

Dos: 500mg (1 capsule)
Frekuensi: 3 kali sehari
Tempoh: 7 hari
Kuantiti: 21 capsule

ARAHAN:
Ambil selepas makan.
Habiskan dos walaupun dah sihat.

⚠️ AMARAN:
Elak jika ada alergi Penicillin.
Jangan hentikan ubat tanpa nasihat doktor.

-------------------------------------------
Batch No: BTH-2024-ABC
Expiry Date: 31/12/2027
-------------------------------------------

Dispensed by: Nurul Hidayah (Ahli Farmasi)

═══════════════════════════════════════
```

### 15.5 Entity-Relationship Diagram (Simplified)

```
┌─────────────┐          ┌──────────────┐
│   pesakit   │──────────│  preskripsi  │
└─────────────┘ 1      * └──────────────┘
                              │ 1
                              │
                              │ *
                        ┌──────────────────┐
                        │ preskripsi_item  │
                        └──────────────────┘
                              │ 1
                              │
                              │ *
                        ┌──────────────┐
                        │  dispensing  │──────┐
                        └──────────────┘      │
                              │ *             │ 1
                              │               │
                              │ 1         ┌───────────┐
                        ┌──────────┐      │    PMR    │
                        │   ubat   │      └───────────┘
                        └──────────┘
                              │ 1
                              │
                              │ *
                        ┌─────────────┐
                        │ stok_ubat   │
                        └─────────────┘
                              │ *
                              │
                              │ 1
                        ┌─────────────────┐
                        │ goods_receipt   │
                        └─────────────────┘
                              │ *
                              │
                              │ 1
                        ┌─────────────┐
                        │  supplier   │
                        └─────────────┘
```

**Key Relationships:**
- 1 Pesakit → * Preskripsi
- 1 Preskripsi → * PreskripsiItem
- 1 PreskripsiItem → * Dispensing (support partial dispensing multiple times)
- 1 Ubat → * StokUbat (multiple batches)
- 1 StokUbat → * Dispensing
- 1 Dispensing → 1 PMR entry
- 1 Dispensing → 0..1 PoisonRegister entry (jika Group A poison)
- 1 Supplier → * GoodsReceipt
- 1 GoodsReceipt → * StokUbat

### 15.6 Akta Racun 1952 - Poison Schedule Overview

**Group A Poisons (Kumpulan A):**
- Highly toxic substances requiring strict record-keeping
- Examples: Morphine, Codeine, Strychnine
- **Requirement:** Maintain Poison Register with complete details

**Group B Poisons (Kumpulan B):**
- Moderately toxic substances
- Examples: Some pesticides, industrial chemicals
- **Requirement:** Labeled properly, restricted sale

**Group C Poisons (Kumpulan C):**
- Less toxic but still regulated
- **Requirement:** Proper labeling

**First Schedule Poisons:**
- Cannot be sold without prescription
- Examples: Prescription-only medicines (POM)

**Second Schedule Poisons:**
- Can be sold under pharmacist supervision
- Examples: Some over-the-counter medicines with restrictions

**For this sistem:** Focus pada Group A Poisons (auto-maintain Poison Register), others hanya require proper classification dalam drug database.

### 15.7 FEFO vs FIFO Example

**Scenario:**
- Ubat: Paracetamol 500mg
- Stok tersedia:

| Batch No | Tarikh Terima | Tarikh Luput | Kuantiti |
|----------|---------------|--------------|----------|
| BTH-2024-001 | 2024-06-01 | 2026-06-30 | 500 |
| BTH-2024-015 | 2024-08-15 | 2027-08-31 | 300 |
| BTH-2024-020 | 2024-10-20 | 2026-03-31 | 200 |

**FIFO (First In First Out):**
- Akan pilih BTH-2024-001 (received first)

**FEFO (First Expiry First Out):**
- Akan pilih BTH-2024-020 (expire first: 2026-03-31)

**Sistem ni implement FEFO** untuk minimize ubat luput wastage.

---

**END OF PRD**

---

## Appendix: Change Log

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-13 | System | Initial PRD creation |

