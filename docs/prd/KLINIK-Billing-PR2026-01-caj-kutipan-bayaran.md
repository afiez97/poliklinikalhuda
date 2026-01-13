# PRD: Modul Bil & Pembayaran - Caj & Kutipan Bayaran

**Kod PRD:** KLINIK-Billing-PR2026-01-caj-kutipan-bayaran
**Modul:** Bil & Pembayaran
**Submodul:** Caj & Kutipan Bayaran
**Tarikh Dicipta:** 2026-01-13
**Versi:** 1.0
**Pemilik Produk:** Pemilik Klinik
**Stakeholder:** Kerani Akaun, Pengurus Klinik, Pemilik Klinik, Pesakit

---

## 1. Ringkasan Eksekutif

### 1.1 Objektif
Sistem Bil & Pembayaran bertujuan untuk mengautomasikan proses penjanaan bil, kutipan bayaran pelbagai kaedah (tunai, kad, QR Pay, e-Wallet, panel), pengurusan refund, dan pelaporan kewangan yang komprehensif bagi Poliklinik Al-Huda dengan pematuhan audit dan keperluan cukai.

### 1.2 Skop
- Penjanaan bil automatik daripada caj konsultasi (EMR) dan ubat (Farmasi)
- Kutipan bayaran dengan pelbagai kaedah: Tunai, Kad Kredit/Debit, QR Pay (DuitNow QR), e-Wallet (Touch 'n Go, GrabPay, Boost), Pindahan Bank, Panel/Insurans
- Support partial payment, deposit, dan outstanding balance tracking
- Diskaun dan promosi dengan approval workflow
- Refund dan credit notes dengan approval
- SST (Sales & Service Tax) calculation dan breakdown
- Auto-generate resit dan invoice (print + email PDF)
- Integration dengan EMR (consultation charges) dan Farmasi (dispensed drugs)
- Daily reconciliation dan cashier closing report
- Comprehensive reporting untuk audit dan cukai
- Outstanding balance tracking dengan reminder automatik

### 1.3 Out of Scope
- Online payment gateway (credit card processing) - Fasa 1 fokus manual confirmation
- Integration dengan accounting software (QuickBooks, SQL Accounting) - akan datang di Fasa 2
- Multi-currency billing (fokus: RM sahaja)
- Installment payment plan (akan consider di masa depan)

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa
1. **Kaedah pembayaran terhad:** Sistem semasa hanya terima tunai dan kad, tiada support untuk QR Pay dan e-Wallet yang semakin popular
2. **Bil manual atau semi-manual:** Bil dijana manual atau semi-automatik, lambat dan terdedah kepada kesilapan
3. **Tiada tracking outstanding balance:** Hutang pesakit tidak ditrack dengan sistematik, menyebabkan kehilangan revenue
4. **Reconciliation manual:** Reconciliation harian dilakukan manual, memakan masa dan terdedah kepada kesilapan
5. **Reporting tidak komprehensif:** Laporan kewangan tidak mencukupi untuk audit dan cukai
6. **Tiada audit trail:** Perubahan kepada bil atau payment tidak direkod dengan teliti

### 2.2 Impak
- Kehilangan peluang revenue daripada pesakit yang prefer QR Pay/e-Wallet
- Kesilapan billing menyebabkan dispute dengan pesakit
- Outstanding balance tidak dikutip, impak cash flow
- Masa terbuang untuk reconciliation manual
- Risiko ketidakpatuhan audit dan cukai
- Kesukaran untuk analisis trend kewangan dan business intelligence

---

## 3. User Stories

### 3.1 User Stories Utama

1. **Sebagai Kerani Akaun**, **saya mahu** sistem auto-generate bil berdasarkan caj konsultasi dan ubat daripada EMR dan Farmasi **supaya** saya tidak perlu key in semua items manual dan mengurangkan kesilapan **bila** pesakit selesai konsultasi dan ambil ubat **saya sepatutnya** dapat melihat bil lengkap yang sedia untuk payment

2. **Sebagai Kerani Akaun**, **saya mahu** menerima pembayaran menggunakan pelbagai kaedah (tunai, kad, QR Pay, e-Wallet, pindahan bank) **supaya** pesakit ada fleksibiliti dan klinik tidak kehilangan sales **bila** pesakit nak bayar **saya sepatutnya** boleh pilih kaedah pembayaran dan rekod payment dengan mudah

3. **Sebagai Kerani Akaun**, **saya mahu** memaparkan QR code DuitNow untuk pesakit scan dan bayar **supaya** pembayaran lebih cepat dan cashless **bila** pesakit pilih kaedah QR Pay **saya sepatutnya** boleh papar QR code klinik dan confirm payment selepas pesakit transfer

4. **Sebagai Kerani Akaun**, **saya mahu** apply diskaun (senior citizen, staff, promo code) dengan approval **supaya** diskaun hanya diberi kepada yang berhak **bila** pesakit layak untuk diskaun **saya sepatutnya** boleh apply diskaun dan sistem minta approval jika amount melebihi threshold

5. **Sebagai Kerani Akaun**, **saya mahu** menerima partial payment dan track balance owed **supaya** pesakit yang tidak mampu bayar penuh boleh bayar sebahagian dahulu **bila** pesakit bayar sebahagian sahaja **saya sepatutnya** boleh rekod partial payment dan sistem show balance tertunggak

6. **Sebagai Kerani Akaun**, **saya mahu** mencetak resit dan email resit PDF kepada pesakit **supaya** pesakit ada bukti pembayaran **bila** payment selesai **saya sepatutnya** boleh print resit dan email kepada pesakit jika mereka berikan alamat email

7. **Sebagai Kerani Akaun**, **saya mahu** process refund dengan sebab dan approval **supaya** refund hanya dibuat dengan justifikasi yang sah **bila** pesakit request refund **saya sepatutnya** boleh create refund request dengan sebab dan tunggu approval dari Pengurus atau Pemilik

8. **Sebagai Kerani Akaun**, **saya mahu** handle billing untuk pesakit panel/insurans **supaya** pesakit hanya bayar co-payment dan balance akan claim dari panel **bila** pesakit ada panel **saya sepatutnya** boleh generate invoice untuk panel dan track claim status

9. **Sebagai Kerani Akaun**, **saya mahu** membuat cashier closing report end of day **supaya** saya boleh reconcile cash dan card dengan sistem **bila** end of shift **saya sepatutnya** boleh generate closing report yang show total payment by method dan variance

10. **Sebagai Kerani Akaun**, **saya mahu** melihat senarai pesakit dengan outstanding balance **supaya** saya boleh follow up untuk payment **bila** saya semak outstanding **saya sepatutnya** nampak aging report (30/60/90 hari) dan boleh send reminder

11. **Sebagai Pengurus Klinik**, **saya mahu** melihat daily sales report dan revenue breakdown **supaya** saya tahu prestasi kewangan klinik **bila** saya buka dashboard **saya sepatutnya** nampak total revenue hari ini, breakdown by payment method, dan trending

12. **Sebagai Pemilik Klinik**, **saya mahu** melihat monthly revenue report dan SST report **supaya** saya boleh file tax return dengan betul **bila** end of month **saya sepatutnya** boleh generate comprehensive report untuk accountant dan LHDN

13. **Sebagai Pesakit**, **saya mahu** menerima resit digital via email **supaya** saya tidak hilang resit kertas **bila** saya bayar **saya sepatutnya** terima email dengan resit PDF attachment

### 3.2 Edge Cases

1. **Sebagai Kerani Akaun**, **saya mahu** void invoice atau payment dengan sebab **supaya** saya boleh betulkan kesilapan **bila** invoice atau payment salah **saya sepatutnya** boleh void dengan input sebab dan dapatkan approval jika amount besar

2. **Sebagai Kerani Akaun**, **saya mahu** split payment (sebahagian tunai, sebahagian kad) **supaya** pesakit boleh bayar menggunakan multiple methods **bila** pesakit nak split payment **saya sepatutnya** boleh rekod multiple payment methods untuk satu invoice

3. **Sebagai Kerani Akaun**, **saya mahu** handle deposit untuk prosedur yang akan datang **supaya** pesakit boleh bayar deposit dahulu **bila** pesakit book prosedur khas **saya sepatutnya** boleh rekod deposit dan deduct from final bill kemudian

4. **Sebagai Kerani Akaun**, **saya mahu** apply package pricing untuk MCU atau saringan kesihatan **supaya** pesakit dapat harga package yang lebih murah **bila** pesakit pilih package **saya sepatutnya** sistem auto-add semua items dalam package dan charge package price

5. **Sebagai Pengurus Klinik**, **saya mahu** approve refund yang melebihi threshold **supaya** refund besar tidak dibuat tanpa pengetahuan saya **bila** ada refund request > RM500 **saya sepatutnya** terima notification dan boleh approve atau reject dengan sebab

---

## 4. Keperluan Fungsian

### 4.1 Penjanaan Bil (Invoice Generation)

**FR-1:** Sistem mesti auto-generate invoice berdasarkan:
- Caj konsultasi daripada modul EMR (apabila doktor finalize EMR)
- Ubat dispensed daripada modul Farmasi (apabila ahli farmasi dispense ubat)
- Caj lain-lain (prosedur, ujian makmal, item walk-in)

**FR-2:** Sistem mesti support invoice items dengan:
- Jenis item (Consultation, Medication, Procedure, Lab Test, Others)
- Nama item
- Kuantiti
- Harga per unit
- Jumlah harga (kuantiti × harga unit)
- SST taxable (Ya/Tidak)
- Diskaun (jika applicable)

**FR-3:** Sistem mesti calculate invoice dengan:
- Subtotal (sum of all items before discount and tax)
- Diskaun (percentage atau fixed amount)
- Subtotal selepas diskaun
- SST amount (jika applicable, 6% atau configurable rate)
- Rounding adjustment (to nearest 5 sen)
- Total amount payable

**FR-4:** Sistem mesti auto-generate invoice number dengan format: `INV-YYYYMMDD-9999`

**FR-5:** Sistem mesti support invoice status: Draft, Pending Payment, Partially Paid, Fully Paid, Overdue, Voided, Refunded

### 4.2 Kaedah Pembayaran

**FR-6:** Sistem mesti support payment methods: Tunai, Kad Kredit/Debit, QR Pay (DuitNow QR), e-Wallet, Pindahan Bank, Panel/Insurans

**FR-7:** Sistem mesti support split payment (multiple payment methods untuk satu invoice)

**FR-8:** Sistem mesti support partial payment dengan tracking balance owed

**FR-9:** Sistem mesti support deposit payment untuk future procedures

### 4.3 Diskaun & Promosi

**FR-10:** Sistem mesti support diskaun: Percentage, Fixed amount, Promo code, Senior citizen (auto-suggest ≥ 60 tahun), Staff discount

**FR-11:** Sistem mesti implement approval workflow untuk diskaun melebihi threshold

**FR-12:** Sistem mesti validate promo code (validity period, usage limit, minimum purchase)

### 4.4 Package & Bundle Pricing

**FR-13:** Sistem mesti support pre-defined packages dengan auto-add items dan package price

### 4.5 SST (Sales & Service Tax)

**FR-14:** Sistem mesti support SST calculation dengan configurable rate (default 6%), per-item taxable flag, auto-calculate dan show breakdown

**FR-15:** Sistem mesti generate SST report untuk LHDN (monthly)

### 4.6 Rounding Policy

**FR-16:** Sistem mesti implement rounding to nearest 5 sen (mengikut Bank Negara guideline)

### 4.7 Resit & Invoice Printing

**FR-17:** Sistem mesti auto-generate resit untuk payment received dengan maklumat lengkap

**FR-18:** Sistem mesti support invoice printing untuk panel/insurans dengan official tax invoice format

**FR-19:** Sistem mesti support email resit/invoice PDF

### 4.8 Refund & Credit Notes

**FR-20:** Sistem mesti support refund workflow dengan approval untuk amount > threshold

**FR-21:** Sistem mesti auto-generate credit note selepas refund approved

### 4.9 Void Invoice & Payment

**FR-22:** Sistem mesti support void invoice dengan approval workflow

**FR-23:** Sistem mesti support void payment dengan restore balance owed

### 4.10 Outstanding Balance & Reminder

**FR-24:** Sistem mesti track outstanding balance dengan aging report (0-30, 31-60, 61-90, >90 hari)

**FR-25:** Sistem mesti auto-send reminder (SMS/WhatsApp) untuk outstanding balance

### 4.11 Panel/Insurans Billing

**FR-26:** Sistem mesti support panel billing dengan co-payment calculation dan claim status tracking

**FR-27:** Sistem mesti generate panel claim report

### 4.12 Integration dengan EMR & Farmasi

**FR-28:** Integration dengan Modul EMR - auto-receive consultation charge

**FR-29:** Integration dengan Modul Farmasi - auto-receive dispensed drugs

**FR-30:** Sistem mesti consolidate charges dalam satu invoice jika same visit

### 4.13 Reconciliation & Cashier Closing

**FR-31:** Sistem mesti provide cashier closing report dengan variance calculation

**FR-32:** Sistem mesti support daily reconciliation

### 4.14 Reporting

**FR-33:** Sistem mesti provide reports: Daily Sales, Monthly Revenue, SST, Outstanding Balance, Refund, Voided Transaction, Payment Method Breakdown, Panel Claim, Cashier Performance

**FR-34:** Semua reports mesti boleh export to PDF dan Excel

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

Sistem ini memerlukan 16 jadual utama:

1. `invoices` - Invoice/bills
2. `invoice_items` - Invoice line items
3. `payments` - Payment transactions
4. `receipts` - Official receipts
5. `refunds` - Refund transactions
6. `credit_notes` - Credit notes
7. `promo_codes` - Promo codes
8. `packages` - Service packages
9. `package_items` - Package line items
10. `panels` - Insurance panels
11. `deposits` - Patient deposits
12. `outstanding_reminders` - Reminder logs
13. `cashier_closing` - Cashier closing reports
14. `discount_approvals` - Discount approval records

**Jadual Utama: `invoices`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `invoice_number` | varchar(50) UNIQUE NOT NULL | INV-YYYYMMDD-9999 |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `emr_id` | bigint UNSIGNED NULL | FK → emr.id |
| `invoice_date` | date NOT NULL | Invoice date |
| `subtotal` | decimal(10,2) NOT NULL | Before discount/tax |
| `discount_amount` | decimal(10,2) DEFAULT 0 | Discount |
| `sst_amount` | decimal(10,2) DEFAULT 0 | SST |
| `rounding_adjustment` | decimal(10,2) DEFAULT 0 | Rounding |
| `total_amount` | decimal(10,2) NOT NULL | Final total |
| `paid_amount` | decimal(10,2) DEFAULT 0 | Paid so far |
| `balance_owed` | decimal(10,2) DEFAULT 0 | Outstanding |
| `status` | enum | draft/pending_payment/partially_paid/fully_paid/overdue/voided/refunded |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

### 5.3 Models (Eloquent)

Models yang perlu dicipta:
- Invoice, InvoiceItem, Payment, Receipt, Refund, CreditNote
- PromoCode, Package, PackageItem, Panel, Deposit
- OutstandingReminder, CashierClosing, DiscountApproval

### 5.4 Services & Repositories

Services:
- InvoiceService - Invoice generation & calculation
- PaymentService - Payment processing
- RefundService - Refund workflow
- DiscountService - Discount logic & approval
- ReportService - Generate reports
- ReconciliationService - Cashier closing
- PanelService - Panel billing

Repositories:
- InvoiceRepository, PaymentRepository, RefundRepository
- PromoCodeRepository, PackageRepository, PanelRepository

---

## 6. Workflow

### 6.1 Workflow Penjanaan Bil

```
Pesakit selesai konsultasi + ambil ubat
    ↓
Sistem auto-receive dari EMR & Farmasi
    ↓
Sistem auto-create Invoice (Draft)
    ↓
Sistem auto-create InvoiceItems
    ↓
Sistem calculate (Subtotal, SST, Total)
    ↓
Kerani buka invoice → status: Pending Payment
    ↓
(Optional) Apply diskaun
    ↓
Invoice ready untuk payment
```

### 6.2 Workflow Pembayaran

```
Kerani pilih payment method
    ↓
Cash: Input amount → calculate change
QR Pay: Display QR → confirm payment
e-Wallet: Select provider → input reference
Card: Input card details → approval code
Panel: Select panel → co-payment
    ↓
Sistem create Payment record
    ↓
Sistem update Invoice (paid_amount, balance, status)
    ↓
Sistem auto-generate Receipt
    ↓
Print/Email resit
```

### 6.3 Workflow Refund

```
Kerani create Refund Request
    ↓
Jika amount > threshold → Require approval
    ↓
Pengurus approve/reject
    ↓
Jika approved → Process refund
    ↓
Sistem auto-generate Credit Note
    ↓
Sistem update Invoice status
```

---

## 7. Keperluan UI/UX

### 7.1 Key Pages

1. **Dashboard Billing** - Summary cards, quick actions, recent transactions
2. **Invoice Listing** - Table dengan filter, search, status badges
3. **Invoice Detail & Payment** - Patient info, items, payment form
4. **QR Pay Display** - Modal dengan QR code, reference input
5. **Discount Application** - Modal dengan approval preview
6. **Refund Request Form** - Select invoice, reason, approval workflow
7. **Cashier Closing Report** - Variance calculation, approval
8. **Reports Page** - Select report type, filters, export
9. **Outstanding Balance** - Aging report, send reminder
10. **Panel Claim Tracking** - Status tracking, update claim

### 7.2 Design System
- Framework: Bootstrap 5 + CoreUI
- Icons: CoreUI Icons / Font Awesome
- Color Scheme: Professional financial palette
- Responsive: Mobile-first design

---

## 8. Keperluan Keselamatan

### 8.1 PDPA Compliance
- Semua maklumat pesakit confidential
- Access control: Kerani Akaun, Pengurus sahaja
- Audit trail untuk semua akses

### 8.2 Role-Based Access Control

**Kerani Akaun:** Full access billing, require approval untuk discount/refund > threshold
**Pengurus Klinik:** View all, approval, reports
**Pemilik Klinik:** Full access, high-value approval
**Admin:** Configuration

### 8.3 Audit Trail
- Log: Create invoice, process payment, apply discount, approve, void, refund
- Immutable records (voided tidak delete)

### 8.4 Data Integrity
- Invoice amount cannot be negative
- Paid amount cannot exceed total
- Rounding ≤ 0.05
- Approval workflow enforced

---

## 9. Keperluan Prestasi

### 9.1 Response Time
- Invoice listing: ≤ 1 saat
- Payment processing: ≤ 2 saat
- Receipt PDF: ≤ 3 saat
- Reports: ≤ 5 saat

### 9.2 Scalability
- Support 3-5 concurrent cashiers
- Proper indexing: invoice_number, status, pesakit_id, payment_date
- Caching untuk promo codes, packages, panels
- Pagination: 15 records per page

---

## 10. Keperluan Ujian

### 10.1 Unit Testing
- InvoiceService::calculateTotal()
- PaymentService::processPayment()
- DiscountService::validatePromoCode()
- RefundService::calculateRefundAmount()

### 10.2 Feature Testing
- Invoice workflow (auto-create, calculation)
- Payment workflow (all methods, split, partial)
- Discount (promo code, approval)
- Refund (approval, credit note)
- Void (invoice, payment)
- Cashier closing (variance)

### 10.3 Integration Testing
- EMR integration (consultation charge)
- Farmasi integration (dispensed drugs)
- SMS/WhatsApp reminder

### 10.4 UAT
- Kerani test full billing workflow
- Pengurus test approval
- Test QR Pay, e-Wallet
- Test cashier closing
- Test all reports

---

## 11. Langkah Implementasi

### Fasa 1: Setup & Database (1 minggu)
- Setup 16 jadual
- Create migrations, models
- Seed sample data

### Fasa 2: Invoice Management - Part 1 (1 minggu)
- InvoiceController, Service, Repository
- Invoice calculation logic
- Listing, detail page

### Fasa 3: Invoice Management - Part 2 (Integration) (1 minggu)
- Listener untuk EMR event
- Listener untuk Farmasi event
- Consolidation logic

### Fasa 4-17: Payment, Discount, Refund, Reports, etc. (12 minggu)
[Details in full PRD]

**Anggaran Masa:** 15.5 minggu (3.5-4 bulan)

---

## 12. Kriteria Kejayaan

### 12.1 Metrics
1. Billing Accuracy: ≥ 99%
2. QR Pay + e-Wallet adoption: ≥ 30% dalam 3 bulan
3. Outstanding collection: ≥ 80% dalam 30 hari
4. Cashier closing variance: ≤ RM20 untuk 95% closings
5. User satisfaction: ≥ 80%

---

## 13. Risks & Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| QR Pay manual confirmation error | HIGH | MEDIUM | Require reference number; training; audit log |
| e-Wallet recording fraud | MEDIUM | MEDIUM | Compulsory reference; daily reconciliation |
| Cashier variance | MEDIUM | MEDIUM | Training; double-check; supervisor approval |
| Outstanding tidak dikutip | MEDIUM | HIGH | Persistent follow-up; payment plan |
| SST calculation salah | HIGH | LOW | Testing; configurable rate; regular review |

---

## 14. Acceptance Criteria

### 14.1 Functional
- ✅ Auto-generate invoice dari EMR & Farmasi
- ✅ All payment methods berfungsi
- ✅ QR Pay display dan confirmation
- ✅ Split payment dan partial payment
- ✅ Receipt auto-generate dan email
- ✅ Discount dengan approval
- ✅ Refund workflow dengan credit note
- ✅ Void dengan approval
- ✅ Outstanding tracking dengan reminder
- ✅ Cashier closing dengan variance
- ✅ Panel billing
- ✅ All reports generate dengan betul

### 14.2 Non-Functional
- Performance: ≤ 2 saat untuk payment
- Security: PDPA compliant, audit trail
- Usability: ≤ 3 minit untuk invoice + payment
- Reliability: Zero data loss, transaction integrity

---

## 15. Lampiran

### 15.1 Contoh Invoice, Resit, Credit Note, Cashier Closing
[Details in full PRD]

### 15.2 Rounding Examples
| Original | Rounded | Adjustment |
|----------|---------|------------|
| RM123.43 | RM123.45 | +RM0.02 |
| RM123.47 | RM123.45 | -RM0.02 |

### 15.3 Entity-Relationship Diagram
[Simplified ER diagram showing relationships]

---

**END OF PRD**

---

## Appendix: Change Log

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-13 | System | Initial PRD creation |