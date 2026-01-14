# PRD: Modul Queue Management - Pengurusan Giliran

**Kod PRD:** KLINIK-Queue-PR2026-01-pengurusan-giliran
**Modul:** Queue Management
**Submodul:** Aliran Pesakit
**Tarikh Dicipta:** 2026-01-13
**Versi:** 1.0
**Pemilik Produk:** Pengurusan Klinik
**Stakeholder:** Semua Staf (Kerani, Jururawat, Doktor, Ahli Farmasi), Pesakit

---

## 1. Ringkasan Eksekutif

### 1.1 Objektif
Sistem Queue Management bertujuan untuk mengoptimumkan aliran pesakit di Poliklinik Al-Huda dengan pengurusan nombor giliran digital, paparan menunggu yang informatif, voice announcement, estimated wait time, dan analytics untuk mengurangkan masa menunggu pesakit serta meningkatkan kecekapan operasi klinik.

### 1.2 Skop
- Multi-queue dengan kategori berbeza (Pendaftaran, Konsultasi per doktor, Farmasi, Makmal, Billing)
- Nombor giliran dengan prefix mengikut kategori (R-001, D1-001, F-001)
- Kiosk self-service untuk ambil nombor giliran
- Paparan digital di waiting area dengan voice announcement (TTS)
- Web dashboard untuk staf panggil nombor giliran
- Priority queue untuk OKU, warga emas, ibu mengandung, emergency, VIP
- Estimated wait time (EWT) calculation dan display
- Auto-transfer queue antara stesen (Konsultasi → Farmasi → Billing)
- SMS/WhatsApp notification bila hampir dipanggil
- Comprehensive analytics dan reporting
- Integration dengan modul Pendaftaran (auto-generate queue selepas daftar)
- Multi-counter/room support

### 1.3 Out of Scope
- Hardware procurement (kiosk, TV) - klinik sediakan
- Voice recognition untuk panggilan
- Mobile app untuk pesakit (Fasa 1 - fokus SMS/WhatsApp notification)
- Appointment-based queue dengan time slots (guna Modul Temujanji sedia ada)

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa
1. **Pesakit menunggu lama tanpa kepastian:** Tiada sistem giliran formal, pesakit tidak tahu berapa lama perlu menunggu
2. **Kekacauan di waiting area:** Pesakit tidak pasti bila giliran mereka, menyebabkan pertanyaan berulang kepada staf
3. **No-show tidak dikesan:** Pesakit yang tidak hadir semasa dipanggil tidak dikesan dengan sistematik
4. **Tiada data untuk penambahbaikan:** Tiada analytics tentang masa menunggu, peak hours, atau bottlenecks
5. **Staf terpaksa jerit panggil nama:** Kaedah lama yang tidak profesional dan inefficient
6. **Pesakit priority tidak diprioritikan:** Warga emas, OKU, ibu mengandung tidak diberi keutamaan

### 2.2 Impak
- Patient experience yang buruk (frustrasi, ketidakpuasan)
- Kehilangan pesakit kepada klinik lain
- Beban kerja staf meningkat (jawab pertanyaan berulang)
- Tiada data untuk optimize operasi
- Imej klinik tidak profesional

---

## 3. User Stories

### 3.1 User Stories Utama

1. **Sebagai Pesakit**, **saya mahu** mengambil nombor giliran dengan mudah di kiosk self-service **supaya** saya tidak perlu beratur panjang untuk daftar **bila** saya sampai di klinik **saya sepatutnya** boleh pilih jenis perkhidmatan dan dapat slip nombor dengan estimated wait time

2. **Sebagai Pesakit**, **saya mahu** melihat nombor giliran semasa dan anggaran masa menunggu di paparan digital **supaya** saya tahu berapa lama perlu menunggu dan boleh plan masa saya **bila** saya menunggu di waiting area **saya sepatutnya** nampak nombor yang dipanggil, nombor saya, dan estimated wait time

3. **Sebagai Pesakit**, **saya mahu** dengar voice announcement bila nombor saya dipanggil **supaya** saya tidak terlepas giliran walaupun tidak perhatikan paparan **bila** giliran saya sampai **saya sepatutnya** dengar announcement "Nombor D1-015, sila ke Bilik Doktor 1"

4. **Sebagai Pesakit**, **saya mahu** menerima SMS/WhatsApp bila hampir giliran saya **supaya** saya boleh bersiap sedia walaupun sedang berehat di tempat lain **bila** tinggal 3 nombor lagi **saya sepatutnya** terima notification "Giliran anda hampir tiba, sila bersiap"

5. **Sebagai Kerani Front Desk**, **saya mahu** sistem auto-generate nombor giliran selepas pesakit selesai mendaftar **supaya** saya tidak perlu manually issue nombor **bila** pendaftaran selesai **saya sepatutnya** pesakit terus dapat nombor giliran yang sesuai (berdasarkan temujanji atau walk-in)

6. **Sebagai Kerani Front Desk**, **saya mahu** boleh issue nombor priority untuk pesakit khas (OKU, warga emas) **supaya** mereka tidak perlu menunggu lama **bila** pesakit senior atau OKU datang **saya sepatutnya** boleh assign priority queue dengan satu klik

7. **Sebagai Doktor/Jururawat**, **saya mahu** panggil nombor giliran dengan satu klik di dashboard **supaya** saya tidak perlu jerit atau keluar bilik **bila** saya siap untuk pesakit seterusnya **saya sepatutnya** klik "Panggil Seterusnya" dan sistem auto-announce dan update paparan

8. **Sebagai Doktor/Jururawat**, **saya mahu** recall nombor jika pesakit tidak datang **supaya** saya boleh panggil semula sebelum skip **bila** pesakit tidak respond **saya sepatutnya** boleh klik "Panggil Semula" sehingga 3 kali sebelum mark sebagai no-show

9. **Sebagai Ahli Farmasi**, **saya mahu** pesakit auto-transfer ke queue farmasi selepas selesai konsultasi **supaya** aliran pesakit lancar tanpa pesakit perlu ambil nombor baru **bila** doktor selesai dengan pesakit **saya sepatutnya** pesakit automatik masuk queue farmasi dengan priority maintained

10. **Sebagai Pengurus Klinik**, **saya mahu** melihat analytics masa menunggu dan peak hours **supaya** saya boleh optimize staff allocation dan reduce wait time **bila** saya review laporan mingguan **saya sepatutnya** nampak average wait time, peak hours, bottlenecks, dan staff performance

11. **Sebagai Staf**, **saya mahu** lihat senarai pesakit dalam queue saya dengan maklumat asas **supaya** saya boleh prepare untuk pesakit seterusnya **bila** saya buka dashboard **saya sepatutnya** nampak nama pesakit, umur, sebab lawatan untuk beberapa nombor seterusnya

12. **Sebagai Warga Emas/OKU**, **saya mahu** mendapat keutamaan dalam queue **supaya** saya tidak perlu menunggu lama seperti pesakit biasa **bila** saya daftar **saya sepatutnya** auto-assigned ke priority queue berdasarkan umur atau status OKU

### 3.2 Edge Cases

1. **Sebagai Staf**, **saya mahu** skip pesakit yang tidak hadir selepas 3 panggilan **supaya** queue tidak terhenti dan pesakit lain tidak menunggu lama **bila** pesakit tidak respond **saya sepatutnya** boleh mark sebagai no-show dan proceed ke nombor seterusnya

2. **Sebagai Staf**, **saya mahu** hold pesakit sementara (waiting for lab result) **supaya** saya boleh serve pesakit lain dahulu dan recall kemudian **bila** pesakit perlu tunggu lab result **saya sepatutnya** boleh hold dan recall kemudian tanpa hilang giliran

3. **Sebagai Staf**, **saya mahu** transfer pesakit ke queue lain (misalnya, ke doktor pakar lain) **supaya** pesakit tidak perlu ambil nombor baru **bila** perlu tukar doktor **saya sepatutnya** boleh transfer dengan maintain priority dalam queue baru

4. **Sebagai Pesakit**, **saya mahu** delay giliran saya jika saya belum ready **supaya** saya tidak terlepas giliran **bila** saya terima SMS "giliran hampir" tetapi masih di kantin **saya sepatutnya** boleh reply untuk delay 10 minit dan sistem adjust queue

5. **Sebagai Pengurus Klinik**, **saya mahu** sistem auto-notify jika queue terlalu panjang **supaya** saya boleh ambil tindakan (buka kaunter tambahan) **bila** wait time > 30 minit **saya sepatutnya** terima alert

---

## 4. Keperluan Fungsian

### 4.1 Queue Configuration

**FR-1:** Sistem mesti support multiple queue types dengan konfigurasi berbeza:

| Queue Type | Prefix | Counter/Room | Priority Support |
|------------|--------|--------------|------------------|
| Pendaftaran | R | Kaunter 1, 2, 3 | Ya |
| Konsultasi Doktor 1 | D1 | Bilik Doktor 1 | Ya |
| Konsultasi Doktor 2 | D2 | Bilik Doktor 2 | Ya |
| Konsultasi Doktor 3 | D3 | Bilik Doktor 3 | Ya |
| Farmasi | F | Kaunter Farmasi | Ya |
| Makmal | L | Bilik Makmal | Ya |
| Billing | B | Kaunter Billing | Ya |

**FR-2:** Sistem mesti support queue configuration:
- Nama queue
- Prefix (2 characters max)
- Counter/Room assignments
- Operating hours
- Average service time (untuk EWT calculation)
- Max queue size (optional limit)
- Auto-transfer destination (contoh: D1 → F → B)

**FR-3:** Nombor giliran format: `[Prefix]-[3-digit sequential]`, reset setiap hari pada 00:00

### 4.2 Nombor Giliran Generation

**FR-4:** Sistem mesti support multiple channels untuk generate nombor giliran:

**A) Kiosk Self-Service:**
- Touchscreen interface (user-friendly, large buttons)
- Language selection (BM, English, Mandarin)
- Pilih jenis queue (Jumpa Doktor, Ambil Ubat, Bayar Bil)
- Untuk temujanji: Scan IC atau input IC untuk auto-detect appointment
- Print slip nombor dengan:
  - Nombor giliran (large font)
  - Nama queue
  - Estimated wait time
  - Tarikh dan masa issue
  - Barcode/QR code (untuk scan di counter)
  - Bilangan orang di hadapan

**B) Kerani Issue:**
- Generate nombor via web dashboard
- Auto-issue selepas pendaftaran selesai (integration dengan Modul Pendaftaran)
- Option untuk print slip atau no print

**FR-5:** Untuk pesakit dengan temujanji, sistem mesti auto-assign ke queue doktor yang betul berdasarkan appointment data

### 4.3 Priority Queue

**FR-6:** Sistem mesti support priority levels:

| Priority | Label | Criteria | Visual Indicator |
|----------|-------|----------|------------------|
| 1 | Emergency | Case emergency | Red badge |
| 2 | OKU | Orang Kurang Upaya | Blue badge |
| 3 | Warga Emas | Umur ≥ 60 tahun | Gold badge |
| 4 | Ibu Mengandung | Pregnant | Pink badge |
| 5 | VIP | VIP patient | Purple badge |
| 6 | Normal | Standard patient | No badge |

**FR-7:** Priority auto-assignment:
- Warga emas (≥60 tahun): Auto-assign berdasarkan DOB dari patient record
- Temujanji: Slightly higher priority than walk-in
- Manual override oleh staf untuk OKU, ibu mengandung, emergency

**FR-8:** Priority queue logic:
- Priority patients dipanggil lebih awal
- Ratio configurable (contoh: setiap 3 normal, panggil 1 priority)
- Emergency bypasses semua queue

### 4.4 Queue Display (Paparan Digital)

**FR-9:** Sistem mesti provide display interface untuk TV/monitor di waiting area:

**Display Components:**
- Header: Nama klinik, tarikh, masa semasa
- Main display: Nombor yang sedang dipanggil (large font, per queue type)
- Counter/Room info: "Nombor D1-015, sila ke Bilik Doktor 1"
- Queue list: Nombor-nombor seterusnya dalam queue (5-10 nombor)
- Estimated wait time: "Anggaran masa menunggu: 15 minit"
- Announcements area: Notis atau promosi klinik

**FR-10:** Display features:
- Auto-refresh (real-time update)
- Fullscreen mode untuk TV
- Customizable layout dan colors (branding klinik)
- Multi-language support (BM, English, Mandarin)
- Visual alert bila nombor dipanggil (flash/highlight)

**FR-11:** Voice announcement (TTS - Text-to-Speech):
- Announce bila nombor dipanggil
- Format: "Nombor [Nombor Giliran], sila ke [Counter/Room]"
- Multi-language (BM, English, Mandarin configurable)
- Volume adjustable
- Option untuk repeat announcement

### 4.5 Staff Dashboard (Calling Interface)

**FR-12:** Web dashboard untuk staf dengan features:

**Queue View:**
- Senarai nombor dalam queue (current queue assigned to this counter/room)
- Patient info preview: Nama, IC (masked), umur, priority badge
- Waiting time per patient

**Actions:**
- **Call Next** - Panggil nombor seterusnya (auto-select based on priority logic)
- **Call Specific** - Panggil nombor tertentu (skip others)
- **Recall** - Panggil semula nombor yang sama (max 3 times)
- **Serving** - Mark sebagai sedang dilayan
- **Complete** - Mark sebagai selesai, trigger auto-transfer jika configured
- **No-Show** - Mark sebagai tidak hadir (selepas 3 recall)
- **Hold** - Tahan sementara (waiting for something)
- **Transfer** - Pindah ke queue lain
- **Cancel** - Batalkan nombor giliran

**FR-13:** Keyboard shortcuts untuk efficiency:
- `N` - Call Next
- `R` - Recall
- `C` - Complete
- `S` - No-Show

### 4.6 Queue Status & Lifecycle

**FR-14:** Queue ticket status flow:

```
[Generated/Waiting]
       ↓
  [Called] ←→ [Recall] (max 3x)
       ↓
  [Serving]
       ↓
  [Completed] → [Auto-Transfer to next queue] → [Waiting in new queue]
       or
  [No-Show] (selepas 3 recall tanpa response)
       or
  [Cancelled]
       or
  [On-Hold] → [Recall from Hold]
```

**FR-15:** Status definitions:

| Status | Description |
|--------|-------------|
| Waiting | Dalam queue, menunggu dipanggil |
| Called | Dipanggil, menunggu pesakit datang |
| Serving | Pesakit sedang dilayan |
| Completed | Perkhidmatan selesai |
| No-Show | Tidak hadir selepas 3 panggilan |
| Cancelled | Dibatalkan oleh staf atau pesakit |
| On-Hold | Ditahan sementara (contoh: tunggu lab result) |
| Transferred | Dipindahkan ke queue lain |

### 4.7 Estimated Wait Time (EWT)

**FR-16:** Sistem mesti calculate EWT berdasarkan:
- Average service time per queue type (configurable, default values)
- Number of patients ahead in queue
- Number of active counters/rooms serving
- Historical data (learning over time)

**FR-17:** EWT formula (basic):
```
EWT = (Patients Ahead × Average Service Time) / Active Counters
```

**FR-18:** Display EWT:
- On slip: "Anggaran masa menunggu: 15 minit"
- On display: Real-time update
- On patient notification: Update bila EWT changes significantly

### 4.8 Auto-Transfer Queue

**FR-19:** Sistem mesti support auto-transfer selepas service selesai:

**Configurable flows:**
- Konsultasi (D1/D2/D3) → Farmasi (F) → Billing (B)
- Konsultasi → Makmal (L) → Konsultasi (return) → Farmasi → Billing
- Pendaftaran → Konsultasi

**FR-20:** Auto-transfer behavior:
- Maintain priority level
- Add to new queue dengan status "Waiting"
- Notify patient via SMS/display jika new EWT > threshold (contoh: > 10 minit)

**FR-21:** Manual transfer option:
- Staf boleh transfer ke any queue
- Reason required (optional)

### 4.9 Patient Notification

**FR-22:** SMS/WhatsApp notification triggers:

| Trigger | Message |
|---------|---------|
| Queue number issued | "Nombor giliran anda: D1-015. Anggaran menunggu: 20 minit" |
| Approaching (3 numbers away) | "Giliran anda hampir tiba (3 lagi). Sila bersiap di waiting area" |
| Called | "Nombor D1-015 dipanggil. Sila ke Bilik Doktor 1 sekarang" |
| No-Show warning | "Anda tidak hadir semasa dipanggil. Reply DELAY untuk 10 minit lagi atau nombor akan dibatalkan" |
| Transferred | "Anda telah dipindahkan ke Kaunter Farmasi. Nombor baru: F-045" |

**FR-23:** Patient can reply:
- "DELAY" - Delay 10 minit (add back to queue dengan slight penalty)
- "CANCEL" - Cancel queue number

### 4.10 Integration dengan Pendaftaran

**FR-24:** Auto-generate queue number selepas pendaftaran:
- Check jika pesakit ada temujanji → Assign ke queue doktor yang betul
- Walk-in → Assign ke queue pendaftaran dahulu, then konsultasi
- Display queue number di pendaftaran confirmation screen

**FR-25:** Pass patient data ke queue:
- Patient ID
- Name (for staff dashboard)
- Age (for priority auto-assignment)
- Appointment info (if any)
- Reason for visit (for doctor preview)

### 4.11 Multi-Counter/Room Support

**FR-26:** Sistem mesti support:
- Multiple counters per queue type (Kaunter 1, 2, 3 untuk Pendaftaran)
- Multiple rooms per queue type (Bilik Doktor 1, 2, 3)
- Staf login ke specific counter/room
- Queue distribution across counters (round-robin atau load balancing)

**FR-27:** Display show counter/room info:
- "Nombor R-015, sila ke Kaunter 2"
- "Nombor D1-020, sila ke Bilik Doktor 1"

### 4.12 Reporting & Analytics

**FR-28:** Dashboard analytics (real-time):
- Current queue length per queue type
- Average wait time today (so far)
- Longest wait time
- Patients served today
- No-show count
- Active counters/rooms

**FR-29:** Historical reports:

1. **Wait Time Report:**
   - Average wait time by queue/day/hour
   - Wait time distribution (histogram)
   - Comparison: This week vs last week

2. **Peak Hours Analysis:**
   - Busiest hours of day
   - Busiest days of week
   - Heat map visualization

3. **Queue Throughput Report:**
   - Patients served per hour/day
   - Service time per patient
   - Throughput by queue type

4. **Staff Performance Report:**
   - Patients served per staff
   - Average service time per staff
   - Comparison across staff

5. **No-Show Report:**
   - No-show count and rate
   - No-show by queue type
   - Trend over time

6. **Patient Journey Report:**
   - Total time from registration to exit
   - Time spent at each station
   - Bottleneck identification

**FR-30:** All reports exportable to PDF and Excel

### 4.13 Kiosk Management

**FR-31:** Kiosk admin features:
- Enable/disable kiosk remotely
- Monitor kiosk status (online/offline, paper status)
- Configure kiosk options (available queue types)
- View kiosk usage statistics

**FR-32:** Kiosk error handling:
- Alert admin if paper low
- Alert admin if kiosk offline
- Fallback message: "Sila dapatkan nombor giliran di kaunter"

---

## 5. Keperluan Teknikal

### 5.1 Arkitektur Sistem

**Framework:** Laravel 12
**Frontend:** Blade Templates + Bootstrap 5 + CoreUI
**Real-time:** Laravel Echo + Pusher/Soketi (WebSocket)
**Database:** MySQL 8.0
**Queue Processing:** Laravel Queue (Redis)
**TTS:** Browser Speech Synthesis API atau Google TTS
**SMS Gateway:** Twilio/MSG91 (integration dengan existing setup)
**Pattern:** Service Layer + Repository Pattern

### 5.2 Struktur Database

Sistem ini memerlukan 10 jadual utama:

1. `queue_types` - Queue type configuration
2. `queue_counters` - Counter/Room master
3. `queue_tickets` - Individual queue tickets
4. `queue_calls` - Call history per ticket
5. `queue_transfers` - Transfer history
6. `queue_daily_stats` - Daily aggregated statistics
7. `queue_hourly_stats` - Hourly statistics
8. `queue_staff_assignments` - Staff to counter assignment
9. `queue_kiosks` - Kiosk configuration
10. `queue_notifications` - Notification log

**Jadual: `queue_types`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `code` | varchar(10) UNIQUE NOT NULL | Queue prefix (R, D1, F) |
| `name` | varchar(100) NOT NULL | Queue name (Pendaftaran) |
| `name_en` | varchar(100) NULL | English name |
| `name_zh` | varchar(100) NULL | Chinese name |
| `avg_service_time` | int DEFAULT 5 | Average minutes per service |
| `max_queue_size` | int NULL | Max tickets per day (NULL = unlimited) |
| `priority_ratio` | int DEFAULT 3 | Every N normal, call 1 priority |
| `auto_transfer_to` | bigint UNSIGNED NULL | FK → queue_types.id |
| `operating_start` | time NULL | Operating start time |
| `operating_end` | time NULL | Operating end time |
| `is_active` | boolean DEFAULT true | Active status |
| `display_order` | int DEFAULT 0 | Order on display/kiosk |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `queue_counters`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `queue_type_id` | bigint UNSIGNED NOT NULL | FK → queue_types.id |
| `code` | varchar(20) NOT NULL | Counter code (K1, K2, BD1) |
| `name` | varchar(100) NOT NULL | Counter name (Kaunter 1) |
| `name_en` | varchar(100) NULL | English name |
| `name_zh` | varchar(100) NULL | Chinese name |
| `location` | varchar(255) NULL | Physical location |
| `is_active` | boolean DEFAULT true | Active status |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `queue_tickets`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `ticket_number` | varchar(20) NOT NULL | Full number (D1-015) |
| `sequence` | int NOT NULL | Daily sequence (15) |
| `queue_type_id` | bigint UNSIGNED NOT NULL | FK → queue_types.id |
| `queue_date` | date NOT NULL | Queue date |
| `pesakit_id` | bigint UNSIGNED NULL | FK → pesakit.id (if registered) |
| `temujanji_id` | bigint UNSIGNED NULL | FK → temujanji.id (if appointment) |
| `priority_level` | tinyint DEFAULT 6 | 1=Emergency to 6=Normal |
| `priority_reason` | varchar(100) NULL | OKU, Warga Emas, etc |
| `status` | enum NOT NULL DEFAULT 'waiting' | waiting/called/serving/completed/no_show/cancelled/on_hold/transferred |
| `current_counter_id` | bigint UNSIGNED NULL | FK → queue_counters.id |
| `served_by` | bigint UNSIGNED NULL | FK → users.id |
| `issued_at` | timestamp NOT NULL | Issue timestamp |
| `called_at` | timestamp NULL | First call timestamp |
| `serving_at` | timestamp NULL | Start serving timestamp |
| `completed_at` | timestamp NULL | Completion timestamp |
| `call_count` | tinyint DEFAULT 0 | Number of times called |
| `estimated_wait_time` | int NULL | EWT in minutes at issue time |
| `actual_wait_time` | int NULL | Actual wait (called_at - issued_at) |
| `service_time` | int NULL | Service time (completed_at - serving_at) |
| `source` | enum DEFAULT 'counter' | kiosk/counter/auto/mobile |
| `parent_ticket_id` | bigint UNSIGNED NULL | FK → queue_tickets.id (if transferred) |
| `notes` | text NULL | Staff notes |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Index:** UNIQUE(queue_type_id, queue_date, sequence)
**Index:** idx_status_date ON (status, queue_date)
**Index:** idx_pesakit ON (pesakit_id)

**Jadual: `queue_calls`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `ticket_id` | bigint UNSIGNED NOT NULL | FK → queue_tickets.id |
| `counter_id` | bigint UNSIGNED NOT NULL | FK → queue_counters.id |
| `called_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `call_type` | enum NOT NULL | initial/recall |
| `called_at` | timestamp NOT NULL | Call timestamp |
| `responded` | boolean DEFAULT false | Patient responded |
| `created_at` | timestamp | Created timestamp |

**Jadual: `queue_transfers`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `from_ticket_id` | bigint UNSIGNED NOT NULL | FK → queue_tickets.id |
| `to_ticket_id` | bigint UNSIGNED NOT NULL | FK → queue_tickets.id |
| `from_queue_type_id` | bigint UNSIGNED NOT NULL | FK → queue_types.id |
| `to_queue_type_id` | bigint UNSIGNED NOT NULL | FK → queue_types.id |
| `transfer_type` | enum NOT NULL | auto/manual |
| `reason` | varchar(255) NULL | Transfer reason |
| `transferred_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `transferred_at` | timestamp NOT NULL | Transfer timestamp |
| `created_at` | timestamp | Created timestamp |

**Jadual: `queue_staff_assignments`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `user_id` | bigint UNSIGNED NOT NULL | FK → users.id |
| `counter_id` | bigint UNSIGNED NOT NULL | FK → queue_counters.id |
| `assignment_date` | date NOT NULL | Assignment date |
| `start_time` | time NOT NULL | Shift start |
| `end_time` | time NULL | Shift end (NULL = still active) |
| `is_active` | boolean DEFAULT true | Currently active |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Index:** UNIQUE(user_id, counter_id, assignment_date)

**Jadual: `queue_notifications`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `ticket_id` | bigint UNSIGNED NOT NULL | FK → queue_tickets.id |
| `notification_type` | enum NOT NULL | issued/approaching/called/no_show_warning/transferred |
| `channel` | enum NOT NULL | sms/whatsapp |
| `recipient` | varchar(50) NOT NULL | Phone number |
| `message` | text NOT NULL | Message content |
| `status` | enum DEFAULT 'pending' | pending/sent/failed |
| `sent_at` | timestamp NULL | Sent timestamp |
| `error_message` | text NULL | Error if failed |
| `created_at` | timestamp | Created timestamp |

### 5.3 Models (Eloquent)

Models yang perlu dicipta:
- QueueType
- QueueCounter
- QueueTicket
- QueueCall
- QueueTransfer
- QueueStaffAssignment
- QueueNotification
- QueueDailyStat
- QueueHourlyStat
- QueueKiosk

### 5.4 Services

Services:
- QueueService - Core queue operations
- QueueTicketService - Ticket generation, status update
- QueueDisplayService - Display data formatting
- QueueNotificationService - SMS/WhatsApp notifications
- QueueAnalyticsService - Statistics and reporting
- QueueTransferService - Handle transfers
- EWTCalculatorService - Estimated wait time calculation

### 5.5 Real-time Events (Broadcasting)

Events untuk real-time update:

| Event | Channel | Trigger |
|-------|---------|---------|
| TicketCalled | queue.display | Bila nombor dipanggil |
| QueueUpdated | queue.display | Bila queue berubah |
| CounterStatusChanged | queue.staff | Counter open/close |
| TicketStatusChanged | queue.ticket.{id} | Status update |

---

## 6. Workflow

### 6.1 Workflow Pesakit Ambil Nombor (Kiosk)

```
Pesakit sampai di klinik
    ↓
Pesakit ke kiosk self-service
    ↓
Pilih bahasa (BM/EN/ZH)
    ↓
Pilih jenis perkhidmatan:
    - Jumpa Doktor (ada temujanji)
    - Jumpa Doktor (walk-in)
    - Ambil Ubat
    - Bayar Bil
    ↓
Jika ada temujanji:
    Scan IC atau input IC
        ↓
    Sistem cari temujanji hari ini
        ↓
    Confirm temujanji details
        ↓
    Auto-assign ke queue doktor yang betul
        ↓
Jika walk-in:
    Auto-assign ke queue pendaftaran (R)
        ↓
Sistem generate ticket:
    - Assign sequence number
    - Calculate EWT
    - Check priority (umur untuk warga emas)
    ↓
Print slip nombor giliran
    ↓
Send SMS confirmation (jika phone number available)
    ↓
Pesakit ambil slip, duduk di waiting area
```

### 6.2 Workflow Staff Panggil Nombor

```
Staf login ke dashboard
    ↓
Staf assign diri ke counter/room
    ↓
Staf klik "Panggil Seterusnya"
    ↓
Sistem select ticket:
    - Check priority ratio (setiap 3 normal, 1 priority)
    - Select highest priority waiting ticket
    ↓
Sistem update ticket:
    - status = 'called'
    - called_at = now()
    - current_counter_id = staf counter
    - call_count++
    ↓
Sistem broadcast events:
    - Update display (highlight called number)
    - Voice announcement (TTS)
    - Send SMS to patient
    ↓
Pesakit datang ke counter
    ↓
Staf klik "Mula Layanan"
    - status = 'serving'
    - serving_at = now()
    ↓
Staf layani pesakit
    ↓
Staf klik "Selesai"
    - status = 'completed'
    - completed_at = now()
    - Calculate actual wait time & service time
    ↓
Jika ada auto-transfer:
    - Create new ticket in destination queue
    - Link parent_ticket_id
    - Notify patient
    ↓
Dashboard ready untuk panggil seterusnya
```

### 6.3 Workflow No-Show

```
Staf panggil nombor
    ↓
Pesakit tidak datang (30 saat)
    ↓
Staf klik "Panggil Semula" (Recall)
    - call_count++ (max 3)
    - Create queue_calls record
    - Voice announcement again
    ↓
Masih tidak datang?
    ↓
If call_count < 3:
    Repeat recall
    ↓
If call_count >= 3:
    Staf klik "Tidak Hadir" (No-Show)
        - status = 'no_show'
        - Send SMS warning to patient
        ↓
    Sistem proceed ke nombor seterusnya
```

### 6.4 Workflow Auto-Transfer

```
Pesakit selesai di Konsultasi (D1)
    ↓
Staf klik "Selesai"
    ↓
Sistem check: D1 → F (auto-transfer configured)
    ↓
Sistem create new ticket di queue Farmasi (F):
    - Copy patient details
    - Maintain priority level
    - Set parent_ticket_id = D1 ticket
    - Calculate new EWT
    ↓
Sistem create queue_transfers record
    ↓
Sistem broadcast:
    - Update Farmasi queue display
    - Send SMS to patient dengan new number
    ↓
Pesakit proceed ke Farmasi waiting area
```

---

## 7. Keperluan UI/UX

### 7.1 Key Interfaces

**1. Kiosk Interface (Touchscreen)**
- Large buttons, easy to tap
- High contrast colors (for visibility)
- Multi-language toggle (BM/EN/ZH)
- Clear step-by-step flow
- Accessibility: Large fonts, simple icons

**2. Public Display (TV)**
- Fullscreen mode
- Auto-rotate between queue types (optional)
- Large fonts visible from distance
- Visual flash when number called
- Clock and date display
- Clinic branding (logo, colors)

**3. Staff Dashboard (Web)**
- Clean, minimal interface
- One-click actions (large buttons)
- Queue list dengan patient preview
- Keyboard shortcuts
- Real-time updates (no refresh needed)
- Counter/room status indicator

**4. Admin Dashboard**
- Queue configuration management
- Real-time monitoring (all queues)
- Analytics charts
- Staff assignment
- Kiosk management

**5. Patient Slip**
- Clear ticket number (large font)
- Queue name
- EWT
- Instructions
- Barcode/QR code
- Date/time

### 7.2 Design System
- Framework: Bootstrap 5 + CoreUI
- Icons: CoreUI Icons / Font Awesome
- Color Scheme: Calming medical colors (blues, greens)
- Responsive: Yes (staff dashboard on tablet)
- Accessibility: WCAG 2.1 compliance untuk kiosk

### 7.3 Voice Announcement Format

**Bahasa Malaysia:**
"Nombor [D1-015], sila ke [Bilik Doktor 1]"

**English:**
"Number [D1-015], please proceed to [Doctor Room 1]"

**Mandarin:**
"号码 [D1-015]，请到 [诊所一]"

---

## 8. Keperluan Keselamatan

### 8.1 Data Protection
- Patient data minimal on display (no full name, no IC)
- Staff dashboard: Masked IC (850101-XX-XXXX)
- Audit trail untuk semua ticket actions
- Ticket data retained untuk analytics (anonymized selepas 1 tahun)

### 8.2 Access Control

**Public Access (No Auth):**
- Kiosk interface
- Public display

**Staff Access (Auth Required):**
- Staff calling dashboard
- View queue list dengan patient info

**Admin Access:**
- Queue configuration
- Reporting
- Staff assignment
- System settings

### 8.3 Audit Trail
- Log all ticket status changes
- Log all calls and recalls
- Log staff assignments
- Log configuration changes

---

## 9. Keperluan Prestasi

### 9.1 Response Time
- Kiosk ticket generation: < 2 saat
- Display update (WebSocket): < 500ms
- Voice announcement: < 1 saat after call
- Staff dashboard load: < 1 saat
- Report generation: < 5 saat

### 9.2 Scalability
- Support 500+ tickets per day
- Support 10 concurrent queue types
- Support 20 concurrent counters
- Support 5 kiosks
- Support 50 concurrent staff users

### 9.3 Real-time Requirements
- WebSocket connection untuk display dan staff dashboard
- Fallback: Polling setiap 5 saat jika WebSocket fail
- Heartbeat check untuk kiosk status

---

## 10. Keperluan Ujian

### 10.1 Unit Testing
- QueueTicketService::generateTicket()
- EWTCalculatorService::calculate()
- QueueService::getNextTicket() (priority logic)
- QueueTransferService::transfer()

### 10.2 Feature Testing
- Ticket generation via kiosk flow
- Ticket generation via counter
- Call next with priority logic
- Recall and no-show flow
- Auto-transfer flow
- Notification sending

### 10.3 Integration Testing
- Integration dengan Pendaftaran (auto-generate ticket)
- Integration dengan SMS gateway
- WebSocket broadcasting

### 10.4 UAT Scenarios
- Pesakit walk-in full journey (daftar → konsultasi → farmasi → billing)
- Warga emas priority journey
- No-show scenario
- Peak hour simulation (multiple tickets)

---

## 11. Langkah Implementasi

### Fasa 1: Setup & Core Database (1 minggu)
- [ ] Setup database schema (10 jadual)
- [ ] Create migrations
- [ ] Create models dengan relationships
- [ ] Seed queue types dan counters

### Fasa 2: Ticket Generation (1 minggu)
- [ ] QueueTicketService
- [ ] Generate ticket logic
- [ ] Priority assignment
- [ ] EWT calculation
- [ ] Counter ticket generation page

### Fasa 3: Staff Dashboard (1.5 minggu)
- [ ] Staff assignment to counter
- [ ] Queue list view
- [ ] Call next logic (dengan priority)
- [ ] Recall, complete, no-show actions
- [ ] Keyboard shortcuts

### Fasa 4: Real-time & Display (1.5 minggu)
- [ ] WebSocket setup (Laravel Echo + Pusher/Soketi)
- [ ] Event broadcasting
- [ ] Public display page
- [ ] Auto-refresh logic
- [ ] Voice announcement (TTS)

### Fasa 5: Kiosk Interface (1 minggu)
- [ ] Kiosk touchscreen UI
- [ ] Multi-language support
- [ ] IC scan untuk appointment
- [ ] Slip printing (PDF generation)

### Fasa 6: Auto-Transfer & Notifications (1 minggu)
- [ ] Auto-transfer logic
- [ ] Transfer history
- [ ] SMS/WhatsApp notifications
- [ ] Notification templates
- [ ] Patient reply handling (DELAY, CANCEL)

### Fasa 7: Integration dengan Pendaftaran (0.5 minggu)
- [ ] Event listener untuk pendaftaran selesai
- [ ] Auto-generate ticket
- [ ] Pass patient data ke queue

### Fasa 8: Analytics & Reporting (1 minggu)
- [ ] Daily/hourly stats aggregation
- [ ] Real-time dashboard analytics
- [ ] Historical reports (6 reports)
- [ ] Export to PDF/Excel

### Fasa 9: Admin & Configuration (0.5 minggu)
- [ ] Queue type CRUD
- [ ] Counter CRUD
- [ ] Kiosk management
- [ ] System settings

### Fasa 10: Testing & UAT (1 minggu)
- [ ] Unit tests
- [ ] Feature tests
- [ ] UAT dengan staf
- [ ] Bug fixes

### Fasa 11: Deployment (0.5 minggu)
- [ ] Deploy to production
- [ ] Configure kiosk devices
- [ ] Configure display TVs
- [ ] Training untuk staf

**Anggaran Masa:** 11 minggu (2.5-3 bulan)

---

## 12. Kriteria Kejayaan

### 12.1 Metrics
1. Average wait time reduction: ≥ 30% dalam 3 bulan
2. No-show detection rate: ≥ 95%
3. Patient satisfaction (survey): ≥ 4.0/5.0
4. Staff efficiency (patients served per hour): Increase ≥ 20%
5. System uptime: ≥ 99.5%

---

## 13. Risks & Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Kiosk hardware failure | HIGH | MEDIUM | Fallback to counter ticket generation; spare kiosk |
| WebSocket connection unstable | MEDIUM | MEDIUM | Polling fallback; monitor connection health |
| Voice announcement not heard | MEDIUM | LOW | Visual flash on display; SMS notification backup |
| Peak hour overload | HIGH | LOW | Queue size limits; horizontal scaling; caching |
| Patient miss notification | MEDIUM | MEDIUM | Multiple channels (display + voice + SMS); recall mechanism |

---

## 14. Acceptance Criteria

### 14.1 Functional
- ✅ Multi-queue dengan prefix berbeza berfungsi
- ✅ Kiosk boleh issue ticket dengan print slip
- ✅ Display papar nombor dipanggil dengan voice announcement
- ✅ Staff dashboard boleh call, recall, complete, no-show
- ✅ Priority queue berfungsi (warga emas, OKU)
- ✅ EWT calculated dan displayed
- ✅ Auto-transfer antara queue berfungsi
- ✅ SMS/WhatsApp notification sent
- ✅ Integration dengan Pendaftaran (auto-generate ticket)
- ✅ Analytics dashboard accurate
- ✅ All 6 reports generate correctly

### 14.2 Non-Functional
- Performance: Ticket generation < 2 saat
- Real-time: Display update < 500ms
- Availability: 99.5% uptime
- Usability: Staff dapat guna dalam 5 minit training

---

## 15. Lampiran

### 15.1 Contoh Slip Nombor Giliran

```
╔══════════════════════════════════════╗
║       POLIKLINIK AL-HUDA             ║
║    Sistem Pengurusan Giliran         ║
╠══════════════════════════════════════╣
║                                      ║
║          NOMBOR ANDA                 ║
║                                      ║
║         ┌─────────────┐              ║
║         │   D1-015    │              ║
║         └─────────────┘              ║
║                                      ║
║   Queue: Konsultasi Doktor 1         ║
║                                      ║
║   Anggaran Menunggu: 15 minit        ║
║   Di hadapan anda: 5 orang           ║
║                                      ║
║   Tarikh: 13/01/2026                 ║
║   Masa: 09:30 AM                     ║
║                                      ║
║   [██████████████████████]           ║
║   (Barcode untuk scan)               ║
║                                      ║
║   Sila tunggu di ruang menunggu.     ║
║   Perhatikan paparan dan             ║
║   dengar pengumuman.                 ║
╚══════════════════════════════════════╝
```

### 15.2 Contoh Display Layout

```
┌────────────────────────────────────────────────────────────┐
│  POLIKLINIK AL-HUDA           13 Jan 2026  09:45 AM       │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │          NOMBOR YANG DIPANGGIL                       │  │
│  ├──────────────┬──────────────┬──────────────┬─────────┤  │
│  │ PENDAFTARAN  │   DOKTOR 1   │   DOKTOR 2   │ FARMASI │  │
│  ├──────────────┼──────────────┼──────────────┼─────────┤  │
│  │    R-023     │    D1-015    │    D2-008    │  F-032  │  │
│  │  Kaunter 2   │ Bilik Dok 1  │ Bilik Dok 2  │  K.Far  │  │
│  └──────────────┴──────────────┴──────────────┴─────────┘  │
│                                                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  SETERUSNYA                                          │  │
│  │  R: 024, 025, 026 │ D1: 016, 017 │ D2: 009 │ F: 033  │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                            │
│  Anggaran Menunggu:  Pendaftaran: 5 min │ Doktor: 20 min  │
├────────────────────────────────────────────────────────────┤
│  NOTIS: Waktu operasi 8:00 AM - 5:00 PM. Terima kasih.    │
└────────────────────────────────────────────────────────────┘
```

### 15.3 Entity-Relationship Diagram

```
┌──────────────┐       ┌─────────────────┐
│ queue_types  │───────│ queue_counters  │
└──────────────┘ 1   * └─────────────────┘
       │ 1                      │ 1
       │                        │
       │ *                      │ *
┌──────────────┐       ┌─────────────────────┐
│queue_tickets │───────│queue_staff_assignment│
└──────────────┘ *   1 └─────────────────────┘
       │ 1                      │ *
       │                        │
       │ *                      │ 1
┌──────────────┐         ┌──────────┐
│ queue_calls  │         │  users   │
└──────────────┘         └──────────┘

┌──────────────┐       ┌───────────────────┐
│queue_tickets │───────│ queue_transfers   │
└──────────────┘ 1   * └───────────────────┘

┌──────────────┐       ┌─────────────────────┐
│queue_tickets │───────│queue_notifications  │
└──────────────┘ 1   * └─────────────────────┘
```

---

**END OF PRD**

---

## Appendix: Change Log

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-13 | System | Initial PRD creation |