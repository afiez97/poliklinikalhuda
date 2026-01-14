# Product Requirements Document (PRD)
# Sistem Temujanji Pesakit - Klinik Swasta

**Kod Dokumen**: KLINIK-TemujanjiPesakit-PR2026-01
**Tajuk**: Pengurusan Temujanji Pesakit
**Versi**: 1.0
**Tarikh**: 12 Januari 2026
**Penulis**: Product Team
**Status**: Draft

---

## 1. Ringkasan Eksekutif

### 1.1 Gambaran Keseluruhan
Sistem Pengurusan Temujanji Pesakit adalah modul untuk menguruskan tempahan, penjadualan, dan penjejakan temujanji pesakit di klinik swasta. Sistem ini membolehkan pesakit membuat tempahan secara online atau walk-in, mengurus slot temujanji doktor, dan menghantar notifikasi automatik.

### 1.2 Objektif
- Memudahkan pesakit membuat temujanji tanpa perlu datang ke klinik
- Mengurangkan masa menunggu dan sesak kaunter
- Mengoptimumkan jadual doktor dengan pengurusan slot yang efisien
- Mengurangkan kadar no-show melalui sistem reminder automatik
- Meningkatkan pengalaman pesakit dan produktiviti kakitangan

### 1.3 Skop
**Dalam Skop:**
- Tempahan temujanji online (self-service portal)
- Tempahan temujanji walk-in oleh kerani
- Pengurusan slot doktor (tetap + dinamik)
- Notifikasi SMS/WhatsApp automatik
- Check-in pesakit untuk temujanji
- Pembatalan dan reschedule temujanji
- Dashboard doktor dan kerani
- Kalendar temujanji
- Pengurusan no-show dan blacklist
- Laporan dan statistik temujanji

**Luar Skop:**
- Pembayaran online temujanji
- Integrasi video consultation
- Sistem queue management
- Sistem prescription elektronik

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa
1. Pesakit perlu datang ke klinik atau telefon untuk buat temujanji
2. Tiada sistem reminder menyebabkan kadar no-show tinggi (15-20%)
3. Jadual doktor tidak tersusun, menyebabkan waktu lapang atau terlalu padat
4. Sukar untuk mengurus pembatalan dan reschedule temujanji
5. Tiada tracking untuk pesakit yang selalu no-show
6. Kerani menghabiskan banyak masa mengurus temujanji secara manual
7. Pesakit tidak dapat melihat ketersediaan slot doktor dengan mudah

### 2.2 Impak Kepada Perniagaan
- Kehilangan pesakit yang tidak sabar menunggu atau tidak dapat hubungi klinik
- Kerugian hasil dari no-show yang tinggi
- Penggunaan masa doktor tidak optimum
- Kepuasan pesakit menurun
- Beban kerja kerani tinggi untuk urusan pentadbiran

---

## 3. User Stories Utama

### 3.1 Pesakit
- **Sebagai** Pesakit, **saya mahu** membuat temujanji online melalui portal web **supaya** saya tidak perlu datang ke klinik atau telefon untuk buat temujanji
- **Sebagai** Pesakit, **saya mahu** memilih doktor dan waktu yang sesuai **supaya** saya boleh merancang jadual harian saya dengan baik
- **Sebagai** Pesakit, **saya mahu** menerima notifikasi SMS/WhatsApp untuk reminder **supaya** saya tidak terlupa temujanji saya
- **Sebagai** Pesakit, **saya mahu** membatalkan atau menukar masa temujanji dengan mudah **bila** ada halangan atau perubahan jadual **supaya** saya tidak perlu datang ke klinik
- **Sebagai** Pesakit, **saya mahu** melihat sejarah temujanji saya **supaya** saya boleh jejak rekod lawatan saya

### 3.2 Kerani Kaunter
- **Sebagai** Kerani kaunter, **saya mahu** membuat temujanji untuk pesakit walk-in dengan cepat **supaya** pesakit tidak perlu menunggu lama di kaunter
- **Sebagai** Kerani kaunter, **saya mahu** melihat kalendar temujanji semua doktor **supaya** saya boleh cari slot yang available dengan mudah
- **Sebagai** Kerani kaunter, **saya mahu** check-in pesakit yang datang untuk temujanji **supaya** doktor tahu pesakit sudah sampai
- **Sebagai** Kerani kaunter, **saya mahu** mengurus pembatalan dan reschedule temujanji **bila** pesakit buat permohonan **supaya** slot dapat dibuka untuk pesakit lain
- **Sebagai** Kerani kaunter, **saya mahu** melihat statistik no-show dan cancellation **supaya** saya boleh follow-up dengan pesakit yang bermasalah

### 3.3 Doktor
- **Sebagai** Doktor, **saya mahu** melihat senarai temujanji hari ini di dashboard **supaya** saya tahu berapa pesakit yang perlu saya jumpa dan boleh merancang masa dengan baik
- **Sebagai** Doktor, **saya mahu** menetapkan slot waktu available saya **supaya** pesakit hanya boleh buat temujanji pada waktu yang saya tetapkan
- **Sebagai** Doktor, **saya mahu** menutup slot tertentu untuk cuti atau mesyuarat **bila** saya tidak available **supaya** tiada pesakit book pada waktu tersebut
- **Sebagai** Doktor, **saya mahu** melihat maklumat pesakit sebelum temujanji bermula **supaya** saya boleh prepare dan baca sejarah perubatan mereka

### 3.4 Pengurus Klinik
- **Sebagai** Pengurus klinik, **saya mahu** melihat laporan temujanji harian/bulanan **supaya** saya boleh analisa trend dan buat keputusan operasi
- **Sebagai** Pengurus klinik, **saya mahu** menetapkan polisi no-show dan cancellation **supaya** sistem dapat enforce peraturan secara automatik
- **Sebagai** Pengurus klinik, **saya mahu** blacklist pesakit yang selalu no-show **supaya** mereka tidak boleh buat temujanji untuk tempoh tertentu
- **Sebagai** Pengurus klinik, **saya mahu** melihat statistik penggunaan doktor **supaya** saya boleh optimumkan jadual dan tambah doktor jika perlu

---

## 4. Keperluan Fungsian

### 4.1 Modul Tempahan Temujanji Online (Self-Service Portal)

#### 4.1.1 Portal Pesakit
- Pesakit boleh register/login ke portal
- Pesakit boleh pilih jenis temujanji:
  - Temujanji Umum (general check-up)
  - Temujanji Mengikut Doktor (pilih doktor tertentu)
  - Temujanji Mengikut Jabatan (Pediatrik, Dermatologi, dll)
  - Temujanji Mengikut Perkhidmatan (Vaksinasi, Health Screening, dll)
- Pesakit boleh lihat kalendar slot yang available
- Pesakit boleh pilih tarikh dan masa
- Pesakit boleh masukkan catatan/reason for visit
- Pesakit terima confirmation SMS/WhatsApp selepas booking
- Pesakit boleh lihat senarai temujanji mereka (upcoming & past)

#### 4.1.2 Kalendar Slot Available
- Display kalendar bulanan dengan tarikh yang available
- Display time slots mengikut doktor/jabatan yang dipilih
- Slot yang penuh ditunjukkan dengan warna kelabu/disabled
- Slot yang available ditunjukkan dengan warna hijau/enabled
- Real-time update bila slot di-book atau dibatalkan

### 4.2 Modul Tempahan Temujanji Walk-in (Kerani)

#### 4.2.1 Skrin Tempahan
- Kerani boleh search pesakit mengikut No. IC atau nama
- Kerani boleh register pesakit baharu jika belum wujud (link ke modul Pendaftaran Pesakit)
- Kerani boleh pilih jenis temujanji
- Kerani boleh pilih doktor/jabatan/perkhidmatan
- Kerani boleh pilih tarikh dan slot masa
- Kerani boleh masukkan catatan
- Kerani boleh print confirmation slip untuk pesakit

#### 4.2.2 Dashboard Kerani
- Senarai semua temujanji hari ini (by status)
- Carian pantas temujanji (by No. IC, nama, kod temujanji)
- Kalendar view untuk semua doktor
- Statistik hari ini: Total temujanji, Pending, Checked-in, Completed, No-show, Cancelled
- Notification untuk temujanji yang akan bermula dalam 15 minit

### 4.3 Pengurusan Slot Doktor

#### 4.3.1 Konfigurasi Slot Tetap
- Admin boleh tetapkan slot waktu kerja doktor (contoh: 9am-12pm, 2pm-5pm)
- Admin boleh tetapkan durasi setiap slot (15 min, 30 min, 45 min, 60 min)
- Admin boleh tetapkan maksimum pesakit per slot
- Admin boleh tetapkan hari bekerja (Isnin-Sabtu)

#### 4.3.2 Slot Dinamik
- Doktor boleh tambah slot khas (contoh: slot emergency)
- Doktor boleh tutup slot untuk cuti/mesyuarat
- Doktor boleh tukar durasi slot untuk kes tertentu
- Admin boleh override slot jika perlu

#### 4.3.3 Slot Mengikut Doktor vs Jabatan
- Untuk temujanji "by doctor", sistem check slot doktor tersebut
- Untuk temujanji "by department", sistem check slot semua doktor dalam jabatan
- Untuk temujanji "umum", sistem cadangkan doktor yang paling available

### 4.4 Check-in Pesakit

#### 4.4.1 Proses Check-in
- Kerani boleh check-in pesakit bila mereka sampai
- Status temujanji tukar dari "Dijadualkan" → "Checked-in"
- Doktor dapat notification di dashboard bahawa pesakit sudah sampai
- Sistem rekod waktu check-in untuk statistik waiting time

#### 4.4.2 Early/Late Check-in
- Sistem allow check-in 30 minit sebelum waktu temujanji
- Sistem mark "Late" jika check-in lewat 15 minit dari waktu temujanji
- Sistem auto mark "No-show" jika tidak check-in dalam 30 minit selepas waktu temujanji

### 4.5 Notifikasi SMS/WhatsApp Automatik

#### 4.5.1 Jenis Notifikasi
1. **Confirmation Booking**
   - Hantar selepas temujanji berjaya di-book
   - Mengandungi: Kod temujanji, tarikh, masa, nama doktor, lokasi klinik

2. **Reminder 24 Jam Sebelum**
   - Hantar 24 jam sebelum waktu temujanji
   - Mengandungi: Reminder, tarikh, masa, nama doktor, link untuk reschedule/cancel

3. **Reminder 2 Jam Sebelum**
   - Hantar 2 jam sebelum waktu temujanji
   - Mengandungi: Reminder terakhir, arahan check-in, nombor telefon klinik

4. **Notification Reschedule/Cancellation**
   - Hantar bila temujanji di-reschedule atau dibatalkan
   - Mengandungi: Status, sebab (jika ada), tarikh baharu (untuk reschedule)

#### 4.5.2 Template Notifikasi
- Admin boleh edit template notifikasi
- Sokongan multi-bahasa (BM & Inggeris)
- Placeholder untuk data dinamik: {nama}, {tarikh}, {masa}, {doktor}, {kod_temujanji}

### 4.6 Pembatalan Temujanji

#### 4.6.1 Pembatalan oleh Pesakit (Online)
- Pesakit boleh cancel temujanji melalui portal
- Pesakit boleh cancel melalui link dalam SMS/WhatsApp
- Sistem enforce minimum notice: 2 jam sebelum waktu temujanji
- Pesakit perlu bagi sebab pembatalan (optional, for feedback)
- Sistem hantar confirmation SMS selepas pembatalan

#### 4.6.2 Pembatalan oleh Kerani
- Kerani boleh cancel temujanji dengan sebab
- Tiada had masa (boleh cancel walaupun kurang 2 jam)
- Sistem rekod siapa yang cancel dan bila

#### 4.6.3 Pembatalan oleh Doktor/Klinik
- Admin/Doktor boleh cancel temujanji (contoh: doktor sakit, cuti kecemasan)
- Sistem auto notify pesakit dengan penjelasan
- Sistem tawarkan slot alternatif untuk reschedule

### 4.7 Reschedule Temujanji

#### 4.7.1 Reschedule oleh Pesakit
- Pesakit boleh tukar tarikh/masa melalui portal atau link SMS
- Maksimum 2 kali reschedule per temujanji
- Minimum notice: 2 jam sebelum waktu temujanji lama
- Pesakit pilih slot baharu dari kalendar available
- Sistem hantar confirmation SMS untuk slot baharu

#### 4.7.2 Reschedule oleh Kerani
- Kerani boleh reschedule tanpa had bilangan kali
- Tiada had minimum notice
- Kerani boleh override polisi jika perlu (dengan sebab)

### 4.8 Pengurusan No-Show

#### 4.8.1 Auto Detection No-Show
- Sistem auto mark "No-show" jika:
  - Pesakit tidak check-in dalam 30 minit selepas waktu temujanji
  - Pesakit tidak cancel temujanji sebelum waktu tamat
- Sistem rekod history no-show untuk setiap pesakit

#### 4.8.2 Polisi No-Show
- Selepas 3 kali no-show: Pesakit di-blacklist automatik untuk 30 hari
- Pesakit yang di-blacklist tidak boleh buat temujanji online
- Kerani boleh override dan allow booking (dengan approval pengurus)
- Sistem hantar warning SMS selepas no-show ke-2

#### 4.8.3 Blacklist Management
- Admin boleh lihat senarai pesakit yang di-blacklist
- Admin boleh unblock pesakit sebelum tempoh tamat
- Sistem auto unblock selepas 30 hari
- Sistem rekod sebab blacklist dan siapa yang unblock

### 4.9 Dashboard Doktor

#### 4.9.1 Jadual Hari Ini
- Senarai temujanji hari ini (by time)
- Status setiap temujanji: Pending, Checked-in, Sedang Rawatan, Selesai
- Nama pesakit, umur, sebab lawatan
- Button untuk mark "Sedang Rawatan" dan "Selesai"

#### 4.9.2 Kalendar Slot
- View kalendar mingguan/bulanan slot doktor
- Doktor boleh tambah/tutup slot
- Doktor boleh lihat bilangan pesakit per slot

#### 4.9.3 Quick Actions
- Button check-in pesakit (jika pesakit sampai terus ke bilik doktor)
- Button lihat rekod perubatan pesakit (link ke modul lain)
- Button tambah notes untuk temujanji

### 4.10 Laporan dan Statistik

#### 4.10.1 Laporan Harian
- Total temujanji dibuat
- Breakdown by status: Completed, No-show, Cancelled
- Breakdown by doktor
- Average waiting time (dari check-in hingga rawatan)

#### 4.10.2 Laporan Bulanan
- Trend temujanji (chart line)
- Kadar no-show percentage
- Doktor utilization rate
- Peak hours dan peak days

#### 4.10.3 Laporan Pesakit
- Pesakit paling kerap buat temujanji
- Pesakit dengan no-show terbanyak
- Pesakit baharu vs pesakit lama

---

## 5. Keperluan Teknikal

### 5.1 Teknologi
- **Framework**: Laravel 12
- **Frontend**: Blade Templates + Bootstrap 5 + CoreUI
- **Database**: MySQL 8.0
- **SMS Gateway**: Twilio / local provider (MSG91, Infobip)
- **WhatsApp API**: WhatsApp Business API / Twilio WhatsApp
- **Notification Queue**: Laravel Queue (database driver atau Redis)
- **Scheduler**: Laravel Task Scheduler untuk auto-reminder dan auto no-show detection

### 5.2 Arsitektur Aplikasi
Mengikut pattern yang ditetapkan dalam `DEVELOPER_GUIDE.md`:
```
Route Attributes (Controller)
   ↓
FormRequest (Validation)
   ↓
Service Layer (Business Logic)
   ↓
Repository Layer (Data Access)
   ↓
Model (Eloquent ORM)
   ↓
Database
```

### 5.3 Struktur Jadual Database

#### 5.3.1 Jadual: `temujanji`
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kod_temujanji` | varchar(50) UNIQUE NOT NULL | Auto-generated (APT-YYYYMMDD-0001) |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `doktor_id` | bigint UNSIGNED NULL | FK → users.id (role=doktor), NULL untuk temujanji umum |
| `jabatan_id` | bigint UNSIGNED NULL | FK → jabatan.id |
| `perkhidmatan` | varchar(100) NULL | Jenis perkhidmatan (Vaksinasi, Health Screening, dll) |
| `jenis_temujanji` | enum('umum','by_doktor','by_jabatan','by_perkhidmatan') NOT NULL | Jenis temujanji |
| `tarikh_temujanji` | date NOT NULL | Tarikh temujanji |
| `masa_mula` | time NOT NULL | Masa mula slot |
| `masa_tamat` | time NOT NULL | Masa tamat slot |
| `status` | enum('pending','dijadualkan','checked_in','sedang_rawatan','selesai','cancelled','no_show') NOT NULL DEFAULT 'pending' | Status temujanji |
| `catatan_pesakit` | text NULL | Sebab lawatan atau catatan dari pesakit |
| `catatan_doktor` | text NULL | Notes dari doktor selepas temujanji |
| `waktu_check_in` | datetime NULL | Waktu pesakit check-in |
| `waktu_rawatan_mula` | datetime NULL | Waktu rawatan bermula |
| `waktu_selesai` | datetime NULL | Waktu temujanji selesai |
| `is_late` | boolean DEFAULT FALSE | Pesakit check-in lewat |
| `sebab_batal` | text NULL | Sebab pembatalan (jika cancelled) |
| `dibatalkan_oleh` | enum('pesakit','kerani','doktor','sistem') NULL | Siapa yang batalkan |
| `reschedule_count` | int DEFAULT 0 | Bilangan kali di-reschedule |
| `source` | enum('online','walk_in','telefon') NOT NULL | Sumber tempahan |
| `reminder_24h_sent` | boolean DEFAULT FALSE | Flag untuk reminder 24 jam |
| `reminder_2h_sent` | boolean DEFAULT FALSE | Flag untuk reminder 2 jam |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `updated_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Waktu rekod dicipta |
| `updated_at` | timestamp | Waktu rekod dikemaskini |
| `deleted_at` | timestamp NULL | Soft delete |

**Indexes:**
- `idx_pesakit_id` on `pesakit_id`
- `idx_doktor_id` on `doktor_id`
- `idx_tarikh_temujanji` on `tarikh_temujanji`
- `idx_status` on `status`
- `idx_kod_temujanji` on `kod_temujanji`

#### 5.3.2 Jadual: `slot_doktor`
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `doktor_id` | bigint UNSIGNED NOT NULL | FK → users.id (role=doktor) |
| `hari` | enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL | Hari bekerja |
| `masa_mula` | time NOT NULL | Masa mula slot |
| `masa_tamat` | time NOT NULL | Masa tamat slot |
| `durasi_slot` | int NOT NULL DEFAULT 30 | Durasi per slot (minit) |
| `max_pesakit_per_slot` | int NOT NULL DEFAULT 1 | Maksimum pesakit per slot |
| `is_active` | boolean DEFAULT TRUE | Slot aktif atau tidak |
| `created_at` | timestamp | Waktu rekod dicipta |
| `updated_at` | timestamp | Waktu rekod dikemaskini |

**Indexes:**
- `idx_doktor_hari` on (`doktor_id`, `hari`)

#### 5.3.3 Jadual: `slot_tutup`
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `doktor_id` | bigint UNSIGNED NOT NULL | FK → users.id (role=doktor) |
| `tarikh_mula` | date NOT NULL | Tarikh mula cuti/tutup |
| `tarikh_tamat` | date NOT NULL | Tarikh tamat cuti/tutup |
| `masa_mula` | time NULL | Masa mula (NULL = sepanjang hari) |
| `masa_tamat` | time NULL | Masa tamat (NULL = sepanjang hari) |
| `sebab` | varchar(255) NULL | Sebab tutup (Cuti, Mesyuarat, Emergency) |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Waktu rekod dicipta |
| `updated_at` | timestamp | Waktu rekod dikemaskini |

**Indexes:**
- `idx_doktor_tarikh` on (`doktor_id`, `tarikh_mula`, `tarikh_tamat`)

#### 5.3.4 Jadual: `pesakit_blacklist`
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `sebab` | text NOT NULL | Sebab blacklist (contoh: "3 kali no-show") |
| `tarikh_blacklist` | date NOT NULL | Tarikh mula blacklist |
| `tarikh_tamat_blacklist` | date NOT NULL | Tarikh tamat blacklist (30 hari dari mula) |
| `is_active` | boolean DEFAULT TRUE | Status blacklist aktif |
| `unblocked_by` | bigint UNSIGNED NULL | FK → users.id (siapa yang unblock) |
| `unblocked_at` | datetime NULL | Waktu di-unblock |
| `sebab_unblock` | text NULL | Sebab unblock |
| `created_at` | timestamp | Waktu rekod dicipta |
| `updated_at` | timestamp | Waktu rekod dikemaskini |

**Indexes:**
- `idx_pesakit_active` on (`pesakit_id`, `is_active`)

#### 5.3.5 Jadual: `notification_log`
| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `temujanji_id` | bigint UNSIGNED NOT NULL | FK → temujanji.id |
| `jenis_notifikasi` | enum('confirmation','reminder_24h','reminder_2h','reschedule','cancellation') NOT NULL | Jenis notifikasi |
| `channel` | enum('sms','whatsapp','email') NOT NULL | Kanal notifikasi |
| `nombor_telefon` | varchar(20) NOT NULL | Nombor penerima |
| `mesej` | text NOT NULL | Kandungan mesej |
| `status` | enum('pending','sent','failed') NOT NULL DEFAULT 'pending' | Status hantar |
| `sent_at` | datetime NULL | Waktu dihantar |
| `error_message` | text NULL | Error message jika gagal |
| `created_at` | timestamp | Waktu rekod dicipta |

**Indexes:**
- `idx_temujanji_jenis` on (`temujanji_id`, `jenis_notifikasi`)
- `idx_status` on `status`

#### 5.3.6 Relationship Dengan Jadual Sedia Ada
- `temujanji.pesakit_id` → `pesakit.id` (dari modul Pendaftaran Pesakit)
- `temujanji.doktor_id` → `users.id` WHERE `role='doktor'`
- `slot_doktor.doktor_id` → `users.id` WHERE `role='doktor'`

### 5.4 Konfigurasi (config/temujanji.php)
```php
return [
    // Kod temujanji
    'kod_prefix' => 'APT',  // Appointment
    'kod_format' => 'APT-YYYYMMDD-9999',  // APT-20260112-0001

    // Slot settings
    'default_slot_duration' => 30,  // minit
    'max_pesakit_per_slot' => 1,
    'allow_check_in_minutes_before' => 30,
    'mark_late_minutes_after' => 15,
    'mark_no_show_minutes_after' => 30,

    // Reschedule policy
    'max_reschedule_count' => 2,
    'min_notice_hours_for_reschedule' => 2,

    // Cancellation policy
    'min_notice_hours_for_cancellation' => 2,

    // No-show policy
    'no_show_threshold' => 3,
    'blacklist_duration_days' => 30,

    // Notification settings
    'reminder_24h_enabled' => true,
    'reminder_2h_enabled' => true,
    'sms_provider' => env('SMS_PROVIDER', 'twilio'),  // twilio, msg91, infobip
    'whatsapp_enabled' => env('WHATSAPP_ENABLED', true),

    // Status
    'statuses' => [
        'pending' => 'Menunggu Pengesahan',
        'dijadualkan' => 'Dijadualkan',
        'checked_in' => 'Sudah Check-in',
        'sedang_rawatan' => 'Sedang Rawatan',
        'selesai' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'no_show' => 'Tidak Hadir',
    ],

    'status_badges' => [
        'pending' => '<span class="badge badge-warning">Menunggu Pengesahan</span>',
        'dijadualkan' => '<span class="badge badge-info">Dijadualkan</span>',
        'checked_in' => '<span class="badge badge-primary">Sudah Check-in</span>',
        'sedang_rawatan' => '<span class="badge badge-success">Sedang Rawatan</span>',
        'selesai' => '<span class="badge badge-secondary">Selesai</span>',
        'cancelled' => '<span class="badge badge-danger">Dibatalkan</span>',
        'no_show' => '<span class="badge badge-dark">Tidak Hadir</span>',
    ],

    // Jenis temujanji
    'jenis_temujanji' => [
        'umum' => 'Temujanji Umum',
        'by_doktor' => 'Temujanji Mengikut Doktor',
        'by_jabatan' => 'Temujanji Mengikut Jabatan',
        'by_perkhidmatan' => 'Temujanji Mengikut Perkhidmatan',
    ],
];
```

### 5.5 Model Eloquent

#### TemuJanji Model (app/Models/TemuJanji.php)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemuJanji extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'temujanji';

    protected $fillable = [
        'kod_temujanji', 'pesakit_id', 'doktor_id', 'jabatan_id', 'perkhidmatan',
        'jenis_temujanji', 'tarikh_temujanji', 'masa_mula', 'masa_tamat',
        'status', 'catatan_pesakit', 'catatan_doktor', 'waktu_check_in',
        'waktu_rawatan_mula', 'waktu_selesai', 'is_late', 'sebab_batal',
        'dibatalkan_oleh', 'reschedule_count', 'source', 'reminder_24h_sent',
        'reminder_2h_sent', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'tarikh_temujanji' => 'date',
        'waktu_check_in' => 'datetime',
        'waktu_rawatan_mula' => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_late' => 'boolean',
        'reminder_24h_sent' => 'boolean',
        'reminder_2h_sent' => 'boolean',
        'reschedule_count' => 'integer',
    ];

    // Relationships
    public function pesakit()
    {
        return $this->belongsTo(Pesakit::class, 'pesakit_id');
    }

    public function doktor()
    {
        return $this->belongsTo(User::class, 'doktor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeHariIni($query)
    {
        return $query->whereDate('tarikh_temujanji', today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        return config("temujanji.status_badges.{$this->status}", $this->status);
    }

    // Methods
    public function canReschedule(): bool
    {
        return $this->reschedule_count < config('temujanji.max_reschedule_count', 2);
    }

    public function canCancel(): bool
    {
        $now = now();
        $temujanji_time = $this->tarikh_temujanji->setTimeFromTimeString($this->masa_mula);
        $min_notice_hours = config('temujanji.min_notice_hours_for_cancellation', 2);

        return $now->diffInHours($temujanji_time) >= $min_notice_hours;
    }
}
```

---

## 6. Workflow dan User Flow

### 6.1 Workflow Tempahan Temujanji Online (Pesakit)

```
[Pesakit] → Login/Register Portal
    ↓
Pilih Jenis Temujanji (Umum/By Doktor/By Jabatan/By Perkhidmatan)
    ↓
Pilih Doktor/Jabatan (jika applicable)
    ↓
Lihat Kalendar Slot Available
    ↓
Pilih Tarikh & Masa Slot
    ↓
Masukkan Catatan (optional)
    ↓
Confirm Booking
    ↓
[Sistem] Generate Kod Temujanji (APT-YYYYMMDD-0001)
[Sistem] Update Database (status: pending → dijadualkan)
[Sistem] Hantar SMS/WhatsApp Confirmation
    ↓
[Pesakit] Terima Confirmation
```

### 6.2 Workflow Check-in Pesakit

```
[Pesakit] → Datang ke Klinik
    ↓
[Kerani] Search Temujanji (by Kod/IC/Nama)
    ↓
[Kerani] Click Button "Check-in"
    ↓
[Sistem] Update Status: dijadualkan → checked_in
[Sistem] Rekod waktu_check_in
[Sistem] Notify Doktor di Dashboard
    ↓
[Doktor] Lihat Pesakit dalam Senarai "Checked-in"
    ↓
[Doktor] Click "Mula Rawatan"
    ↓
[Sistem] Update Status: checked_in → sedang_rawatan
[Sistem] Rekod waktu_rawatan_mula
    ↓
[Doktor] Selesai Rawatan
[Doktor] Click "Selesai"
    ↓
[Sistem] Update Status: sedang_rawatan → selesai
[Sistem] Rekod waktu_selesai
```

### 6.3 Workflow Auto No-Show Detection

```
[Laravel Scheduler] → Jalankan Command Setiap 15 Minit
    ↓
[Sistem] Cari Temujanji yang:
  - Status = 'dijadualkan'
  - Tarikh/Masa sudah lepas 30 minit
  - Tidak di-check-in
    ↓
[Sistem] Update Status: dijadualkan → no_show
[Sistem] Increment No-Show Counter untuk Pesakit
    ↓
[Sistem] Check: Adakah Pesakit Mencapai Threshold (3 kali)?
    ↓
    Jika YA:
    [Sistem] Blacklist Pesakit untuk 30 hari
    [Sistem] Hantar SMS Warning
    ↓
    Jika TIDAK:
    [Sistem] Hantar SMS Reminder (jika no-show ke-2)
```

### 6.4 Workflow Reschedule Temujanji

```
[Pesakit] → Click Link "Reschedule" di Portal/SMS
    ↓
[Sistem] Check: Boleh Reschedule?
  - Reschedule count < 2?
  - Minimum 2 jam sebelum waktu temujanji?
    ↓
    Jika TIDAK:
    [Sistem] Display Error Message
    [End]
    ↓
    Jika YA:
    [Pesakit] Pilih Slot Baharu
    ↓
    [Sistem] Update Database:
      - tarikh_temujanji, masa_mula, masa_tamat (guna slot baharu)
      - reschedule_count + 1
    [Sistem] Hantar SMS Confirmation untuk slot baharu
    ↓
    [Pesakit] Terima Confirmation
```

### 6.5 Workflow Auto Reminder

```
[Laravel Scheduler] → Jalankan Command Setiap Jam
    ↓
[Sistem] Cari Temujanji yang:
  - Tarikh/Masa = 24 jam dari sekarang
  - Status = 'dijadualkan'
  - reminder_24h_sent = FALSE
    ↓
[Sistem] Hantar SMS/WhatsApp Reminder
[Sistem] Update reminder_24h_sent = TRUE
[Sistem] Log ke notification_log
    ↓
---
[Sistem] Cari Temujanji yang:
  - Tarikh/Masa = 2 jam dari sekarang
  - Status = 'dijadualkan'
  - reminder_2h_sent = FALSE
    ↓
[Sistem] Hantar SMS/WhatsApp Reminder
[Sistem] Update reminder_2h_sent = TRUE
[Sistem] Log ke notification_log
```

---

## 7. Keperluan UI/UX

### 7.1 Portal Pesakit (Self-Service)
- **Halaman Login/Register**: Simple form dengan validation
- **Dashboard Pesakit**: Senarai temujanji upcoming & past, button "Buat Temujanji Baharu"
- **Halaman Tempahan**: Step wizard (Pilih Jenis → Pilih Doktor → Pilih Slot → Confirm)
- **Kalendar View**: Kalendar bulanan dengan slot available (hijau), penuh (kelabu)
- **Confirmation Page**: Display kod temujanji, tarikh, masa, nama doktor, lokasi klinik
- **Responsive Design**: Mobile-friendly (Bootstrap 5)

### 7.2 Dashboard Kerani
- **Halaman Utama**: Statistik hari ini, senarai temujanji hari ini (by status)
- **Kalendar View**: Kalendar semua doktor dengan color coding by doktor
- **Form Tempahan Walk-in**: Search pesakit → Pilih jenis → Pilih slot → Confirm
- **Senarai Temujanji**: Table dengan filter (status, doktor, tarikh), search, pagination
- **Button Quick Actions**: Check-in, Cancel, Reschedule, View Details

### 7.3 Dashboard Doktor
- **Halaman Utama**: Jadual hari ini dengan time slots
- **Senarai Pesakit**: Pesakit yang checked-in, upcoming, selesai
- **Button Actions**: Mula Rawatan, Selesai, Lihat Rekod
- **Kalendar Slot**: View jadual minggu/bulan, button tambah/tutup slot

### 7.4 Halaman Admin
- **Konfigurasi Slot Doktor**: Form tetapkan hari bekerja, masa, durasi
- **Pengurusan Blacklist**: Senarai pesakit blacklist, button unblock
- **Laporan**: Charts dan tables untuk statistik temujanji
- **Template Notifikasi**: Editor untuk edit template SMS/WhatsApp

---

## 8. Keperluan Keselamatan

### 8.1 Authentication & Authorization
- **Laravel Sanctum/Breeze**: Untuk authentication portal pesakit
- **Role-based Access Control**: Pesakit, Kerani, Doktor, Admin
- **Middleware Auth**: Semua route temujanji protected dengan middleware `auth`
- **Doktor hanya boleh lihat temujanji mereka sendiri** (kecuali admin)

### 8.2 Data Protection (PDPA Compliance)
- **Audit Trail**: Semua create/update/delete temujanji direkod (created_by, updated_by)
- **Soft Delete**: Data tidak dihapus secara kekal, hanya di-soft delete
- **Consent**: Pesakit bagi consent untuk terima SMS/WhatsApp notification semasa register
- **Data Encryption**: Sensitive data (nombor telefon) di-encrypt dalam database (optional, jika diperlukan)

### 8.3 API Security
- **Rate Limiting**: Limit 60 requests per minute untuk portal pesakit
- **CSRF Protection**: Semua POST/PATCH/DELETE request dilindungi CSRF token
- **SQL Injection Prevention**: Guna Eloquent ORM (automatic parameter binding)
- **XSS Prevention**: Guna Blade {{ }} escaping untuk output

### 8.4 SMS/WhatsApp Security
- **API Key Protection**: Store API keys dalam `.env`, tidak commit ke Git
- **Webhook Verification**: Verify webhook signature dari SMS provider
- **Log Notification**: Rekod semua notifikasi dalam notification_log untuk audit

---

## 9. Keperluan Prestasi

### 9.1 Response Time
- **Halaman Dashboard**: < 2 saat
- **Search Pesakit**: < 1 saat
- **Kalendar Slot Available**: < 2 saat (with caching)
- **Submit Tempahan**: < 3 saat (termasuk SMS notification)

### 9.2 Scalability
- **Database Indexing**: Index pada pesakit_id, doktor_id, tarikh_temujanji, status
- **Query Optimization**: Guna eager loading untuk relationships (`with('pesakit', 'doktor')`)
- **Caching**: Cache slot doktor configuration (Redis/file cache) untuk 15 minit
- **Queue untuk SMS**: Guna Laravel Queue untuk hantar SMS secara async (database queue atau Redis)

### 9.3 Concurrent Users
- **Portal Pesakit**: Support 100 concurrent users
- **Dashboard Kerani/Doktor**: Support 20 concurrent users

---

## 10. Keperluan Ujian

### 10.1 Unit Testing
- **TemuJanjiService**: Test business logic (create, reschedule, cancel, no-show detection)
- **TemuJanjiRepository**: Test database queries (search, filter by status, get available slots)
- **SlotCalculator**: Test slot availability calculation
- **BlacklistService**: Test blacklist logic (threshold, auto-block, unblock)

### 10.2 Feature Testing
- **Tempahan Temujanji**: Test full workflow (pilih slot → submit → terima confirmation SMS)
- **Check-in**: Test check-in workflow (check-in → notify doktor)
- **Reschedule**: Test reschedule workflow dengan validation (max 2 kali, minimum 2 jam)
- **Cancellation**: Test cancellation dengan validation
- **No-Show Detection**: Test auto no-show command
- **Auto Reminder**: Test scheduler command untuk hantar reminder

### 10.3 Integration Testing
- **SMS Gateway**: Test integration dengan Twilio/MSG91
- **WhatsApp API**: Test integration dengan WhatsApp Business API
- **Queue**: Test queue processing untuk notification

### 10.4 User Acceptance Testing (UAT)
- **Scenario 1**: Pesakit buat temujanji online → Check-in → Rawatan → Selesai
- **Scenario 2**: Pesakit reschedule temujanji 2 kali → Cuba reschedule kali ke-3 (expect error)
- **Scenario 3**: Pesakit no-show 3 kali → Auto blacklist → Cuba buat temujanji (expect error)
- **Scenario 4**: Doktor tutup slot untuk cuti → Pesakit tidak boleh pilih slot tersebut
- **Scenario 5**: Kerani cancel temujanji atas nama doktor → Pesakit terima SMS notification

---

## 11. Langkah Implementasi

### 11.1 Fasa 1: Setup & Database (Minggu 1)
- [ ] Setup Laravel project structure (sudah ada)
- [ ] Create migrations untuk jadual `temujanji`, `slot_doktor`, `slot_tutup`, `pesakit_blacklist`, `notification_log`
- [ ] Create Model Eloquent: `TemuJanji`, `SlotDoktor`, `SlotTutup`, `PesakitBlacklist`, `NotificationLog`
- [ ] Create configuration file `config/temujanji.php`
- [ ] Run migrations dan seed sample data

### 11.2 Fasa 2: Repository & Service Layer (Minggu 1-2)
- [ ] Create `TemuJanjiRepository` dengan methods: `create()`, `update()`, `search()`, `getByStatus()`, `getHariIni()`, `getAvailableSlots()`
- [ ] Create `SlotDoktorRepository` untuk pengurusan slot doktor
- [ ] Create `TemuJanjiService` dengan business logic: `createTemujanji()`, `rescheduleTemujanji()`, `cancelTemujanji()`, `checkIn()`, `markSelesai()`
- [ ] Create `BlacklistService` untuk pengurusan blacklist pesakit
- [ ] Create `NotificationService` untuk hantar SMS/WhatsApp

### 11.3 Fasa 3: FormRequest Validation (Minggu 2)
- [ ] Create `StoreTemuJanjiRequest` untuk validation tempahan
- [ ] Create `UpdateTemuJanjiRequest` untuk validation reschedule
- [ ] Create `CheckInRequest` untuk validation check-in
- [ ] Create `CancelTemuJanjiRequest` untuk validation cancellation

### 11.4 Fasa 4: Controller & Routes (Minggu 2-3)
- [ ] Create `Admin/TemuJanjiController` dengan Route Attributes:
  - `index()` - Senarai temujanji
  - `create()` - Form tempahan walk-in
  - `store()` - Submit tempahan walk-in
  - `checkIn()` - Check-in pesakit
  - `reschedule()` - Reschedule temujanji
  - `cancel()` - Cancel temujanji
  - `dashboard()` - Dashboard kerani
- [ ] Create `Portal/TemuJanjiController` untuk portal pesakit:
  - `index()` - Senarai temujanji pesakit
  - `create()` - Form tempahan online
  - `store()` - Submit tempahan online
  - `reschedule()` - Reschedule temujanji
  - `cancel()` - Cancel temujanji
- [ ] Create `Doktor/TemuJanjiController` untuk dashboard doktor:
  - `index()` - Dashboard doktor dengan jadual hari ini
  - `mulaRawatan()` - Mark sedang rawatan
  - `selesai()` - Mark selesai
- [ ] Create `Admin/SlotDoktorController` untuk konfigurasi slot doktor

### 11.5 Fasa 5: Views & UI (Minggu 3-4)
- [ ] Create Blade templates untuk Portal Pesakit:
  - `portal/temujanji/index.blade.php` - Senarai temujanji pesakit
  - `portal/temujanji/create.blade.php` - Form tempahan dengan kalendar
  - `portal/temujanji/confirmation.blade.php` - Confirmation page
- [ ] Create Blade templates untuk Kerani:
  - `admin/temujanji/index.blade.php` - Senarai semua temujanji
  - `admin/temujanji/create.blade.php` - Form tempahan walk-in
  - `admin/temujanji/dashboard.blade.php` - Dashboard kerani
  - `admin/temujanji/calendar.blade.php` - Kalendar view
- [ ] Create Blade templates untuk Doktor:
  - `doktor/temujanji/index.blade.php` - Dashboard doktor
  - `doktor/temujanji/jadual.blade.php` - Jadual hari ini
- [ ] Integrate Bootstrap 5 + CoreUI components

### 11.6 Fasa 6: SMS/WhatsApp Integration (Minggu 4)
- [ ] Setup Twilio/MSG91 account dan dapatkan API credentials
- [ ] Create `SmsService` untuk hantar SMS via Twilio
- [ ] Create `WhatsAppService` untuk hantar WhatsApp via Twilio WhatsApp API
- [ ] Create notification templates untuk: Confirmation, Reminder 24h, Reminder 2h, Reschedule, Cancellation
- [ ] Implement queue untuk hantar notification secara async
- [ ] Test SMS/WhatsApp sending

### 11.7 Fasa 7: Scheduler & Auto Commands (Minggu 5)
- [ ] Create `DetectNoShowCommand` untuk auto mark no-show
- [ ] Create `SendReminderCommand` untuk hantar reminder 24h & 2h
- [ ] Create `AutoUnblockPesakitCommand` untuk auto unblock selepas 30 hari
- [ ] Setup Laravel Scheduler dalam `app/Console/Kernel.php`:
  ```php
  $schedule->command('temujanji:detect-no-show')->everyFifteenMinutes();
  $schedule->command('temujanji:send-reminder')->hourly();
  $schedule->command('temujanji:auto-unblock')->daily();
  ```
- [ ] Test scheduler commands

### 11.8 Fasa 8: Testing (Minggu 5-6)
- [ ] Write unit tests untuk Services dan Repositories
- [ ] Write feature tests untuk Controllers
- [ ] Write integration tests untuk SMS/WhatsApp
- [ ] Perform manual UAT dengan sample data
- [ ] Fix bugs yang dijumpai semasa testing

### 11.9 Fasa 9: Deployment & Training (Minggu 6)
- [ ] Setup production environment (server, database, Redis queue)
- [ ] Setup cron job untuk Laravel Scheduler
- [ ] Configure SMS/WhatsApp provider credentials dalam production `.env`
- [ ] Deploy aplikasi ke production server
- [ ] Training untuk Kerani dan Doktor
- [ ] Monitor error logs dan notification logs

### 11.10 Fasa 10: Monitoring & Optimization (Ongoing)
- [ ] Monitor kadar no-show dan effectiveness of reminder
- [ ] Monitor SMS delivery rate
- [ ] Optimize query performance jika ada masalah
- [ ] Collect feedback dari pengguna untuk improvement

---

## 12. Kriteria Kejayaan

### 12.1 Metrics Utama
- **Kadar Penggunaan Portal Pesakit**: > 60% temujanji dibuat secara online (vs walk-in)
- **Kadar No-Show**: < 10% (target turun dari 15-20% semasa)
- **SMS Delivery Rate**: > 95%
- **Kadar Reschedule**: < 20%
- **Kadar Cancellation**: < 15%
- **Average Waiting Time**: < 20 minit (dari check-in hingga rawatan)

### 12.2 User Satisfaction
- **Kepuasan Pesakit**: > 4.0/5.0 (survey)
- **Kepuasan Doktor**: > 4.0/5.0 (survey)
- **Kepuasan Kerani**: > 4.0/5.0 (survey)

### 12.3 Technical Metrics
- **Uptime**: > 99%
- **Response Time**: < 2 saat untuk 95% requests
- **Bug Rate**: < 5 bugs per bulan selepas deployment

---

## 13. Risks & Mitigation

### 13.1 Risks
| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| SMS provider downtime | Medium | High | Implement fallback provider (backup SMS gateway) |
| High no-show rate masih tinggi | Medium | Medium | Monitor dan adjust reminder timing/content |
| Pesakit tidak biasa dengan sistem online | High | Medium | Provide tutorial video dan support hotline |
| Blacklist policy terlalu strict | Low | Medium | Allow admin override dan unblock manual |
| Slot management terlalu kompleks | Medium | High | Simplify configuration, provide wizard setup |

### 13.2 Dependencies
- **SMS Provider**: Twilio/MSG91 (critical)
- **WhatsApp API**: Twilio WhatsApp (optional, boleh fallback ke SMS)
- **Internet Connection**: Stable connection diperlukan untuk portal pesakit
- **Queue Worker**: Perlu ensure queue worker running untuk process notifications

---

## 14. Sokongan & Maintenance

### 14.1 Dokumentasi
- **User Manual**: Manual pengguna untuk Pesakit, Kerani, Doktor (Bahasa Malaysia)
- **Admin Guide**: Panduan konfigurasi slot, template notifikasi, blacklist management
- **Technical Documentation**: API documentation, database schema, workflow diagrams
- **FAQ**: Soalan lazim untuk pesakit dan staff

### 14.2 Training
- **Kerani**: 1 hari training untuk tempahan, check-in, reschedule, cancellation
- **Doktor**: 0.5 hari training untuk dashboard dan pengurusan slot
- **Admin**: 1 hari training untuk konfigurasi sistem dan laporan

### 14.3 Support
- **Helpdesk**: Email/telefon support untuk technical issues
- **Bug Fixes**: Response time < 24 jam untuk critical bugs
- **Feature Requests**: Collect dan review setiap 3 bulan

---

## 15. Lampiran

### 15.1 Contoh SMS Template

#### Confirmation Booking
```
Temujanji anda telah berjaya ditempah!

Kod Temujanji: {kod_temujanji}
Tarikh: {tarikh}
Masa: {masa}
Doktor: {nama_doktor}
Lokasi: Poliklinik Al-Huda

Untuk reschedule/cancel, klik: {link}

Sila datang 15 minit awal untuk check-in.
```

#### Reminder 24 Jam
```
Reminder: Anda ada temujanji esok!

Tarikh: {tarikh}
Masa: {masa}
Doktor: {nama_doktor}

Jika tidak dapat hadir, sila reschedule/cancel di: {link}

Terima kasih!
Poliklinik Al-Huda
```

#### Reminder 2 Jam
```
Reminder: Temujanji anda dalam 2 jam lagi!

Masa: {masa}
Doktor: {nama_doktor}

Sila datang 15 minit awal untuk check-in.

Lokasi: Poliklinik Al-Huda
No. Tel: {nombor_klinik}
```

### 15.2 Wireframes (To be created)
- Portal Pesakit: Kalendar slot available
- Dashboard Kerani: Senarai temujanji
- Dashboard Doktor: Jadual hari ini

### 15.3 Database ER Diagram (To be created)
- Relationship diagram untuk `temujanji`, `pesakit`, `users`, `slot_doktor`, `slot_tutup`, `pesakit_blacklist`, `notification_log`

---

## Approval

**Disediakan oleh**: Product Team
**Tarikh**: 12 Januari 2026

**Untuk Kelulusan**:
- [ ] Product Owner
- [ ] Technical Lead
- [ ] Pengurus Klinik
- [ ] Stakeholders

---

**Catatan**: Dokumen ini adalah living document dan akan dikemaskini mengikut keperluan semasa development.
