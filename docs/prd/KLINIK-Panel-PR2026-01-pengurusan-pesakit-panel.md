# PRD: Modul Panel Insurans / GL - Pengurusan Pesakit Panel

**Kod PRD:** KLINIK-Panel-PR2026-01-pengurusan-pesakit-panel
**Modul:** Panel Insurans / GL (Guarantee Letter)
**Submodul:** Pengurusan Pesakit Panel
**Tarikh Dicipta:** 2026-01-13
**Versi:** 1.0
**Pemilik Produk:** Pemilik Klinik
**Stakeholder:** Kerani Front Desk, Kerani Akaun, Pengurus Klinik, Doktor

---

## 1. Ringkasan Eksekutif

### 1.1 Objektif
Sistem Panel Insurans / GL bertujuan untuk mengautomasikan pengurusan pesakit panel, verifikasi Guarantee Letter (GL), tracking had manfaat, pre-authorization workflow, claim submission, dan payment reconciliation dengan insurer untuk meningkatkan kecekapan operasi dan mengurangkan claim rejection di Poliklinik Al-Huda.

### 1.2 Skop
- Pengurusan panel (Corporate, Insurance, Government - SOCSO/PERKESO)
- Upload dan verify Guarantee Letter (GL) dengan extract details automatik
- Real-time tracking had manfaat (annual limit, per-visit limit, per-category limit)
- Pre-authorization (PA) workflow untuk prosedur mahal
- Multi-step panel eligibility verification
- Auto-calculate co-payment dan deductible
- Exclusion management untuk services yang tidak covered
- Full claim workflow (submit, track status, handle rejection, appeal)
- Batch claim submission untuk multiple invoices
- Payment reconciliation dengan panel payment advice
- ICD-10 diagnosis coding integration dengan EMR
- Panel contract management dengan fee schedule
- SLA tracking untuk claim processing
- Employee/Dependent management
- Comprehensive reporting untuk panel utilization dan claim status

### 1.3 Out of Scope
- Real-time API integration dengan insurers (Fasa 1 - manual verification, Fasa 2 - API)
- E-claim submission via government portal (SOCSO/PERKESO) - akan consider Fasa 2
- Direct settlement dengan insurers (cashless) - Fasa 1 fokus GL-based claims
- International insurance panel

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa
1. **Proses GL verification lambat:** Manual phone call atau email ke insurer untuk verify GL, memakan masa dan delay patient treatment
2. **Tiada tracking had manfaat:** Pesakit exceed limit baru staff tahu, menyebabkan claim reject dan klinik rugi
3. **Claim rejection tinggi:** Lack of proper documentation, wrong diagnosis code, atau exceed limit menyebabkan rejection rate tinggi
4. **Manual claim submission:** Print invoice, attach documents, hantar by hand/courier, lambat dan terdedah kepada missing documents
5. **Payment reconciliation manual:** Match payment advice dengan claims secara manual, time-consuming dan error-prone
6. **Tiada SLA tracking:** Claim tertunggak lama baru follow up, impact cash flow
7. **Co-payment tidak konsisten:** Manual calculate co-payment, sometimes staff lupa collect

### 2.2 Impak
- Delay dalam patient treatment (tunggu GL verification)
- Revenue loss akibat claim rejection atau exceed limit
- Cash flow issue akibat slow claim payment
- Staff workload tinggi untuk manual processing
- Poor patient experience (confusion about co-payment, unexpected charges)
- Compliance risk (missing ICD-10 code, incomplete documentation)

---

## 3. User Stories

### 3.1 User Stories Utama

1. **Sebagai Kerani Front Desk**, **saya mahu** verify GL pesakit panel dengan cepat semasa check-in **supaya** saya tahu coverage limit dan apa yang covered sebelum pesakit jumpa doktor **bila** pesakit datang dengan GL **saya sepatutnya** boleh upload GL dan sistem auto-extract details (GL number, coverage limit, validity period)

2. **Sebagai Kerani Front Desk**, **saya mahu** sistem alert jika pesakit hampir exceed had manfaat **supaya** saya boleh inform pesakit awal dan elakkan claim rejection **bila** saya buat invoice untuk pesakit panel **saya sepatutnya** nampak balance limit (80% warning, 90% critical, 100% block)

3. **Sebagai Kerani Front Desk**, **saya mahu** sistem auto-calculate co-payment yang pesakit perlu bayar **supaya** saya tidak perlu calculate manual dan elakkan kesilapan **bila** pesakit selesai treatment **saya sepatutnya** sistem show patient portion (co-payment + excluded items) dan panel portion

4. **Sebagai Kerani Front Desk**, **saya mahu** sistem flag excluded items yang panel tidak cover **supaya** saya boleh inform pesakit perlu bayar sendiri **bila** doktor prescribed ubat atau prosedur **saya sepatutnya** nampak excluded items highlighted dan can explain to patient

5. **Sebagai Kerani Akaun**, **saya mahu** submit claim ke panel dengan documentation lengkap **supaya** claim tidak reject dan payment cepat **bila** saya submit claim **saya sepatutnya** sistem auto-generate itemized invoice dengan ICD-10 code, attach GL copy, dan checklist documents required

6. **Sebagai Kerani Akaun**, **saya mahu** track status semua claims yang submitted **supaya** saya boleh follow up yang pending atau overdue **bila** saya buka claim dashboard **saya sepatutnya** nampak aging report dan SLA alert untuk claims yang overdue

7. **Sebagai Kerani Akaun**, **saya mahu** batch submit multiple claims untuk same panel **supaya** saya boleh process lebih cepat **bila** end of month **saya sepatutnya** boleh select multiple invoices dan generate batch claim file

8. **Sebagai Kerani Akaun**, **saya mahu** reconcile payment advice daripada panel dengan outstanding claims **supaya** saya tahu which claims sudah paid dan which masih pending **bila** panel send payment advice **saya sepatutnya** boleh upload payment file dan sistem auto-match dengan claims

9. **Sebagai Doktor**, **saya mahu** tahu pesakit ada panel coverage sebelum saya prescribed treatment **supaya** saya boleh plan treatment mengikut panel guidelines **bila** saya buka EMR pesakit panel **saya sepatutnya** nampak panel details, coverage limit, dan exclusions

10. **Sebagai Pengurus Klinik**, **saya mahu** melihat panel utilization report dan claim success rate **supaya** saya boleh evaluate panel performance dan negotiate better rates **bila** saya review monthly report **saya sepatutnya** nampak revenue by panel, claim rejection rate, dan average payment turnaround time

11. **Sebagai Kerani Akaun**, **saya mahu** handle pre-authorization untuk prosedur mahal **supaya** saya dapat approval dahulu sebelum proceed treatment **bila** pesakit perlu procedure > RM500 **saya sepatutnya** boleh submit PA request dengan supporting docs dan track approval status

12. **Sebagai Kerani Front Desk**, **saya mahu** verify employee atau dependent eligibility **supaya** saya tahu pesakit layak claim under panel **bila** pesakit claim sebagai dependent **saya sepatutnya** boleh link to principal employee dan verify dependent relationship

### 3.2 Edge Cases

1. **Sebagai Kerani Akaun**, **saya mahu** handle claim rejection dengan appeal process **supaya** saya boleh resubmit dengan additional documents **bila** claim rejected **saya sepatutnya** boleh view rejection reason, upload additional docs, dan resubmit appeal

2. **Sebagai Kerani Front Desk**, **saya mahu** handle GL yang expired **supaya** saya boleh inform pesakit perlu renew GL atau bayar cash **bila** GL expiry date passed **saya sepatutnya** sistem block panel billing dan suggest cash payment

3. **Sebagai Kerani Akaun**, **saya mahu** convert rejected claim to patient invoice **supaya** pesakit boleh bayar jika panel reject **bila** panel final reject claim **saya sepatutnya** boleh generate invoice untuk pesakit dan inform them to pay

4. **Sebagai Pengurus Klinik**, **saya mahu** receive alert bila panel contract near expiry **supaya** saya boleh renew contract awal **bila** contract balance 30 hari **saya sepatutnya** dapat notification untuk renewal

5. **Sebagai Kerani Akaun**, **saya mahu** handle partial payment daripada panel **supaya** saya boleh track short payment **bila** panel bayar kurang daripada claim amount **saya sepatutnya** sistem flag discrepancy dan create adjustment entry

---

## 4. Keperluan Fungsian

### 4.1 Panel Management

**FR-1:** Sistem mesti support panel types: Corporate Panel (company contracts), Insurance Panel (AIA, Prudential, Great Eastern, Allianz, Takaful), Government Panel (SOCSO, PERKESO)

**FR-2:** Sistem mesti store panel details:
- Panel code (unique identifier)
- Panel name
- Panel type (Corporate/Insurance/Government)
- Contact person, phone, email, address
- Contract effective date dan expiry date
- Payment terms (30 days, 60 days)
- SLA for claim processing (days)
- Status (Active/Inactive/Suspended)

**FR-3:** Sistem mesti define coverage packages per panel:
- Package name (contoh: "Gold Package", "Standard Package")
- Annual coverage limit (contoh: RM5,000 per year)
- Per-visit limit (contoh: RM500 per visit)
- Per-category limits (Medication: RM200, Consultation: RM100, Procedure: RM300)
- Co-payment percentage (contoh: 10% patient bayar)
- Deductible amount (contoh: first RM50 patient bayar)

**FR-4:** Sistem mesti define fee schedule per panel:
- Consultation fee rate
- Procedure rates (by procedure code)
- Medication markup percentage
- Override standard clinic rates dengan panel rates

**FR-5:** Sistem mesti define exclusions per panel:
- Excluded procedures (contoh: cosmetic procedures, health screening)
- Excluded medications (contoh: supplements, cosmetic drugs)
- Excluded diagnosis (contoh: pre-existing conditions)

### 4.2 Guarantee Letter (GL) Management

**FR-6:** Sistem mesti support GL upload (PDF/Image format)

**FR-7:** Sistem mesti extract GL details (manual input jika auto-extract tidak available):
- GL number (unique)
- Panel name
- Employee name + IC/Passport
- Employee ID (staff number)
- Dependent name (jika applicable)
- Coverage limit for this GL
- Validity period (effective date - expiry date)
- Diagnoses covered (jika specific)
- Special remarks

**FR-8:** Sistem mesti validate GL:
- Check GL number unique (tiada duplicate)
- Check validity period (effective date ≤ today ≤ expiry date)
- Check panel active status
- Alert jika GL expired atau akan expire dalam 7 hari

**FR-9:** Sistem mesti track GL utilization:
- GL amount used (sum of invoices under this GL)
- GL balance remaining
- Alert bila GL utilization reach 80%, 90%, 100%
- Block billing bila GL exceed limit

### 4.3 Panel Eligibility Verification

**FR-10:** Sistem mesti verify patient eligibility:
- Check employee ID atau policy number valid
- Check panel active status
- Check coverage package assigned
- Check effective date dan expiry date
- Display coverage details dan exclusions

**FR-11:** Sistem mesti support manual verification fallback:
- Record verification method (System/Phone Call/Email)
- Record verification person (insurer staff name)
- Record verification date and time
- Attach verification notes

### 4.4 Employee & Dependent Management

**FR-12:** Sistem mesti link dependent to principal employee:
- Principal employee (main cardholder)
- Dependent relationship (Spouse/Child/Parent)
- Dependent IC/Passport
- Dependent coverage status (Active/Inactive)

**FR-13:** Sistem mesti track limits separately or combined:
- Combined limit (principal + all dependents share same limit)
- Separate limit (each dependent has own limit)
- Configurable per panel

### 4.5 Had Manfaat (Benefit Limit) Tracking

**FR-14:** Sistem mesti track limits real-time:
- Annual limit (reset setiap tahun pada renewal date)
- Per-visit limit (per konsultasi)
- Per-category limit (Medication, Consultation, Procedure, Lab)

**FR-15:** Sistem mesti calculate balance selepas setiap visit:
- Utilization to date
- Balance remaining
- Projected utilization (jika current invoice included)

**FR-16:** Sistem mesti alert bila approach limit:
- 80% utilization → Warning (yellow)
- 90% utilization → Critical (orange)
- 100% utilization → Block (red, cannot proceed)

**FR-17:** Sistem mesti handle exceed scenario:
- Option 1: Block service completely
- Option 2: Allow service tetapi patient bayar excess amount
- Configurable per panel

### 4.6 Co-Payment & Deductible

**FR-18:** Sistem mesti auto-calculate co-payment:
- Apply co-payment percentage to covered amount
- Example: Invoice RM100, co-payment 10% → Patient bayar RM10, Panel claim RM90

**FR-19:** Sistem mesti auto-calculate deductible:
- Deduct from covered amount
- Example: Invoice RM100, deductible RM50 → Patient bayar RM50, Panel claim RM50
- Deductible apply once per visit atau per year (configurable)

**FR-20:** Sistem mesti split invoice:
- Panel portion (to claim)
- Patient portion (to collect: co-payment + deductible + excluded items)

### 4.7 Exclusion Management

**FR-21:** Sistem mesti flag excluded items automatically:
- Check invoice items against panel exclusion list
- Highlight excluded items in different color
- Auto-calculate excluded amount (patient must pay)

**FR-22:** Sistem mesti inform user about exclusions:
- Display exclusion reason
- Suggest patient payment for excluded items
- Confirm with patient before proceed

### 4.8 Pre-Authorization (PA) Workflow

**FR-23:** Sistem mesti support PA request submission:
- PA required for procedures > threshold amount (configurable, contoh: > RM500)
- PA request form dengan:
  - Patient details
  - Procedure details (name, code, estimated cost)
  - Diagnosis (ICD-10 code)
  - Justification (clinical notes)
  - Supporting documents (medical reports, lab results)
- Submit to panel via email atau online portal

**FR-24:** Sistem mesti track PA status:
- Pending (submitted, waiting response)
- Approved (PA approval number received)
- Rejected (rejection reason)
- Expired (PA validity period passed)

**FR-25:** Sistem mesti link PA to invoice:
- When create invoice for approved PA, attach PA approval number
- Validate invoice amount tidak exceed PA approved amount

### 4.9 Invoice & Claim Submission

**FR-26:** Sistem mesti auto-generate claim invoice dengan:
- Itemized billing (setiap item dengan description, quantity, price)
- ICD-10 diagnosis code (auto-populate dari EMR)
- Panel details (panel name, GL number, employee ID)
- Co-payment dan deductible breakdown
- Total claimable amount
- PA approval number (jika applicable)

**FR-27:** Sistem mesti attach required documents:
- GL copy (PDF/Image)
- Invoice copy
- Medical certificate (MC) jika applicable
- Lab reports atau investigation results
- PA approval letter jika applicable
- Prescription copy untuk medication claims

**FR-28:** Sistem mesti validate claim before submission:
- Check GL valid dan not expired
- Check benefit limit not exceeded
- Check ICD-10 code present
- Check PA approval (jika required)
- Checklist mandatory documents

**FR-29:** Sistem mesti track claim status:
- Draft (not yet submitted)
- Submitted (sent to panel)
- Acknowledged (panel received)
- Under Review (panel processing)
- Approved (approved for payment)
- Rejected (with rejection reason)
- Paid (payment received)

**FR-30:** Sistem mesti support batch claim submission:
- Select multiple invoices for same panel
- Generate batch claim file (Excel/CSV atau PDF)
- Track batch reference number
- Monitor batch status

### 4.10 Claim Rejection & Appeals

**FR-31:** Sistem mesti record rejection details:
- Rejection reason (from panel)
- Rejection date
- Rejected amount
- Adjustable amount (if panel approve partial)

**FR-32:** Sistem mesti support appeal process:
- Upload additional supporting documents
- Resubmit claim dengan notes
- Track appeal status (Appealed/Approved/Final Rejected)

**FR-33:** Sistem mesti convert rejected claim to patient invoice:
- If final rejection → generate invoice untuk patient
- Inform patient to pay
- Track conversion to patient billing

### 4.11 Payment Reconciliation

**FR-34:** Sistem mesti support payment advice upload:
- Upload panel payment advice file (Excel/CSV/PDF)
- Extract payment details:
  - Invoice/Claim number
  - Approved amount
  - Paid amount
  - Deduction/Adjustment
  - Payment date
  - Payment reference number

**FR-35:** Sistem mesti auto-match payment dengan claims:
- Match by invoice number atau GL number
- Flag matched claims as "Paid"
- Update payment received date
- Flag discrepancies:
  - Short payment (paid < approved)
  - Overpayment (paid > approved)
  - Unmatched payment (no corresponding claim)
  - Unmatched claim (no payment received)

**FR-36:** Sistem mesti generate reconciliation report:
- Total payment received
- Total claims matched
- Total discrepancies
- Outstanding claims (submitted but not paid)
- Aging report (0-30, 31-60, 61-90, >90 hari)

### 4.12 ICD-10 Diagnosis Coding

**FR-37:** Sistem mesti integrate dengan EMR untuk auto-populate ICD-10:
- Doktor pilih diagnosis dalam EMR
- ICD-10 code auto-populate ke claim invoice
- Validate ICD-10 code format (alphanumeric, 3-7 characters)

**FR-38:** Sistem mesti support manual ICD-10 entry:
- ICD-10 code search (by code atau description)
- Allow multiple diagnosis codes per claim
- Primary diagnosis dan secondary diagnosis

### 4.13 Panel Contract Management

**FR-39:** Sistem mesti store contract details:
- Contract number
- Effective date dan expiry date
- Renewal date
- Contract document upload (PDF)
- Fee schedule attached
- Coverage rules attached

**FR-40:** Sistem mesti alert contract expiry:
- Alert 90 hari sebelum expiry
- Alert 60 hari
- Alert 30 hari
- Notification kepada Pengurus Klinik

### 4.14 SLA Tracking

**FR-41:** Sistem mesti track SLA per panel:
- Define SLA days (contoh: 14 days untuk payment, 7 days untuk approval)
- Calculate claim age (days dari submission date)
- Alert bila approach SLA deadline (80%, 90%, 100%)
- Color code claims: Green (within SLA), Yellow (approaching), Red (overdue)

**FR-42:** Sistem mesti generate SLA compliance report:
- % claims paid within SLA
- Average payment turnaround time
- Overdue claims list
- SLA performance by panel

### 4.15 Integration dengan Modul Lain

**FR-43:** Integration dengan EMR:
- Auto-receive diagnosis (ICD-10 code) bila doktor finalize EMR
- Display panel coverage info dalam EMR (so doktor aware)
- Send panel alerts to EMR (exceed limit, PA required)

**FR-44:** Integration dengan Billing:
- Auto-receive invoice items bila dispensing atau consultation selesai
- Apply panel rates (override standard rates)
- Calculate co-payment dan panel portion
- Split invoice to panel claim vs patient payment

**FR-45:** Integration dengan Farmasi:
- Check medication against panel exclusion list
- Apply panel medication rates atau markup
- Flag excluded medications

### 4.16 Reporting

**FR-46:** Sistem mesti provide reports berikut:

1. **Panel Utilization Report:**
   - Total visits by panel (monthly/yearly)
   - Total revenue by panel
   - Average claim value
   - Top 10 most utilized panels

2. **Claim Status Report:**
   - Claims by status (Submitted/Approved/Rejected/Paid)
   - Total claimable amount
   - Total paid amount
   - Total outstanding amount

3. **Outstanding Claims Aging Report:**
   - 0-30 hari (count + amount)
   - 31-60 hari
   - 61-90 hari
   - >90 hari (overdue)

4. **Claim Rejection Report:**
   - Rejection rate by panel
   - Top rejection reasons
   - Total rejected amount
   - Appeal success rate

5. **GL Expiry Report:**
   - GLs expiring dalam 7/14/30 hari
   - Expired GLs yang masih ada outstanding claims

6. **SLA Compliance Report:**
   - % claims within SLA by panel
   - Average turnaround time
   - Overdue claims

7. **Revenue by Panel Report:**
   - Panel contribution to total revenue
   - Revenue trend by month
   - Comparison: Panel revenue vs Cash revenue

8. **Top Diagnosis by Panel:**
   - Most common ICD-10 codes
   - Treatment patterns by panel

**FR-47:** Semua reports mesti boleh export to PDF dan Excel

---

## 5. Keperluan Teknikal

### 5.1 Arkitektur Sistem

**Framework:** Laravel 12
**Frontend:** Blade Templates + Bootstrap 5 + CoreUI
**Database:** MySQL 8.0
**Pattern:** Service Layer + Repository Pattern
**Validation:** FormRequest
**Routing:** Spatie Route Attributes
**File Storage:** Laravel Storage (local atau S3 untuk GL documents)

### 5.2 Struktur Database

Sistem ini memerlukan 18 jadual utama:

1. `panels` - Panel master data
2. `panel_packages` - Coverage packages per panel
3. `panel_fee_schedule` - Fee rates per panel
4. `panel_exclusions` - Excluded items per panel
5. `guarantee_letters` - GL records
6. `gl_utilization` - GL usage tracking
7. `panel_employees` - Employee master (principal cardholder)
8. `panel_dependents` - Dependents linked to employees
9. `panel_eligibility_checks` - Verification logs
10. `benefit_limit_tracking` - Real-time limit usage
11. `pre_authorizations` - PA requests and approvals
12. `panel_claims` - Claim submissions
13. `claim_documents` - Attached documents per claim
14. `claim_rejections` - Rejection records
15. `claim_appeals` - Appeal records
16. `payment_advices` - Panel payment records
17. `payment_reconciliation` - Reconciliation matching
18. `panel_contracts` - Contract management

**Jadual Utama: `panels`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `panel_code` | varchar(50) UNIQUE NOT NULL | Panel code (PAN-001) |
| `panel_name` | varchar(255) NOT NULL | Panel name |
| `panel_type` | enum NOT NULL | corporate/insurance/government |
| `contact_person` | varchar(255) NULL | Contact person |
| `phone` | varchar(50) NULL | Phone number |
| `email` | varchar(255) NULL | Email |
| `address` | text NULL | Address |
| `payment_terms_days` | int DEFAULT 30 | Payment terms (30/60 days) |
| `sla_approval_days` | int DEFAULT 7 | SLA for approval |
| `sla_payment_days` | int DEFAULT 14 | SLA for payment |
| `status` | enum NOT NULL DEFAULT 'active' | active/inactive/suspended |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `guarantee_letters`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `gl_number` | varchar(100) UNIQUE NOT NULL | GL number |
| `panel_id` | bigint UNSIGNED NOT NULL | FK → panels.id |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `employee_id` | bigint UNSIGNED NULL | FK → panel_employees.id |
| `gl_document_path` | varchar(255) NULL | Path to uploaded GL |
| `coverage_limit` | decimal(10,2) NOT NULL | Coverage limit for this GL |
| `effective_date` | date NOT NULL | GL effective date |
| `expiry_date` | date NOT NULL | GL expiry date |
| `diagnoses_covered` | text NULL | Specific diagnoses (if any) |
| `special_remarks` | text NULL | Special notes |
| `verification_status` | enum NOT NULL | pending/verified/expired |
| `verification_method` | enum NULL | system/phone/email |
| `verified_by` | bigint UNSIGNED NULL | FK → users.id |
| `verified_at` | timestamp NULL | Verification timestamp |
| `status` | enum NOT NULL DEFAULT 'active' | active/utilized/expired/cancelled |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `panel_claims`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `claim_number` | varchar(50) UNIQUE NOT NULL | CLM-YYYYMMDD-9999 |
| `invoice_id` | bigint UNSIGNED NOT NULL | FK → invoices.id |
| `panel_id` | bigint UNSIGNED NOT NULL | FK → panels.id |
| `gl_id` | bigint UNSIGNED NULL | FK → guarantee_letters.id |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `pa_id` | bigint UNSIGNED NULL | FK → pre_authorizations.id |
| `claim_date` | date NOT NULL | Claim submission date |
| `service_date` | date NOT NULL | Date of service |
| `icd10_primary` | varchar(10) NOT NULL | Primary diagnosis ICD-10 |
| `icd10_secondary` | text NULL | Secondary ICD-10 codes (JSON) |
| `total_invoice_amount` | decimal(10,2) NOT NULL | Total invoice |
| `co_payment_amount` | decimal(10,2) DEFAULT 0 | Co-payment |
| `deductible_amount` | decimal(10,2) DEFAULT 0 | Deductible |
| `excluded_amount` | decimal(10,2) DEFAULT 0 | Excluded items |
| `claimable_amount` | decimal(10,2) NOT NULL | Amount to claim |
| `approved_amount` | decimal(10,2) NULL | Approved by panel |
| `paid_amount` | decimal(10,2) NULL | Actually paid |
| `claim_status` | enum NOT NULL | draft/submitted/acknowledged/under_review/approved/rejected/paid |
| `rejection_reason` | text NULL | Reason if rejected |
| `submitted_at` | timestamp NULL | Submission timestamp |
| `approved_at` | timestamp NULL | Approval timestamp |
| `paid_at` | timestamp NULL | Payment timestamp |
| `sla_due_date` | date NULL | SLA due date |
| `is_overdue` | boolean DEFAULT false | SLA overdue flag |
| `batch_id` | varchar(50) NULL | Batch reference |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

### 5.3 Models (Eloquent)

Models yang perlu dicipta:
- Panel, PanelPackage, PanelFeeSchedule, PanelExclusion
- GuaranteeLetter, GLUtilization
- PanelEmployee, PanelDependent
- PanelEligibilityCheck, BenefitLimitTracking
- PreAuthorization
- PanelClaim, ClaimDocument, ClaimRejection, ClaimAppeal
- PaymentAdvice, PaymentReconciliation
- PanelContract

### 5.4 Services & Repositories

Services:
- PanelService - Panel management
- GLService - GL verification, upload, tracking
- EligibilityService - Verify patient eligibility
- BenefitLimitService - Track limits, alert
- PreAuthorizationService - PA workflow
- ClaimService - Claim submission, tracking
- ReconciliationService - Payment reconciliation
- ReportService - Generate panel reports

Repositories:
- PanelRepository, GLRepository, ClaimRepository
- PreAuthorizationRepository, PaymentAdviceRepository

---

## 6. Workflow

### 6.1 Workflow GL Verification (Check-in)

```
Pesakit panel datang dengan GL
    ↓
Kerani upload GL (PDF/Image)
    ↓
Sistem extract GL details (GL number, coverage limit, validity)
    ↓
Sistem validate:
    - GL number unique?
    - GL valid period?
    - Panel active?
    ↓
If valid:
    Sistem create GL record (status: verified)
    Display coverage details to Kerani
    Link GL to patient
    ↓
Kerani inform patient:
    - Coverage limit: RM5000
    - Co-payment: 10%
    - Exclusions: Cosmetic, supplements
    ↓
Patient proceed to consultation
```

### 6.2 Workflow Billing dengan Panel

```
Patient selesai consultation + ambil ubat
    ↓
Sistem auto-receive dari EMR (ICD-10 diagnosis) & Farmasi (medications)
    ↓
Sistem create invoice (auto-populate):
    - Panel rates (override standard rates)
    - ICD-10 code dari EMR
    ↓
Sistem check exclusions:
    - Flag excluded items (cosmetic drugs, supplements)
    ↓
Sistem calculate:
    - Total invoice: RM500
    - Excluded: RM50 (supplement)
    - Covered amount: RM450
    - Deductible: RM50 (patient bayar)
    - Remaining: RM400
    - Co-payment 10%: RM40 (patient bayar)
    - Panel claim: RM360
    ↓
Sistem check benefit limit:
    - Annual limit: RM5000
    - Used to date: RM3000
    - Current claim: RM360
    - Balance after: RM1640
    - Status: OK (66% utilized)
    ↓
Sistem split invoice:
    - Patient portion: RM50 + RM50 + RM40 = RM140 (collect now)
    - Panel portion: RM360 (to claim)
    ↓
Kerani collect patient portion (RM140)
    ↓
Print receipt untuk patient
    ↓
Create claim record (status: draft)
```

### 6.3 Workflow Claim Submission

```
Kerani Akaun buka claim dashboard
    ↓
Select claims to submit (same panel)
    ↓
Sistem validate each claim:
    ✓ GL valid?
    ✓ ICD-10 code present?
    ✓ Benefit limit not exceeded?
    ✓ PA approval attached (if required)?
    ✓ Mandatory documents attached?
    ↓
If all valid:
    Sistem generate claim invoice (itemized billing)
    Attach documents (GL copy, MC, lab reports)
    ↓
    Kerani review dan submit
    ↓
    Sistem update claim status: Submitted
    Record submission date
    Calculate SLA due date (submission + 14 days)
    ↓
    Send claim to panel (email atau upload to portal)
    ↓
Panel process claim (external)
    ↓
Kerani update claim status manually:
    - Acknowledged (panel received)
    - Under Review (panel processing)
    - Approved (panel approve payment)
    ↓
Wait for payment...
```

### 6.4 Workflow Payment Reconciliation

```
Panel send payment advice (Excel/CSV/PDF)
    ↓
Kerani Akaun upload payment advice file
    ↓
Sistem extract payment details:
    - Claim number: CLM-20260113-0001
    - Approved amount: RM360
    - Paid amount: RM360
    - Payment date: 2026-01-27
    - Payment reference: PAY123456
    ↓
Sistem auto-match dengan outstanding claims:
    - Match by claim number
    ↓
If matched:
    Update claim status: Paid
    Update paid_amount: RM360
    Update paid_at: 2026-01-27
    Update payment reference
    ↓
Sistem check discrepancy:
    - Approved RM360 = Paid RM360 → No discrepancy
    ↓
Sistem generate reconciliation report:
    - Total payment: RM5000
    - Claims matched: 15
    - Claims outstanding: 5
    - Discrepancies: 2 (short payment)
```

### 6.5 Workflow Pre-Authorization

```
Patient perlu prosedur mahal (> RM500)
    ↓
Kerani create PA request:
    - Patient details
    - Procedure: Colonoscopy
    - Estimated cost: RM800
    - Diagnosis: ICD-10 K51 (Ulcerative colitis)
    - Justification: Persistent symptoms
    - Attach: Lab results, medical reports
    ↓
Submit PA to panel (email or online)
    ↓
Sistem track PA status: Pending
    ↓
Panel review PA (external)
    ↓
Panel respond:
    - Option A: Approved (PA number: PA123456, approved amount: RM800)
    - Option B: Rejected (reason: "Insufficient documentation")
    ↓
If approved:
    Sistem update PA status: Approved
    Record PA number: PA123456
    Link PA to patient
    ↓
Proceed with procedure
    ↓
Bila buat invoice, attach PA number
    Validate invoice amount ≤ PA approved amount
```

---

## 7. Keperluan UI/UX

### 7.1 Key Pages

1. **Panel Dashboard** - Summary cards, alerts, quick actions
2. **Panel Management** - CRUD panels, packages, fee schedule, exclusions
3. **GL Verification** - Upload GL, extract details, verify
4. **GL Listing** - All GLs with status, expiry alerts
5. **Eligibility Check** - Verify patient, display coverage
6. **Benefit Limit Tracking** - Real-time limit usage, alerts
7. **Pre-Authorization** - PA request form, track status
8. **Claim Submission** - Create claim, attach docs, batch submit
9. **Claim Tracking** - Claim dashboard with status, aging, SLA
10. **Payment Reconciliation** - Upload payment advice, auto-match
11. **Claim Rejection & Appeal** - View rejection, resubmit
12. **Panel Reports** - Generate reports, export
13. **Panel Contract** - Contract details, expiry alerts

### 7.2 Design System
- Framework: Bootstrap 5 + CoreUI
- Icons: CoreUI Icons / Font Awesome
- Color Scheme: Professional panel management palette (blue/green)
- Responsive: Mobile-first design (tablet support untuk front desk)

### 7.3 Key UI Components

**GL Upload Component:**
- Drag & drop upload area
- Image preview
- Auto-extract fields (editable jika tidak accurate)
- Validation status indicators

**Benefit Limit Widget:**
- Progress bar untuk limit utilization
- Color-coded: Green (<80%), Yellow (80-90%), Red (>90%)
- Balance display
- Alert messages

**Claim Status Badge:**
- Color-coded badges:
  - Draft: badge-secondary
  - Submitted: badge-info
  - Approved: badge-success
  - Rejected: badge-danger
  - Paid: badge-primary

**SLA Alert Indicator:**
- Green: Within SLA
- Yellow: Approaching SLA (80-90%)
- Red: Overdue (>100%)
- Display days remaining/overdue

---

## 8. Keperluan Keselamatan

### 8.1 PDPA Compliance
- GL documents (contain patient IC, medical info) adalah confidential
- Access control: Kerani Front Desk, Kerani Akaun, Pengurus sahaja
- Audit trail untuk semua GL access dan claim submission

### 8.2 Role-Based Access Control

**Kerani Front Desk:** GL verification, eligibility check, patient registration
**Kerani Akaun:** Claim submission, payment reconciliation, reports
**Pengurus Klinik:** View all, approve PA, panel contract management
**Admin:** Panel configuration, fee schedule, exclusions

### 8.3 Audit Trail
- Log: GL verification, claim submission, claim status update, payment reconciliation
- Immutable records (cannot delete GL atau claim records)

### 8.4 Data Integrity
- GL number must be unique
- Claim amount cannot exceed GL coverage limit
- Co-payment calculation must be accurate
- Payment matching must prevent duplicate reconciliation

---

## 9. Keperluan Prestasi

### 9.1 Response Time
- GL verification: ≤ 2 saat
- Eligibility check: ≤ 1 saat
- Claim submission: ≤ 3 saat
- Payment reconciliation (100 claims): ≤ 10 saat

### 9.2 Scalability
- Support 10-20 panels
- Support 100-500 GLs active at any time
- Support 500-2000 claims per month
- Proper indexing: panel_code, gl_number, claim_number, claim_status

---

## 10. Keperluan Ujian

### 10.1 Unit Testing
- GLService::extractGLDetails()
- BenefitLimitService::checkLimit()
- ClaimService::calculateClaimAmount()
- ReconciliationService::matchPayment()

### 10.2 Feature Testing
- GL verification workflow
- Benefit limit tracking dengan alerts
- Pre-authorization workflow
- Claim submission dengan validation
- Payment reconciliation auto-matching
- Claim rejection dan appeal

### 10.3 Integration Testing
- EMR integration (ICD-10 auto-populate)
- Billing integration (panel rates apply)
- Farmasi integration (exclusion check)

### 10.4 UAT
- Kerani Front Desk test GL verification
- Kerani Akaun test claim submission
- Test payment reconciliation dengan sample payment advice
- Test all reports

---

## 11. Langkah Implementasi

### Fasa 1: Setup & Panel Master (1 minggu)
- Setup 18 jadual
- Create migrations, models
- Seed sample panels, packages

### Fasa 2: GL Management (1.5 minggu)
- GL upload component
- GL verification workflow
- GL utilization tracking

### Fasa 3: Eligibility & Benefit Limit (1.5 minggu)
- Eligibility verification
- Real-time limit tracking
- Alert system

### Fasa 4: Pre-Authorization (1 minggu)
- PA request form
- PA status tracking
- PA approval linking

### Fasa 5: Claim Submission (2 minggu)
- Claim creation dengan validation
- Document attachment
- Batch claim submission
- ICD-10 integration dengan EMR

### Fasa 6: Claim Tracking & SLA (1 minggu)
- Claim status dashboard
- SLA monitoring
- Aging report

### Fasa 7: Payment Reconciliation (1.5 minggu)
- Payment advice upload
- Auto-matching logic
- Discrepancy handling

### Fasa 8: Rejection & Appeal (1 minggu)
- Rejection recording
- Appeal workflow
- Convert to patient invoice

### Fasa 9: Integration (1 minggu)
- EMR integration (ICD-10)
- Billing integration (panel rates)
- Farmasi integration (exclusions)

### Fasa 10: Reporting (1 minggu)
- 8 comprehensive reports
- Export to PDF/Excel

### Fasa 11: UAT & Deployment (1 minggu)
- UAT dengan Kerani
- Bug fixes
- Training
- Deployment

**Anggaran Masa:** 13.5 minggu (3-3.5 bulan)

---

## 12. Kriteria Kejayaan

### 12.1 Metrics
1. GL verification time: ≤ 2 minit (from upload to verified)
2. Claim rejection rate: ≤ 10%
3. SLA compliance: ≥ 90% claims paid within SLA
4. Payment reconciliation accuracy: ≥ 98%
5. User satisfaction: ≥ 85%

---

## 13. Risks & Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| GL auto-extract tidak accurate | MEDIUM | HIGH | Manual verification + edit functionality; training for Kerani |
| Panel change coverage terms mid-year | HIGH | MEDIUM | Alert system for contract changes; version control for packages |
| Claim rejection tinggi | HIGH | MEDIUM | Validation checklist before submission; training on documentation requirements |
| Payment reconciliation mismatch | HIGH | LOW | Manual override functionality; clear discrepancy reporting |
| SLA not met by panels | MEDIUM | HIGH | Persistent follow-up; escalation workflow; report to management |

---

## 14. Acceptance Criteria

### 14.1 Functional
- ✅ GL upload dan verification berfungsi
- ✅ Real-time benefit limit tracking dengan alerts
- ✅ Pre-authorization workflow complete
- ✅ Claim submission dengan validation
- ✅ Batch claim submission
- ✅ Payment reconciliation auto-matching
- ✅ Claim rejection dan appeal workflow
- ✅ ICD-10 integration dengan EMR
- ✅ All 8 reports generate correctly

### 14.2 Non-Functional
- Performance: GL verification ≤ 2 saat
- Security: PDPA compliant, audit trail
- Usability: Intuitive workflow untuk Kerani
- Reliability: Zero data loss, accurate calculations

---

## 15. Lampiran

### 15.1 Contoh GL Document
[Sample GL with annotations]

### 15.2 Contoh Claim Invoice
[Itemized billing format]

### 15.3 Entity-Relationship Diagram
[ER diagram showing panel, GL, claim relationships]

---

**END OF PRD**

---

## Appendix: Change Log

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-13 | System | Initial PRD creation |