# PRD: [Nama Modul/Feature]

**Kod PRD:** KLINIK-[NamaModul]-PR[YYYY]-[NN]-[nama-feature]
**Dicipta:** [Tarikh]
**Penulis:** [Nama]
**Dikemaskini:** [Tarikh]

### Format Kod PRD

- **`KLINIK`** - Prefix tetap untuk projek Poliklinik Al-Huda
- **`[NamaModul]`** - Nama modul (contoh: PendaftaranPesakit, TemujanjiPesakit, Ubat, Billing)
- **`PR`** - Fixed prefix untuk "Product Requirement"
- **`[YYYY]`** - Tahun 4 digit (contoh: 2026)
- **`[NN]`** - Nombor sequential 2 digit bermula dari 01 setiap tahun untuk setiap modul
- **`[nama-feature]`** - Nama deskriptif dalam format kebab-case

**Contoh:**
- `KLINIK-PendaftaranPesakit-PR2026-01-pengurusan-maklumat-pesakit`
- `KLINIK-TemujanjiPesakit-PR2026-01-pengurusan-temujanji`
- `KLINIK-Ubat-PR2026-01-pengurusan-inventori`

---

## 1. Ringkasan Eksekutif

### 1.1 Gambaran Keseluruhan
[Berikan ringkasan 2-3 ayat tentang apa yang dilakukan oleh feature ini dan mengapa ia dibina]

### 1.2 Metadata
- **Nama Feature**: [Nama penuh yang deskriptif]
- **Modul**: [contoh: Pendaftaran Pesakit, Temujanji, Ubat, Rekod Perubatan]
- **Peranan Sasaran**: [contoh: Kerani, Jururawat, Doktor, Admin]
- **Keutamaan**: [Tinggi / Sederhana / Rendah]
- **Status**: [Perancangan / Dalam Pembangunan / Semakan / Selesai]
- **Anggaran Usaha**: [Kecil / Sederhana / Besar]

### 1.3 Objektif
[Senaraikan objektif utama feature ini (3-5 bullet points)]
- Objektif 1
- Objektif 2
- Objektif 3

### 1.4 Skop

**Dalam Skop:**
- Feature A
- Feature B
- Feature C

**Luar Skop:**
- Feature X
- Feature Y

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa
[Terangkan keadaan semasa dan masalah atau batasan yang wujud]

### 2.2 Impak Kepada Perniagaan
[Terangkan bagaimana masalah ini memberi impak kepada operasi klinik, pesakit, atau staff]

### 2.3 Hasil Yang Diingini
[Terangkan keadaan ideal selepas feature ini dilaksanakan]

---

## 3. User Stories

### 3.1 User Stories Utama

- **Sebagai** [peranan], **saya mahu** [tindakan] **supaya** [faedah]

- **Sebagai** [peranan], **saya mahu** [tindakan] **supaya** [faedah]

- **Sebagai** [peranan], **saya mahu** [tindakan] **supaya** [faedah]

### 3.2 Edge Cases & User Stories Sekunder

- **Sebagai** [peranan], **bila** [kondisi], **saya sepatutnya** [tingkah laku yang dijangka]

- **Sebagai** [peranan], **bila** [kondisi], **saya sepatutnya** [tingkah laku yang dijangka]

**Nota Format:**
- Satu ayat sahaja untuk setiap user story
- Guna bold untuk keyword: **Sebagai**, **saya mahu**, **supaya**, **bila**, **saya sepatutnya**
- Tiada full stop di hujung ayat
- Tiada sub-bullets

---

## 4. Keperluan Fungsian

### 4.1 Ciri-ciri Teras

- [ ] **FR-01:** [Penerangan keperluan]
- [ ] **FR-02:** [Penerangan keperluan]
- [ ] **FR-03:** [Penerangan keperluan]

### 4.2 Kebenaran & Kawalan Akses

- **Peranan Diperlukan**: [Senaraikan peranan yang boleh akses feature ini]
- **Kebenaran Diperlukan**: [Senaraikan permission spesifik yang diperlukan]
- **Authorization Logic**: [Terangkan sebarang peraturan authorization yang kompleks]

### 4.3 Validasi Data

- **Field Wajib**: [Senaraikan field form yang wajib diisi]
- **Peraturan Validasi**: [Terangkan logik validasi, contoh: email unik, min/max length]
- **Peraturan Perniagaan**: [Sebarang logik perniagaan khas, contoh: "Tidak boleh delete pesakit yang ada temujanji aktif"]

### 4.4 Audit Trail & PDPA Compliance

- [ ] **Adakah feature ini perlu audit trail?** [Ya/Tidak]
- **Field Audit**: [created_by, updated_by, deleted_at]
- **Data Consent**: [Adakah pesakit perlu beri consent untuk data ini?]
- **Data Retention**: [Berapa lama data disimpan? Boleh delete secara kekal atau soft delete sahaja?]

---

## 5. Keperluan Teknikal

### 5.1 Teknologi Stack

- **Framework**: Laravel 12
- **Frontend**: Blade Templates + Bootstrap 5 + CoreUI
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze/Sanctum
- **File Storage**: Laravel Storage (local/S3)
- **Queue**: Laravel Queue (database/Redis)
- **Cache**: Redis/File Cache

### 5.2 Arsitektur Aplikasi

Mengikut pattern yang ditetapkan dalam `DEVELOPER_GUIDE.md`:

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
│   │       └── [ModuleName]Controller.php (dengan Route Attributes)
│   └── Requests/
│       ├── Store[ModuleName]Request.php
│       └── Update[ModuleName]Request.php
├── Services/
│   └── [ModuleName]Service.php
├── Repositories/
│   └── [ModuleName]Repository.php
├── Models/
│   └── [ModelName].php
├── Traits/
│   └── [TraitName].php (jika perlu)
└── Exceptions/
    └── [ModuleName]Exception.php (jika perlu)

config/
└── [module_name].php (configuration file)

resources/
└── views/
    └── admin/
        └── [module_name]/
            ├── index.blade.php
            ├── create.blade.php
            ├── edit.blade.php
            └── show.blade.php
```

### 5.4 Command untuk Generate

```bash
# Model dengan migration dan factory
php artisan make:model [ModelName] -mf

# Controller
php artisan make:controller Admin/[ModuleName]Controller

# FormRequests
php artisan make:request Store[ModelName]Request
php artisan make:request Update[ModelName]Request

# Service (manual create)
# Create file: app/Services/[ModuleName]Service.php

# Repository (manual create)
# Create file: app/Repositories/[ModuleName]Repository.php

# Exception (optional)
php artisan make:exception [ModuleName]Exception
```

### 5.5 Database Schema

#### Jadual Baharu

**Jadual: `[table_name]`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `nama_field` | varchar(255) NOT NULL | Penerangan |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `updated_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Waktu rekod dicipta |
| `updated_at` | timestamp | Waktu rekod dikemaskini |
| `deleted_at` | timestamp NULL | Soft delete |

**Indexes:**
- `idx_field_name` on `field_name`

**Foreign Keys:**
- `fk_table_user_created` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
- `fk_table_user_updated` FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL

#### Pengubahsuaian Jadual Sedia Ada

- [ ] **Jadual**: `existing_table`
  - **Perubahan**: [Tambah/ubah/buang column]

### 5.6 Model Eloquent

#### Model Baharu

**Model: `[ModelName]`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class [ModelName] extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = '[table_name]';

    protected $fillable = [
        'field1', 'field2', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_field' => 'date',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return config("module.status_badges.{$this->status}", $this->status);
    }
}
```

**Relationships:**
- `belongsTo()` - [Related model]
- `hasMany()` - [Related model]

**Factory**: [Ya/Tidak]
**Seeder**: [Ya/Tidak]

### 5.7 Configuration File

**File: `config/[module_name].php`**

```php
<?php

return [
    // Kod generation
    'kod_prefix' => '[PREFIX]',
    'kod_format' => '[PREFIX]-YYYYMMDD-9999',

    // Categories/Statuses
    'categories' => ['category1', 'category2'],
    'statuses' => ['active', 'inactive'],

    // Labels (Bahasa Malaysia)
    'category_labels' => [
        'category1' => 'Kategori 1',
        'category2' => 'Kategori 2',
    ],

    'status_labels' => [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
    ],

    'status_badges' => [
        'active' => '<span class="badge badge-success">Aktif</span>',
        'inactive' => '<span class="badge badge-secondary">Tidak Aktif</span>',
    ],

    // Business rules
    'max_records_per_page' => 15,
    'threshold_value' => 100,
];
```

### 5.8 Routes (Route Attributes)

**File: `app/Http/Controllers/Admin/[ModuleName]Controller.php`**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\[ModelName];
use App\Http\Requests\Store[ModelName]Request;
use App\Http\Requests\Update[ModelName]Request;
use App\Services\[ModuleName]Service;
use App\Traits\HandlesApiResponses;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('admin/[module-name]')]
#[Middleware(['web', 'auth'])]
class [ModuleName]Controller extends Controller
{
    use HandlesApiResponses;

    protected [ModuleName]Service $service;

    public function __construct([ModuleName]Service $service)
    {
        $this->service = $service;
    }

    #[Get('/', name: 'admin.[module-name].index')]
    public function index()
    {
        // Implementation
    }

    #[Get('/create', name: 'admin.[module-name].create')]
    public function create()
    {
        // Implementation
    }

    #[Post('/', name: 'admin.[module-name].store')]
    public function store(Store[ModelName]Request $request)
    {
        try {
            $this->service->create($request->validated());
            return $this->successRedirect('admin.[module-name].index', 'Rekod berjaya dicipta');
        } catch (\Exception $e) {
            Log::error('[ModuleName] creation failed', ['error' => $e->getMessage()]);
            return $this->errorRedirect('Gagal mencipta rekod');
        }
    }

    #[Get('/{[model]}', name: 'admin.[module-name].show')]
    public function show([ModelName] $[model])
    {
        // Implementation
    }

    #[Get('/{[model]}/edit', name: 'admin.[module-name].edit')]
    public function edit([ModelName] $[model])
    {
        // Implementation
    }

    #[Patch('/{[model]}', name: 'admin.[module-name].update')]
    public function update(Update[ModelName]Request $request, [ModelName] $[model])
    {
        try {
            $this->service->update($[model]->id, $request->validated());
            return $this->successRedirect('admin.[module-name].index', 'Rekod berjaya dikemaskini');
        } catch (\Exception $e) {
            Log::error('[ModuleName] update failed', ['id' => $[model]->id, 'error' => $e->getMessage()]);
            return $this->errorRedirect('Gagal mengemaskini rekod');
        }
    }

    #[Delete('/{[model]}', name: 'admin.[module-name].destroy')]
    public function destroy([ModelName] $[model])
    {
        try {
            $this->service->delete($[model]->id);
            return $this->successRedirect('admin.[module-name].index', 'Rekod berjaya dihapus');
        } catch (\Exception $e) {
            Log::error('[ModuleName] deletion failed', ['id' => $[model]->id, 'error' => $e->getMessage()]);
            return $this->errorRedirect('Gagal menghapus rekod');
        }
    }
}
```

**Route Middleware**: `['web', 'auth']`

### 5.9 FormRequest Validation

**File: `app/Http/Requests/Store[ModelName]Request.php`**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Store[ModelName]Request extends FormRequest
{
    public function authorize(): bool
    {
        return true; // atau check permissions
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', config('module.categories')),
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama adalah wajib',
            'category.required' => 'Kategori adalah wajib',
        ];
    }
}
```

---

## 6. Workflow dan User Flow

### 6.1 Workflow Utama

```
[Pengguna] → [Tindakan 1]
    ↓
[Sistem] Validasi [kondisi]
    ↓
[Sistem] Lakukan [operasi]
    ↓
[Pengguna] Lihat [feedback/hasil]
```

### 6.2 State Management

**Status Flow:**
```
[Status Awal] → [Status 2] → [Status 3] → [Status Akhir]
```

### 6.3 Error Handling

**Exception Handling:**
- Guna `try-catch` blocks dalam controller methods
- Log semua errors dengan context
- Return user-friendly error messages
- Guna `HandlesApiResponses` trait untuk consistent responses

---

## 7. Keperluan UI/UX

### 7.1 Layout

- **Jenis Halaman**: [Full page / Modal / Inline section]
- **Navigation**: [Tambah ke menu? Ya/Tidak - nyatakan lokasi menu]

### 7.2 Bootstrap 5 + CoreUI Components

Senaraikan component yang digunakan:
- [ ] **Card** - [Tujuan]
- [ ] **Table** - [Tujuan]
- [ ] **Form** - [Tujuan]
- [ ] **Modal** - [Tujuan]
- [ ] **Badge** - [Tujuan]
- [ ] **Button** - [Tujuan]

### 7.3 Icons

- **Heroicons**: [contoh: heroicon-o-users, heroicon-o-calendar]

### 7.4 Responsive Design

- **Mobile Support**: [Ya/Tidak]
- **Tablet Support**: [Ya/Tidak]
- **Breakpoints**: [Specify jika ada custom breakpoints]

---

## 8. Keperluan Keselamatan

### 8.1 Authentication & Authorization

- **Authentication**: Laravel Breeze/Sanctum
- **Middleware**: `auth` untuk semua admin routes
- **Role-based Access**: [Senaraikan roles yang boleh akses]

### 8.2 Data Protection (PDPA Compliance)

- **Audit Trail**: Rekod created_by, updated_by untuk semua operasi
- **Soft Delete**: Guna soft delete untuk data sensitive
- **Consent**: Rekod consent pesakit untuk penggunaan data
- **Data Encryption**: [Jika perlu encrypt field tertentu]

### 8.3 Input Validation & Security

- **CSRF Protection**: Semua POST/PATCH/DELETE dilindungi CSRF token
- **SQL Injection Prevention**: Guna Eloquent ORM
- **XSS Prevention**: Guna Blade `{{ }}` escaping
- **File Upload Security**: Validate file type, size, scan malware (jika applicable)

---

## 9. Keperluan Prestasi

### 9.1 Response Time

- **Halaman Senarai**: < 2 saat
- **Halaman Form**: < 1 saat
- **Submit Form**: < 3 saat

### 9.2 Scalability

- **Database Indexing**: Index pada foreign keys dan search fields
- **Query Optimization**: Guna eager loading untuk relationships
- **Caching**: Cache configuration files dan static data
- **Pagination**: Limit records per page (default: 15)

### 9.3 Concurrent Users

- **Expected Users**: [Nyatakan bilangan concurrent users dijangka]

---

## 10. Keperluan Ujian

### 10.1 Unit Testing

Create tests in `tests/Unit/[Feature]Test.php`:

- [ ] **Test**: Model relationships work correctly
- [ ] **Test**: Service methods return expected results
- [ ] **Test**: Validation rules are enforced

### 10.2 Feature Testing

Create tests in `tests/Feature/[ModuleName]Test.php`:

- [ ] **Test**: Authenticated user can view index page
- [ ] **Test**: Authenticated user can create record
- [ ] **Test**: Authenticated user can update record
- [ ] **Test**: Authenticated user can delete record
- [ ] **Test**: Unauthenticated user cannot access pages
- [ ] **Test**: Validation rules are enforced on submit

```php
public function test_authenticated_user_can_view_index()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('admin.[module-name].index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.[module-name].index');
}
```

### 10.3 Integration Testing

- [ ] **Test**: Integration dengan SMS gateway (jika applicable)
- [ ] **Test**: Queue processing
- [ ] **Test**: File upload dan storage

### 10.4 User Acceptance Testing (UAT)

**Scenario 1**: [Terangkan scenario testing]
- Expected Result: [Apa yang dijangka berlaku]

**Scenario 2**: [Terangkan scenario testing]
- Expected Result: [Apa yang dijangka berlaku]

---

## 11. Langkah Implementasi

### 11.1 Fasa 1: Setup & Database (Minggu [X])

- [ ] Create migrations untuk jadual baharu
- [ ] Create Model Eloquent dengan relationships
- [ ] Create configuration file
- [ ] Run migrations dan seed sample data

### 11.2 Fasa 2: Repository & Service Layer (Minggu [X])

- [ ] Create Repository dengan methods: `create()`, `update()`, `delete()`, `search()`
- [ ] Create Service dengan business logic
- [ ] Create custom Exception (jika perlu)

### 11.3 Fasa 3: FormRequest Validation (Minggu [X])

- [ ] Create `Store[ModelName]Request`
- [ ] Create `Update[ModelName]Request`
- [ ] Test validation rules

### 11.4 Fasa 4: Controller & Routes (Minggu [X])

- [ ] Create Controller dengan Route Attributes
- [ ] Implement CRUD methods
- [ ] Add error handling dengan `HandlesApiResponses` trait
- [ ] Clear route cache: `php artisan route:clear`

### 11.5 Fasa 5: Views & UI (Minggu [X])

- [ ] Create Blade templates (index, create, edit, show)
- [ ] Integrate Bootstrap 5 + CoreUI components
- [ ] Add responsive design
- [ ] Test UI di browser

### 11.6 Fasa 6: Testing (Minggu [X])

- [ ] Write unit tests
- [ ] Write feature tests
- [ ] Perform manual UAT
- [ ] Fix bugs

### 11.7 Fasa 7: Deployment (Minggu [X])

- [ ] Deploy ke production server
- [ ] Training untuk pengguna
- [ ] Monitor error logs

---

## 12. Kriteria Kejayaan

### 12.1 Metrics Utama

- **Metric 1**: [Target value]
- **Metric 2**: [Target value]
- **Metric 3**: [Target value]

### 12.2 User Satisfaction

- **Kepuasan Pengguna**: > 4.0/5.0 (survey)

### 12.3 Technical Metrics

- **Uptime**: > 99%
- **Response Time**: < 2 saat untuk 95% requests
- **Bug Rate**: < 5 bugs per bulan selepas deployment

---

## 13. Risks & Mitigation

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| [Risiko 1] | [High/Medium/Low] | [High/Medium/Low] | [Cara mitigation] |
| [Risiko 2] | [High/Medium/Low] | [High/Medium/Low] | [Cara mitigation] |

---

## 14. Dependencies

### 14.1 External Packages

- [ ] **Package Name**: [contoh: spatie/laravel-permission]
  - **Version**: [Specify version jika kritikal]
  - **Purpose**: [Mengapa diperlukan?]

### 14.2 Related Features/Modules

- **Bergantung Kepada**: [Senaraikan features yang mesti wujud dahulu]
- **Memberi Impak Kepada**: [Senaraikan features yang akan terjejas oleh perubahan ini]

### 14.3 Third-Party Integrations

- [ ] **Service**: [contoh: SMS gateway, Email service]
  - **Configuration Required**: [Environment variables, API keys]

---

## 15. Acceptance Criteria

### 15.1 Functional Acceptance

- [ ] Semua functional requirements (FR-01, FR-02, dll) dilaksanakan
- [ ] Semua user stories dapat diselesaikan dengan jayanya
- [ ] Authorization berfungsi seperti yang dinyatakan
- [ ] Data validation enforce semua peraturan
- [ ] Error handling memberikan feedback yang jelas

### 15.2 Technical Acceptance

- [ ] Semua feature tests lulus
- [ ] Semua unit tests lulus
- [ ] Kod mengikut conventions dari `DEVELOPER_GUIDE.md` dan `.github/copilot-instructions.md`
- [ ] Kod diformat dengan `./vendor/bin/pint`
- [ ] Tiada N+1 query problems (guna eager loading)
- [ ] Route cache cleared selepas tambah routes

### 15.3 Quality Acceptance

- [ ] Kod di-review oleh peer
- [ ] Manual testing selesai
- [ ] Tiada console errors atau warnings
- [ ] Responsive design berfungsi di mobile/tablet
- [ ] Accessibility considerations ditangani

### 15.4 Documentation Acceptance

- [ ] PRD dikemaskini dengan implementation notes akhir
- [ ] DEVELOPER_GUIDE.md dikemaskini (jika perlu)
- [ ] Inline code comments untuk logik yang kompleks

---

## 16. Lampiran

### 16.1 Contoh Screenshots/Wireframes

[Tambah screenshots atau wireframes UI jika ada]

### 16.2 Database ER Diagram

[Tambah ER diagram untuk jadual-jadual yang terlibat]

### 16.3 References

- [Link ke PRD berkaitan]
- [Link ke design mockups]
- [Link ke dokumentasi API]

### 16.4 Change Log

| Tarikh | Penulis | Perubahan |
|--------|---------|-----------|
| [Tarikh] | [Nama] | PRD awal dicipta |
| [Tarikh] | [Nama] | [Penerangan perubahan] |

### 16.5 Approval

- [ ] **Product Owner**: [Nama] - [Tarikh]
- [ ] **Tech Lead**: [Nama] - [Tarikh]
- [ ] **Pengurus Klinik**: [Nama] - [Tarikh]
- [ ] **Stakeholders**: [Nama-nama] - [Tarikh]

---

**Status Implementasi**: [Belum Bermula / Dalam Pembangunan / Selesai]
**Tarikh Selesai**: [Tarikh bila feature deploy ke production]

---

**Catatan**: Dokumen ini adalah living document dan akan dikemaskini mengikut keperluan semasa development.
