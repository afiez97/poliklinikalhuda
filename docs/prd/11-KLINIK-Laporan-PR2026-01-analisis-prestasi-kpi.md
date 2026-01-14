# PRD: Modul Laporan & Analitik - Analisis Prestasi & KPI

**Kod PRD:** KLINIK-Laporan-PR2026-01-analisis-prestasi-kpi
**Modul:** Laporan & Analitik
**Submodul:** Analisis Prestasi
**Tarikh Dicipta:** 2026-01-13
**Versi:** 1.0
**Pemilik Produk:** Pemilik Klinik
**Stakeholder:** Pemilik Klinik, Pengurus Klinik, Department Heads

---

## 1. Ringkasan Eksekutif

### 1.1 Objektif
Sistem Laporan & Analitik bertujuan untuk menyediakan platform Business Intelligence (BI) yang komprehensif bagi Poliklinik Al-Huda dengan dashboard interaktif, KPI tracking, data warehouse, custom report builder, dan predictive analytics untuk membolehkan pengurusan membuat keputusan berdasarkan data yang tepat dan konsisten.

### 1.2 Skop
- Multi-level dashboard (Executive, Operational, Department)
- Comprehensive KPI tracking (Financial, Clinical, Operational, Customer, Compliance)
- Star schema data warehouse dengan ETL jobs
- Rich data visualizations (charts, heat maps, gauges)
- Multiple report types (Daily, Weekly, Monthly, Quarterly, Annual, Ad-hoc)
- Custom report builder dengan drag-and-drop
- Period comparison dan benchmarking
- Alert system bila KPI below target
- Role-based access control
- Export to PDF, Excel, CSV
- Scheduled auto-email reports
- Mobile-responsive dashboard
- Basic predictive analytics (forecasting)
- Data governance framework
- API-ready untuk external BI tools

### 1.3 Out of Scope
- Advanced machine learning models (Fasa 2)
- Real-time streaming analytics
- Multi-branch comparison (single clinic focus)
- Integration dengan external accounting software
- Natural language query (ask questions in plain text)

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa
1. **Data tidak konsisten:** Data tersebar di pelbagai modul tanpa standardization, menyebabkan laporan berbeza-beza hasilnya
2. **Tiada single source of truth:** Pengurus perlu compile data manual dari berbagai sistem untuk laporan
3. **Reporting lambat:** Laporan bulanan ambil masa berhari-hari untuk siapkan secara manual
4. **Tiada real-time visibility:** Pengurusan tidak tahu prestasi semasa klinik sehingga end of month
5. **Tiada KPI tracking formal:** Tiada benchmark untuk measure prestasi klinik
6. **Data quality issues:** Missing data, duplicate entries, inconsistent formats
7. **Limited analysis capability:** Hanya basic Excel analysis, tiada advanced visualization atau predictive

### 2.2 Impak
- Keputusan perniagaan dibuat berdasarkan incomplete atau outdated data
- Missed opportunities untuk improve operations
- Staff spend hours compiling reports manually
- Cannot identify trends atau issues early
- Difficult to set dan track performance targets
- Compliance reporting makan masa

---

## 3. User Stories

### 3.1 User Stories Utama

1. **Sebagai Pemilik Klinik**, **saya mahu** melihat executive dashboard dengan KPI utama (revenue, patient volume, collection rate) **supaya** saya tahu prestasi klinik pada bila-bila masa **bila** saya login ke sistem **saya sepatutnya** nampak overview semua metrics penting dalam satu halaman

2. **Sebagai Pemilik Klinik**, **saya mahu** membandingkan prestasi bulan ini dengan bulan lepas dan tahun lepas **supaya** saya tahu sama ada klinik berkembang atau tidak **bila** saya view monthly report **saya sepatutnya** nampak comparison charts dan percentage change

3. **Sebagai Pengurus Klinik**, **saya mahu** menerima alert bila KPI jatuh di bawah target **supaya** saya boleh ambil tindakan segera **bila** collection rate < 80% atau wait time > 30 minit **saya sepatutnya** terima email/SMS notification

4. **Sebagai Pengurus Klinik**, **saya mahu** melihat operational dashboard dengan real-time data **supaya** saya boleh monitor operasi harian **bila** saya buka dashboard **saya sepatutnya** nampak today's patient count, queue status, revenue so far

5. **Sebagai Pengurus Klinik**, **saya mahu** schedule laporan mingguan untuk auto-email kepada management **supaya** saya tidak perlu manually generate dan send setiap minggu **bila** saya setup scheduled report **saya sepatutnya** boleh pilih recipients, frequency, dan format

6. **Sebagai Pengurus Klinik**, **saya mahu** drill-down dari summary ke detail **supaya** saya boleh investigate issues **bila** saya klik pada metric yang tinggi/rendah **saya sepatutnya** dapat lihat breakdown dan detail records

7. **Sebagai Department Head (Farmasi)**, **saya mahu** melihat pharmacy-specific dashboard **supaya** saya fokus pada KPIs yang relevan dengan department saya **bila** saya login **saya sepatutnya** nampak dispensing volume, stock levels, expiry alerts, top drugs

8. **Sebagai Pengurus Klinik**, **saya mahu** buat custom report dengan memilih metrics dan dimensions **supaya** saya boleh analyse data mengikut keperluan spesifik **bila** saya guna report builder **saya sepatutnya** boleh drag-and-drop fields, apply filters, dan save template

9. **Sebagai Pemilik Klinik**, **saya mahu** melihat revenue forecast untuk bulan hadapan **supaya** saya boleh plan cash flow dan resources **bila** saya view predictive analytics **saya sepatutnya** nampak projected revenue based on historical trends

10. **Sebagai Pengurus Klinik**, **saya mahu** export laporan ke PDF dan Excel **supaya** saya boleh share dengan stakeholders yang tidak ada system access **bila** saya generate report **saya sepatutnya** boleh download dalam format pilihan saya

11. **Sebagai Pemilik Klinik**, **saya mahu** view dashboard di mobile phone **supaya** saya boleh monitor klinik walaupun tidak di office **bila** saya buka dashboard di phone **saya sepatutnya** nampak responsive layout dengan key metrics

12. **Sebagai Pengurus Klinik**, **saya mahu** melihat data quality score **supaya** saya tahu sama ada data boleh dipercayai **bila** saya view any report **saya sepatutnya** nampak data completeness indicator dan any data quality issues

### 3.2 Edge Cases

1. **Sebagai Pengurus Klinik**, **saya mahu** filter reports by date range, doctor, department **supaya** saya boleh analyse specific segments **bila** saya apply filters **saya sepatutnya** results update real-time

2. **Sebagai Pemilik Klinik**, **saya mahu** share dashboard link dengan board members **supaya** mereka boleh view tanpa login **bila** saya generate shareable link **saya sepatutnya** dapat link dengan expiry date dan view-only access

3. **Sebagai Pengurus Klinik**, **saya mahu** restore deleted atau modified report templates **supaya** saya tidak hilang kerja **bila** ada versioning **saya sepatutnya** boleh revert to previous version

4. **Sebagai Admin**, **saya mahu** melihat audit trail siapa yang access apa reports **supaya** saya boleh ensure data security **bila** saya check audit log **saya sepatutnya** nampak user, report, timestamp, action

5. **Sebagai Pengurus Klinik**, **saya mahu** compare doctor performance side-by-side **supaya** saya boleh identify best practices dan areas for improvement **bila** saya select multiple doctors **saya sepatutnya** nampak comparison table dan charts

---

## 4. Keperluan Fungsian

### 4.1 Dashboard Framework

**FR-1:** Sistem mesti support multi-level dashboards:

| Dashboard Type | Target User | Refresh Rate | Key Metrics |
|----------------|-------------|--------------|-------------|
| Executive Dashboard | Pemilik Klinik | Daily | Revenue, Patient Volume, Collection Rate, NPS |
| Operational Dashboard | Pengurus | Hourly/Real-time | Today's stats, Queue status, Staff attendance |
| EMR Dashboard | Clinical Lead | Daily | Consultations, Diagnosis patterns, Doctor performance |
| Farmasi Dashboard | Pharmacy Head | Hourly | Dispensing, Stock levels, Expiry, Top drugs |
| Billing Dashboard | Finance Lead | Hourly | Revenue, Outstanding, Payment methods, Panel claims |
| Queue Dashboard | Operations | Real-time | Wait time, Throughput, No-show rate |

**FR-2:** Dashboard components mesti support:
- Widget-based layout (drag to rearrange)
- Fullscreen mode per widget
- Refresh button per widget
- Date range selector (global)
- Export widget as image/PDF
- Drill-down on click

### 4.2 KPI Management

**FR-3:** Sistem mesti track KPIs across categories:

**Financial KPIs:**
| KPI | Formula | Target | Alert Threshold |
|-----|---------|--------|-----------------|
| Daily Revenue | Sum of paid invoices | RM5,000/day | < RM3,000 |
| Monthly Revenue | Sum of monthly revenue | RM150,000/month | < RM120,000 |
| Collection Rate | Collected / Billed × 100 | ≥ 90% | < 80% |
| AR Aging (>30 days) | Outstanding > 30 days | < 10% of total AR | > 15% |
| Average Bill Size | Total Revenue / Patient Count | RM80 | < RM60 |
| Panel Claim Success Rate | Approved / Submitted × 100 | ≥ 95% | < 90% |

**Clinical KPIs:**
| KPI | Formula | Target | Alert Threshold |
|-----|---------|--------|-----------------|
| Daily Patient Volume | Count of unique patients | 80-100/day | < 50 |
| Average Consultation Time | Total time / Consultations | 10-15 min | > 20 min |
| Doctor Utilization | Consultation time / Available time | 70-80% | < 60% |
| Return Visit Rate | Return visits / Total visits | 30-40% | < 20% |
| Prescription Rate | Visits with Rx / Total visits | 80-90% | Benchmark only |

**Operational KPIs:**
| KPI | Formula | Target | Alert Threshold |
|-----|---------|--------|-----------------|
| Average Wait Time | Total wait / Patients served | < 15 min | > 30 min |
| Queue Efficiency | Served / Generated × 100 | ≥ 95% | < 90% |
| No-Show Rate | No-shows / Appointments × 100 | < 10% | > 15% |
| Dispensing Time | Pharmacy queue wait | < 10 min | > 15 min |
| Staff Productivity | Patients served / Staff | Benchmark | Varies |

**Customer KPIs:**
| KPI | Formula | Target | Alert Threshold |
|-----|---------|--------|-----------------|
| Patient Satisfaction | Survey average | ≥ 4.0/5.0 | < 3.5 |
| Net Promoter Score (NPS) | Promoters - Detractors | ≥ 50 | < 30 |
| Patient Retention Rate | Returning patients / Total | ≥ 60% | < 50% |
| Complaint Rate | Complaints / Visits × 100 | < 1% | > 2% |

**Compliance KPIs:**
| KPI | Formula | Target | Alert Threshold |
|-----|---------|--------|-----------------|
| Panel SLA Compliance | Claims within SLA / Total | ≥ 90% | < 85% |
| Audit Completion | Completed audits / Required | 100% | < 100% |
| PDPA Consent Rate | With consent / Total patients | ≥ 99% | < 95% |
| Data Completeness | Complete records / Total | ≥ 98% | < 95% |

**FR-4:** KPI configuration mesti allow:
- Set target values per KPI
- Set alert thresholds (warning, critical)
- Define calculation formula
- Set comparison period (vs last month, vs last year)
- Enable/disable alerts

### 4.3 Data Warehouse

**FR-5:** Implement star schema data warehouse:

**Fact Tables:**
- `fact_visits` - Patient visits with measures (revenue, wait time, service time)
- `fact_sales` - Invoice line items with amounts
- `fact_payments` - Payment transactions
- `fact_dispensing` - Pharmacy dispensing records
- `fact_claims` - Panel claims with status
- `fact_queue` - Queue tickets with times

**Dimension Tables:**
- `dim_date` - Date hierarchy (day, week, month, quarter, year)
- `dim_time` - Time of day (hour, AM/PM, shift)
- `dim_patient` - Patient demographics (age group, gender, type)
- `dim_doctor` - Doctor attributes
- `dim_service` - Service categories
- `dim_payment_method` - Payment types
- `dim_panel` - Insurance panels
- `dim_drug` - Drug categories

**FR-6:** ETL (Extract, Transform, Load) jobs:
- Nightly batch ETL for historical data
- Hourly incremental updates for recent data
- Real-time sync for critical metrics
- Data transformation dan cleansing rules
- Error logging dan retry mechanism

**FR-7:** Separate reporting database:
- Read replica atau separate DB instance
- No impact to production database
- Optimized for analytical queries (indexes, aggregations)

### 4.4 Report Types

**FR-8:** Pre-defined report templates:

**Daily Reports:**
- Daily Summary Report (revenue, patients, highlights)
- Daily Cash Report (payments by method)
- Daily Queue Report (wait times, no-shows)

**Weekly Reports:**
- Weekly Performance Summary
- Weekly Doctor Performance
- Weekly Pharmacy Report (top drugs, stock alerts)

**Monthly Reports:**
- Monthly Executive Summary
- Monthly Financial Report (P&L summary)
- Monthly Clinical Report (patient demographics, diagnoses)
- Monthly Panel Claims Report
- Monthly Staff Performance

**Quarterly Reports:**
- Quarterly Business Review
- Quarterly Trend Analysis
- Quarterly Compliance Report

**Annual Reports:**
- Annual Performance Report
- Year-over-Year Comparison
- Annual Financial Summary

**FR-9:** Report features:
- Date range selection
- Multiple filters (doctor, department, panel, patient type)
- Grouping options (by day, week, month)
- Sorting options
- Subtotals and grand totals
- Charts embedded in reports

### 4.5 Data Visualization

**FR-10:** Sistem mesti support visualization types:

| Chart Type | Use Case | Example |
|------------|----------|---------|
| Line Chart | Trends over time | Revenue trend, Patient volume trend |
| Bar Chart | Category comparison | Revenue by doctor, Patients by day of week |
| Stacked Bar | Composition | Revenue breakdown by service type |
| Pie/Donut Chart | Distribution | Payment method distribution |
| Gauge Chart | KPI status | Collection rate gauge |
| Heat Map | Patterns | Busy hours heat map |
| Data Table | Detailed data | Transaction list, Patient list |
| Scorecard | Single metric | Today's Revenue, Total Patients |
| Sparkline | Inline trend | Mini trend in table |
| Funnel Chart | Process stages | Patient journey funnel |

**FR-11:** Interactive features:
- Hover tooltips
- Click to drill-down
- Zoom and pan for time series
- Legend toggle (show/hide series)
- Data point highlighting

### 4.6 Comparison & Benchmarking

**FR-12:** Period comparison options:
- This month vs Last month
- This month vs Same month last year (YoY)
- This quarter vs Last quarter
- This year vs Last year
- Custom date range comparison

**FR-13:** Target vs Actual:
- Display target line on charts
- Show variance (actual - target)
- Show variance percentage
- Color coding (green = above target, red = below)

**FR-14:** Entity comparison:
- Doctor-to-doctor comparison
- Department comparison
- Day-of-week comparison
- Shift comparison (AM vs PM)

### 4.7 Alert & Notification System

**FR-15:** Configurable alerts:
- Per-KPI threshold settings
- Warning level (yellow)
- Critical level (red)
- Alert channels: Email, SMS, Dashboard notification
- Alert frequency: Immediate, Daily digest, Weekly digest

**FR-16:** Alert types:
- Threshold breach (KPI < target)
- Anomaly detection (unusual spike or drop)
- Trend alert (3 consecutive periods declining)
- Missing data alert (no data for expected period)

**FR-17:** Escalation rules:
- Level 1: Email to Pengurus
- Level 2: SMS to Pengurus (if not acknowledged in 2 hours)
- Level 3: Email to Pemilik (if not resolved in 24 hours)

### 4.8 Custom Report Builder

**FR-18:** Drag-and-drop report builder:
- Select data source (visits, sales, payments, etc.)
- Drag metrics (measures) to report
- Drag dimensions (groupings) to report
- Apply filters
- Choose visualization type
- Preview report
- Save as template

**FR-19:** Report template management:
- Save custom reports
- Categorize reports (Financial, Clinical, Operational)
- Share reports with other users
- Schedule reports
- Version control (revert to previous version)

**FR-20:** Advanced options:
- Calculated fields (create formulas)
- Conditional formatting
- Pivot table functionality
- Cross-tabulation

### 4.9 Scheduled Reports

**FR-21:** Report scheduling:
- Frequency: Daily, Weekly, Monthly, Quarterly
- Day of week/month selection
- Time of day
- Recipients (email list)
- Format: PDF, Excel, or both
- Include/exclude sections

**FR-22:** Scheduled report management:
- View all scheduled reports
- Pause/resume schedules
- View execution history
- Retry failed deliveries

### 4.10 Export & Sharing

**FR-23:** Export formats:
- PDF (formatted for print)
- Excel (data with charts)
- CSV (raw data)
- Image (PNG/JPEG for charts)

**FR-24:** Sharing options:
- Email report as attachment
- Generate shareable link (view-only)
- Link expiry date (1 day, 7 days, 30 days)
- Password protection (optional)
- Track link access

### 4.11 Data Quality & Governance

**FR-25:** Data quality dashboard:
- Overall data quality score (0-100)
- Completeness metrics per table
- Accuracy metrics (validation rule compliance)
- Timeliness (data freshness)
- Consistency (cross-reference checks)

**FR-26:** Data validation rules:
- Required fields check
- Format validation (IC, phone, email)
- Range validation (age > 0, price > 0)
- Referential integrity (FK exists)
- Business rule validation (invoice total = sum of items)

**FR-27:** Anomaly detection:
- Statistical outlier detection
- Unusual patterns (spike in refunds, drop in patients)
- Alert for anomalies requiring investigation

**FR-28:** Data lineage:
- Track data source for each metric
- Show transformation steps
- Audit trail for data changes
- Impact analysis (what reports affected by data issue)

**FR-29:** Data dictionary:
- Definition for each metric
- Calculation formula
- Data source
- Update frequency
- Owner (who maintains)

### 4.12 Historical Data Retention

**FR-30:** Tiered retention policy:

| Data Type | Detail Retention | Aggregated Retention |
|-----------|------------------|----------------------|
| Transactional (invoices, payments) | 3 years | 7 years (monthly) |
| Patient visits | 3 years | 7 years (monthly) |
| Queue data | 1 year | 5 years (monthly) |
| Audit logs | 7 years | Permanent |
| KPI snapshots | 3 years | Permanent (monthly) |

**FR-31:** Archive process:
- Automated archival job
- Archive to cold storage (cheaper)
- Restore on-demand (for audits)
- Deletion after retention period (configurable)

### 4.13 Predictive Analytics

**FR-32:** Basic forecasting models:

| Forecast Type | Method | Horizon | Use Case |
|---------------|--------|---------|----------|
| Revenue Forecast | Moving average + Seasonality | 1-3 months | Cash flow planning |
| Patient Volume | Time series | 1 month | Resource planning |
| Stock Depletion | Linear regression | 2-4 weeks | Reorder planning |
| Seasonal Trends | Pattern recognition | 1 year | Capacity planning |

**FR-33:** Forecast visualization:
- Actual vs Forecast line chart
- Confidence interval bands
- Accuracy metrics (MAPE, RMSE)
- Scenario comparison (optimistic, pessimistic)

### 4.14 Access Control & Security

**FR-34:** Role-based access:

| Role | Dashboard Access | Report Access | Admin Access |
|------|------------------|---------------|--------------|
| Pemilik Klinik | All dashboards | All reports | Full config |
| Pengurus Klinik | All dashboards | All reports | Limited config |
| Department Head | Own department | Own department | No config |
| Staff | None | None | None |

**FR-35:** Data-level security:
- Doctor can only see own performance
- Department head sees only department data
- Financial data restricted to Finance roles
- Patient-level data restricted (PHI)

**FR-36:** Audit trail:
- Log all report access
- Log all exports
- Log all configuration changes
- Log all data queries

### 4.15 Mobile Access

**FR-37:** Mobile-responsive dashboard:
- Simplified layout for phone
- Key metrics cards
- Touch-friendly charts
- Pull-to-refresh
- Swipe between dashboards

**FR-38:** Mobile-specific features:
- Push notifications for alerts
- Offline view of last cached data
- Quick filters
- Share via mobile apps

### 4.16 API & Integration

**FR-39:** REST API for data access:
- Authentication (API key or OAuth)
- Endpoints for metrics, reports, raw data
- Pagination untuk large datasets
- Rate limiting
- API documentation (OpenAPI/Swagger)

**FR-40:** Export for external BI tools:
- ODBC/JDBC connection (for Power BI, Tableau)
- Scheduled data export (CSV/JSON)
- Real-time webhook untuk updates

---

## 5. Keperluan Teknikal

### 5.1 Arkitektur Sistem

**Framework:** Laravel 12
**Frontend:** Blade Templates + Bootstrap 5 + CoreUI
**Charting Library:** Chart.js atau ApexCharts
**Dashboard Framework:** Custom atau Laravel-based BI package
**Database:** MySQL 8.0 (reporting replica)
**Data Warehouse:** Star schema dalam MySQL atau dedicated analytics DB
**ETL:** Laravel Jobs + Scheduler
**Caching:** Redis untuk dashboard caching
**Queue:** Laravel Queue untuk report generation

### 5.2 Struktur Database

Sistem ini memerlukan:

**Data Warehouse Tables (12 tables):**
- Fact tables: 6 (visits, sales, payments, dispensing, claims, queue)
- Dimension tables: 6 (date, time, patient, doctor, service, panel)

**Reporting System Tables (10 tables):**
1. `kpi_definitions` - KPI master
2. `kpi_targets` - Target values per period
3. `kpi_snapshots` - Historical KPI values
4. `report_templates` - Saved report templates
5. `report_schedules` - Scheduled reports
6. `report_executions` - Execution history
7. `dashboard_widgets` - Widget configurations
8. `dashboard_layouts` - User dashboard layouts
9. `alerts` - Alert definitions
10. `alert_notifications` - Notification log

**Jadual: `kpi_definitions`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `code` | varchar(50) UNIQUE NOT NULL | KPI code (REVENUE_DAILY) |
| `name` | varchar(255) NOT NULL | KPI name |
| `category` | enum NOT NULL | financial/clinical/operational/customer/compliance |
| `description` | text NULL | Description |
| `formula` | text NULL | Calculation formula |
| `unit` | varchar(50) NULL | Unit (RM, %, count, minutes) |
| `data_source` | varchar(255) NOT NULL | Source table/view |
| `aggregation` | enum NOT NULL | sum/avg/count/min/max |
| `frequency` | enum NOT NULL | realtime/hourly/daily/monthly |
| `is_active` | boolean DEFAULT true | Active status |
| `display_order` | int DEFAULT 0 | Display order |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `kpi_targets`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kpi_id` | bigint UNSIGNED NOT NULL | FK → kpi_definitions.id |
| `period_type` | enum NOT NULL | daily/monthly/quarterly/yearly |
| `period_start` | date NOT NULL | Period start date |
| `period_end` | date NOT NULL | Period end date |
| `target_value` | decimal(15,2) NOT NULL | Target value |
| `warning_threshold` | decimal(15,2) NULL | Warning level |
| `critical_threshold` | decimal(15,2) NULL | Critical level |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `kpi_snapshots`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kpi_id` | bigint UNSIGNED NOT NULL | FK → kpi_definitions.id |
| `snapshot_date` | date NOT NULL | Snapshot date |
| `period_type` | enum NOT NULL | daily/monthly/quarterly/yearly |
| `actual_value` | decimal(15,2) NULL | Actual value |
| `target_value` | decimal(15,2) NULL | Target for comparison |
| `variance` | decimal(15,2) NULL | Actual - Target |
| `variance_pct` | decimal(5,2) NULL | Variance percentage |
| `status` | enum NULL | on_track/warning/critical |
| `created_at` | timestamp | Created timestamp |

**Index:** UNIQUE(kpi_id, snapshot_date, period_type)

**Jadual: `report_templates`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `code` | varchar(50) UNIQUE NOT NULL | Template code |
| `name` | varchar(255) NOT NULL | Report name |
| `category` | varchar(100) NOT NULL | Category (Financial, Clinical, etc.) |
| `description` | text NULL | Description |
| `type` | enum NOT NULL | predefined/custom |
| `config` | json NOT NULL | Report configuration (metrics, filters, charts) |
| `is_public` | boolean DEFAULT false | Shared with all users |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |
| `deleted_at` | timestamp NULL | Soft delete |

**Jadual: `report_schedules`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `template_id` | bigint UNSIGNED NOT NULL | FK → report_templates.id |
| `name` | varchar(255) NOT NULL | Schedule name |
| `frequency` | enum NOT NULL | daily/weekly/monthly/quarterly |
| `day_of_week` | tinyint NULL | 1-7 (for weekly) |
| `day_of_month` | tinyint NULL | 1-31 (for monthly) |
| `time_of_day` | time NOT NULL | Execution time |
| `recipients` | json NOT NULL | Email list |
| `format` | json NOT NULL | [pdf, excel] |
| `filters` | json NULL | Applied filters |
| `is_active` | boolean DEFAULT true | Active status |
| `last_run_at` | timestamp NULL | Last execution |
| `next_run_at` | timestamp NULL | Next scheduled execution |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `alerts`**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `kpi_id` | bigint UNSIGNED NOT NULL | FK → kpi_definitions.id |
| `alert_type` | enum NOT NULL | threshold/anomaly/trend |
| `condition` | varchar(50) NOT NULL | lt/lte/gt/gte (less than, greater than) |
| `threshold_value` | decimal(15,2) NULL | Threshold value |
| `severity` | enum NOT NULL | warning/critical |
| `channels` | json NOT NULL | [email, sms, dashboard] |
| `recipients` | json NOT NULL | User IDs or emails |
| `message_template` | text NULL | Custom message |
| `is_active` | boolean DEFAULT true | Active status |
| `cooldown_minutes` | int DEFAULT 60 | Minimum time between alerts |
| `created_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Jadual: `fact_visits` (Data Warehouse)**

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `visit_date_key` | int NOT NULL | FK → dim_date.date_key |
| `visit_time_key` | int NOT NULL | FK → dim_time.time_key |
| `patient_key` | bigint NOT NULL | FK → dim_patient.patient_key |
| `doctor_key` | bigint NOT NULL | FK → dim_doctor.doctor_key |
| `service_key` | int NOT NULL | FK → dim_service.service_key |
| `emr_id` | bigint NOT NULL | Source EMR ID |
| `visit_type` | varchar(50) NOT NULL | walk_in/appointment |
| `wait_time_minutes` | int NULL | Wait time |
| `service_time_minutes` | int NULL | Consultation time |
| `total_bill_amount` | decimal(10,2) NULL | Total billed |
| `paid_amount` | decimal(10,2) NULL | Amount paid |
| `diagnosis_count` | int DEFAULT 1 | Number of diagnoses |
| `prescription_count` | int DEFAULT 0 | Number of prescriptions |
| `created_at` | timestamp | Created timestamp |

**Jadual: `dim_date` (Data Warehouse)**

| Column | Type | Description |
|--------|------|-------------|
| `date_key` | int PK | YYYYMMDD format |
| `full_date` | date NOT NULL | Full date |
| `day_of_week` | tinyint NOT NULL | 1-7 |
| `day_name` | varchar(20) NOT NULL | Monday, Tuesday... |
| `day_of_month` | tinyint NOT NULL | 1-31 |
| `week_of_year` | tinyint NOT NULL | 1-52 |
| `month` | tinyint NOT NULL | 1-12 |
| `month_name` | varchar(20) NOT NULL | January, February... |
| `quarter` | tinyint NOT NULL | 1-4 |
| `year` | smallint NOT NULL | 2026 |
| `is_weekend` | boolean NOT NULL | Weekend flag |
| `is_holiday` | boolean DEFAULT false | Holiday flag |

### 5.3 Models (Eloquent)

Models yang perlu dicipta:
- KpiDefinition, KpiTarget, KpiSnapshot
- ReportTemplate, ReportSchedule, ReportExecution
- DashboardWidget, DashboardLayout
- Alert, AlertNotification
- FactVisit, FactSales, FactPayment, FactDispensing, FactClaim, FactQueue
- DimDate, DimTime, DimPatient, DimDoctor, DimService, DimPanel

### 5.4 Services

Services:
- DashboardService - Dashboard rendering, widget data
- KpiService - KPI calculation, snapshot, comparison
- ReportService - Report generation, export
- ETLService - Data warehouse sync
- AlertService - Alert monitoring, notification
- ForecastService - Predictive analytics
- DataQualityService - Data validation, scoring

### 5.5 Jobs (Laravel Queue)

- `ETLDailyJob` - Nightly data warehouse refresh
- `ETLHourlyJob` - Hourly incremental update
- `KpiSnapshotJob` - Daily KPI snapshot
- `ReportScheduleJob` - Execute scheduled reports
- `AlertCheckJob` - Check alerts every 15 minutes
- `ForecastUpdateJob` - Weekly forecast update

---

## 6. Workflow

### 6.1 ETL Workflow

```
Operational Database (MySQL Production)
    ↓
ETL Job (Laravel Scheduled Job - Nightly)
    ↓
Extract data from:
    - invoices, invoice_items
    - payments
    - emr records
    - dispensing records
    - queue_tickets
    - panel_claims
    ↓
Transform:
    - Clean data (remove nulls, fix formats)
    - Aggregate (daily totals, averages)
    - Derive metrics (wait time = called_at - issued_at)
    - Link to dimensions (patient_key, doctor_key)
    ↓
Load into Data Warehouse:
    - fact_visits
    - fact_sales
    - fact_payments
    - fact_dispensing
    - fact_claims
    - fact_queue
    ↓
Update dimension tables (new patients, new doctors)
    ↓
Calculate KPI snapshots
    ↓
Check alerts
    ↓
Log ETL completion
```

### 6.2 Dashboard Viewing Workflow

```
User login
    ↓
System check user role
    ↓
Load appropriate dashboard layout
    ↓
For each widget:
    - Check cache (Redis)
    - If cache valid → serve from cache
    - If cache expired → query data warehouse
    - Render visualization
    - Store in cache
    ↓
Display dashboard
    ↓
User interactions:
    - Click widget → drill-down
    - Change date range → refresh all widgets
    - Click refresh → clear cache, reload
```

### 6.3 Report Generation Workflow

```
User select report template
    ↓
User set parameters:
    - Date range
    - Filters (doctor, department, etc.)
    - Output format (view/PDF/Excel)
    ↓
System validate parameters
    ↓
System query data warehouse
    ↓
System apply calculations
    ↓
System render report:
    - If view → display on screen
    - If PDF → generate PDF, download/email
    - If Excel → generate Excel, download/email
    ↓
Log report execution
```

### 6.4 Alert Workflow

```
Scheduled job runs every 15 minutes
    ↓
For each active alert:
    - Calculate current KPI value
    - Compare with threshold
    ↓
If threshold breached:
    - Check cooldown (last alert time)
    - If cooldown passed:
        - Create alert_notification record
        - Send email (if channel enabled)
        - Send SMS (if channel enabled)
        - Display in dashboard (if channel enabled)
        - Update last_alert_time
    ↓
If alert resolved (back to normal):
    - Send resolution notification (optional)
```

---

## 7. Keperluan UI/UX

### 7.1 Key Interfaces

**1. Executive Dashboard**
- Hero metrics (4 large cards): Revenue, Patients, Collection Rate, Satisfaction
- Trend charts (revenue, patient volume)
- KPI scorecard (traffic light indicators)
- Comparison widgets (vs last period)
- Alert summary

**2. Operational Dashboard**
- Real-time metrics (today's stats)
- Queue status widget
- Active staff widget
- Hourly breakdown chart
- Recent transactions list

**3. Department Dashboards**
- Department-specific KPIs
- Performance charts
- Staff performance table
- Drill-down to details

**4. Report Viewer**
- Report header (title, date range, filters)
- Data visualization area
- Data table
- Export buttons (PDF, Excel, CSV)
- Share button

**5. Custom Report Builder**
- Data source selector
- Available fields panel (drag source)
- Report canvas (drop target)
- Filter panel
- Visualization options
- Preview button
- Save/Schedule buttons

**6. Alert Management**
- Active alerts list
- Create/Edit alert form
- Alert history
- Notification preferences

**7. Admin Settings**
- KPI configuration
- Target management
- Dashboard layout management
- User access management
- ETL monitoring

### 7.2 Design System
- Framework: Bootstrap 5 + CoreUI
- Charts: Chart.js atau ApexCharts
- Icons: CoreUI Icons / Font Awesome
- Color Scheme: Professional BI colors (blues, greens for positive, reds for negative)
- Responsive: Yes (mobile-first)

### 7.3 Chart Color Palette

| Purpose | Color |
|---------|-------|
| Primary (current period) | #3B82F6 (blue) |
| Comparison (previous period) | #94A3B8 (gray) |
| Positive/On track | #10B981 (green) |
| Warning | #F59E0B (amber) |
| Critical/Negative | #EF4444 (red) |
| Neutral | #6B7280 (gray) |

---

## 8. Keperluan Keselamatan

### 8.1 Data Protection
- Data warehouse contains aggregated data (minimize PII)
- Patient identifiable data masked in reports (unless authorized)
- Encryption for sensitive exports
- Secure shareable links (token-based, expiry)

### 8.2 Access Control
- Role-based dashboard access
- Data-level security (doctor sees own data only)
- Report template sharing controls
- API authentication (API key or OAuth)

### 8.3 Audit Trail
- Log all report access
- Log all exports
- Log all configuration changes
- Log all API calls
- Retain logs for 7 years

---

## 9. Keperluan Prestasi

### 9.1 Response Time
- Dashboard load: < 3 saat
- Widget refresh: < 2 saat
- Report generation (small): < 5 saat
- Report generation (large): < 30 saat
- Export (PDF/Excel): < 10 saat

### 9.2 Scalability
- Support 3 years of transactional data
- Support 10+ concurrent dashboard users
- Efficient aggregation queries
- Redis caching untuk hot data
- Pagination untuk large datasets

### 9.3 ETL Performance
- Nightly ETL: < 30 minit
- Hourly incremental: < 5 minit
- No impact to production database

---

## 10. Keperluan Ujian

### 10.1 Unit Testing
- KpiService::calculate() accuracy
- ForecastService::predict() accuracy
- DataQualityService::validate() rules
- Report calculations

### 10.2 Feature Testing
- Dashboard rendering
- Report generation all formats
- Alert triggering
- Scheduled report execution
- Export functionality

### 10.3 Integration Testing
- ETL job accuracy (source vs warehouse)
- Email delivery for alerts and reports
- API endpoints

### 10.4 Performance Testing
- Dashboard load under concurrent users
- Large report generation
- ETL job duration

### 10.5 UAT Scenarios
- Pemilik review executive dashboard
- Pengurus investigate KPI issue (drill-down)
- Generate monthly report and export
- Create custom report and schedule

---

## 11. Langkah Implementasi

### Fasa 1: Data Warehouse Setup (2 minggu)
- [ ] Design star schema
- [ ] Create dimension tables
- [ ] Create fact tables
- [ ] Create ETL jobs (basic)
- [ ] Test data loading

### Fasa 2: KPI Framework (1.5 minggu)
- [ ] Create KPI definitions table
- [ ] Create KPI targets table
- [ ] Implement KPI calculation service
- [ ] Create KPI snapshot job
- [ ] Build KPI management UI

### Fasa 3: Dashboard Framework (2 minggu)
- [ ] Create dashboard layout system
- [ ] Implement widget framework
- [ ] Build executive dashboard
- [ ] Build operational dashboard
- [ ] Implement caching

### Fasa 4: Department Dashboards (1.5 minggu)
- [ ] Build EMR dashboard
- [ ] Build Farmasi dashboard
- [ ] Build Billing dashboard
- [ ] Build Queue dashboard
- [ ] Role-based access

### Fasa 5: Pre-defined Reports (1.5 minggu)
- [ ] Create report template system
- [ ] Build daily reports
- [ ] Build weekly reports
- [ ] Build monthly reports
- [ ] Export to PDF/Excel

### Fasa 6: Custom Report Builder (2 minggu)
- [ ] Build drag-and-drop interface
- [ ] Implement field selection
- [ ] Implement filters
- [ ] Implement visualizations
- [ ] Save/load templates

### Fasa 7: Alert System (1 minggu)
- [ ] Create alert definitions
- [ ] Implement threshold checking
- [ ] Build notification system (email, SMS)
- [ ] Alert dashboard
- [ ] Escalation rules

### Fasa 8: Scheduled Reports (1 minggu)
- [ ] Create scheduling system
- [ ] Implement execution job
- [ ] Email delivery
- [ ] Execution history
- [ ] Management UI

### Fasa 9: Comparison & Benchmarking (1 minggu)
- [ ] Period comparison logic
- [ ] Target vs Actual visualization
- [ ] Entity comparison
- [ ] Benchmark indicators

### Fasa 10: Predictive Analytics (1 minggu)
- [ ] Revenue forecast model
- [ ] Patient volume forecast
- [ ] Stock depletion forecast
- [ ] Forecast visualization

### Fasa 11: Data Quality & Governance (1 minggu)
- [ ] Data quality scoring
- [ ] Validation rules
- [ ] Anomaly detection
- [ ] Data dictionary
- [ ] Data lineage

### Fasa 12: Mobile & API (1 minggu)
- [ ] Mobile-responsive dashboards
- [ ] Push notifications
- [ ] REST API endpoints
- [ ] API documentation

### Fasa 13: Testing & UAT (1.5 minggu)
- [ ] Unit tests
- [ ] Feature tests
- [ ] Performance tests
- [ ] UAT dengan management
- [ ] Bug fixes

### Fasa 14: Deployment (0.5 minggu)
- [ ] Deploy to production
- [ ] Configure ETL schedules
- [ ] Training untuk users
- [ ] Monitor performance

**Anggaran Masa:** 18 minggu (4-4.5 bulan)

---

## 12. Kriteria Kejayaan

### 12.1 Metrics
1. Report generation time: 80% reduction dari manual process
2. Data freshness: Dashboard data < 1 hour old
3. KPI visibility: 100% of defined KPIs tracked
4. User adoption: 80% of management using dashboard weekly
5. Alert effectiveness: 90% of alerts actioned within SLA

---

## 13. Risks & Mitigation

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| ETL impact production DB | HIGH | MEDIUM | Use read replica; run during off-hours; optimize queries |
| Data quality issues | HIGH | HIGH | Data validation rules; data quality dashboard; cleansing jobs |
| Complex report builder | MEDIUM | MEDIUM | Start with simple version; iterate based on feedback |
| Forecast accuracy low | MEDIUM | MEDIUM | Clear accuracy indicators; disclaimer on forecasts; manual override |
| Performance issues with large data | HIGH | MEDIUM | Proper indexing; aggregation tables; caching; pagination |

---

## 14. Acceptance Criteria

### 14.1 Functional
- ✅ Multi-level dashboards display correctly
- ✅ All defined KPIs calculated accurately
- ✅ ETL jobs run successfully and on schedule
- ✅ Reports generate in all formats (PDF, Excel, CSV)
- ✅ Custom report builder works
- ✅ Alerts trigger and notify correctly
- ✅ Scheduled reports delivered on time
- ✅ Comparison features work accurately
- ✅ Basic forecasts available
- ✅ Data quality score visible

### 14.2 Non-Functional
- Performance: Dashboard < 3 saat
- Security: Role-based access enforced
- Mobile: Responsive dashboards work on phone
- Availability: 99% uptime

---

## 15. Lampiran

### 15.1 Sample KPI Scorecard

```
╔══════════════════════════════════════════════════════════════╗
║                    KPI SCORECARD - January 2026              ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║  FINANCIAL                                                   ║
║  ┌────────────────┬──────────┬──────────┬────────┬────────┐  ║
║  │ KPI            │ Target   │ Actual   │ Status │ Trend  │  ║
║  ├────────────────┼──────────┼──────────┼────────┼────────┤  ║
║  │ Revenue        │ RM150K   │ RM142K   │ 🟡     │ ↗️     │  ║
║  │ Collection Rate│ 90%      │ 88%      │ 🟡     │ →      │  ║
║  │ Avg Bill Size  │ RM80     │ RM85     │ 🟢     │ ↗️     │  ║
║  │ AR Aging >30d  │ <10%     │ 8%       │ 🟢     │ ↘️     │  ║
║  └────────────────┴──────────┴──────────┴────────┴────────┘  ║
║                                                              ║
║  CLINICAL                                                    ║
║  ┌────────────────┬──────────┬──────────┬────────┬────────┐  ║
║  │ Patient Volume │ 2000     │ 1850     │ 🟡     │ ↗️     │  ║
║  │ Avg Wait Time  │ <15 min  │ 12 min   │ 🟢     │ ↘️     │  ║
║  │ Doctor Util    │ 75%      │ 72%      │ 🟡     │ →      │  ║
║  │ No-Show Rate   │ <10%     │ 8%       │ 🟢     │ ↘️     │  ║
║  └────────────────┴──────────┴──────────┴────────┴────────┘  ║
║                                                              ║
║  🟢 On Track   🟡 Warning   🔴 Critical                       ║
║  ↗️ Improving  → Stable    ↘️ Declining                       ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
```

### 15.2 Sample Executive Dashboard Layout

```
┌─────────────────────────────────────────────────────────────────┐
│  POLIKLINIK AL-HUDA - Executive Dashboard       📅 Jan 2026    │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌────────────┐ │
│  │   REVENUE   │ │  PATIENTS   │ │ COLLECTION  │ │    NPS     │ │
│  │             │ │             │ │    RATE     │ │            │ │
│  │  RM142,500  │ │    1,850    │ │    88%      │ │     52     │ │
│  │   ↗️ +5%    │ │   ↗️ +8%    │ │   → 0%      │ │   ↗️ +4    │ │
│  │  vs last mo │ │  vs last mo │ │  vs last mo │ │ vs last mo │ │
│  └─────────────┘ └─────────────┘ └─────────────┘ └────────────┘ │
│                                                                 │
│  ┌──────────────────────────────┐ ┌────────────────────────────┐│
│  │    REVENUE TREND (6 months)  │ │   PATIENT VOLUME TREND     ││
│  │                              │ │                            ││
│  │    📈 [Line Chart]           │ │   📈 [Line Chart]          ││
│  │                              │ │                            ││
│  │    — Actual  --- Target      │ │   — This Year  --- Last Yr ││
│  └──────────────────────────────┘ └────────────────────────────┘│
│                                                                 │
│  ┌──────────────────────────────┐ ┌────────────────────────────┐│
│  │    REVENUE BY DEPARTMENT     │ │     ALERTS (3)             ││
│  │                              │ │                            ││
│  │    🥧 [Pie Chart]            │ │  ⚠️ Collection rate below  ││
│  │                              │ │     target                 ││
│  │    Consultation: 45%        │ │  ⚠️ 5 claims overdue SLA   ││
│  │    Pharmacy: 40%            │ │  🔴 Queue wait time > 30min ││
│  │    Procedure: 15%           │ │                            ││
│  └──────────────────────────────┘ └────────────────────────────┘│
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 15.3 Data Warehouse Star Schema

```
                         ┌─────────────┐
                         │  dim_date   │
                         │─────────────│
                         │ date_key PK │
                         │ full_date   │
                         │ day_name    │
                         │ month       │
                         │ quarter     │
                         │ year        │
                         └──────┬──────┘
                                │
┌─────────────┐                 │                 ┌─────────────┐
│ dim_patient │                 │                 │ dim_doctor  │
│─────────────│          ┌──────┴──────┐          │─────────────│
│patient_key  │──────────│ fact_visits │──────────│doctor_key   │
│ age_group   │          │─────────────│          │ name        │
│ gender      │          │ visit_id PK │          │ specialty   │
│ type        │          │ date_key FK │          │ department  │
└─────────────┘          │ patient_key │          └─────────────┘
                         │ doctor_key  │
                         │ service_key │
┌─────────────┐          │ wait_time   │          ┌─────────────┐
│ dim_service │          │ service_time│          │  dim_time   │
│─────────────│──────────│ bill_amount │──────────│─────────────│
│service_key  │          │ paid_amount │          │ time_key    │
│ name        │          └─────────────┘          │ hour        │
│ category    │                                   │ shift       │
│ price       │                                   │ am_pm       │
└─────────────┘                                   └─────────────┘
```

---

**END OF PRD**

---

## Appendix: Change Log

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-13 | System | Initial PRD creation |