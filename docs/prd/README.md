# Product Requirements Documents (PRD)

Direktori ini mengandungi semua Product Requirements Documents untuk Sistem Klinik Swasta **Poliklinik Al-Huda**.

---

## Tujuan

Setiap feature baharu, enhancement penting, atau penambahan modul **mesti** mempunyai dokumen PRD yang sepadan sebelum implementasi bermula. Ini memastikan:

- Keperluan dan spesifikasi yang jelas sebelum coding bermula
- Perancangan yang betul untuk database schema, UI components, dan testing
- Penjajaran dengan architecture projek (Laravel 12, Blade Templates, Bootstrap 5 + CoreUI)
- Dokumentasi keputusan dan kriteria penerimaan
- Traceability untuk maintenance dan enhancement masa depan

---

## Format Penamaan PRD

Semua fail PRD mengikut format standard yang selaras dengan struktur modul klinik:

```
KLINIK-[NamaModul]-PR[YYYY]-[NN]-[nama-feature]
```

### Format Breakdown

- **`KLINIK`** - Prefix tetap untuk projek Poliklinik Al-Huda
- **`[NamaModul]`** - Nama modul (contoh: PendaftaranPesakit, TemujanjiPesakit, Ubat, Billing)
- **`PR`** - Fixed prefix untuk "Product Requirement"
- **`[YYYY]`** - Tahun 4 digit (contoh: 2026)
- **`[NN]`** - Nombor sequential 2 digit bermula dari 01 setiap tahun untuk setiap modul
- **`[nama-feature]`** - Nama deskriptif dalam format kebab-case

### Contoh Penamaan

**Untuk main module features:**
```
KLINIK-PendaftaranPesakit-PR2026-01-pengurusan-maklumat-pesakit.md
KLINIK-TemujanjiPesakit-PR2026-01-pengurusan-temujanji.md
KLINIK-Ubat-PR2026-01-pengurusan-inventori.md
KLINIK-Billing-PR2026-01-sistem-pembayaran.md
```

**Untuk sub-module features (jika ada nested structure):**
```
KLINIK-RekodPerubatan-VitalSigns-PR2026-01-vital-signs-tracking.md
KLINIK-Laporan-Kewangan-PR2026-01-monthly-financial-report.md
```

### Rujukan Modul Klinik

| Modul | Example PRD Prefix |
|-------|-------------------|
| **Pendaftaran Pesakit** | `KLINIK-PendaftaranPesakit-` |
| **Temujanji Pesakit** | `KLINIK-TemujanjiPesakit-` |
| **Rekod Perubatan** | `KLINIK-RekodPerubatan-` |
| **Ubat & Inventori** | `KLINIK-Ubat-` |
| **Billing & Pembayaran** | `KLINIK-Billing-` |
| **Pengurusan Doktor** | `KLINIK-Doktor-` |
| **Pengurusan Staf** | `KLINIK-Staf-` |
| **Laporan** | `KLINIK-Laporan-` |
| **Dashboard** | `KLINIK-Dashboard-` |
| **Integrasi** | `KLINIK-Integrasi-` |

### Sequence Numbering

- Nombor **reset kepada 01** pada permulaan setiap tahun **untuk setiap modul**
- Nombor adalah **module-specific** (contoh: KLINIK-PendaftaranPesakit-PR2026-01 dan KLINIK-Ubat-PR2026-01 boleh wujud bersama)
- Nombor **auto-incremented** dengan scan existing PRDs untuk modul tertentu
- Jika modul tidak mempunyai PRD untuk tahun semasa, mulakan dengan 01
- Cari nombor tertinggi untuk kombinasi module-year tertentu dan increment dengan 1

### Rasional untuk Module-Based Naming

1. **Traceability** - PRD boleh dikenal pasti dengan segera mengikut modul
2. **Organization** - Features dikumpulkan mengikut domain perniagaan
3. **Scalability** - Multiple teams boleh bekerja pada modul berbeza tanpa conflict numbering
4. **Search** - Mudah untuk cari semua PRD yang berkaitan dengan modul tertentu
5. **Maintenance** - Senang untuk maintain dan update PRD mengikut modul

---

## Senarai PRD Sedia Ada (Mengikut Flow Proses Klinik)

Susunan PRD disusun mengikut **aliran proses sebenar** di klinik:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         FLOW PROSES KLINIK                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                   │
│  │ 1. TETAPAN & │───▶│ 2. SUMBER    │───▶│ 3. PENDAFTA- │                   │
│  │    KESELAMATAN│    │    MANUSIA   │    │    RAN       │                   │
│  └──────────────┘    └──────────────┘    └──────────────┘                   │
│         │                                        │                           │
│         ▼                                        ▼                           │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                   │
│  │ Setup awal   │    │ Urus staf &  │    │ Daftar       │                   │
│  │ sistem       │    │ jadual kerja │    │ pesakit      │                   │
│  └──────────────┘    └──────────────┘    └──────────────┘                   │
│                                                  │                           │
│                                                  ▼                           │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                   │
│  │ 6. FARMASI   │◀───│ 5. KONSULTASI│◀───│ 4. TEMUJANJI │                   │
│  │              │    │    & EMR     │    │    & QUEUE   │                   │
│  └──────────────┘    └──────────────┘    └──────────────┘                   │
│         │                   │                                                │
│         │                   ▼                                                │
│         │            ┌──────────────┐                                        │
│         │            │ 7. AI TRIAGE │                                        │
│         │            │    & EMR     │                                        │
│         │            └──────────────┘                                        │
│         │                                                                    │
│         ▼                                                                    │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐                   │
│  │ 8. BILLING   │───▶│ 9. PANEL     │───▶│ 10. LAPORAN  │                   │
│  │              │    │    INSURANS  │    │    & ANALITIK│                   │
│  └──────────────┘    └──────────────┘    └──────────────┘                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

### FASA A: SETUP & OPERASI (Mesti Ada Dahulu)

---

#### 1. Modul Tetapan & Keselamatan
**Fail**: [01-KLINIK-Tetapan-PR2026-01-kawalan-sistem-keselamatan.md](01-KLINIK-Tetapan-PR2026-01-kawalan-sistem-keselamatan.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Kritikal |
| **Tarikh** | 13 Januari 2026 |
| **Tempoh** | 14 minggu |

**Ringkasan**: Modul teras untuk pengurusan pengguna, RBAC, MFA, audit trail, dan konfigurasi keselamatan sistem.

**Features Utama**:
- Pengurusan Pengguna (CRUD, bulk import, activate/deactivate)
- RBAC dengan Permissions Granular
- Multi-Factor Authentication (TOTP + Email OTP)
- Audit Trail Komprehensif (7 tahun retention)
- Session Management (single session, auto-logout)
- Password Policy & Brute Force Protection
- IP Whitelist untuk Admin
- Backup & Recovery dengan UI Dashboard

---

#### 2. Modul Sumber Manusia (HR)
**Fail**: [02-KLINIK-HR-PR2026-01-pengurusan-kakitangan.md](02-KLINIK-HR-PR2026-01-pengurusan-kakitangan.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 13 Januari 2026 |
| **Tempoh** | 16 minggu |

**Ringkasan**: Sistem pengurusan kakitangan bersepadu untuk rekod staf, penjadualan, cuti, dan payroll.

**Features Utama**:
- Rekod Kakitangan (semua jenis: tetap, kontrak, part-time, locum)
- Penjadualan Kerja (shift-based dengan roster)
- Sistem Kehadiran (clock in/out dengan GPS geo-tagging)
- Pengurusan Cuti (multi-level approval)
- Payroll dengan Statutory Deductions (EPF, SOCSO, EIS, PCB)
- Komisyen Doktor (integrasi dengan Billing)
- Portal Self-Service untuk Kakitangan
- Statutory Reports (Borang A, 8A, E, EA)

---

### FASA B: PENDAFTARAN & ALIRAN PESAKIT

---

#### 3. Modul Pendaftaran Pesakit
**Fail**: [03-KLINIK-PendaftaranPesakit-PR2026-01-pengurusan-maklumat-pesakit.md](03-KLINIK-PendaftaranPesakit-PR2026-01-pengurusan-maklumat-pesakit.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 12 Januari 2026 |
| **Tempoh** | 6 minggu |

**Ringkasan**: Sistem pengurusan pendaftaran pesakit untuk daftar pesakit baharu, carian rekod, dan kemaskini maklumat.

**Features Utama**:
- Pendaftaran pesakit baharu (< 2 minit)
- Duplicate check dengan IC/Passport
- PDPA consent tracking
- Sokongan warganegara asing dan kanak-kanak
- Audit trail untuk semua perubahan

---

#### 4. Modul Temujanji Pesakit
**Fail**: [04-KLINIK-TemujanjiPesakit-PR2026-01-pengurusan-temujanji.md](04-KLINIK-TemujanjiPesakit-PR2026-01-pengurusan-temujanji.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 12 Januari 2026 |
| **Tempoh** | 6 minggu |

**Ringkasan**: Sistem pengurusan temujanji dengan tempahan online, SMS/WhatsApp reminder, dan pengurusan no-show.

**Features Utama**:
- Tempahan online self-service
- SMS/WhatsApp notification automatik
- Slot management untuk doktor
- Auto no-show detection
- Blacklist management (3 no-show = 30 hari blacklist)

---

#### 5. Modul Queue Management
**Fail**: [05-KLINIK-Queue-PR2026-01-pengurusan-giliran.md](05-KLINIK-Queue-PR2026-01-pengurusan-giliran.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 13 Januari 2026 |
| **Tempoh** | 11 minggu |

**Ringkasan**: Sistem pengurusan giliran pesakit dengan nombor giliran, paparan digital, dan voice announcement.

**Features Utama**:
- Multi-queue dengan prefixes (A-Pendaftaran, B-Doktor, C-Farmasi)
- Kiosk self-service
- Paparan digital real-time (WebSocket)
- Voice announcement (TTS)
- Priority queue untuk kecemasan
- SMS notification untuk giliran
- Expected Wait Time (EWT) calculation

---

### FASA C: KLINIKAL & RAWATAN

---

#### 6. Modul Konsultasi & EMR
**Fail**: [06-KLINIK-KonsultasiEMR-PR2026-01-rekod-rawatan-pesakit.md](06-KLINIK-KonsultasiEMR-PR2026-01-rekod-rawatan-pesakit.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Kritikal |
| **Tarikh** | 12 Januari 2026 |
| **Tempoh** | TBD |

**Ringkasan**: Sistem Electronic Medical Record (EMR) untuk dokumentasi klinikal, preskripsi, dan rekod perubatan pesakit.

**Features Utama**:
- SOAP notes documentation
- Vital signs recording
- Problem list management
- Prescription writing
- Allergy and medication history
- ICD-10 coding
- Clinical templates

---

#### 7. Modul AI – Triage & EMR
**Fail**: [07-KLINIK-AI-PR2026-01-triage-emr-sokongan-klinikal.md](07-KLINIK-AI-PR2026-01-triage-emr-sokongan-klinikal.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 14 Januari 2026 |
| **Tempoh** | 20 minggu |

**Ringkasan**: Sistem sokongan keputusan klinikal pintar dengan AI triage, ringkasan EMR automatik, dan cadangan klinikal.

**Features Utama**:
- AI Triage (5-level Manchester Triage System)
- Red Flag Detection (life-threatening symptoms)
- EMR Summary Generation automatik
- Differential Diagnosis dengan confidence scores
- Drug Interaction Checking (multi-level)
- Explainable AI (reasoning chain, evidence)
- Human-in-the-Loop (mandatory review)
- On-Premise Processing (PDPA compliance)

---

#### 8. Modul Farmasi & Stok Ubat
**Fail**: [08-KLINIK-Farmasi-PR2026-01-pengurusan-ubat-stok.md](08-KLINIK-Farmasi-PR2026-01-pengurusan-ubat-stok.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 13 Januari 2026 |
| **Tempoh** | 10.5 minggu |

**Ringkasan**: Sistem pengurusan ubat dan stok dengan dispensing workflow dan pematuhan Akta Racun 1952.

**Features Utama**:
- Pengurusan katalog ubat
- Stok management (FEFO/FIFO)
- Preskripsi (elektronik & manual)
- Dispensing workflow dengan verification
- Drug interaction checking
- Patient Medication Record (PMR)
- Poison Register (Akta Racun 1952)
- Low stock alerts

---

### FASA D: KEWANGAN & TUNTUTAN

---

#### 9. Modul Bil & Pembayaran
**Fail**: [09-KLINIK-Billing-PR2026-01-caj-kutipan-bayaran.md](09-KLINIK-Billing-PR2026-01-caj-kutipan-bayaran.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 13 Januari 2026 |
| **Tempoh** | 15.5 minggu |

**Ringkasan**: Sistem billing dan kutipan bayaran dengan sokongan pelbagai kaedah pembayaran dan SST compliance.

**Features Utama**:
- Invoice generation automatik
- Multiple payment methods (Cash, Card, QR Pay, e-Wallet)
- Split payment & partial payment
- SST calculation
- Receipt & refund management
- Cashier closing & reconciliation
- Promo codes & discounts
- Deposit management

---

#### 10. Modul Panel Insurans / GL
**Fail**: [10-KLINIK-Panel-PR2026-01-pengurusan-pesakit-panel.md](10-KLINIK-Panel-PR2026-01-pengurusan-pesakit-panel.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 13 Januari 2026 |
| **Tempoh** | 13.5 minggu |

**Ringkasan**: Sistem pengurusan pesakit panel dan Guarantee Letter dengan benefit limit tracking.

**Features Utama**:
- Panel company management
- Guarantee Letter (GL) verification
- Benefit limit tracking (annual, per-visit)
- Pre-Authorization (PA) workflow
- Claim submission dan tracking
- Payment advice reconciliation
- ICD-10 integration
- SLA monitoring untuk insurers

---

### FASA E: LAPORAN & ANALITIK

---

#### 11. Modul Laporan & Analitik
**Fail**: [11-KLINIK-Laporan-PR2026-01-analisis-prestasi-kpi.md](11-KLINIK-Laporan-PR2026-01-analisis-prestasi-kpi.md)

| Aspek | Maklumat |
|-------|----------|
| **Status** | Draft |
| **Keutamaan** | Tinggi |
| **Tarikh** | 13 Januari 2026 |
| **Tempoh** | 18 minggu |

**Ringkasan**: Sistem laporan dan analitik dengan KPI tracking, dashboard berbilang peringkat, dan data warehouse.

**Features Utama**:
- Multi-level Dashboards (Executive, Operational, Department)
- Comprehensive KPI Tracking (Financial, Clinical, Operational)
- Star Schema Data Warehouse
- ETL Jobs untuk data aggregation
- Custom Report Builder (drag-and-drop)
- Alert & Notification System
- Predictive Analytics (forecasting)
- Data Export (PDF, Excel, CSV)

---

## Template PRD

**Fail**: [prd_template.md](prd_template.md)

Template standard untuk membuat PRD baharu bagi Sistem Poliklinik Al-Huda. Template ini mengikut architecture pattern projek:

- **Laravel 12** dengan Blade Templates
- **Route Attributes** pattern (Spatie)
- **Service Layer + Repository Pattern**
- **FormRequest validation**
- **Bootstrap 5 + CoreUI** untuk UI
- **PDPA compliance** dengan audit trail

**Gunakan template ini bila membuat PRD baharu untuk modul lain seperti**:
- Rekod Perubatan Pesakit
- Billing & Pembayaran
- Pengurusan Staf
- Laporan Klinik
- Lab Results Integration

---

## Membuat PRD Baharu

### Proses Automatik (Disyorkan)

Bila anda meminta feature baharu dari GitHub Copilot, ia akan:

1. Automatically scan direktori `docs/prd/`
2. Kenal pasti modul mana yang sesuai untuk feature tersebut
3. Cari nombor sequence tertinggi untuk modul dan tahun semasa
4. Tawarkan untuk create PRD menggunakan nombor yang seterusnya
5. Guna template dari `prd_template.md`
6. Isi bahagian yang relevan berdasarkan feature request anda
7. Save PRD dengan naming format yang betul

**Contoh dialogue:**
```
Anda: "Saya nak tambah sistem billing untuk klinik"

Copilot: "Saya akan create PRD dahulu. Feature ini belong to modul Billing."
         "Scanning existing PRDs untuk KLINIK-Billing..."
         "Next available: KLINIK-Billing-PR2026-01-sistem-pembayaran.md"
         "Creating PRD document..."
```

### Proses Manual

Jika membuat PRD secara manual:

1. Kenal pasti modul mana yang sesuai untuk feature anda
2. Check existing PRD files dalam direktori ini untuk modul tersebut
3. Cari nombor tertinggi untuk kombinasi module-year (contoh: `KLINIK-Ubat-PR2026-02-...` → seterusnya ialah `03`)
4. Copy template dari `prd_template.md`
5. Namakan fail anda: `KLINIK-[Modul]-PR2026-[NN]-[nama-feature].md`
6. Isi semua bahagian dalam template
7. Commit PRD sebelum mula implementation

---

## Workflow

### 1. Feature Request
User meminta feature baharu atau enhancement.

### 2. PRD Creation
- Copilot tawarkan untuk create PRD menggunakan template
- PRD disimpan dengan naming convention yang betul
- PRD di-commit ke repository

### 3. PRD Review & Approval
- Team review PRD untuk completeness
- Stakeholders approve requirements
- Technical lead validate architecture decisions
- PRD status dikemaskini kepada "Approved"

### 4. Implementation
- **Hanya selepas** PRD approval, implementasi boleh bermula
- Ikut implementation steps dari PRD seksyen 11
- Rujuk PRD untuk requirements, specs, dan acceptance criteria

### 5. Testing & Validation
- Tulis tests seperti yang dinyatakan dalam PRD seksyen 10
- Validate mengikut acceptance criteria dalam PRD seksyen 15
- Update PRD dengan sebarang perubahan semasa implementation

### 6. Completion
- Mark PRD status sebagai "Selesai"
- Update change log dengan completion date
- Update related documentation (DEVELOPER_GUIDE.md, README.md jika perlu)

---

## Status PRD

| # | PRD ID | Modul | Status | Priority | Tempoh |
|---|--------|-------|--------|----------|--------|
| 1 | KLINIK-Tetapan-PR2026-01 | Tetapan & Keselamatan | Draft | Kritikal | 14 minggu |
| 2 | KLINIK-HR-PR2026-01 | Sumber Manusia (HR) | Draft | Tinggi | 16 minggu |
| 3 | KLINIK-PendaftaranPesakit-PR2026-01 | Pendaftaran Pesakit | Draft | Tinggi | 6 minggu |
| 4 | KLINIK-TemujanjiPesakit-PR2026-01 | Temujanji Pesakit | Draft | Tinggi | 6 minggu |
| 5 | KLINIK-Queue-PR2026-01 | Queue Management | Draft | Tinggi | 11 minggu |
| 6 | KLINIK-KonsultasiEMR-PR2026-01 | Konsultasi & EMR | Draft | Kritikal | TBD |
| 7 | KLINIK-AI-PR2026-01 | AI Triage & EMR | Draft | Tinggi | 20 minggu |
| 8 | KLINIK-Farmasi-PR2026-01 | Farmasi & Stok Ubat | Draft | Tinggi | 10.5 minggu |
| 9 | KLINIK-Billing-PR2026-01 | Bil & Pembayaran | Draft | Tinggi | 15.5 minggu |
| 10 | KLINIK-Panel-PR2026-01 | Panel Insurans / GL | Draft | Tinggi | 13.5 minggu |
| 11 | KLINIK-Laporan-PR2026-01 | Laporan & Analitik | Draft | Tinggi | 18 minggu |

**Jumlah Anggaran Tempoh**: ~131 minggu (boleh parallelkan beberapa modul)

**Legend**:
- **Draft**: PRD sedang ditulis atau menunggu approval
- **Approved**: PRD telah diluluskan, boleh mula development
- **In Progress**: Development sedang berjalan
- **Review**: Development selesai, dalam review/testing
- **Selesai**: Feature telah deploy ke production

---

## Roadmap Modul (Cadangan)

### Fasa A: Setup & Operasi (Q1 2026) - MESTI DAHULU
- 🔄 **Tetapan & Keselamatan** - PRD ready (14 minggu)
- 🔄 **Sumber Manusia (HR)** - PRD ready (16 minggu)

### Fasa B: Pendaftaran & Aliran Pesakit (Q1-Q2 2026)
- 🔄 **Pendaftaran Pesakit** - PRD ready (6 minggu)
- 🔄 **Temujanji Pesakit** - PRD ready (6 minggu)
- 🔄 **Queue Management** - PRD ready (11 minggu)

### Fasa C: Klinikal & Rawatan (Q2-Q3 2026)
- 🔄 **Konsultasi & EMR** - PRD ready
- 🔄 **AI Triage & EMR** - PRD ready (20 minggu)
- 🔄 **Farmasi & Stok Ubat** - PRD ready (10.5 minggu)

### Fasa D: Kewangan & Tuntutan (Q3 2026)
- 🔄 **Bil & Pembayaran** - PRD ready (15.5 minggu)
- 🔄 **Panel Insurans / GL** - PRD ready (13.5 minggu)

### Fasa E: Laporan & Analitik (Q4 2026)
- 🔄 **Laporan & Analitik** - PRD ready (18 minggu)

### Fasa Future: Advanced Features
- ⏳ **Lab Results Integration** - Lab test results
- ⏳ **Imaging & Radiology** - X-ray, ultrasound records
- ⏳ **Mobile App untuk Pesakit** - Patient mobile app
- ⏳ **Telemedicine** - Video consultation

**Legend**:
- ✅ Selesai
- 🔄 PRD Ready
- ⏳ Belum bermula

---

## Best Practices

### Do's ✅

- **Kenal pasti modul yang betul** sebelum namakan PRD
- Isi semua bahagian yang relevan dengan teliti
- Update PRD semasa implementation jika requirements berubah
- Reference PRD ID dalam commit messages dan pull requests (contoh: "KLINIK-TemujanjiPesakit-PR2026-01")
- Simpan PRD sebagai source of truth yang up-to-date
- Guna module-specific numbering untuk elakkan conflicts

### Don'ts ❌

- Jangan skip PRD creation untuk "small" features - ia sering berkembang
- Jangan implement features tanpa approved PRD
- Jangan guna generic numbering - sentiasa include module prefix
- Jangan create PRDs dengan nombor arbitrary - guna auto-increment per module
- Jangan guna spaces atau underscores dalam filename - guna kebab-case
- Jangan tinggalkan bahagian kosong - tulis "N/A" jika truly not applicable
- Jangan lupa update PRD status bila kerja sedang berjalan
- Jangan mix module codes - simpan PRDs organized mengikut modul

---

## Project-Specific Considerations

Projek Laravel ini mempunyai architecture patterns unik yang PRD mesti address:

### Module Structure
```
app/
├── Http/Controllers/Admin/[ModuleName]Controller.php
├── Services/[ModuleName]Service.php
├── Repositories/[ModuleName]Repository.php
├── Models/[ModelName].php
└── Http/Requests/Store[ModelName]Request.php
```

### Component Generation
```bash
php artisan make:model [ModelName] -mf
php artisan make:controller Admin/[ModuleName]Controller
php artisan make:request Store[ModelName]Request
```

### Route Attributes Pattern
PRDs mesti specify route attributes dalam controller:
```php
#[Prefix('admin/[module-name]')]
#[Middleware(['web', 'auth'])]
class [ModuleName]Controller extends Controller
{
    #[Get('/', name: 'admin.[module-name].index')]
    public function index() { }
}
```

### Audit Trail & PDPA Compliance
Semua PRDs mesti consider:
- Audit trail logging (`created_by`, `updated_by`)
- Soft delete untuk data sensitive
- PDPA consent tracking
- Data retention policies

### Testing Requirements
Semua PRDs mesti include:
- Feature tests untuk all user flows
- Unit tests untuk business logic
- Test coverage untuk authorization dan validation

### Code Formatting
Semua implementations mesti run:
```bash
./vendor/bin/pint
```

---

## Konvensyen Penulisan PRD

Bila membuat PRD baharu, ikut konvensyen ini:

### 1. Bahasa
- Guna **Bahasa Malaysia** untuk semua content
- User stories guna format: **Sebagai**, **saya mahu**, **supaya**, **bila**, **saya sepatutnya**
- Technical terms boleh guna English (contoh: "database", "API", "controller")

### 2. Format User Stories
- Satu ayat sahaja
- Bold untuk keywords
- Tiada full stop di hujung
- Tiada sub-bullets

**Contoh**:
```markdown
- **Sebagai** Kerani kaunter, **saya mahu** mendaftar pesakit baharu dengan cepat dan mudah **supaya** pesakit tidak perlu menunggu lama dan data rekod adalah tepat
```

### 3. Database Schema
- Guna format table dengan columns, types, descriptions
- Nyatakan indexes dan foreign keys
- Ikut naming convention: snake_case untuk column names

### 4. Code Examples
- Berikan code examples untuk Model, Controller, Service, Repository
- Guna architecture pattern projek (Service + Repository)
- Include error handling dengan HandlesApiResponses trait

### 5. Langkah Implementasi
- Pecahkan kepada fasa yang jelas
- Setiap fasa ada checklist
- Nyatakan anggaran masa (minggu)

---

## Rujukan

- **[DEVELOPER_GUIDE.md](../../DEVELOPER_GUIDE.md)** - Architecture patterns dan coding conventions
- **[REFACTORING_SUMMARY.md](../../REFACTORING_SUMMARY.md)** - Ringkasan refactoring yang telah dibuat
- **[.github/copilot-instructions.md](../../.github/copilot-instructions.md)** - GitHub Copilot instructions untuk projek ini

---

## Sokongan

Untuk soalan atau cadangan berkaitan PRD:
1. Buka issue di GitHub repository
2. Hubungi Product Owner
3. Discuss dalam team meeting

---

**Dikemaskini**: 14 Januari 2026
**Versi**: 3.0 (Disusun mengikut flow proses klinik sebenar)
