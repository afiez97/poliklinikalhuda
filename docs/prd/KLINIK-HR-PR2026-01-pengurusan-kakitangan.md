# PRD: Sumber Manusia (HR) - Pengurusan Kakitangan

**Kod PRD:** KLINIK-HR-PR2026-01-pengurusan-kakitangan
**Dicipta:** 13 Januari 2026
**Penulis:** AI Assistant
**Dikemaskini:** 13 Januari 2026

---

## 1. Ringkasan Eksekutif

### 1.1 Gambaran Keseluruhan

Modul Sumber Manusia (HR) adalah sistem pengurusan kakitangan bersepadu yang mengurus rekod staf, penjadualan kerja, permohonan cuti, kehadiran, dan pemprosesan gaji untuk Poliklinik Al-Huda. Sistem ini mematuhi Akta Kerja 1955 dan peraturan statutory Malaysia (EPF, SOCSO, EIS, PCB).

### 1.2 Metadata

- **Nama Feature**: Sistem Pengurusan Sumber Manusia Bersepadu
- **Modul**: Sumber Manusia (HR)
- **Submodule**: Pengurusan Kakitangan
- **Peranan Sasaran**: HR, Pengurus, Kakitangan (Self-Service)
- **Keutamaan**: Tinggi
- **Status**: Perancangan
- **Anggaran Usaha**: Besar (16 minggu)

### 1.3 Objektif

- Memusatkan semua data kakitangan dalam satu sistem bersepadu
- Mengautomasi proses penjadualan kerja dan pengurusan shift
- Menyediakan workflow permohonan cuti yang efisien dengan kelulusan berbilang peringkat
- Mengautomasi pengiraan gaji termasuk potongan statutory (EPF, SOCSO, EIS, PCB)
- Mematuhi Akta Kerja 1955 dan peraturan buruh Malaysia
- Menyediakan portal self-service untuk kakitangan
- Menjana laporan HR dan statutory reports secara automatik

### 1.4 Skop

**Dalam Skop:**
- Pengurusan rekod kakitangan (semua jenis: tetap, kontrak, part-time, locum, intern)
- Pengurusan jadual kerja dan shift rotation
- Sistem clock-in/clock-out dengan geo-tagging
- Permohonan dan kelulusan cuti (semua jenis cuti)
- Pemprosesan gaji (payroll) dengan statutory deductions
- Pengurusan dokumen kakitangan
- Penilaian prestasi dengan KPI tracking
- Portal self-service untuk kakitangan
- Integrasi komisyen doktor dengan Billing
- Laporan HR dan statutory reports

**Luar Skop:**
- Recruitment dan talent acquisition
- Learning Management System (LMS)
- Succession planning
- Benefits administration (insurans perubatan, dll)
- Mobile app (fasa seterusnya)

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa

Pengurusan kakitangan di klinik menghadapi masalah data tidak berpusat:
- Rekod kakitangan disimpan dalam fail fizikal dan spreadsheet yang tersebar
- Penjadualan shift dilakukan secara manual dan sering berlaku konflik
- Permohonan cuti melalui kertas dan sukar untuk track baki cuti
- Pengiraan gaji dilakukan secara manual yang memakan masa dan berisiko ralat
- Tiada sistem untuk track kehadiran dengan tepat
- Dokumen penting kakitangan (APC doktor, sijil) tidak dipantau tarikhnya
- Pematuhan Akta Kerja sukar dipastikan tanpa sistem automatik

### 2.2 Impak Kepada Perniagaan

- **Operasi**: Konflik jadual menyebabkan kekurangan staf pada waktu sibuk
- **Kewangan**: Ralat pengiraan gaji menyebabkan pembayaran lebih/kurang
- **Pematuhan**: Risiko penalti kerana tidak patuhi statutory requirements
- **Produktiviti**: HR menghabiskan masa untuk kerja manual berbanding strategic HR
- **Kepuasan Staf**: Proses cuti yang lambat dan payslip yang tidak tepat
- **Risiko**: APC/sijil doktor tamat tempoh tanpa disedari

### 2.3 Hasil Yang Diingini

- Semua data kakitangan dalam satu sistem bersepadu dan mudah diakses
- Penjadualan shift automatik dengan pengesanan konflik
- Proses cuti digital dengan kelulusan dalam masa 24 jam
- Pengiraan gaji automatik dengan zero error rate
- Kehadiran direkod secara digital dengan bukti lokasi
- Peringatan automatik untuk dokumen yang akan tamat tempoh
- Laporan statutory dijana automatik untuk submission

---

## 3. User Stories

### 3.1 User Stories Utama

- **Sebagai** HR, **saya mahu** mendaftar kakitangan baharu dengan semua maklumat lengkap **supaya** rekod pekerja tersusun dan mudah dicari

- **Sebagai** HR, **saya mahu** menyediakan roster shift bulanan untuk semua staf **supaya** operasi klinik berjalan lancar tanpa kekurangan tenaga

- **Sebagai** Pengurus, **saya mahu** meluluskan permohonan cuti staf di bawah saya **supaya** perancangan tenaga kerja dapat dilakukan dengan baik

- **Sebagai** HR, **saya mahu** sistem mengira gaji automatik termasuk EPF, SOCSO, EIS, dan PCB **supaya** pembayaran gaji tepat dan menepati masa

- **Sebagai** Kakitangan, **saya mahu** melihat payslip dan baki cuti saya **supaya** saya boleh merancang kewangan dan cuti dengan lebih baik

- **Sebagai** Kakitangan, **saya mahu** memohon cuti melalui sistem **supaya** proses lebih cepat dan dapat ditrack statusnya

- **Sebagai** Kakitangan, **saya mahu** clock-in dan clock-out melalui sistem **supaya** kehadiran saya direkodkan dengan tepat

- **Sebagai** HR, **saya mahu** menerima peringatan apabila dokumen kakitangan hampir tamat tempoh **supaya** saya dapat menguruskan pembaharuan lebih awal

- **Sebagai** Pengurus, **saya mahu** melihat laporan kehadiran pasukan saya **supaya** saya dapat memantau disiplin dan produktiviti

- **Sebagai** HR, **saya mahu** menjana laporan EPF dan SOCSO untuk submission bulanan **supaya** pematuhan statutory terjamin

- **Sebagai** Doktor, **saya mahu** melihat komisyen saya berdasarkan bil pesakit **supaya** saya dapat memantau pendapatan saya

- **Sebagai** Pengurus, **saya mahu** menilai prestasi staf menggunakan KPI **supaya** penilaian adalah objektif dan boleh diukur

### 3.2 Edge Cases & User Stories Sekunder

- **Sebagai** HR, **bila** staf berhenti kerja sebelum hujung bulan, **saya sepatutnya** dapat mengira gaji pro-rata dan baki cuti yang perlu dibayar

- **Sebagai** HR, **bila** staf memohon cuti melebihi baki, **saya sepatutnya** melihat amaran dan pilihan untuk cuti tanpa gaji

- **Sebagai** Kakitangan, **bila** saya terlupa clock-out, **saya sepatutnya** dapat memohon adjustment dengan kelulusan ketua

- **Sebagai** HR, **bila** staf bekerja overtime, **saya sepatutnya** dapat merekod jam OT dan sistem mengira bayaran mengikut kadar Akta Kerja

- **Sebagai** Pengurus, **bila** ramai staf memohon cuti pada hari yang sama, **saya sepatutnya** melihat amaran kekurangan tenaga

- **Sebagai** HR, **bila** APC doktor tamat tempoh, **saya sepatutnya** dapat menyahaktifkan jadual doktor tersebut sehingga APC diperbaharui

- **Sebagai** Kakitangan, **bila** saya ingin swap shift dengan rakan, **saya sepatutnya** dapat memohon swap melalui sistem dengan kelulusan pengurus

- **Sebagai** HR, **bila** minimum wage dikemaskini, **saya sepatutnya** dapat update gaji pokok staf yang terkesan secara bulk

---

## 4. Keperluan Fungsian

### 4.1 Pengurusan Rekod Kakitangan (FR-100 Series)

- [x] **FR-101:** Sistem mesti membenarkan HR untuk mendaftar kakitangan baharu dengan maklumat peribadi lengkap (nama, IC, alamat, telefon, email, emergency contact)
- [x] **FR-102:** Sistem mesti menyokong pendaftaran untuk semua jenis kakitangan: Tetap, Kontrak, Part-time, Locum, Intern
- [x] **FR-103:** Sistem mesti menyimpan maklumat pekerjaan (jawatan, jabatan, tarikh mula, gaji pokok, elaun, bank account)
- [x] **FR-104:** Sistem mesti menyimpan maklumat kelayakan (kelayakan akademik, sijil profesional, pengalaman)
- [x] **FR-105:** Sistem mesti generate Kod Pekerja automatik dengan format: `EMP-YYYYMMDD-XXXX`
- [x] **FR-106:** Sistem mesti membenarkan upload dan simpan dokumen sokongan (IC, sijil, kontrak, surat tawaran)
- [x] **FR-107:** Sistem mesti merekod sejarah pekerjaan termasuk kenaikan pangkat, pertukaran jabatan, dan perubahan gaji
- [x] **FR-108:** Sistem mesti membenarkan carian dan penapisan kakitangan mengikut jabatan, jawatan, status, jenis
- [x] **FR-109:** Sistem mesti membenarkan HR untuk mengaktif/menyahaktif akaun kakitangan
- [x] **FR-110:** Sistem mesti menyimpan maklumat statutory (no EPF, no SOCSO, no cukai pendapatan)

### 4.2 Pengurusan Jadual Kerja & Shift (FR-200 Series)

- [x] **FR-201:** Sistem mesti membenarkan konfigurasi jenis shift (pagi, petang, malam, split) dengan masa mula dan tamat
- [x] **FR-202:** Sistem mesti membenarkan HR untuk mencipta roster mingguan/bulanan untuk semua staf
- [x] **FR-203:** Sistem mesti mengesan konflik jadual (staf dijadualkan dua shift serentak, cuti bertindih)
- [x] **FR-204:** Sistem mesti membenarkan staf memohon swap shift dengan kelulusan pengurus
- [x] **FR-205:** Sistem mesti merekod overtime (OT) dengan pengiraan mengikut kadar Akta Kerja 1955
- [x] **FR-206:** Sistem mesti memaparkan kalendar jadual untuk setiap staf dan jabatan
- [x] **FR-207:** Sistem mesti mengira jumlah jam kerja per minggu dan memberi amaran jika melebihi had
- [x] **FR-208:** Sistem mesti membenarkan tetapan cuti umum (public holidays) mengikut negeri
- [x] **FR-209:** Sistem mesti auto-generate jadual doktor berdasarkan availability dan had maksimum pesakit

### 4.3 Sistem Kehadiran (FR-300 Series)

- [x] **FR-301:** Sistem mesti membenarkan clock-in/clock-out melalui web dengan geo-tagging (GPS location)
- [x] **FR-302:** Sistem mesti merekod timestamp, koordinat GPS, dan IP address untuk setiap punch
- [x] **FR-303:** Sistem mesti mengira status kehadiran: Hadir, Lewat, Balik Awal, Tidak Hadir
- [x] **FR-304:** Sistem mesti menetapkan grace period untuk lewat (configurable, default 15 minit)
- [x] **FR-305:** Sistem mesti membenarkan staf memohon adjustment kehadiran dengan kelulusan
- [x] **FR-306:** Sistem mesti menjana laporan kehadiran harian, mingguan, dan bulanan
- [x] **FR-307:** Sistem mesti mengira jumlah jam bekerja actual vs scheduled
- [x] **FR-308:** Sistem mesti membenarkan HR untuk override kehadiran dengan justifikasi

### 4.4 Pengurusan Cuti (FR-400 Series)

- [x] **FR-401:** Sistem mesti menyokong jenis cuti: Tahunan, Sakit, Kecemasan, Tanpa Gaji, Bersalin, Paterniti, Cuti Ganti, Cuti Umum
- [x] **FR-402:** Sistem mesti mengira entitlement cuti mengikut tempoh perkhidmatan (Akta Kerja 1955)
- [x] **FR-403:** Sistem mesti membenarkan staf memohon cuti melalui sistem dengan tarikh dan jenis cuti
- [x] **FR-404:** Sistem mesti melaksanakan workflow kelulusan berbilang peringkat: Ketua Unit → HR
- [x] **FR-405:** Sistem mesti mengira dan memaparkan baki cuti real-time
- [x] **FR-406:** Sistem mesti menyekat permohonan cuti jika baki tidak mencukupi (kecuali tanpa gaji)
- [x] **FR-407:** Sistem mesti menghantar notifikasi kepada approver apabila ada permohonan pending
- [x] **FR-408:** Sistem mesti membenarkan lampiran dokumen sokongan (MC untuk cuti sakit)
- [x] **FR-409:** Sistem mesti merekod sejarah cuti untuk rujukan dan laporan
- [x] **FR-410:** Sistem mesti memberi amaran jika ramai staf memohon cuti pada hari yang sama
- [x] **FR-411:** Sistem mesti menyokong carry forward cuti tahunan dengan had maksimum
- [x] **FR-412:** Sistem mesti mengira cuti pro-rata untuk staf baharu dan yang berhenti

### 4.5 Pemprosesan Gaji / Payroll (FR-500 Series)

- [x] **FR-501:** Sistem mesti membenarkan konfigurasi komponen gaji: Gaji Pokok, Elaun (Tetap/Variable), Potongan, Overtime
- [x] **FR-502:** Sistem mesti mengira potongan statutory automatik: EPF (pekerja 11%, majikan 13%), SOCSO, EIS
- [x] **FR-503:** Sistem mesti mengira PCB (Potongan Cukai Bulanan) berdasarkan jadual LHDN
- [x] **FR-504:** Sistem mesti mengira overtime mengikut kadar Akta Kerja: 1.5x (hari biasa), 2x (cuti umum)
- [x] **FR-505:** Sistem mesti membenarkan penambahan bonus, komisyen, dan bayaran ad-hoc
- [x] **FR-506:** Sistem mesti generate payslip untuk setiap staf dengan breakdown lengkap
- [x] **FR-507:** Sistem mesti menyokong payroll cycle: Bulanan atau Bi-weekly
- [x] **FR-508:** Sistem mesti generate bank file untuk pembayaran gaji secara bulk
- [x] **FR-509:** Sistem mesti merekod sejarah payroll untuk audit dan rujukan
- [x] **FR-510:** Sistem mesti mengira gaji pro-rata untuk staf baharu dan yang berhenti
- [x] **FR-511:** Sistem mesti membenarkan HR untuk lock payroll selepas finalize
- [x] **FR-512:** Sistem mesti integrate dengan kehadiran untuk auto-calculate unpaid leave

### 4.6 Komisyen Doktor (FR-600 Series)

- [x] **FR-601:** Sistem mesti integrate dengan modul Billing untuk mendapatkan data bil pesakit
- [x] **FR-602:** Sistem mesti membenarkan konfigurasi formula komisyen per doktor (% atau fixed amount)
- [x] **FR-603:** Sistem mesti mengira komisyen bulanan berdasarkan bil yang telah dibayar
- [x] **FR-604:** Sistem mesti menjana laporan komisyen untuk setiap doktor
- [x] **FR-605:** Sistem mesti include komisyen dalam payroll doktor

### 4.7 Pengurusan Dokumen (FR-700 Series)

- [x] **FR-701:** Sistem mesti membenarkan upload dokumen dengan kategorisasi: IC, Sijil, Kontrak, APC, Lain-lain
- [x] **FR-702:** Sistem mesti merekod tarikh tamat tempoh untuk dokumen yang berkaitan (APC, kontrak)
- [x] **FR-703:** Sistem mesti menghantar peringatan automatik sebelum dokumen tamat tempoh (30, 14, 7 hari)
- [x] **FR-704:** Sistem mesti menyekat jadual doktor jika APC telah tamat
- [x] **FR-705:** Sistem mesti membenarkan versioning dokumen (simpan versi lama apabila update)
- [x] **FR-706:** Sistem mesti memaparkan dashboard dokumen yang akan tamat tempoh

### 4.8 Penilaian Prestasi (FR-800 Series)

- [x] **FR-801:** Sistem mesti membenarkan HR untuk menetapkan KPI per jawatan/individu
- [x] **FR-802:** Sistem mesti membenarkan penilaian prestasi tahunan dengan borang digital
- [x] **FR-803:** Sistem mesti menyokong 360-degree feedback (self, peer, supervisor)
- [x] **FR-804:** Sistem mesti mengira skor prestasi berdasarkan KPI achievement
- [x] **FR-805:** Sistem mesti menjana laporan prestasi untuk review dan increment decision
- [x] **FR-806:** Sistem mesti merekod sejarah penilaian untuk rujukan

### 4.9 Portal Self-Service (FR-900 Series)

- [x] **FR-901:** Sistem mesti membenarkan staf login dan akses portal self-service
- [x] **FR-902:** Sistem mesti memaparkan dashboard peribadi: profil, jadual, baki cuti, payslip terkini
- [x] **FR-903:** Sistem mesti membenarkan staf update maklumat peribadi (telefon, alamat, emergency contact)
- [x] **FR-904:** Sistem mesti membenarkan staf memohon cuti dan track status permohonan
- [x] **FR-905:** Sistem mesti membenarkan staf download payslip bulan semasa dan sebelumnya
- [x] **FR-906:** Sistem mesti memaparkan jadual kerja dan shift staf
- [x] **FR-907:** Sistem mesti membenarkan staf clock-in/clock-out
- [x] **FR-908:** Sistem mesti memaparkan notifikasi dan pengumuman HR

### 4.10 Laporan & Statutory Reports (FR-1000 Series)

- [x] **FR-1001:** Sistem mesti menjana laporan headcount mengikut jabatan, jawatan, jenis pekerjaan
- [x] **FR-1002:** Sistem mesti menjana laporan turnover rate
- [x] **FR-1003:** Sistem mesti menjana laporan kehadiran (attendance summary, late report, absence report)
- [x] **FR-1004:** Sistem mesti menjana laporan cuti (leave balance, leave utilization)
- [x] **FR-1005:** Sistem mesti menjana laporan payroll summary
- [x] **FR-1006:** Sistem mesti menjana Borang A (EPF contribution) untuk submission KWSP
- [x] **FR-1007:** Sistem mesti menjana Borang 8A (SOCSO contribution) untuk submission PERKESO
- [x] **FR-1008:** Sistem mesti menjana Borang E (Annual PCB summary) untuk submission LHDN
- [x] **FR-1009:** Sistem mesti menjana EA Form untuk setiap pekerja
- [x] **FR-1010:** Sistem mesti membenarkan export laporan ke PDF dan Excel

### 4.2 Kebenaran & Kawalan Akses

**Peranan Diperlukan:**
- HR: Akses penuh ke semua fungsi HR
- Pengurus: View dan approve untuk staf di bawahnya
- Kakitangan: Self-service portal sahaja

**Kebenaran Diperlukan:**

| Permission | HR | Pengurus | Kakitangan |
|------------|-----|----------|------------|
| `staff.view` | ✓ (Semua) | ✓ (Bawahan) | ✓ (Diri) |
| `staff.create` | ✓ | ✗ | ✗ |
| `staff.update` | ✓ | ✗ | ✓ (Terhad) |
| `staff.delete` | ✓ | ✗ | ✗ |
| `roster.view` | ✓ | ✓ | ✓ (Diri) |
| `roster.create` | ✓ | ✗ | ✗ |
| `roster.update` | ✓ | ✗ | ✗ |
| `leave.view` | ✓ | ✓ (Bawahan) | ✓ (Diri) |
| `leave.apply` | ✓ | ✓ | ✓ |
| `leave.approve` | ✓ | ✓ | ✗ |
| `attendance.view` | ✓ | ✓ (Bawahan) | ✓ (Diri) |
| `attendance.adjust` | ✓ | ✓ | ✗ |
| `payroll.view` | ✓ | ✗ | ✓ (Diri) |
| `payroll.process` | ✓ | ✗ | ✗ |
| `reports.view` | ✓ | ✓ (Terhad) | ✗ |
| `reports.export` | ✓ | ✗ | ✗ |
| `kpi.view` | ✓ | ✓ | ✓ (Diri) |
| `kpi.evaluate` | ✓ | ✓ | ✗ |
| `documents.view` | ✓ | ✗ | ✓ (Diri) |
| `documents.manage` | ✓ | ✗ | ✗ |

**Authorization Logic:**
- Pengurus hanya boleh lihat dan approve untuk staf dalam jabatan/unit yang sama
- Kakitangan hanya boleh akses data diri sendiri
- Payroll data adalah confidential, hanya HR dan staf sendiri boleh lihat
- Gaji staf lain tidak boleh dilihat oleh sesiapa kecuali HR

### 4.3 Validasi Data

**Field Wajib - Kakitangan:**
- `nama`: Required, string, max 255
- `no_ic`: Required, unique, format MyKad (12 digits)
- `email`: Required, email format, unique
- `no_telefon`: Required, string
- `jenis_pekerja`: Required, enum (tetap, kontrak, part_time, locum, intern)
- `jawatan`: Required, exists in jawatan table
- `jabatan`: Required, exists in jabatan table
- `tarikh_mula`: Required, date
- `gaji_pokok`: Required, numeric, min 1500 (minimum wage)

**Field Wajib - Permohonan Cuti:**
- `jenis_cuti`: Required, enum
- `tarikh_mula`: Required, date, after today
- `tarikh_tamat`: Required, date, after or equal tarikh_mula
- `sebab`: Optional for annual, required for emergency/sick

**Peraturan Validasi:**
- No IC mesti format MyKad yang sah (12 digit)
- Gaji pokok tidak boleh kurang dari minimum wage (RM1,500)
- Tarikh mula kerja tidak boleh di masa hadapan lebih dari 30 hari
- Tarikh cuti tidak boleh overlap dengan cuti sedia ada
- Clock-in location mesti dalam radius yang ditetapkan (jika geo-fencing enabled)

**Peraturan Perniagaan:**
- Tidak boleh delete staf yang masih aktif (deactivate sahaja)
- Tidak boleh approve cuti jika staf tidak mempunyai baki mencukupi
- Tidak boleh jadualkan shift untuk staf yang sedang cuti
- Overtime mesti diluluskan terlebih dahulu sebelum dimasukkan dalam payroll
- APC doktor mesti sah untuk dijadualkan

### 4.4 Audit Trail & PDPA Compliance

- [x] **Adakah feature ini perlu audit trail?** Ya - Kritikal
- **Field Audit**: created_by, updated_by, deleted_at, created_at, updated_at
- **Data Consent**: Ya - Data peribadi staf dilindungi PDPA
- **Data Retention**: Rekod pekerja disimpan 7 tahun selepas tamat perkhidmatan

**Audit Events:**

| Event Category | Events |
|----------------|--------|
| Staff Management | staff_created, staff_updated, staff_deactivated, staff_terminated |
| Leave | leave_applied, leave_approved, leave_rejected, leave_cancelled |
| Attendance | clock_in, clock_out, attendance_adjusted |
| Payroll | payroll_generated, payroll_approved, payroll_locked, payslip_viewed |
| Document | document_uploaded, document_updated, document_expired |
| Performance | kpi_set, evaluation_submitted, evaluation_approved |

---

## 5. Keperluan Teknikal

### 5.1 Teknologi Stack

- **Framework**: Laravel 12
- **Frontend**: Blade Templates + Bootstrap 5 + CoreUI
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze (integrated with main system)
- **File Storage**: Laravel Storage (local/S3) untuk dokumen
- **Queue**: Laravel Queue (database driver) untuk payroll processing
- **Cache**: Redis untuk caching data statutory
- **PDF Generation**: DomPDF / Snappy untuk payslip dan laporan
- **Excel Export**: Maatwebsite/Laravel-Excel
- **Geolocation**: Browser Geolocation API

### 5.2 Arsitektur Aplikasi

```
Route Attributes (dalam Controller)
   ↓
Controller (HTTP Layer)
   ↓
FormRequest (Validation Layer)
   ↓
Service Layer (Business Logic)
   ↓
Repository Layer (Data Access)
   ↓
Model (Eloquent ORM)
   ↓
Database
```

### 5.3 Struktur Modul

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       ├── HR/
│   │       │   ├── StaffController.php
│   │       │   ├── RosterController.php
│   │       │   ├── AttendanceController.php
│   │       │   ├── LeaveController.php
│   │       │   ├── PayrollController.php
│   │       │   ├── DocumentController.php
│   │       │   ├── PerformanceController.php
│   │       │   └── ReportController.php
│   │       └── SelfService/
│   │           └── EmployeePortalController.php
│   ├── Requests/
│   │   ├── StoreStaffRequest.php
│   │   ├── UpdateStaffRequest.php
│   │   ├── StoreLeaveRequest.php
│   │   ├── StoreRosterRequest.php
│   │   ├── StoreAttendanceRequest.php
│   │   └── ProcessPayrollRequest.php
│   └── Middleware/
│       └── EnsureStaffActive.php
├── Services/
│   ├── HR/
│   │   ├── StaffService.php
│   │   ├── RosterService.php
│   │   ├── AttendanceService.php
│   │   ├── LeaveService.php
│   │   ├── PayrollService.php
│   │   ├── CommissionService.php
│   │   ├── DocumentService.php
│   │   ├── PerformanceService.php
│   │   └── StatutoryService.php
│   └── Calculators/
│       ├── EpfCalculator.php
│       ├── SocsoCalculator.php
│       ├── EisCalculator.php
│       ├── PcbCalculator.php
│       └── OvertimeCalculator.php
├── Repositories/
│   ├── StaffRepository.php
│   ├── RosterRepository.php
│   ├── AttendanceRepository.php
│   ├── LeaveRepository.php
│   └── PayrollRepository.php
├── Models/
│   ├── Staff.php
│   ├── StaffDocument.php
│   ├── StaffHistory.php
│   ├── Jabatan.php
│   ├── Jawatan.php
│   ├── Shift.php
│   ├── Roster.php
│   ├── Attendance.php
│   ├── LeaveType.php
│   ├── LeaveEntitlement.php
│   ├── LeaveApplication.php
│   ├── LeaveBalance.php
│   ├── PayrollPeriod.php
│   ├── PayrollItem.php
│   ├── Payslip.php
│   ├── SalaryComponent.php
│   ├── Commission.php
│   ├── Kpi.php
│   ├── PerformanceReview.php
│   └── PublicHoliday.php
├── Notifications/
│   ├── LeaveAppliedNotification.php
│   ├── LeaveApprovedNotification.php
│   ├── LeaveRejectedNotification.php
│   ├── DocumentExpiryNotification.php
│   ├── PayslipReadyNotification.php
│   └── RosterPublishedNotification.php
└── Console/
    └── Commands/
        ├── GenerateMonthlyPayroll.php
        ├── CheckDocumentExpiry.php
        ├── CalculateLeaveBalance.php
        └── SyncAttendance.php

config/
├── hr.php
├── payroll.php
└── statutory.php

resources/
└── views/
    └── admin/
        ├── hr/
        │   ├── staff/
        │   │   ├── index.blade.php
        │   │   ├── create.blade.php
        │   │   ├── edit.blade.php
        │   │   └── show.blade.php
        │   ├── roster/
        │   │   ├── index.blade.php
        │   │   ├── calendar.blade.php
        │   │   └── create.blade.php
        │   ├── attendance/
        │   │   ├── index.blade.php
        │   │   └── report.blade.php
        │   ├── leave/
        │   │   ├── index.blade.php
        │   │   ├── apply.blade.php
        │   │   ├── approve.blade.php
        │   │   └── calendar.blade.php
        │   ├── payroll/
        │   │   ├── index.blade.php
        │   │   ├── process.blade.php
        │   │   └── payslip.blade.php
        │   ├── performance/
        │   │   ├── kpi.blade.php
        │   │   └── review.blade.php
        │   └── reports/
        │       ├── index.blade.php
        │       └── statutory.blade.php
        └── self-service/
            ├── dashboard.blade.php
            ├── profile.blade.php
            ├── leave.blade.php
            ├── payslip.blade.php
            └── attendance.blade.php
```

### 5.4 Database Schema

#### Jadual: `staff`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `user_id` | bigint UNSIGNED NULL | FK → users.id (untuk login) |
| `kod_pekerja` | varchar(20) UNIQUE NOT NULL | EMP-YYYYMMDD-XXXX |
| `nama` | varchar(255) NOT NULL | Nama penuh |
| `no_ic` | varchar(12) UNIQUE NOT NULL | No MyKad |
| `tarikh_lahir` | date NOT NULL | Tarikh lahir |
| `jantina` | enum('L','P') NOT NULL | Lelaki/Perempuan |
| `status_perkahwinan` | enum NOT NULL | Bujang/Berkahwin/Duda/Janda |
| `alamat` | text NOT NULL | Alamat penuh |
| `poskod` | varchar(10) NOT NULL | Poskod |
| `bandar` | varchar(100) NOT NULL | Bandar |
| `negeri` | varchar(50) NOT NULL | Negeri |
| `no_telefon` | varchar(20) NOT NULL | No telefon |
| `email` | varchar(255) UNIQUE NOT NULL | Email |
| `emergency_contact_nama` | varchar(255) NOT NULL | Nama emergency contact |
| `emergency_contact_telefon` | varchar(20) NOT NULL | No telefon emergency |
| `emergency_contact_hubungan` | varchar(50) NOT NULL | Hubungan |
| `jenis_pekerja` | enum NOT NULL | tetap/kontrak/part_time/locum/intern |
| `jabatan_id` | bigint UNSIGNED NOT NULL | FK → jabatan.id |
| `jawatan_id` | bigint UNSIGNED NOT NULL | FK → jawatan.id |
| `ketua_id` | bigint UNSIGNED NULL | FK → staff.id (reporting line) |
| `tarikh_mula` | date NOT NULL | Tarikh mula kerja |
| `tarikh_tamat_kontrak` | date NULL | Tarikh tamat kontrak |
| `tarikh_pengesahan` | date NULL | Tarikh pengesahan jawatan |
| `tarikh_berhenti` | date NULL | Tarikh berhenti |
| `sebab_berhenti` | varchar(255) NULL | Sebab berhenti |
| `gaji_pokok` | decimal(10,2) NOT NULL | Gaji pokok |
| `no_akaun_bank` | varchar(20) NOT NULL | No akaun bank |
| `nama_bank` | varchar(100) NOT NULL | Nama bank |
| `no_epf` | varchar(20) NULL | No KWSP |
| `no_socso` | varchar(20) NULL | No PERKESO |
| `no_eis` | varchar(20) NULL | No EIS |
| `no_cukai` | varchar(20) NULL | No cukai pendapatan |
| `is_active` | boolean DEFAULT true | Status aktif |
| `gambar` | varchar(255) NULL | Path to photo |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `updated_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |
| `deleted_at` | timestamp NULL | Soft delete |

**Indexes:**
- `idx_staff_kod` on `kod_pekerja`
- `idx_staff_ic` on `no_ic`
- `idx_staff_jabatan` on `jabatan_id`
- `idx_staff_jawatan` on `jawatan_id`
- `idx_staff_active` on `is_active`

#### Jadual: `jabatan`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod` | varchar(10) UNIQUE NOT NULL | Kod jabatan |
| `nama` | varchar(100) NOT NULL | Nama jabatan |
| `deskripsi` | text NULL | Deskripsi |
| `ketua_id` | bigint UNSIGNED NULL | FK → staff.id (ketua jabatan) |
| `is_active` | boolean DEFAULT true | Status aktif |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `jawatan`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod` | varchar(10) UNIQUE NOT NULL | Kod jawatan |
| `nama` | varchar(100) NOT NULL | Nama jawatan |
| `gred` | varchar(10) NULL | Gred jawatan |
| `gaji_min` | decimal(10,2) NULL | Gaji minimum |
| `gaji_max` | decimal(10,2) NULL | Gaji maksimum |
| `is_active` | boolean DEFAULT true | Status aktif |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `shifts`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod` | varchar(10) UNIQUE NOT NULL | Kod shift |
| `nama` | varchar(50) NOT NULL | Nama shift (Pagi, Petang, Malam) |
| `masa_mula` | time NOT NULL | Masa mula |
| `masa_tamat` | time NOT NULL | Masa tamat |
| `jam_kerja` | decimal(4,2) NOT NULL | Jumlah jam kerja |
| `is_active` | boolean DEFAULT true | Status aktif |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `rosters`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `shift_id` | bigint UNSIGNED NOT NULL | FK → shifts.id |
| `tarikh` | date NOT NULL | Tarikh roster |
| `status` | enum NOT NULL | scheduled/confirmed/cancelled |
| `nota` | text NULL | Nota tambahan |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `updated_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_roster_staff_date` UNIQUE on `staff_id`, `tarikh`
- `idx_roster_date` on `tarikh`

#### Jadual: `attendances`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `tarikh` | date NOT NULL | Tarikh kehadiran |
| `clock_in` | datetime NULL | Masa clock in |
| `clock_in_latitude` | decimal(10,8) NULL | Latitude clock in |
| `clock_in_longitude` | decimal(11,8) NULL | Longitude clock in |
| `clock_in_ip` | varchar(45) NULL | IP address clock in |
| `clock_out` | datetime NULL | Masa clock out |
| `clock_out_latitude` | decimal(10,8) NULL | Latitude clock out |
| `clock_out_longitude` | decimal(11,8) NULL | Longitude clock out |
| `clock_out_ip` | varchar(45) NULL | IP address clock out |
| `jam_kerja` | decimal(4,2) NULL | Jumlah jam kerja |
| `jam_overtime` | decimal(4,2) DEFAULT 0 | Jumlah jam OT |
| `status` | enum NOT NULL | hadir/lewat/balik_awal/tidak_hadir/cuti |
| `lewat_minit` | int DEFAULT 0 | Minit lewat |
| `balik_awal_minit` | int DEFAULT 0 | Minit balik awal |
| `is_adjusted` | boolean DEFAULT false | Adjusted by HR |
| `adjusted_by` | bigint UNSIGNED NULL | FK → users.id |
| `adjustment_reason` | text NULL | Sebab adjustment |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_attendance_staff_date` UNIQUE on `staff_id`, `tarikh`
- `idx_attendance_date` on `tarikh`
- `idx_attendance_status` on `status`

#### Jadual: `leave_types`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod` | varchar(10) UNIQUE NOT NULL | Kod cuti |
| `nama` | varchar(50) NOT NULL | Nama jenis cuti |
| `is_paid` | boolean DEFAULT true | Cuti bergaji |
| `requires_document` | boolean DEFAULT false | Perlu dokumen sokongan |
| `max_consecutive_days` | int NULL | Max hari berturut-turut |
| `is_active` | boolean DEFAULT true | Status aktif |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `leave_entitlements`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `leave_type_id` | bigint UNSIGNED NOT NULL | FK → leave_types.id |
| `tahun_perkhidmatan_min` | int NOT NULL | Tahun perkhidmatan minimum |
| `tahun_perkhidmatan_max` | int NULL | Tahun perkhidmatan maksimum |
| `hari_entitlement` | int NOT NULL | Hari entitlement |
| `jenis_pekerja` | enum NULL | Jenis pekerja (null = semua) |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `leave_balances`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `leave_type_id` | bigint UNSIGNED NOT NULL | FK → leave_types.id |
| `tahun` | year NOT NULL | Tahun |
| `entitlement` | decimal(4,1) NOT NULL | Hari entitlement |
| `brought_forward` | decimal(4,1) DEFAULT 0 | Baki bawa ke hadapan |
| `used` | decimal(4,1) DEFAULT 0 | Hari digunakan |
| `pending` | decimal(4,1) DEFAULT 0 | Hari pending approval |
| `balance` | decimal(4,1) NOT NULL | Baki semasa |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_leave_balance_staff_type_year` UNIQUE on `staff_id`, `leave_type_id`, `tahun`

#### Jadual: `leave_applications`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `no_permohonan` | varchar(20) UNIQUE NOT NULL | LV-YYYYMMDD-XXXX |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `leave_type_id` | bigint UNSIGNED NOT NULL | FK → leave_types.id |
| `tarikh_mula` | date NOT NULL | Tarikh mula cuti |
| `tarikh_tamat` | date NOT NULL | Tarikh tamat cuti |
| `jumlah_hari` | decimal(4,1) NOT NULL | Jumlah hari cuti |
| `sebab` | text NULL | Sebab cuti |
| `dokumen` | varchar(255) NULL | Path to supporting document |
| `status` | enum NOT NULL | pending/approved/rejected/cancelled |
| `approver_1_id` | bigint UNSIGNED NULL | FK → staff.id (Ketua) |
| `approver_1_status` | enum NULL | pending/approved/rejected |
| `approver_1_date` | datetime NULL | Tarikh kelulusan |
| `approver_1_remarks` | text NULL | Remarks kelulusan |
| `approver_2_id` | bigint UNSIGNED NULL | FK → staff.id (HR) |
| `approver_2_status` | enum NULL | pending/approved/rejected |
| `approver_2_date` | datetime NULL | Tarikh kelulusan |
| `approver_2_remarks` | text NULL | Remarks kelulusan |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_leave_app_staff` on `staff_id`
- `idx_leave_app_status` on `status`
- `idx_leave_app_date` on `tarikh_mula`, `tarikh_tamat`

#### Jadual: `payroll_periods`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod` | varchar(20) UNIQUE NOT NULL | PAY-YYYY-MM |
| `bulan` | int NOT NULL | Bulan (1-12) |
| `tahun` | year NOT NULL | Tahun |
| `tarikh_mula` | date NOT NULL | Tarikh mula period |
| `tarikh_tamat` | date NOT NULL | Tarikh tamat period |
| `tarikh_bayar` | date NOT NULL | Tarikh pembayaran |
| `status` | enum NOT NULL | draft/processing/finalized/paid |
| `jumlah_staf` | int DEFAULT 0 | Jumlah staf |
| `jumlah_gaji_kasar` | decimal(12,2) DEFAULT 0 | Jumlah gaji kasar |
| `jumlah_potongan` | decimal(12,2) DEFAULT 0 | Jumlah potongan |
| `jumlah_gaji_bersih` | decimal(12,2) DEFAULT 0 | Jumlah gaji bersih |
| `finalized_by` | bigint UNSIGNED NULL | FK → users.id |
| `finalized_at` | datetime NULL | Tarikh finalized |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `payslips`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `payroll_period_id` | bigint UNSIGNED NOT NULL | FK → payroll_periods.id |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `gaji_pokok` | decimal(10,2) NOT NULL | Gaji pokok |
| `hari_bekerja` | int NOT NULL | Hari bekerja |
| `hari_tidak_hadir` | int DEFAULT 0 | Hari tidak hadir |
| `jam_overtime` | decimal(5,2) DEFAULT 0 | Jam overtime |
| `jumlah_elaun` | decimal(10,2) DEFAULT 0 | Jumlah elaun |
| `jumlah_overtime` | decimal(10,2) DEFAULT 0 | Bayaran overtime |
| `jumlah_komisyen` | decimal(10,2) DEFAULT 0 | Komisyen (doktor) |
| `jumlah_bonus` | decimal(10,2) DEFAULT 0 | Bonus |
| `gaji_kasar` | decimal(10,2) NOT NULL | Gaji kasar |
| `potongan_epf_pekerja` | decimal(10,2) DEFAULT 0 | EPF pekerja (11%) |
| `potongan_socso_pekerja` | decimal(10,2) DEFAULT 0 | SOCSO pekerja |
| `potongan_eis_pekerja` | decimal(10,2) DEFAULT 0 | EIS pekerja |
| `potongan_pcb` | decimal(10,2) DEFAULT 0 | PCB |
| `potongan_lain` | decimal(10,2) DEFAULT 0 | Potongan lain |
| `jumlah_potongan` | decimal(10,2) NOT NULL | Jumlah potongan |
| `gaji_bersih` | decimal(10,2) NOT NULL | Gaji bersih |
| `caruman_epf_majikan` | decimal(10,2) DEFAULT 0 | EPF majikan (13%) |
| `caruman_socso_majikan` | decimal(10,2) DEFAULT 0 | SOCSO majikan |
| `caruman_eis_majikan` | decimal(10,2) DEFAULT 0 | EIS majikan |
| `kos_majikan` | decimal(10,2) NOT NULL | Jumlah kos majikan |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_payslip_period_staff` UNIQUE on `payroll_period_id`, `staff_id`

#### Jadual: `payslip_items`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `payslip_id` | bigint UNSIGNED NOT NULL | FK → payslips.id |
| `jenis` | enum NOT NULL | elaun/potongan/overtime/bonus/komisyen |
| `kod` | varchar(20) NOT NULL | Kod item |
| `nama` | varchar(100) NOT NULL | Nama item |
| `amaun` | decimal(10,2) NOT NULL | Amaun |
| `nota` | text NULL | Nota tambahan |
| `created_at` | timestamp | Created timestamp |

#### Jadual: `salary_components`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod` | varchar(20) UNIQUE NOT NULL | Kod komponen |
| `nama` | varchar(100) NOT NULL | Nama komponen |
| `jenis` | enum NOT NULL | elaun/potongan |
| `kategori` | varchar(50) NOT NULL | Kategori (tetap/variable) |
| `amaun_default` | decimal(10,2) NULL | Amaun default |
| `is_taxable` | boolean DEFAULT true | Subject to PCB |
| `is_epf_applicable` | boolean DEFAULT true | Subject to EPF |
| `is_socso_applicable` | boolean DEFAULT true | Subject to SOCSO |
| `is_active` | boolean DEFAULT true | Status aktif |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `staff_salary_components`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `salary_component_id` | bigint UNSIGNED NOT NULL | FK → salary_components.id |
| `amaun` | decimal(10,2) NOT NULL | Amaun |
| `tarikh_mula` | date NOT NULL | Tarikh mula |
| `tarikh_tamat` | date NULL | Tarikh tamat |
| `is_active` | boolean DEFAULT true | Status aktif |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `staff_documents`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `jenis` | enum NOT NULL | ic/sijil/kontrak/apc/surat_tawaran/lain |
| `nama` | varchar(255) NOT NULL | Nama dokumen |
| `file_path` | varchar(500) NOT NULL | Path to file |
| `file_size` | int NOT NULL | File size in bytes |
| `mime_type` | varchar(100) NOT NULL | MIME type |
| `tarikh_tamat` | date NULL | Tarikh tamat tempoh |
| `is_verified` | boolean DEFAULT false | Disahkan oleh HR |
| `verified_by` | bigint UNSIGNED NULL | FK → users.id |
| `verified_at` | datetime NULL | Tarikh pengesahan |
| `nota` | text NULL | Nota |
| `version` | int DEFAULT 1 | Version number |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_staff_doc_staff` on `staff_id`
- `idx_staff_doc_expiry` on `tarikh_tamat`

#### Jadual: `commissions`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id (doktor) |
| `bulan` | int NOT NULL | Bulan |
| `tahun` | year NOT NULL | Tahun |
| `jumlah_bil` | int DEFAULT 0 | Jumlah bil |
| `jumlah_kutipan` | decimal(12,2) DEFAULT 0 | Jumlah kutipan |
| `kadar_komisyen` | decimal(5,2) NOT NULL | Kadar komisyen (%) |
| `jumlah_komisyen` | decimal(10,2) NOT NULL | Jumlah komisyen |
| `status` | enum NOT NULL | draft/approved/paid |
| `approved_by` | bigint UNSIGNED NULL | FK → users.id |
| `approved_at` | datetime NULL | Tarikh kelulusan |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_commission_staff_period` UNIQUE on `staff_id`, `bulan`, `tahun`

#### Jadual: `kpis`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `tahun` | year NOT NULL | Tahun |
| `staff_id` | bigint UNSIGNED NULL | FK → staff.id (null = template) |
| `jawatan_id` | bigint UNSIGNED NULL | FK → jawatan.id (untuk template) |
| `nama` | varchar(255) NOT NULL | Nama KPI |
| `deskripsi` | text NULL | Deskripsi |
| `sasaran` | decimal(10,2) NOT NULL | Sasaran/target |
| `unit` | varchar(50) NOT NULL | Unit (%, count, RM) |
| `berat` | decimal(5,2) NOT NULL | Weightage (%) |
| `pencapaian` | decimal(10,2) NULL | Pencapaian actual |
| `skor` | decimal(5,2) NULL | Skor (pencapaian/sasaran * berat) |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

#### Jadual: `performance_reviews`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `tahun` | year NOT NULL | Tahun penilaian |
| `tempoh` | enum NOT NULL | mid_year/year_end |
| `skor_kpi` | decimal(5,2) NULL | Skor KPI |
| `skor_kompetensi` | decimal(5,2) NULL | Skor kompetensi |
| `skor_keseluruhan` | decimal(5,2) NULL | Skor keseluruhan |
| `gred` | varchar(5) NULL | Gred (A/B/C/D/E) |
| `kekuatan` | text NULL | Kekuatan |
| `penambahbaikan` | text NULL | Cadangan penambahbaikan |
| `komentar_staf` | text NULL | Komentar staf |
| `komentar_penilai` | text NULL | Komentar penilai |
| `penilai_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `status` | enum NOT NULL | draft/submitted/acknowledged |
| `submitted_at` | datetime NULL | Tarikh submit |
| `acknowledged_at` | datetime NULL | Tarikh acknowledged |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_performance_staff_year` UNIQUE on `staff_id`, `tahun`, `tempoh`

#### Jadual: `public_holidays`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `tarikh` | date NOT NULL | Tarikh cuti umum |
| `nama` | varchar(100) NOT NULL | Nama cuti |
| `negeri` | varchar(50) NULL | Negeri (null = seluruh Malaysia) |
| `is_replacement` | boolean DEFAULT false | Cuti ganti |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_public_holiday_date` on `tarikh`

#### Jadual: `staff_histories`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `staff_id` | bigint UNSIGNED NOT NULL | FK → staff.id |
| `jenis` | enum NOT NULL | promotion/transfer/salary_change/status_change |
| `tarikh_efektif` | date NOT NULL | Tarikh efektif |
| `nilai_lama` | json NOT NULL | Nilai sebelum |
| `nilai_baru` | json NOT NULL | Nilai selepas |
| `sebab` | text NULL | Sebab perubahan |
| `dokumen` | varchar(255) NULL | Supporting document |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |

### 5.5 Model Eloquent

#### Model: `Staff`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'kod_pekerja',
        'nama',
        'no_ic',
        'tarikh_lahir',
        'jantina',
        'status_perkahwinan',
        'alamat',
        'poskod',
        'bandar',
        'negeri',
        'no_telefon',
        'email',
        'emergency_contact_nama',
        'emergency_contact_telefon',
        'emergency_contact_hubungan',
        'jenis_pekerja',
        'jabatan_id',
        'jawatan_id',
        'ketua_id',
        'tarikh_mula',
        'tarikh_tamat_kontrak',
        'tarikh_pengesahan',
        'tarikh_berhenti',
        'sebab_berhenti',
        'gaji_pokok',
        'no_akaun_bank',
        'nama_bank',
        'no_epf',
        'no_socso',
        'no_eis',
        'no_cukai',
        'is_active',
        'gambar',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tarikh_lahir' => 'date',
        'tarikh_mula' => 'date',
        'tarikh_tamat_kontrak' => 'date',
        'tarikh_pengesahan' => 'date',
        'tarikh_berhenti' => 'date',
        'gaji_pokok' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'gaji_pokok',
        'no_akaun_bank',
        'no_epf',
        'no_socso',
        'no_cukai',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function jawatan()
    {
        return $this->belongsTo(Jawatan::class);
    }

    public function ketua()
    {
        return $this->belongsTo(Staff::class, 'ketua_id');
    }

    public function bawahan()
    {
        return $this->hasMany(Staff::class, 'ketua_id');
    }

    public function documents()
    {
        return $this->hasMany(StaffDocument::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function rosters()
    {
        return $this->hasMany(Roster::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function salaryComponents()
    {
        return $this->hasMany(StaffSalaryComponent::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class);
    }

    public function histories()
    {
        return $this->hasMany(StaffHistory::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByJabatan($query, $jabatanId)
    {
        return $query->where('jabatan_id', $jabatanId);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_pekerja', $jenis);
    }

    // Accessors
    public function getTempohPerkhidmatanAttribute()
    {
        $start = $this->tarikh_mula;
        $end = $this->tarikh_berhenti ?? now();
        return $start->diffInYears($end);
    }

    public function getUmurAttribute()
    {
        return $this->tarikh_lahir->age;
    }

    public function getIsDoktorAttribute()
    {
        return str_contains(strtolower($this->jawatan->nama ?? ''), 'doktor');
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            if (empty($staff->kod_pekerja)) {
                $staff->kod_pekerja = static::generateKodPekerja();
            }
        });
    }

    public static function generateKodPekerja()
    {
        $prefix = 'EMP-' . date('Ymd') . '-';
        $lastStaff = static::where('kod_pekerja', 'like', $prefix . '%')
            ->orderBy('kod_pekerja', 'desc')
            ->first();

        if ($lastStaff) {
            $lastNumber = (int) substr($lastStaff->kod_pekerja, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

#### Model: `LeaveApplication`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $table = 'leave_applications';

    protected $fillable = [
        'no_permohonan',
        'staff_id',
        'leave_type_id',
        'tarikh_mula',
        'tarikh_tamat',
        'jumlah_hari',
        'sebab',
        'dokumen',
        'status',
        'approver_1_id',
        'approver_1_status',
        'approver_1_date',
        'approver_1_remarks',
        'approver_2_id',
        'approver_2_status',
        'approver_2_date',
        'approver_2_remarks',
        'created_by',
    ];

    protected $casts = [
        'tarikh_mula' => 'date',
        'tarikh_tamat' => 'date',
        'jumlah_hari' => 'decimal:1',
        'approver_1_date' => 'datetime',
        'approver_2_date' => 'datetime',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver1()
    {
        return $this->belongsTo(Staff::class, 'approver_1_id');
    }

    public function approver2()
    {
        return $this->belongsTo(Staff::class, 'approver_2_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tarikh_mula', [$startDate, $endDate])
              ->orWhereBetween('tarikh_tamat', [$startDate, $endDate]);
        });
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'approved' => '<span class="badge bg-success">Diluluskan</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'cancelled' => '<span class="badge bg-secondary">Dibatalkan</span>',
            default => $this->status,
        };
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leave) {
            if (empty($leave->no_permohonan)) {
                $leave->no_permohonan = static::generateNoPermohonan();
            }
        });
    }

    public static function generateNoPermohonan()
    {
        $prefix = 'LV-' . date('Ymd') . '-';
        $last = static::where('no_permohonan', 'like', $prefix . '%')
            ->orderBy('no_permohonan', 'desc')
            ->first();

        $newNumber = $last ? ((int) substr($last->no_permohonan, -4) + 1) : 1;

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
```

### 5.6 Configuration Files

**File: `config/hr.php`**

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Staff Configuration
    |--------------------------------------------------------------------------
    */
    'staff' => [
        'kod_prefix' => 'EMP',
        'jenis_pekerja' => ['tetap', 'kontrak', 'part_time', 'locum', 'intern'],
        'status_perkahwinan' => ['bujang', 'berkahwin', 'duda', 'janda'],

        'jenis_labels' => [
            'tetap' => 'Tetap',
            'kontrak' => 'Kontrak',
            'part_time' => 'Part-Time',
            'locum' => 'Locum',
            'intern' => 'Intern',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Attendance Configuration
    |--------------------------------------------------------------------------
    */
    'attendance' => [
        'grace_period_minutes' => 15,
        'late_threshold_minutes' => 30,
        'geo_fence_enabled' => true,
        'geo_fence_radius_meters' => 100,
        'clinic_latitude' => 3.1390, // Example: KL coordinates
        'clinic_longitude' => 101.6869,
    ],

    /*
    |--------------------------------------------------------------------------
    | Leave Configuration
    |--------------------------------------------------------------------------
    */
    'leave' => [
        'carry_forward_max_days' => 5,
        'advance_booking_days' => 30,
        'min_notice_days' => 3,

        'default_entitlements' => [
            // Akta Kerja 1955 minimum
            ['years_min' => 0, 'years_max' => 2, 'days' => 8],
            ['years_min' => 2, 'years_max' => 5, 'days' => 12],
            ['years_min' => 5, 'years_max' => null, 'days' => 16],
        ],

        'sick_leave_entitlement' => [
            ['years_min' => 0, 'years_max' => 2, 'days' => 14],
            ['years_min' => 2, 'years_max' => 5, 'days' => 18],
            ['years_min' => 5, 'years_max' => null, 'days' => 22],
        ],

        'maternity_days' => 98, // 14 weeks as per EA 1955 (amended 2022)
        'paternity_days' => 7,
    ],

    /*
    |--------------------------------------------------------------------------
    | Document Configuration
    |--------------------------------------------------------------------------
    */
    'documents' => [
        'types' => ['ic', 'sijil', 'kontrak', 'apc', 'surat_tawaran', 'lain'],
        'max_size_mb' => 10,
        'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],

        'expiry_reminder_days' => [30, 14, 7],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Review Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'grading_scale' => [
            'A' => ['min' => 90, 'label' => 'Cemerlang'],
            'B' => ['min' => 75, 'label' => 'Baik'],
            'C' => ['min' => 60, 'label' => 'Memuaskan'],
            'D' => ['min' => 40, 'label' => 'Perlu Penambahbaikan'],
            'E' => ['min' => 0, 'label' => 'Tidak Memuaskan'],
        ],
    ],
];
```

**File: `config/payroll.php`**

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payroll Configuration
    |--------------------------------------------------------------------------
    */
    'cycle' => 'monthly', // monthly or bi-weekly
    'pay_day' => 28, // Day of month for payment

    /*
    |--------------------------------------------------------------------------
    | Minimum Wage (as per Malaysia law)
    |--------------------------------------------------------------------------
    */
    'minimum_wage' => 1500.00, // RM1,500 effective 1 May 2022

    /*
    |--------------------------------------------------------------------------
    | Overtime Rates (Akta Kerja 1955)
    |--------------------------------------------------------------------------
    */
    'overtime' => [
        'normal_day' => 1.5,      // 1.5x hourly rate
        'rest_day' => 2.0,        // 2x hourly rate
        'public_holiday' => 3.0,  // 3x hourly rate
        'max_hours_per_month' => 104,
    ],

    /*
    |--------------------------------------------------------------------------
    | Working Hours
    |--------------------------------------------------------------------------
    */
    'working_hours' => [
        'per_day' => 8,
        'per_week' => 45, // Reduced from 48 to 45 (EA Amendment 2022)
    ],

    /*
    |--------------------------------------------------------------------------
    | Commission Configuration
    |--------------------------------------------------------------------------
    */
    'commission' => [
        'default_rate' => 30.00, // 30% default for doctors
        'calculation_basis' => 'collected', // 'billed' or 'collected'
    ],

    /*
    |--------------------------------------------------------------------------
    | Bank File Format
    |--------------------------------------------------------------------------
    */
    'bank_file' => [
        'format' => 'maybank', // maybank, cimb, hlb, etc.
        'delimiter' => ',',
    ],
];
```

**File: `config/statutory.php`**

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | EPF / KWSP Configuration
    |--------------------------------------------------------------------------
    | Reference: KWSP Jadual Ketiga
    |--------------------------------------------------------------------------
    */
    'epf' => [
        'enabled' => true,

        // Employee contribution rates
        'employee_rate' => [
            ['age_max' => 60, 'rate' => 11.00],
            ['age_max' => null, 'rate' => 0.00], // Optional after 60
        ],

        // Employer contribution rates
        'employer_rate' => [
            ['wage_max' => 5000, 'age_max' => 60, 'rate' => 13.00],
            ['wage_max' => null, 'age_max' => 60, 'rate' => 12.00],
            ['wage_max' => null, 'age_max' => null, 'rate' => 4.00], // After 60
        ],

        // Maximum monthly salary for EPF calculation
        'max_salary' => null, // No cap

        // Submission deadline
        'submission_deadline_day' => 15, // 15th of following month
    ],

    /*
    |--------------------------------------------------------------------------
    | SOCSO / PERKESO Configuration
    |--------------------------------------------------------------------------
    | Reference: Jadual Caruman PERKESO
    |--------------------------------------------------------------------------
    */
    'socso' => [
        'enabled' => true,

        // Applicable to employees earning up to RM4,000
        'max_salary' => 4000.00,

        // Contribution rates (percentage of wages)
        'employee_rate' => 0.5,  // 0.5% Category 1
        'employer_rate' => 1.75, // 1.75% Category 1

        // Category 2 (Employment Injury only) - for employees > 60 years
        'category_2_employer_rate' => 1.25,

        // Age limit
        'age_limit' => 60, // After 60, Category 2 only

        // Submission deadline
        'submission_deadline_day' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | EIS Configuration
    |--------------------------------------------------------------------------
    | Employment Insurance System (SIP)
    |--------------------------------------------------------------------------
    */
    'eis' => [
        'enabled' => true,

        // Applicable to employees earning up to RM4,000
        'max_salary' => 4000.00,

        // Contribution rates
        'employee_rate' => 0.2, // 0.2%
        'employer_rate' => 0.2, // 0.2%

        // Age limit
        'age_limit' => 60,

        // Submission deadline
        'submission_deadline_day' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | PCB / MTD Configuration
    |--------------------------------------------------------------------------
    | Potongan Cukai Bulanan / Monthly Tax Deduction
    |--------------------------------------------------------------------------
    */
    'pcb' => [
        'enabled' => true,

        // Tax relief for PCB calculation
        'individual_relief' => 9000.00,
        'epf_relief_max' => 4000.00,
        'socso_relief_max' => 250.00,
        'eis_relief_max' => 250.00,

        // Submission deadline
        'submission_deadline_day' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | HRDF Configuration (Optional)
    |--------------------------------------------------------------------------
    */
    'hrdf' => [
        'enabled' => false,
        'rate' => 1.00, // 1% of wages
        'min_employees' => 10, // Only applicable if >= 10 employees
    ],
];
```

### 5.7 Routes (Route Attributes)

**Route Summary:**

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/hr/staff` | admin.hr.staff.index | Senarai kakitangan |
| GET | `/admin/hr/staff/create` | admin.hr.staff.create | Form tambah kakitangan |
| POST | `/admin/hr/staff` | admin.hr.staff.store | Simpan kakitangan baharu |
| GET | `/admin/hr/staff/{staff}` | admin.hr.staff.show | Lihat profil kakitangan |
| GET | `/admin/hr/staff/{staff}/edit` | admin.hr.staff.edit | Form edit kakitangan |
| PATCH | `/admin/hr/staff/{staff}` | admin.hr.staff.update | Kemaskini kakitangan |
| DELETE | `/admin/hr/staff/{staff}` | admin.hr.staff.destroy | Nyahaktif kakitangan |
| GET | `/admin/hr/roster` | admin.hr.roster.index | Kalendar roster |
| POST | `/admin/hr/roster` | admin.hr.roster.store | Simpan roster |
| GET | `/admin/hr/attendance` | admin.hr.attendance.index | Laporan kehadiran |
| POST | `/admin/hr/attendance/clock` | admin.hr.attendance.clock | Clock in/out |
| GET | `/admin/hr/leave` | admin.hr.leave.index | Senarai permohonan cuti |
| POST | `/admin/hr/leave` | admin.hr.leave.store | Mohon cuti |
| PATCH | `/admin/hr/leave/{leave}/approve` | admin.hr.leave.approve | Luluskan cuti |
| PATCH | `/admin/hr/leave/{leave}/reject` | admin.hr.leave.reject | Tolak cuti |
| GET | `/admin/hr/payroll` | admin.hr.payroll.index | Senarai payroll period |
| POST | `/admin/hr/payroll/process` | admin.hr.payroll.process | Proses payroll |
| GET | `/admin/hr/payroll/{period}/payslips` | admin.hr.payroll.payslips | Senarai payslip |
| GET | `/admin/hr/payslip/{payslip}` | admin.hr.payslip.show | Lihat payslip |
| GET | `/admin/hr/payslip/{payslip}/pdf` | admin.hr.payslip.pdf | Download payslip PDF |
| GET | `/admin/hr/performance` | admin.hr.performance.index | Senarai penilaian |
| POST | `/admin/hr/performance` | admin.hr.performance.store | Submit penilaian |
| GET | `/admin/hr/reports` | admin.hr.reports.index | Dashboard laporan |
| GET | `/admin/hr/reports/statutory` | admin.hr.reports.statutory | Laporan statutory |
| GET | `/self-service` | self-service.dashboard | Dashboard self-service |
| GET | `/self-service/leave` | self-service.leave.index | Permohonan cuti saya |
| POST | `/self-service/leave` | self-service.leave.store | Mohon cuti |
| GET | `/self-service/payslip` | self-service.payslip.index | Payslip saya |
| GET | `/self-service/attendance` | self-service.attendance.index | Kehadiran saya |
| POST | `/self-service/attendance/clock` | self-service.attendance.clock | Clock in/out |

---

## 6. Workflow dan User Flow

### 6.1 Pendaftaran Kakitangan Baharu

```
[HR] → Akses form pendaftaran
    ↓
[HR] → Isi maklumat peribadi dan pekerjaan
    ↓
[Sistem] Validate data (IC unik, gaji >= minimum wage)
    ↓
[Sistem] Generate Kod Pekerja
    ↓
[HR] → Upload dokumen sokongan
    ↓
[Sistem] Cipta akaun user (jika perlu login)
    ↓
[Sistem] Cipta leave balance untuk tahun semasa
    ↓
[HR] → Tetapkan komponen gaji (elaun)
    ↓
[Sistem] Hantar email notifikasi kepada staf baharu
```

### 6.2 Permohonan Cuti

```
[Staf] → Mohon cuti melalui portal
    ↓
[Sistem] Validate (baki cuti, conflict dengan cuti lain)
    ↓
[Sistem] Cipta permohonan dengan status pending
    ↓
[Sistem] Notify Ketua Unit
    ↓
[Ketua] → Review dan approve/reject
    ↓ (Approved)
[Sistem] Notify HR
    ↓
[HR] → Final approval
    ↓ (Approved)
[Sistem] Update leave balance
    ↓
[Sistem] Update roster (mark as on leave)
    ↓
[Sistem] Notify staf
```

### 6.3 Proses Payroll Bulanan

```
[HR] → Initiate payroll untuk bulan X
    ↓
[Sistem] Lock attendance untuk bulan tersebut
    ↓
[Sistem] Calculate untuk setiap staf:
    ├── Gaji pokok (pro-rata jika applicable)
    ├── Elaun tetap
    ├── Overtime (dari attendance)
    ├── Komisyen (dari billing, untuk doktor)
    ├── Unpaid leave deduction
    ├── EPF (pekerja & majikan)
    ├── SOCSO (pekerja & majikan)
    ├── EIS (pekerja & majikan)
    └── PCB
    ↓
[HR] → Review dan adjust jika perlu
    ↓
[HR] → Finalize payroll
    ↓
[Sistem] Lock payroll (tidak boleh edit)
    ↓
[Sistem] Generate payslips
    ↓
[Sistem] Generate bank file
    ↓
[Sistem] Notify staf (payslip ready)
    ↓
[HR] → Submit statutory reports
```

### 6.4 Clock In/Out

```
[Staf] → Akses clock in button
    ↓
[Sistem] Request GPS location
    ↓
[Sistem] Validate location (dalam radius klinik)
    ↓
[Sistem] Record clock in dengan timestamp dan coordinates
    ↓
[Sistem] Calculate status (on-time/late)
    ↓
... (end of shift) ...
    ↓
[Staf] → Akses clock out button
    ↓
[Sistem] Record clock out
    ↓
[Sistem] Calculate jam kerja dan overtime
```

### 6.5 Pengurusan Dokumen Tamat Tempoh

```
[Sistem] Daily job: Check documents expiring
    ↓
[Sistem] 30 hari sebelum tamat → Notify HR dan staf
    ↓
[Sistem] 14 hari sebelum tamat → Reminder
    ↓
[Sistem] 7 hari sebelum tamat → Urgent reminder
    ↓
[Sistem] Hari tamat (APC doktor) → Block scheduling
    ↓
[HR] → Upload dokumen baharu
    ↓
[Sistem] Unblock scheduling
```

### 6.6 State Management

**Staff Status Flow:**
```
[Aktif] → [Tidak Aktif] → [Tamat Perkhidmatan]
```

**Leave Application Status Flow:**
```
[Pending] → [Approved Level 1] → [Approved] → [Completed]
    ↓              ↓
[Rejected]    [Rejected]
    ↓
[Cancelled]
```

**Payroll Status Flow:**
```
[Draft] → [Processing] → [Finalized] → [Paid]
```

---

## 7. Keperluan UI/UX

### 7.1 Layout

- **Jenis Halaman**: Full page dengan sidebar navigation
- **Navigation**: Tambah menu "Sumber Manusia" dengan sub-menu

**Menu Structure:**
```
📁 Sumber Manusia
├── 👥 Kakitangan
│   ├── Senarai Kakitangan
│   └── Tambah Kakitangan
├── 📅 Penjadualan
│   ├── Roster
│   └── Shift
├── ⏰ Kehadiran
│   ├── Laporan Kehadiran
│   └── Adjustment
├── 🏖️ Cuti
│   ├── Permohonan
│   ├── Kelulusan
│   └── Baki Cuti
├── 💰 Payroll
│   ├── Process Payroll
│   ├── Payslips
│   └── Komponen Gaji
├── 📊 Prestasi
│   ├── KPI
│   └── Penilaian
├── 📋 Laporan
│   ├── Laporan HR
│   └── Statutory Reports
└── ⚙️ Tetapan HR
    ├── Jabatan
    ├── Jawatan
    ├── Jenis Cuti
    └── Cuti Umum

📁 Portal Saya (Self-Service)
├── 🏠 Dashboard
├── 👤 Profil Saya
├── 🏖️ Cuti Saya
├── 💵 Payslip Saya
├── ⏰ Kehadiran Saya
└── 📅 Jadual Saya
```

### 7.2 Bootstrap 5 + CoreUI Components

- [x] **Card** - Dashboard widgets, profil staf, payslip summary
- [x] **Table** - Senarai staf, attendance, leave applications
- [x] **Form** - Staff registration, leave application
- [x] **Modal** - Confirmation dialogs, quick view
- [x] **Badge** - Status badges, leave type indicators
- [x] **Button** - Action buttons dengan icons
- [x] **Calendar** - Roster view, leave calendar (FullCalendar.js)
- [x] **Tabs** - Staff profile sections
- [x] **Progress** - Leave balance visualization
- [x] **Alert** - Warnings, expiry notifications
- [x] **Timeline** - Staff history, approval workflow
- [x] **Chart** - Attendance chart, headcount chart

### 7.3 Icons

**Heroicons digunakan:**
- `heroicon-o-users` - Kakitangan
- `heroicon-o-calendar-days` - Roster/Jadual
- `heroicon-o-clock` - Kehadiran
- `heroicon-o-calendar` - Cuti
- `heroicon-o-banknotes` - Payroll
- `heroicon-o-chart-bar` - Prestasi
- `heroicon-o-document-text` - Laporan
- `heroicon-o-cog` - Tetapan
- `heroicon-o-check-circle` - Approved
- `heroicon-o-x-circle` - Rejected
- `heroicon-o-exclamation-triangle` - Warning

### 7.4 Responsive Design

- **Mobile Support**: Ya - Portal self-service optimized untuk mobile
- **Tablet Support**: Ya
- **Breakpoints**: Standard Bootstrap 5

**Mobile Considerations:**
- Clock in/out button besar dan prominent
- Swipe untuk navigate calendar
- Card-based layout untuk senarai

---

## 8. Keperluan Keselamatan

### 8.1 Authentication & Authorization

- **Authentication**: Laravel Breeze (sama dengan main system)
- **Middleware**: `auth`, role-based access
- **Self-Service**: Staf boleh akses data diri sahaja

### 8.2 Data Protection (PDPA Compliance)

- **Audit Trail**: Semua operasi HR direkod
- **Soft Delete**: Rekod staf tidak delete secara kekal
- **Data Encryption**: Gaji dan data sensitif hidden dalam API responses
- **Access Control**: Gaji staf lain tidak boleh dilihat
- **Data Retention**: 7 tahun selepas tamat perkhidmatan

### 8.3 Input Validation & Security

- **CSRF Protection**: Semua forms
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Prevention**: Blade escaping
- **File Upload Security**: Validate type dan size untuk dokumen
- **GPS Spoofing Prevention**: Cross-check dengan IP location

---

## 9. Keperluan Prestasi

### 9.1 Response Time

- **Staff List (100 staf)**: < 2 saat
- **Payroll Processing (50 staf)**: < 30 saat
- **Leave Balance Calculation**: < 1 saat
- **Clock In/Out**: < 3 saat (termasuk GPS)
- **Report Generation**: < 10 saat

### 9.2 Scalability

- **Database Indexing**: Indexes pada semua search fields
- **Query Optimization**: Eager loading untuk relationships
- **Queue Processing**: Payroll calculation via queue
- **Caching**: Cache statutory rates dan konfigurasi
- **Pagination**: Default 15 records per page

### 9.3 Concurrent Users

- **Expected HR Users**: 2-3 concurrent
- **Expected Self-Service Users**: 20-30 concurrent
- **Peak Time**: Awal bulan (payslip viewing)

---

## 10. Keperluan Ujian

### 10.1 Unit Testing

**File: `tests/Unit/Services/PayrollServiceTest.php`**

- [x] **Test**: Calculate EPF correctly untuk pelbagai wage brackets
- [x] **Test**: Calculate SOCSO correctly
- [x] **Test**: Calculate EIS correctly
- [x] **Test**: Calculate overtime correctly (1.5x, 2x, 3x)
- [x] **Test**: Pro-rata calculation untuk staf baru/berhenti
- [x] **Test**: Commission calculation untuk doktor

**File: `tests/Unit/Services/LeaveServiceTest.php`**

- [x] **Test**: Calculate entitlement based on tenure
- [x] **Test**: Deduct leave balance correctly
- [x] **Test**: Block leave application if insufficient balance
- [x] **Test**: Carry forward calculation

### 10.2 Feature Testing

**File: `tests/Feature/StaffManagementTest.php`**

- [x] **Test**: HR can create staff
- [x] **Test**: HR can update staff
- [x] **Test**: HR can deactivate staff
- [x] **Test**: Non-HR cannot access staff management
- [x] **Test**: Staff code is auto-generated

**File: `tests/Feature/LeaveApplicationTest.php`**

- [x] **Test**: Staff can apply leave
- [x] **Test**: Supervisor can approve leave
- [x] **Test**: Leave balance is updated after approval
- [x] **Test**: Cannot apply leave with insufficient balance

**File: `tests/Feature/AttendanceTest.php`**

- [x] **Test**: Staff can clock in
- [x] **Test**: Staff can clock out
- [x] **Test**: Late status is calculated correctly
- [x] **Test**: Working hours is calculated correctly

### 10.3 Integration Testing

- [x] **Test**: Payroll integrates dengan attendance data
- [x] **Test**: Commission integrates dengan billing data
- [x] **Test**: Document expiry notification sent
- [x] **Test**: Statutory reports generated correctly

### 10.4 User Acceptance Testing (UAT)

**Scenario 1**: Pendaftaran Kakitangan Baharu
- Steps: HR daftar staf → Upload dokumen → Tetap elaun
- Expected Result: Staf boleh login, leave balance created

**Scenario 2**: Proses Payroll Bulanan
- Steps: Process payroll → Review → Finalize → Generate bank file
- Expected Result: Payslip dijana dengan calculation betul

**Scenario 3**: Permohonan Cuti
- Steps: Staf mohon → Ketua approve → HR approve
- Expected Result: Leave balance dikurangkan, roster updated

**Scenario 4**: Clock In/Out
- Steps: Staf clock in → Bekerja → Clock out
- Expected Result: Attendance record dengan jam kerja yang betul

---

## 11. Langkah Implementasi

### 11.1 Fasa 1: Database & Models (Minggu 1-2)

- [ ] Create migrations untuk semua jadual
- [ ] Create Models dengan relationships
- [ ] Create config files (hr.php, payroll.php, statutory.php)
- [ ] Create seeders untuk jabatan, jawatan, shift, leave types
- [ ] Run migrations dan seed sample data

### 11.2 Fasa 2: Repository & Service Layer (Minggu 3-4)

- [ ] Create StaffRepository dan StaffService
- [ ] Create RosterRepository dan RosterService
- [ ] Create AttendanceRepository dan AttendanceService
- [ ] Create LeaveRepository dan LeaveService
- [ ] Create PayrollRepository dan PayrollService
- [ ] Create Calculator classes (EPF, SOCSO, EIS, PCB, Overtime)
- [ ] Create CommissionService
- [ ] Create DocumentService
- [ ] Create PerformanceService

### 11.3 Fasa 3: FormRequest Validation (Minggu 5)

- [ ] Create StoreStaffRequest dan UpdateStaffRequest
- [ ] Create StoreLeaveRequest
- [ ] Create StoreRosterRequest
- [ ] Create ProcessPayrollRequest
- [ ] Create custom validation rules (IC format, minimum wage)

### 11.4 Fasa 4: Controllers & Routes (Minggu 6-7)

- [ ] Create StaffController
- [ ] Create RosterController
- [ ] Create AttendanceController
- [ ] Create LeaveController
- [ ] Create PayrollController
- [ ] Create PerformanceController
- [ ] Create ReportController
- [ ] Create EmployeePortalController (Self-Service)
- [ ] Clear route cache

### 11.5 Fasa 5: Views & UI - HR Module (Minggu 8-10)

- [ ] Create staff views (index, create, edit, show)
- [ ] Create roster views dengan calendar
- [ ] Create attendance views dengan laporan
- [ ] Create leave views (application, approval, calendar)
- [ ] Create payroll views (process, payslips)
- [ ] Create performance views (KPI, review)
- [ ] Create report views (HR reports, statutory)

### 11.6 Fasa 6: Views & UI - Self-Service Portal (Minggu 11-12)

- [ ] Create self-service dashboard
- [ ] Create profile view
- [ ] Create leave application view
- [ ] Create payslip view
- [ ] Create attendance view dengan clock in/out
- [ ] Create schedule view

### 11.7 Fasa 7: Notifications & Jobs (Minggu 13)

- [ ] Create LeaveAppliedNotification
- [ ] Create LeaveApprovedNotification
- [ ] Create DocumentExpiryNotification
- [ ] Create PayslipReadyNotification
- [ ] Create CheckDocumentExpiry command (scheduled)
- [ ] Create CalculateLeaveBalance command (yearly)
- [ ] Configure scheduler

### 11.8 Fasa 8: Testing (Minggu 14-15)

- [ ] Write unit tests untuk calculators
- [ ] Write feature tests untuk all endpoints
- [ ] Write integration tests
- [ ] Perform manual UAT
- [ ] Fix bugs

### 11.9 Fasa 9: Deployment & Training (Minggu 16)

- [ ] Deploy ke staging
- [ ] UAT dengan HR dan sample staff
- [ ] Fix final issues
- [ ] Deploy ke production
- [ ] Training untuk HR
- [ ] Training untuk staff (self-service)
- [ ] Monitor logs

---

## 12. Kriteria Kejayaan

### 12.1 Metrics Utama

- **Payroll Accuracy**: 100% (zero error rate)
- **Leave Processing Time**: < 24 jam untuk approval
- **Attendance Compliance**: > 95% clock in/out compliance
- **Document Expiry**: 0 expired documents without renewal

### 12.2 User Satisfaction

- **HR Satisfaction**: > 4.0/5.0
- **Staff Satisfaction (Self-Service)**: > 4.0/5.0
- **Time Saved**: > 50% reduction in manual HR tasks

### 12.3 Technical Metrics

- **Uptime**: > 99%
- **Response Time**: < 3 saat untuk 95% requests
- **Bug Rate**: < 5 bugs per bulan selepas deployment
- **Test Coverage**: > 75%

---

## 13. Risks & Mitigation

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Statutory rates berubah | Medium | Medium | Centralize rates dalam config, easy to update |
| GPS spoofing untuk attendance | Low | Medium | Cross-validate dengan IP, random verification |
| Payroll calculation error | Low | High | Extensive testing, HR review sebelum finalize |
| Data privacy breach | Low | High | Role-based access, audit logging, encryption |
| Clock in/out tidak tepat | Medium | Medium | Grace period, adjustment workflow |
| Resistance to self-service | Medium | Low | Training, clear UI, helpdesk support |
| Document upload abuse | Low | Low | File type/size validation, virus scan |
| Leave abuse | Low | Medium | Approval workflow, HR oversight |

---

## 14. Dependencies

### 14.1 External Packages

- [x] **maatwebsite/laravel-excel**: ^3.1 - Import/export Excel
- [x] **barryvdh/laravel-dompdf**: ^2.0 - Generate PDF payslips
- [x] **fullcalendar/fullcalendar**: ^6.0 - Calendar UI untuk roster dan leave
- [x] **Chart.js**: ^4.0 - Charts untuk dashboard

### 14.2 Related Features/Modules

**Bergantung Kepada:**
- Modul Tetapan & Keselamatan (User management, roles)
- Authentication system

**Memberi Impak Kepada:**
- Modul Billing (Komisyen doktor)
- Modul Laporan (HR reports)
- Semua modul (Staff scheduling affects availability)

### 14.3 Third-Party Integrations

- [x] **Email Service**: Untuk notifications (SMTP)
- [x] **Browser Geolocation API**: Untuk clock in/out
- [ ] **Bank Integration (Optional)**: Auto-upload bank file

---

## 15. Acceptance Criteria

### 15.1 Functional Acceptance

- [x] HR boleh CRUD kakitangan dengan semua maklumat lengkap
- [x] Sistem generate kod pekerja automatik
- [x] HR boleh create roster mingguan/bulanan
- [x] Sistem detect konflik jadual
- [x] Staf boleh clock in/out dengan GPS location
- [x] Sistem calculate late/early status
- [x] Staf boleh mohon cuti melalui portal
- [x] Approval workflow multi-level berfungsi
- [x] Leave balance dikira dan dikemaskini automatik
- [x] Payroll process calculate semua komponen dengan betul
- [x] Statutory deductions (EPF, SOCSO, EIS, PCB) dikira mengikut kadar terkini
- [x] Payslip dijana dalam format PDF
- [x] Commission doktor dikira berdasarkan billing
- [x] Document expiry notification dihantar
- [x] Statutory reports boleh dijana (Borang A, 8A, E, EA)
- [x] Self-service portal berfungsi untuk semua staf

### 15.2 Technical Acceptance

- [x] Semua feature tests lulus
- [x] Semua unit tests lulus (terutama calculators)
- [x] Kod mengikut conventions
- [x] Kod diformat dengan pint
- [x] Tiada N+1 query problems
- [x] Payroll processing tidak timeout

### 15.3 Quality Acceptance

- [x] Kod di-review
- [x] Manual testing selesai
- [x] Responsive design berfungsi
- [x] Payroll calculation verified dengan manual calculation

### 15.4 Documentation Acceptance

- [x] PRD dikemaskini
- [x] User guide untuk HR
- [x] User guide untuk self-service

---

## 16. Lampiran

### 16.1 Jadual Caruman EPF 2024

| Gaji (RM) | Pekerja | Majikan (≤5000) | Majikan (>5000) |
|-----------|---------|-----------------|-----------------|
| ≤ 60 tahun | 11% | 13% | 12% |
| > 60 tahun | 0% (optional) | 4% | 4% |

### 16.2 Jadual Caruman SOCSO 2024

| Gaji (RM) | Pekerja | Majikan |
|-----------|---------|---------|
| Hingga 4,000 | 0.5% | 1.75% |
| > 4,000 | Tidak applicable | Tidak applicable |

### 16.3 Jadual Caruman EIS 2024

| Gaji (RM) | Pekerja | Majikan |
|-----------|---------|---------|
| Hingga 4,000 | 0.2% | 0.2% |
| > 4,000 | Tidak applicable | Tidak applicable |

### 16.4 Kadar Overtime (Akta Kerja 1955)

| Hari | Kadar |
|------|-------|
| Hari Biasa (selepas 8 jam) | 1.5x hourly rate |
| Hari Rehat | 2.0x hourly rate |
| Cuti Umum | 3.0x hourly rate |

### 16.5 Entitlement Cuti Tahunan (Akta Kerja 1955)

| Tempoh Perkhidmatan | Hari Minimum |
|---------------------|--------------|
| < 2 tahun | 8 hari |
| 2-5 tahun | 12 hari |
| > 5 tahun | 16 hari |

### 16.6 Entitlement Cuti Sakit (Akta Kerja 1955)

| Tempoh Perkhidmatan | Hari (tanpa hospitalization) | Hari (dengan hospitalization) |
|---------------------|------------------------------|-------------------------------|
| < 2 tahun | 14 hari | 60 hari |
| 2-5 tahun | 18 hari | 60 hari |
| > 5 tahun | 22 hari | 60 hari |

### 16.7 Change Log

| Tarikh | Penulis | Perubahan |
|--------|---------|-----------|
| 13 Januari 2026 | AI Assistant | PRD awal dicipta |

### 16.8 Approval

- [ ] **Product Owner**: _________________ - _________________
- [ ] **Tech Lead**: _________________ - _________________
- [ ] **Pengurus Klinik**: _________________ - _________________
- [ ] **HR Manager**: _________________ - _________________

---

**Status Implementasi**: Belum Bermula
**Tarikh Selesai**: TBD

---

**Catatan**: Dokumen ini adalah living document dan akan dikemaskini mengikut keperluan semasa development. Kadar statutory (EPF, SOCSO, EIS, PCB) perlu disemak dan dikemaskini mengikut perubahan kerajaan.