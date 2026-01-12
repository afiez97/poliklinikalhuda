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

## Senarai PRD Sedia Ada

### 1. Modul Pendaftaran Pesakit

#### KLINIK-PendaftaranPesakit-PR2026-01
**Fail**: [KLINIK-PendaftaranPesakit-PR2026-01-pengurusan-maklumat-pesakit.md](KLINIK-PendaftaranPesakit-PR2026-01-pengurusan-maklumat-pesakit.md)

**Status**: Draft
**Keutamaan**: Tinggi
**Tarikh**: 12 Januari 2026

**Ringkasan**:
Sistem pengurusan pendaftaran pesakit yang membolehkan Kerani mendaftar pesakit baharu, mencari rekod sedia ada, dan mengemaskini maklumat pesakit dengan cepat dan tepat.

**Objektif Utama**:
- Memudahkan pendaftaran pesakit baharu dengan cepat (< 2 minit)
- Mengurangkan ralat data dengan validation dan workflow approval
- Mematuhi PDPA dengan rekod consent dan audit trail
- Elakkan duplicate records dengan IC/Passport check
- Sokongan untuk warganegara asing dan kanak-kanak

**Jadual Database**:
- `pesakit` - Maklumat asas pesakit
- `pesakit_audit_trail` - Log semua perubahan data

**User Stories**: 10 user stories (6 utama, 4 edge cases)

**Fasa Implementasi**: 6 minggu (7 fasa)

---

### 2. Modul Temujanji Pesakit

#### KLINIK-TemujanjiPesakit-PR2026-01
**Fail**: [KLINIK-TemujanjiPesakit-PR2026-01-pengurusan-temujanji.md](KLINIK-TemujanjiPesakit-PR2026-01-pengurusan-temujanji.md)

**Status**: Draft
**Keutamaan**: Tinggi
**Tarikh**: 12 Januari 2026

**Ringkasan**:
Sistem pengurusan temujanji pesakit yang membolehkan tempahan online, pengurusan slot doktor, notifikasi automatik SMS/WhatsApp, dan pengurusan no-show dengan polisi blacklist.

**Objektif Utama**:
- Memudahkan pesakit membuat temujanji online tanpa datang ke klinik
- Mengurangkan kadar no-show dari 15-20% ke < 10% dengan reminder automatik
- Mengoptimumkan jadual doktor dengan slot management
- Kurangkan beban kerja kerani dengan automation
- Meningkatkan kepuasan pesakit dengan sistem yang mudah

**Jadual Database**:
- `temujanji` - Rekod temujanji
- `slot_doktor` - Konfigurasi slot waktu doktor
- `slot_tutup` - Slot yang ditutup (cuti/mesyuarat)
- `pesakit_blacklist` - Blacklist pesakit yang selalu no-show
- `notification_log` - Log SMS/WhatsApp yang dihantar

**User Stories**: 13 user stories (Pesakit, Kerani, Doktor, Pengurus)

**Features Utama**:
- Tempahan online self-service portal
- Tempahan walk-in oleh kerani
- SMS/WhatsApp notification automatik
- Auto no-show detection
- Blacklist management (3 no-show = 30 hari blacklist)
- Reschedule & cancellation dengan polisi
- Dashboard doktor dan kerani
- Laporan dan statistik

**Fasa Implementasi**: 6 minggu (10 fasa)

**Integrasi**:
- Twilio/MSG91 untuk SMS
- WhatsApp Business API untuk WhatsApp
- Laravel Scheduler untuk auto-reminder dan no-show detection
- Laravel Queue untuk async notifications

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

| PRD ID | Modul | Status | Priority | Start Date | Target Date | Completion |
|--------|-------|--------|----------|------------|-------------|------------|
| KLINIK-PendaftaranPesakit-PR2026-01 | Pendaftaran Pesakit | Draft | Tinggi | TBD | TBD | 0% |
| KLINIK-TemujanjiPesakit-PR2026-01 | Temujanji Pesakit | Draft | Tinggi | TBD | TBD | 0% |

**Legend**:
- **Draft**: PRD sedang ditulis atau menunggu approval
- **Approved**: PRD telah diluluskan, boleh mula development
- **In Progress**: Development sedang berjalan
- **Review**: Development selesai, dalam review/testing
- **Selesai**: Feature telah deploy ke production

---

## Roadmap Modul (Cadangan)

### Fasa 1: Core Patient Management (Q1 2026)
- ✅ **Pengurusan Ubat & Inventori** - Sudah ada (refactored)
- 🔄 **Pendaftaran Pesakit** - PRD ready
- 🔄 **Temujanji Pesakit** - PRD ready

### Fasa 2: Clinical Operations (Q2 2026)
- ⏳ **Rekod Perubatan Pesakit** - Medical records, consultation notes
- ⏳ **Preskripsi & Dispensing Ubat** - Prescription workflow
- ⏳ **Vital Signs & Pemeriksaan** - Vital signs tracking

### Fasa 3: Financial Management (Q3 2026)
- ⏳ **Billing & Pembayaran** - Invoicing, payments
- ⏳ **Insurance Claims** - Insurance integration
- ⏳ **Laporan Kewangan** - Financial reports

### Fasa 4: Advanced Features (Q4 2026)
- ⏳ **Lab Results Integration** - Lab test results
- ⏳ **Imaging & Radiology** - X-ray, ultrasound records
- ⏳ **Reporting & Analytics Dashboard** - BI dashboard
- ⏳ **Mobile App untuk Pesakit** - Patient mobile app

**Legend**:
- ✅ Selesai
- 🔄 PRD Ready / In Development
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

**Dikemaskini**: 13 Januari 2026
**Versi**: 2.0 (Updated untuk Poliklinik Al-Huda dengan module-based naming)
