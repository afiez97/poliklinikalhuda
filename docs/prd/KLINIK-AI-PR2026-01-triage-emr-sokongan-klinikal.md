# PRD: Modul AI – Triage & EMR (Sokongan Klinikal Pintar)

**Kod PRD:** KLINIK-AI-PR2026-01-triage-emr-sokongan-klinikal
**Dicipta:** 14 Januari 2026
**Penulis:** AI Assistant
**Dikemaskini:** 14 Januari 2026

---

## 1. Ringkasan Eksekutif

### 1.1 Gambaran Keseluruhan

Modul AI – Triage & EMR adalah sistem sokongan keputusan klinikal pintar yang menggunakan pendekatan Rule-based + Machine Learning hybrid untuk membantu doktor dan jururawat dalam proses triage pesakit, menjana ringkasan EMR automatik, dan memberikan cadangan klinikal berasaskan bukti. Sistem ini mengutamakan Explainable AI (XAI) dan Human-in-the-Loop untuk memastikan keselamatan pesakit dan kepercayaan pengguna.

### 1.2 Metadata

- **Nama Feature**: Sistem Sokongan Keputusan Klinikal Pintar (AI-CDSS)
- **Modul**: AI – Triage & EMR
- **Submodule**: Sokongan Klinikal Pintar
- **Peranan Sasaran**: Doktor, Jururawat
- **Keutamaan**: Tinggi
- **Status**: Perancangan
- **Anggaran Usaha**: Besar (20 minggu)

### 1.3 Objektif

- Mempercepatkan proses triage dengan AI-assisted symptom analysis dan severity scoring
- Menyediakan ringkasan EMR yang komprehensif untuk memudahkan keputusan klinikal
- Memberikan cadangan differential diagnosis dan rawatan berasaskan clinical guidelines
- Mengesan red flags dan drug interactions secara real-time untuk keselamatan pesakit
- Melaksanakan Explainable AI supaya setiap cadangan boleh difahami dan diaudit
- Memastikan Human-in-the-Loop dengan mandatory review untuk semua cadangan AI
- Mematuhi PDPA dan prinsip etika AI dalam pemprosesan data kesihatan

### 1.4 Skop

**Dalam Skop:**
- AI Triage dengan 5-level severity scoring (Manchester Triage System)
- Symptom checker dengan red flag detection
- Ringkasan EMR automatik (problem list, medications, allergies, chronic conditions)
- Cadangan differential diagnosis dengan confidence scores
- Drug interaction checking (drug-drug, drug-allergy, drug-disease)
- Explainable AI dengan reasoning chain dan clinical references
- Human-in-the-Loop mandatory review workflow
- Feedback loop untuk continuous improvement
- On-premise AI processing untuk PDPA compliance
- AI governance dan ethics monitoring

**Luar Skop:**
- Diagnosis definitif oleh AI (AI hanya memberi cadangan)
- Preskripsi automatik tanpa pengesahan doktor
- Imaging analysis (X-ray, CT, MRI AI interpretation)
- Predictive analytics untuk population health
- Natural Language Processing untuk voice input
- Telemedicine AI consultation
- Integration dengan external health information exchange

---

## 2. Pernyataan Masalah

### 2.1 Masalah Semasa

Proses klinikal di klinik menghadapi beberapa cabaran:
- Triage manual memakan masa dan bergantung sepenuhnya kepada pengalaman individu
- Tiada sistem untuk mengesan red flags secara konsisten
- Doktor perlu scroll melalui rekod panjang EMR untuk mendapatkan gambaran keseluruhan
- Drug interactions kadang-kadang terlepas pandang terutama untuk pesakit dengan polypharmacy
- Tiada akses cepat kepada clinical guidelines semasa consultation
- Risiko salah tafsir simptom terutama untuk kes yang jarang ditemui
- Kepercayaan pengguna terhadap sistem AI masih rendah kerana kurang transparency

### 2.2 Impak Kepada Perniagaan

- **Keselamatan Pesakit**: Risiko missed diagnosis atau delayed treatment untuk kes kritikal
- **Kecekapan**: Masa consultation yang panjang kerana perlu review rekod secara manual
- **Kualiti**: Variasi dalam kualiti triage bergantung kepada siapa yang bertugas
- **Compliance**: Risiko drug interactions yang tidak dikesan
- **Kepuasan**: Pesakit menunggu lama kerana proses triage yang lambat

### 2.3 Hasil Yang Diingini

- Triage yang konsisten dan cepat dengan AI-assisted severity scoring
- Red flags dikesan secara real-time dengan alert yang jelas
- Ringkasan EMR tersedia dalam satu pandangan untuk setiap pesakit
- Drug interactions dikesan sebelum preskripsi ditulis
- Cadangan AI yang boleh dijelaskan dan diaudit
- Doktor dan jururawat mempercayai sistem kerana transparency dan control
- Zero critical missed diagnoses dengan AI backup

---

## 3. User Stories

### 3.1 User Stories Utama

- **Sebagai** Jururawat triage, **saya mahu** memasukkan simptom pesakit dan mendapat skor severity automatik **supaya** saya boleh prioritize pesakit dengan cepat dan konsisten

- **Sebagai** Doktor, **saya mahu** melihat ringkasan EMR pesakit dalam satu pandangan **supaya** saya tidak perlu scroll melalui rekod panjang dan boleh fokus kepada konsultasi

- **Sebagai** Doktor, **saya mahu** mendapat cadangan differential diagnosis berdasarkan simptom **supaya** saya tidak terlepas pandang kemungkinan diagnosis yang jarang

- **Sebagai** Doktor, **saya mahu** melihat penjelasan mengapa AI mencadangkan sesuatu diagnosis **supaya** saya boleh menilai kesesuaian cadangan tersebut

- **Sebagai** Doktor, **saya mahu** sistem alert saya tentang drug interactions sebelum saya tulis preskripsi **supaya** keselamatan pesakit terjamin

- **Sebagai** Jururawat, **saya mahu** sistem alert saya apabila pesakit mempunyai red flags **supaya** saya boleh escalate kepada doktor dengan segera

- **Sebagai** Doktor, **saya mahu** mengesahkan atau menolak cadangan AI dengan satu klik **supaya** workflow saya tidak terganggu

- **Sebagai** Doktor, **saya mahu** memberi feedback kepada sistem apabila cadangan AI tidak tepat **supaya** sistem boleh improve dari masa ke masa

- **Sebagai** Pengurus Klinik, **saya mahu** melihat laporan prestasi AI **supaya** saya boleh memastikan sistem berfungsi dengan baik dan selamat

- **Sebagai** Doktor, **saya mahu** akses kepada clinical guidelines yang relevan untuk kes semasa **supaya** keputusan saya berasaskan bukti terkini

### 3.2 Edge Cases & User Stories Sekunder

- **Sebagai** Doktor, **bila** AI mempunyai confidence rendah (<70%), **saya sepatutnya** melihat warning yang jelas dan cadangan untuk refer kepada pakar

- **Sebagai** Jururawat, **bila** sistem mengesan simptom life-threatening, **saya sepatutnya** melihat alert merah yang tidak boleh diabaikan dan langkah tindakan segera

- **Sebagai** Doktor, **bila** AI tidak dapat memberikan cadangan kerana data tidak mencukupi, **saya sepatutnya** melihat senarai maklumat tambahan yang diperlukan

- **Sebagai** Doktor, **bila** terdapat contraindication antara ubat dengan penyakit kronik pesakit, **saya sepatutnya** melihat alert dengan penjelasan dan alternatif

- **Sebagai** Jururawat, **bila** pesakit mempunyai allergy yang direkodkan, **saya sepatutnya** melihat alert jika ubat yang dicadangkan berkaitan dengan allergen tersebut

- **Sebagai** Pengurus, **bila** berlaku insiden berkaitan cadangan AI, **saya sepatutnya** dapat menjejaki audit trail lengkap untuk siasatan

- **Sebagai** Doktor, **bila** saya tidak bersetuju dengan cadangan AI, **saya sepatutnya** dapat override dengan justifikasi dan sistem merekodkan keputusan saya

- **Sebagai** Admin, **bila** model AI dikemaskini, **saya sepatutnya** melihat versioning dan changelog untuk transparency

---

## 4. Keperluan Fungsian

### 4.1 AI Triage System (FR-100 Series)

- [x] **FR-101:** Sistem mesti menyediakan symptom checker dengan input berstruktur (body region, symptom type, duration, severity)
- [x] **FR-102:** Sistem mesti mengira severity score menggunakan 5-level Manchester Triage System (Emergency, Urgent, Semi-urgent, Standard, Non-urgent)
- [x] **FR-103:** Sistem mesti mengesan red flags (life-threatening symptoms) dan memaparkan alert merah yang prominent
- [x] **FR-104:** Sistem mesti mencadangkan urgency classification dengan reasoning yang boleh dijelaskan
- [x] **FR-105:** Sistem mesti memaparkan recommended actions berdasarkan triage level (contoh: "Lihat doktor segera", "Boleh tunggu 30 minit")
- [x] **FR-106:** Sistem mesti auto-populate triage form berdasarkan chief complaint yang dimasukkan
- [x] **FR-107:** Sistem mesti merekodkan semua input triage dan keputusan untuk audit
- [x] **FR-108:** Sistem mesti membenarkan override triage level dengan justifikasi

### 4.2 EMR Summary Generation (FR-200 Series)

- [x] **FR-201:** Sistem mesti generate ringkasan EMR automatik apabila pesakit dipanggil untuk consultation
- [x] **FR-202:** Ringkasan mesti mengandungi: Active Problem List, Current Medications, Allergies, Chronic Conditions, Recent Visits (6 bulan)
- [x] **FR-203:** Sistem mesti highlight critical information (allergies, contraindications, abnormal lab values)
- [x] **FR-204:** Sistem mesti memaparkan medication adherence trends jika data available
- [x] **FR-205:** Sistem mesti memaparkan vital signs trends dalam bentuk mini-chart
- [x] **FR-206:** Sistem mesti memaparkan lab results trends untuk chronic disease monitoring (HbA1c, cholesterol, BP)
- [x] **FR-207:** Ringkasan mesti boleh di-expand untuk detail penuh atau di-collapse untuk quick view
- [x] **FR-208:** Sistem mesti highlight bila last visit lebih dari 6 bulan (potential lost to follow-up)

### 4.3 Clinical Decision Support (FR-300 Series)

- [x] **FR-301:** Sistem mesti mencadangkan differential diagnosis berdasarkan symptoms dan patient history
- [x] **FR-302:** Setiap cadangan diagnosis mesti mempunyai confidence score (0-100%)
- [x] **FR-303:** Sistem mesti memaparkan reasoning chain yang menjelaskan kenapa diagnosis dicadangkan
- [x] **FR-304:** Sistem mesti menyenaraikan supporting evidence dari EMR yang menyokong setiap cadangan
- [x] **FR-305:** Sistem mesti mencadangkan investigations yang relevan untuk confirm/exclude diagnosis
- [x] **FR-306:** Sistem mesti mencadangkan treatment options berdasarkan clinical guidelines
- [x] **FR-307:** Sistem mesti link ke clinical guidelines (MOH, WHO) yang berkaitan
- [x] **FR-308:** Cadangan mesti dikategorikan mengikut confidence level: High (>85%), Medium (70-85%), Low (<70%)

### 4.4 Drug Interaction & Safety Alerts (FR-400 Series)

- [x] **FR-401:** Sistem mesti check drug-drug interactions untuk semua medications (current + new)
- [x] **FR-402:** Sistem mesti check drug-allergy interactions berdasarkan recorded allergies
- [x] **FR-403:** Sistem mesti check drug-disease contraindications berdasarkan problem list
- [x] **FR-404:** Sistem mesti check drug-food interactions untuk critical medications
- [x] **FR-405:** Sistem mesti validate dosage berdasarkan patient weight, age, dan renal function
- [x] **FR-406:** Interactions mesti dikategorikan: Severe (block prescription), Moderate (warning), Mild (info)
- [x] **FR-407:** Sistem mesti memaparkan alternative medications jika ada contraindication
- [x] **FR-408:** Sistem mesti require override justification untuk severe interactions

### 4.5 Explainable AI (FR-500 Series)

- [x] **FR-501:** Setiap cadangan AI mesti mempunyai confidence score yang visible
- [x] **FR-502:** Sistem mesti memaparkan reasoning chain dalam bahasa yang mudah difahami
- [x] **FR-503:** Sistem mesti highlight risk factors yang menyumbang kepada cadangan
- [x] **FR-504:** Sistem mesti link ke clinical references (guidelines, studies) yang menyokong cadangan
- [x] **FR-505:** Sistem mesti memaparkan similar past cases (anonymized) jika relevan
- [x] **FR-506:** Sistem mesti explain limitations dan uncertainties dalam cadangan
- [x] **FR-507:** Sistem mesti memaparkan "What-if" analysis (contoh: "Jika umur >60, risk score akan meningkat")

### 4.6 Human-in-the-Loop (FR-600 Series)

- [x] **FR-601:** Semua cadangan AI mesti disemak dan disahkan oleh doktor/jururawat sebelum tindakan
- [x] **FR-602:** Sistem mesti menyediakan button "Accept", "Reject", "Modify" untuk setiap cadangan
- [x] **FR-603:** Bila reject/modify, sistem mesti require justification dari clinician
- [x] **FR-604:** Low confidence predictions (<70%) mesti auto-flag untuk senior doctor review
- [x] **FR-605:** Critical alerts mesti require explicit acknowledgement sebelum proceed
- [x] **FR-606:** Sistem mesti merekodkan semua decisions (accept/reject/modify) untuk audit
- [x] **FR-607:** Sistem mesti escalate unacknowledged critical alerts selepas timeout (5 minit)

### 4.7 Feedback & Learning Loop (FR-700 Series)

- [x] **FR-701:** Sistem mesti membenarkan clinicians rate cadangan AI (helpful/not helpful/incorrect)
- [x] **FR-702:** Sistem mesti collect actual diagnosis outcome untuk comparison dengan AI prediction
- [x] **FR-703:** Sistem mesti learn dari corrections dan improve model secara berkala
- [x] **FR-704:** Sistem mesti track accuracy metrics per category (diagnosis type, symptom type)
- [x] **FR-705:** Sistem mesti identify patterns where AI consistently underperforms
- [x] **FR-706:** Feedback mesti de-identified sebelum digunakan untuk model training

### 4.8 Red Flag Detection (FR-800 Series)

- [x] **FR-801:** Sistem mesti detect life-threatening symptoms (chest pain, difficulty breathing, stroke symptoms, etc.)
- [x] **FR-802:** Sistem mesti detect critical lab values (severely abnormal results)
- [x] **FR-803:** Sistem mesti detect deteriorating trends (worsening vitals, declining renal function)
- [x] **FR-804:** Sistem mesti detect drug allergies dengan high severity
- [x] **FR-805:** Sistem mesti detect contraindications untuk existing conditions
- [x] **FR-806:** Red flags mesti trigger immediate visual + audio alert
- [x] **FR-807:** Red flags mesti tidak boleh dismissed tanpa explicit acknowledgement
- [x] **FR-808:** Sistem mesti log semua red flag alerts dan responses

### 4.9 Clinical Knowledge Base (FR-900 Series)

- [x] **FR-901:** Sistem mesti integrate dengan MOH clinical guidelines
- [x] **FR-902:** Sistem mesti integrate dengan WHO guidelines
- [x] **FR-903:** Sistem mesti integrate dengan drug database (MIMS atau equivalent)
- [x] **FR-904:** Sistem mesti support ICD-10/ICD-11 coding
- [x] **FR-905:** Sistem mesti support local clinic protocols
- [x] **FR-906:** Knowledge base mesti versioned dan updateable
- [x] **FR-907:** Sistem mesti track bila guidelines dikemaskini dan notify admin
- [x] **FR-908:** Sistem mesti allow clinic to add custom protocols

### 4.10 AI Governance & Ethics (FR-1000 Series)

- [x] **FR-1001:** Sistem mesti maintain complete audit trail untuk semua AI decisions
- [x] **FR-1002:** Sistem mesti track AI model versioning dengan changelog
- [x] **FR-1003:** Sistem mesti monitor AI performance metrics (accuracy, precision, recall)
- [x] **FR-1004:** Sistem mesti detect dan report potential bias dalam predictions
- [x] **FR-1005:** Sistem mesti provide incident reporting mechanism untuk AI-related issues
- [x] **FR-1006:** Sistem mesti allow rollback ke previous model version jika issues detected
- [x] **FR-1007:** Sistem mesti generate monthly AI performance report
- [x] **FR-1008:** Semua AI processing mesti on-premise untuk PDPA compliance

### 4.2 Kebenaran & Kawalan Akses

**Peranan Diperlukan:**
- Doktor: Full access ke semua AI features
- Jururawat: Triage, EMR summary, red flag alerts (tiada clinical decision support)
- Admin: AI governance, performance monitoring, knowledge base management

**Kebenaran Diperlukan:**

| Permission | Doktor | Jururawat | Admin |
|------------|--------|-----------|-------|
| `ai.triage.view` | ✓ | ✓ | ✓ |
| `ai.triage.execute` | ✓ | ✓ | ✗ |
| `ai.triage.override` | ✓ | ✗ | ✗ |
| `ai.emr_summary.view` | ✓ | ✓ | ✗ |
| `ai.diagnosis.view` | ✓ | ✗ | ✗ |
| `ai.diagnosis.accept_reject` | ✓ | ✗ | ✗ |
| `ai.drug_check.view` | ✓ | ✓ | ✗ |
| `ai.drug_check.override` | ✓ | ✗ | ✗ |
| `ai.feedback.submit` | ✓ | ✓ | ✗ |
| `ai.governance.view` | ✓ | ✗ | ✓ |
| `ai.governance.manage` | ✗ | ✗ | ✓ |
| `ai.knowledge_base.view` | ✓ | ✓ | ✓ |
| `ai.knowledge_base.manage` | ✗ | ✗ | ✓ |

**Authorization Logic:**
- Doktor mesti MMC-registered untuk akses clinical decision support
- Senior doctor review required untuk low-confidence cases
- Admin tidak boleh akses patient data, hanya aggregated AI metrics
- All AI interactions logged dengan user identity

### 4.3 Validasi Data

**Field Wajib - Triage Input:**
- `chief_complaint`: Required, string, max 500
- `symptoms`: Required, array of symptom objects
- `onset`: Required, enum (minutes, hours, days, weeks, months)
- `severity_patient`: Required, 1-10 scale
- `vital_signs`: Required for full triage (BP, HR, RR, Temp, SpO2)

**Field Wajib - Feedback:**
- `prediction_id`: Required, exists in predictions table
- `rating`: Required, enum (helpful, not_helpful, incorrect)
- `actual_diagnosis`: Optional, ICD-10 code (for outcome tracking)
- `comments`: Optional, string, max 1000

**Peraturan Validasi:**
- Symptoms mesti valid dari symptom ontology
- Vital signs mesti dalam physiologically possible range
- ICD-10 codes mesti valid format
- Drug codes mesti valid MIMS codes

**Peraturan Perniagaan:**
- AI cadangan tidak boleh disimpan tanpa human review
- Critical alerts mesti acknowledged dalam 5 minit
- Override justification mesti minimum 10 characters
- Low confidence cases mesti escalate ke senior

### 4.4 Audit Trail & PDPA Compliance

- [x] **Adakah feature ini perlu audit trail?** Ya - Kritikal
- **Field Audit**: created_by, updated_by, timestamp untuk semua AI interactions
- **Data Consent**: Pesakit mesti consent untuk AI-assisted care (dalam general consent)
- **Data Retention**: AI prediction logs disimpan 7 tahun, training data de-identified

**Audit Events:**

| Event Category | Events |
|----------------|--------|
| Triage | triage_initiated, triage_completed, triage_overridden |
| EMR Summary | summary_generated, summary_viewed |
| Clinical Decision | prediction_generated, prediction_accepted, prediction_rejected, prediction_modified |
| Drug Safety | interaction_detected, interaction_acknowledged, interaction_overridden |
| Red Flag | red_flag_triggered, red_flag_acknowledged, red_flag_escalated |
| Feedback | feedback_submitted, outcome_recorded |
| Governance | model_updated, knowledge_base_updated, incident_reported |

---

## 5. Keperluan Teknikal

### 5.1 Teknologi Stack

- **Framework**: Laravel 12
- **Frontend**: Blade Templates + Bootstrap 5 + CoreUI + Alpine.js (for reactivity)
- **Database**: MySQL 8.0
- **AI/ML Framework**: Python (FastAPI) with scikit-learn, XGBoost untuk ML models
- **AI Communication**: Laravel ↔ FastAPI via internal REST API
- **Knowledge Graph**: Neo4j atau equivalent untuk clinical ontology
- **Cache**: Redis untuk caching predictions dan knowledge base
- **Queue**: Laravel Queue (Redis driver) untuk async AI processing
- **File Storage**: Local storage untuk model artifacts
- **Logging**: ELK Stack atau equivalent untuk AI audit logs

### 5.2 Arsitektur Aplikasi

```
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Application                       │
├─────────────────────────────────────────────────────────────┤
│  Controller Layer                                            │
│  ├── AiTriageController                                      │
│  ├── AiSummaryController                                     │
│  ├── AiDiagnosisController                                   │
│  ├── AiDrugCheckController                                   │
│  └── AiGovernanceController                                  │
├─────────────────────────────────────────────────────────────┤
│  Service Layer                                               │
│  ├── TriageService                                           │
│  ├── EmrSummaryService                                       │
│  ├── ClinicalDecisionService                                 │
│  ├── DrugInteractionService                                  │
│  ├── ExplainabilityService                                   │
│  └── AiGovernanceService                                     │
├─────────────────────────────────────────────────────────────┤
│  AI Gateway (Internal API Client)                            │
│  └── AiEngineClient (communicates with Python AI Engine)     │
├─────────────────────────────────────────────────────────────┤
│  Repository Layer                                            │
│  ├── PredictionRepository                                    │
│  ├── FeedbackRepository                                      │
│  └── AuditRepository                                         │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼ (Internal REST API)
┌─────────────────────────────────────────────────────────────┐
│                    Python AI Engine (FastAPI)                │
├─────────────────────────────────────────────────────────────┤
│  Endpoints                                                   │
│  ├── /triage/predict                                         │
│  ├── /diagnosis/predict                                      │
│  ├── /drug/check-interactions                                │
│  ├── /summary/generate                                       │
│  └── /explain/{prediction_id}                                │
├─────────────────────────────────────────────────────────────┤
│  ML Models                                                   │
│  ├── TriageModel (XGBoost + Rule Engine)                     │
│  ├── DiagnosisModel (Multi-label Classifier)                 │
│  └── DrugInteractionModel (Graph-based)                      │
├─────────────────────────────────────────────────────────────┤
│  Knowledge Base                                              │
│  ├── SymptomOntology                                         │
│  ├── DrugDatabase                                            │
│  ├── ClinicalGuidelines                                      │
│  └── ICD10Mapping                                            │
└─────────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│                    Databases (On-Premise)                    │
├─────────────────────────────────────────────────────────────┤
│  MySQL 8.0          │  Redis          │  Neo4j (optional)   │
│  - Predictions      │  - Cache        │  - Clinical Graph   │
│  - Feedback         │  - Sessions     │  - Drug Relations   │
│  - Audit Logs       │  - Rate Limit   │  - Symptom Ontology │
└─────────────────────────────────────────────────────────────┘
```

### 5.3 Struktur Modul

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Admin/
│   │       └── AI/
│   │           ├── TriageController.php
│   │           ├── SummaryController.php
│   │           ├── DiagnosisController.php
│   │           ├── DrugCheckController.php
│   │           ├── FeedbackController.php
│   │           └── GovernanceController.php
│   ├── Requests/
│   │   ├── TriageRequest.php
│   │   ├── DiagnosisFeedbackRequest.php
│   │   └── OverrideRequest.php
│   └── Middleware/
│       ├── EnsureAiConsent.php
│       └── LogAiInteraction.php
├── Services/
│   └── AI/
│       ├── TriageService.php
│       ├── EmrSummaryService.php
│       ├── ClinicalDecisionService.php
│       ├── DrugInteractionService.php
│       ├── ExplainabilityService.php
│       ├── FeedbackService.php
│       ├── AiGovernanceService.php
│       └── AiEngineClient.php
├── Repositories/
│   └── AI/
│       ├── PredictionRepository.php
│       ├── FeedbackRepository.php
│       ├── KnowledgeBaseRepository.php
│       └── AiAuditRepository.php
├── Models/
│   ├── AiPrediction.php
│   ├── AiTriageResult.php
│   ├── AiDiagnosisSuggestion.php
│   ├── AiDrugAlert.php
│   ├── AiFeedback.php
│   ├── AiRedFlagAlert.php
│   ├── AiModelVersion.php
│   ├── AiAuditLog.php
│   ├── ClinicalGuideline.php
│   └── DrugInteraction.php
├── Events/
│   ├── RedFlagDetected.php
│   ├── CriticalAlertTriggered.php
│   └── PredictionGenerated.php
├── Listeners/
│   ├── NotifyRedFlagAlert.php
│   ├── EscalateCriticalAlert.php
│   └── LogPrediction.php
├── Jobs/
│   ├── GenerateEmrSummary.php
│   ├── CheckDrugInteractions.php
│   └── ProcessFeedback.php
└── Notifications/
    ├── RedFlagAlertNotification.php
    ├── CriticalEscalationNotification.php
    └── LowConfidenceWarningNotification.php

ai-engine/ (Python FastAPI - separate service)
├── app/
│   ├── main.py
│   ├── api/
│   │   ├── triage.py
│   │   ├── diagnosis.py
│   │   ├── drug_check.py
│   │   ├── summary.py
│   │   └── explain.py
│   ├── models/
│   │   ├── triage_model.py
│   │   ├── diagnosis_model.py
│   │   └── interaction_model.py
│   ├── knowledge/
│   │   ├── symptom_ontology.py
│   │   ├── drug_database.py
│   │   ├── guidelines.py
│   │   └── icd_mapping.py
│   ├── explainability/
│   │   ├── shap_explainer.py
│   │   └── reasoning_chain.py
│   └── config/
│       └── settings.py
├── models/  (trained model files)
│   ├── triage_v1.0.pkl
│   ├── diagnosis_v1.0.pkl
│   └── interaction_v1.0.pkl
├── data/  (knowledge base data)
│   ├── symptoms.json
│   ├── drugs.json
│   ├── guidelines.json
│   └── icd10.json
└── requirements.txt

config/
├── ai.php
└── ai_governance.php

resources/
└── views/
    └── admin/
        └── ai/
            ├── triage/
            │   ├── form.blade.php
            │   └── result.blade.php
            ├── summary/
            │   └── panel.blade.php
            ├── diagnosis/
            │   └── suggestions.blade.php
            ├── drug-check/
            │   └── alerts.blade.php
            └── governance/
                ├── dashboard.blade.php
                ├── performance.blade.php
                └── incidents.blade.php
```

### 5.4 Database Schema

#### Jadual: `ai_predictions`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `uuid` | uuid UNIQUE NOT NULL | Unique identifier for tracking |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `encounter_id` | bigint UNSIGNED NULL | FK → encounters.id |
| `prediction_type` | enum NOT NULL | triage/diagnosis/drug_check/summary |
| `model_version` | varchar(20) NOT NULL | AI model version used |
| `input_data` | json NOT NULL | Input data (encrypted) |
| `output_data` | json NOT NULL | Prediction output (encrypted) |
| `confidence_score` | decimal(5,2) NULL | Overall confidence (0-100) |
| `confidence_level` | enum NULL | high/medium/low |
| `explanation` | json NULL | XAI reasoning chain |
| `status` | enum NOT NULL | pending/reviewed/accepted/rejected/modified |
| `reviewed_by` | bigint UNSIGNED NULL | FK → users.id |
| `reviewed_at` | datetime NULL | Review timestamp |
| `review_action` | enum NULL | accept/reject/modify |
| `review_justification` | text NULL | Justification for reject/modify |
| `processing_time_ms` | int NULL | Processing time in milliseconds |
| `created_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_prediction_pesakit` on `pesakit_id`
- `idx_prediction_type` on `prediction_type`
- `idx_prediction_status` on `status`
- `idx_prediction_created` on `created_at`

#### Jadual: `ai_triage_results`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `prediction_id` | bigint UNSIGNED NOT NULL | FK → ai_predictions.id |
| `chief_complaint` | text NOT NULL | Chief complaint text |
| `symptoms` | json NOT NULL | Array of symptoms |
| `vital_signs` | json NULL | Vital signs data |
| `severity_score` | int NOT NULL | 1-5 (Manchester) |
| `severity_label` | enum NOT NULL | emergency/urgent/semi_urgent/standard/non_urgent |
| `urgency_minutes` | int NULL | Recommended wait time |
| `red_flags` | json NULL | Detected red flags |
| `recommended_actions` | json NULL | Recommended next steps |
| `overridden` | boolean DEFAULT false | Was overridden by clinician |
| `override_severity` | enum NULL | Overridden severity level |
| `override_reason` | text NULL | Override justification |
| `created_at` | timestamp | Created timestamp |

**Indexes:**
- `idx_triage_prediction` on `prediction_id`
- `idx_triage_severity` on `severity_label`

#### Jadual: `ai_diagnosis_suggestions`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `prediction_id` | bigint UNSIGNED NOT NULL | FK → ai_predictions.id |
| `rank` | int NOT NULL | Rank order (1 = most likely) |
| `icd_code` | varchar(10) NOT NULL | ICD-10 code |
| `diagnosis_name` | varchar(255) NOT NULL | Diagnosis name (BM/EN) |
| `confidence_score` | decimal(5,2) NOT NULL | Confidence (0-100) |
| `supporting_evidence` | json NOT NULL | Evidence from EMR |
| `reasoning_chain` | json NOT NULL | Step-by-step reasoning |
| `risk_factors` | json NULL | Contributing risk factors |
| `recommended_investigations` | json NULL | Suggested tests |
| `recommended_treatments` | json NULL | Treatment guidelines |
| `clinical_references` | json NULL | Links to guidelines |
| `is_accepted` | boolean NULL | Clinician decision |
| `created_at` | timestamp | Created timestamp |

**Indexes:**
- `idx_diagnosis_prediction` on `prediction_id`
- `idx_diagnosis_icd` on `icd_code`

#### Jadual: `ai_drug_alerts`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `prediction_id` | bigint UNSIGNED NOT NULL | FK → ai_predictions.id |
| `alert_type` | enum NOT NULL | drug_drug/drug_allergy/drug_disease/drug_food/dosage |
| `severity` | enum NOT NULL | severe/moderate/mild |
| `drug_1_code` | varchar(20) NOT NULL | First drug code |
| `drug_1_name` | varchar(255) NOT NULL | First drug name |
| `drug_2_code` | varchar(20) NULL | Second drug/allergen/disease code |
| `drug_2_name` | varchar(255) NULL | Second drug/allergen/disease name |
| `interaction_description` | text NOT NULL | Description of interaction |
| `clinical_significance` | text NOT NULL | Clinical impact |
| `recommendation` | text NOT NULL | Recommended action |
| `alternatives` | json NULL | Alternative medications |
| `references` | json NULL | Clinical references |
| `acknowledged` | boolean DEFAULT false | Clinician acknowledged |
| `acknowledged_by` | bigint UNSIGNED NULL | FK → users.id |
| `acknowledged_at` | datetime NULL | Acknowledgement time |
| `overridden` | boolean DEFAULT false | Alert overridden |
| `override_reason` | text NULL | Override justification |
| `created_at` | timestamp | Created timestamp |

**Indexes:**
- `idx_drug_alert_prediction` on `prediction_id`
- `idx_drug_alert_severity` on `severity`
- `idx_drug_alert_type` on `alert_type`

#### Jadual: `ai_red_flag_alerts`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `prediction_id` | bigint UNSIGNED NOT NULL | FK → ai_predictions.id |
| `pesakit_id` | bigint UNSIGNED NOT NULL | FK → pesakit.id |
| `flag_type` | enum NOT NULL | life_threatening/critical_lab/deteriorating/allergy/contraindication |
| `flag_code` | varchar(50) NOT NULL | Internal flag code |
| `flag_description` | text NOT NULL | Description |
| `urgency_level` | enum NOT NULL | immediate/urgent/soon |
| `recommended_action` | text NOT NULL | What to do |
| `triggered_at` | datetime NOT NULL | When triggered |
| `acknowledged` | boolean DEFAULT false | Acknowledged |
| `acknowledged_by` | bigint UNSIGNED NULL | FK → users.id |
| `acknowledged_at` | datetime NULL | Acknowledgement time |
| `escalated` | boolean DEFAULT false | Was escalated |
| `escalated_at` | datetime NULL | Escalation time |
| `escalated_to` | bigint UNSIGNED NULL | FK → users.id |
| `resolution_status` | enum NULL | resolved/false_positive/referred |
| `resolution_notes` | text NULL | Resolution notes |
| `resolved_by` | bigint UNSIGNED NULL | FK → users.id |
| `resolved_at` | datetime NULL | Resolution time |
| `created_at` | timestamp | Created timestamp |

**Indexes:**
- `idx_red_flag_pesakit` on `pesakit_id`
- `idx_red_flag_acknowledged` on `acknowledged`
- `idx_red_flag_triggered` on `triggered_at`

#### Jadual: `ai_feedback`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `prediction_id` | bigint UNSIGNED NOT NULL | FK → ai_predictions.id |
| `feedback_type` | enum NOT NULL | rating/outcome/correction |
| `rating` | enum NULL | helpful/not_helpful/incorrect |
| `actual_diagnosis_icd` | varchar(10) NULL | Actual diagnosis (outcome) |
| `actual_diagnosis_name` | varchar(255) NULL | Actual diagnosis name |
| `correction_details` | json NULL | Correction information |
| `comments` | text NULL | Free text comments |
| `is_used_for_training` | boolean DEFAULT false | Used for model training |
| `de_identified_at` | datetime NULL | When de-identified |
| `submitted_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |

**Indexes:**
- `idx_feedback_prediction` on `prediction_id`
- `idx_feedback_rating` on `rating`

#### Jadual: `ai_model_versions`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `model_type` | enum NOT NULL | triage/diagnosis/drug_check |
| `version` | varchar(20) NOT NULL | Version number (semver) |
| `description` | text NULL | Version description |
| `changelog` | json NULL | Changes in this version |
| `performance_metrics` | json NULL | Accuracy, precision, recall |
| `training_data_summary` | json NULL | Summary of training data |
| `is_active` | boolean DEFAULT false | Currently active version |
| `activated_at` | datetime NULL | Activation time |
| `deactivated_at` | datetime NULL | Deactivation time |
| `deployed_by` | bigint UNSIGNED NULL | FK → users.id |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_model_version_type` on `model_type`
- `idx_model_version_active` on `is_active`

#### Jadual: `ai_audit_logs`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `event_type` | varchar(50) NOT NULL | Event type |
| `event_category` | varchar(50) NOT NULL | Category |
| `prediction_id` | bigint UNSIGNED NULL | FK → ai_predictions.id |
| `pesakit_id` | bigint UNSIGNED NULL | FK → pesakit.id |
| `user_id` | bigint UNSIGNED NULL | FK → users.id |
| `model_version` | varchar(20) NULL | Model version |
| `action` | varchar(100) NOT NULL | Action performed |
| `details` | json NULL | Additional details |
| `ip_address` | varchar(45) NULL | IP address |
| `user_agent` | text NULL | User agent |
| `created_at` | timestamp NOT NULL | Event timestamp |

**Indexes:**
- `idx_ai_audit_event` on `event_type`
- `idx_ai_audit_prediction` on `prediction_id`
- `idx_ai_audit_user` on `user_id`
- `idx_ai_audit_created` on `created_at`

**Partitioning:**
- Partition by RANGE on `created_at` (monthly partitions)

#### Jadual: `ai_incidents`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `incident_code` | varchar(20) UNIQUE NOT NULL | INC-YYYYMMDD-XXXX |
| `prediction_id` | bigint UNSIGNED NULL | FK → ai_predictions.id |
| `incident_type` | enum NOT NULL | missed_diagnosis/false_positive/system_error/patient_harm |
| `severity` | enum NOT NULL | critical/high/medium/low |
| `title` | varchar(255) NOT NULL | Incident title |
| `description` | text NOT NULL | Detailed description |
| `patient_impact` | text NULL | Impact on patient |
| `root_cause` | text NULL | Root cause analysis |
| `corrective_action` | text NULL | Corrective action taken |
| `status` | enum NOT NULL | reported/investigating/resolved/closed |
| `reported_by` | bigint UNSIGNED NOT NULL | FK → users.id |
| `assigned_to` | bigint UNSIGNED NULL | FK → users.id |
| `resolved_by` | bigint UNSIGNED NULL | FK → users.id |
| `resolved_at` | datetime NULL | Resolution time |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_incident_type` on `incident_type`
- `idx_incident_status` on `status`
- `idx_incident_severity` on `severity`

#### Jadual: `clinical_guidelines`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `source` | enum NOT NULL | moh/who/nice/local |
| `code` | varchar(50) UNIQUE NOT NULL | Guideline code |
| `title` | varchar(255) NOT NULL | Title |
| `category` | varchar(100) NOT NULL | Clinical category |
| `icd_codes` | json NULL | Related ICD codes |
| `content` | longtext NOT NULL | Guideline content |
| `version` | varchar(20) NOT NULL | Guideline version |
| `effective_date` | date NOT NULL | Effective date |
| `expiry_date` | date NULL | Expiry date |
| `url` | varchar(500) NULL | Source URL |
| `is_active` | boolean DEFAULT true | Active status |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_guideline_source` on `source`
- `idx_guideline_category` on `category`
- `idx_guideline_active` on `is_active`

#### Jadual: `drug_interactions`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint UNSIGNED PK | Primary key |
| `interaction_type` | enum NOT NULL | drug_drug/drug_allergy/drug_disease/drug_food |
| `drug_1_code` | varchar(20) NOT NULL | First drug code |
| `drug_1_name` | varchar(255) NOT NULL | First drug name |
| `interactant_code` | varchar(50) NOT NULL | Interactant code |
| `interactant_name` | varchar(255) NOT NULL | Interactant name |
| `severity` | enum NOT NULL | severe/moderate/mild |
| `description` | text NOT NULL | Interaction description |
| `mechanism` | text NULL | Mechanism of interaction |
| `clinical_effect` | text NOT NULL | Clinical effect |
| `management` | text NOT NULL | Management recommendation |
| `evidence_level` | enum NULL | established/probable/suspected/possible |
| `references` | json NULL | Literature references |
| `source` | varchar(50) NOT NULL | Data source (MIMS, etc.) |
| `is_active` | boolean DEFAULT true | Active status |
| `created_at` | timestamp | Created timestamp |
| `updated_at` | timestamp | Updated timestamp |

**Indexes:**
- `idx_interaction_drug1` on `drug_1_code`
- `idx_interaction_interactant` on `interactant_code`
- `idx_interaction_type` on `interaction_type`
- `idx_interaction_severity` on `severity`

### 5.5 Configuration Files

**File: `config/ai.php`**

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Engine Configuration
    |--------------------------------------------------------------------------
    */
    'engine' => [
        'base_url' => env('AI_ENGINE_URL', 'http://localhost:8000'),
        'timeout' => env('AI_ENGINE_TIMEOUT', 30),
        'retry_attempts' => 3,
        'api_key' => env('AI_ENGINE_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Triage Configuration
    |--------------------------------------------------------------------------
    */
    'triage' => [
        'enabled' => true,
        'model_version' => 'v1.0',

        'severity_levels' => [
            1 => ['label' => 'emergency', 'display' => 'Kecemasan', 'color' => 'danger', 'wait_minutes' => 0],
            2 => ['label' => 'urgent', 'display' => 'Segera', 'color' => 'warning', 'wait_minutes' => 10],
            3 => ['label' => 'semi_urgent', 'display' => 'Separa Segera', 'color' => 'info', 'wait_minutes' => 30],
            4 => ['label' => 'standard', 'display' => 'Standard', 'color' => 'primary', 'wait_minutes' => 60],
            5 => ['label' => 'non_urgent', 'display' => 'Tidak Segera', 'color' => 'secondary', 'wait_minutes' => 120],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Diagnosis Configuration
    |--------------------------------------------------------------------------
    */
    'diagnosis' => [
        'enabled' => true,
        'model_version' => 'v1.0',
        'max_suggestions' => 5,
        'min_confidence' => 20, // Minimum confidence to show
    ],

    /*
    |--------------------------------------------------------------------------
    | Drug Interaction Configuration
    |--------------------------------------------------------------------------
    */
    'drug_check' => [
        'enabled' => true,
        'model_version' => 'v1.0',
        'data_source' => 'mims', // mims, drugbank, custom

        'severity_actions' => [
            'severe' => 'block', // block, warn, info
            'moderate' => 'warn',
            'mild' => 'info',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Confidence Thresholds
    |--------------------------------------------------------------------------
    */
    'confidence' => [
        'high' => 85,
        'medium' => 70,
        'low' => 0,

        'escalation_threshold' => 70, // Below this, escalate to senior
        'hide_threshold' => 20, // Below this, don't show
    ],

    /*
    |--------------------------------------------------------------------------
    | Red Flag Configuration
    |--------------------------------------------------------------------------
    */
    'red_flags' => [
        'enabled' => true,
        'acknowledgement_timeout_minutes' => 5,
        'escalation_enabled' => true,
        'audio_alert' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Human-in-the-Loop Configuration
    |--------------------------------------------------------------------------
    */
    'human_in_loop' => [
        'mandatory_review' => true,
        'allow_auto_accept' => false, // Never auto-accept
        'require_justification_on_reject' => true,
        'min_justification_length' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Feedback Configuration
    |--------------------------------------------------------------------------
    */
    'feedback' => [
        'enabled' => true,
        'collect_outcomes' => true,
        'de_identify_for_training' => true,
        'de_identification_delay_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | EMR Summary Configuration
    |--------------------------------------------------------------------------
    */
    'summary' => [
        'enabled' => true,
        'recent_visits_months' => 6,
        'include_vitals_trend' => true,
        'include_lab_trend' => true,
        'highlight_critical' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Configuration
    |--------------------------------------------------------------------------
    */
    'audit' => [
        'log_all_predictions' => true,
        'log_all_reviews' => true,
        'retention_years' => 7,
    ],
];
```

**File: `config/ai_governance.php`**

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Ethics & Governance
    |--------------------------------------------------------------------------
    */
    'ethics' => [
        'bias_monitoring_enabled' => true,
        'fairness_metrics' => ['demographic_parity', 'equalized_odds'],
        'protected_attributes' => ['age', 'gender', 'ethnicity'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Versioning
    |--------------------------------------------------------------------------
    */
    'versioning' => [
        'require_changelog' => true,
        'require_performance_metrics' => true,
        'allow_rollback' => true,
        'max_versions_kept' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'metrics' => [
            'accuracy',
            'precision',
            'recall',
            'f1_score',
            'auc_roc',
        ],
        'alert_thresholds' => [
            'accuracy_drop' => 5, // Alert if drops by 5%
            'latency_increase' => 50, // Alert if increases by 50%
        ],
        'monitoring_interval_minutes' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Incident Management
    |--------------------------------------------------------------------------
    */
    'incidents' => [
        'require_root_cause' => true,
        'escalation_matrix' => [
            'critical' => ['admin', 'clinical_director'],
            'high' => ['admin'],
            'medium' => ['ai_admin'],
            'low' => ['ai_admin'],
        ],
        'resolution_sla_hours' => [
            'critical' => 4,
            'high' => 24,
            'medium' => 72,
            'low' => 168, // 1 week
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reporting
    |--------------------------------------------------------------------------
    */
    'reporting' => [
        'daily_summary' => true,
        'weekly_performance_report' => true,
        'monthly_governance_report' => true,
        'report_recipients' => [], // Email addresses
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Privacy (PDPA)
    |--------------------------------------------------------------------------
    */
    'privacy' => [
        'processing_location' => 'on_premise',
        'encryption_at_rest' => true,
        'encryption_in_transit' => true,
        'data_minimization' => true,
        'purpose_limitation' => true,
        'retention_policy_years' => 7,
    ],
];
```

### 5.6 Routes (Route Attributes)

**Route Summary:**

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/ai/triage` | admin.ai.triage.index | Triage dashboard |
| POST | `/admin/ai/triage/assess` | admin.ai.triage.assess | Run AI triage assessment |
| POST | `/admin/ai/triage/{result}/override` | admin.ai.triage.override | Override triage result |
| GET | `/admin/ai/summary/{pesakit}` | admin.ai.summary.show | Get EMR summary |
| POST | `/admin/ai/summary/{pesakit}/refresh` | admin.ai.summary.refresh | Refresh EMR summary |
| GET | `/admin/ai/diagnosis/{encounter}` | admin.ai.diagnosis.show | Get diagnosis suggestions |
| POST | `/admin/ai/diagnosis/{prediction}/review` | admin.ai.diagnosis.review | Review diagnosis suggestion |
| GET | `/admin/ai/drug-check` | admin.ai.drug-check.index | Drug check interface |
| POST | `/admin/ai/drug-check/analyze` | admin.ai.drug-check.analyze | Analyze drug interactions |
| POST | `/admin/ai/drug-check/{alert}/acknowledge` | admin.ai.drug-check.acknowledge | Acknowledge drug alert |
| POST | `/admin/ai/drug-check/{alert}/override` | admin.ai.drug-check.override | Override drug alert |
| GET | `/admin/ai/red-flags` | admin.ai.red-flags.index | Red flag alerts |
| POST | `/admin/ai/red-flags/{alert}/acknowledge` | admin.ai.red-flags.acknowledge | Acknowledge red flag |
| POST | `/admin/ai/red-flags/{alert}/resolve` | admin.ai.red-flags.resolve | Resolve red flag |
| GET | `/admin/ai/feedback` | admin.ai.feedback.index | Feedback dashboard |
| POST | `/admin/ai/feedback` | admin.ai.feedback.store | Submit feedback |
| GET | `/admin/ai/governance` | admin.ai.governance.index | Governance dashboard |
| GET | `/admin/ai/governance/performance` | admin.ai.governance.performance | Performance metrics |
| GET | `/admin/ai/governance/incidents` | admin.ai.governance.incidents | Incident list |
| POST | `/admin/ai/governance/incidents` | admin.ai.governance.incidents.store | Report incident |
| GET | `/admin/ai/governance/models` | admin.ai.governance.models | Model versions |
| POST | `/admin/ai/governance/models/{version}/activate` | admin.ai.governance.models.activate | Activate model |
| POST | `/admin/ai/governance/models/{version}/rollback` | admin.ai.governance.models.rollback | Rollback model |

---

## 6. Workflow dan User Flow

### 6.1 Triage Workflow

```
[Jururawat] → Pilih pesakit dari queue
    ↓
[Sistem] → Auto-load patient history
    ↓
[Jururawat] → Input chief complaint dan symptoms
    ↓
[Sistem] → Capture vital signs (manual/auto dari device)
    ↓
[AI Engine] → Analyze symptoms + vitals + history
    ↓
[AI Engine] → Calculate severity score (1-5)
    ↓
[AI Engine] → Detect red flags
    ↓ (Red flags detected?)
    ├── Yes → [Sistem] Trigger red flag alert (visual + audio)
    │           ↓
    │         [Jururawat] → Acknowledge dan escalate
    │           ↓
    │         [Doktor] → Review immediately
    │
    └── No → [Sistem] Display triage result dengan reasoning
              ↓
            [Jururawat] → Review dan confirm/override
              ↓
            [Sistem] → Assign ke queue mengikut severity
              ↓
            [Sistem] → Log audit trail
```

### 6.2 Clinical Decision Support Workflow

```
[Doktor] → Open consultation untuk pesakit
    ↓
[Sistem] → Auto-generate EMR Summary (async)
    ↓
[Doktor] → View EMR Summary panel
    ↓
[Doktor] → Document current symptoms dan findings
    ↓
[AI Engine] → Generate differential diagnosis
    ↓
[Sistem] → Display suggestions dengan:
           - Confidence scores
           - Reasoning chain
           - Supporting evidence
           - Recommended investigations
    ↓
[Doktor] → Review setiap suggestion
    ↓
[Doktor] → Accept / Reject / Modify setiap suggestion
    ↓ (If reject/modify)
    └── [Sistem] → Require justification
          ↓
        [Sistem] → Log untuk feedback loop
    ↓
[Doktor] → Select final diagnosis
    ↓
[Sistem] → Suggest treatment options
    ↓
[Doktor] → Prescribe medications
    ↓
[AI Engine] → Check drug interactions
    ↓ (Interactions detected?)
    ├── Severe → [Sistem] Block prescription, show alert
    │             ↓
    │           [Doktor] → Override dengan justification ATAU choose alternative
    │
    ├── Moderate → [Sistem] Show warning
    │               ↓
    │             [Doktor] → Acknowledge dan proceed
    │
    └── Mild → [Sistem] Show info badge
    ↓
[Sistem] → Save prescription
    ↓
[Sistem] → Log audit trail
```

### 6.3 Red Flag Escalation Workflow

```
[AI Engine] → Detect red flag
    ↓
[Sistem] → Create red flag alert
    ↓
[Sistem] → Display prominent alert (visual + audio)
    ↓
[Start Timer] → 5 minute acknowledgement timeout
    ↓
[Clinician] → Acknowledge alert?
    ├── Yes (within 5 min) →
    │     ↓
    │   [Clinician] → Take action
    │     ↓
    │   [Clinician] → Document resolution
    │     ↓
    │   [Sistem] → Close alert, log audit
    │
    └── No (timeout) →
          ↓
        [Sistem] → Escalate to senior doctor
          ↓
        [Sistem] → Send notification (in-app + SMS)
          ↓
        [Senior Doctor] → Acknowledge dan take over
```

### 6.4 Feedback Loop Workflow

```
[Clinician] → Rate AI prediction (helpful/not helpful/incorrect)
    ↓
[Sistem] → Store feedback with prediction_id
    ↓
[Clinician] → (Optional) Record actual outcome
    ↓
[Sistem] → Store outcome data
    ↓
[Background Job] → De-identify data after 30 days
    ↓
[Background Job] → Include in training dataset
    ↓
[AI Team] → Periodic model retraining
    ↓
[Admin] → Review model performance
    ↓
[Admin] → Deploy new model version (if improved)
    ↓
[Sistem] → Track new version performance
```

### 6.5 State Management

**Prediction Status Flow:**
```
[Created] → [Pending Review] → [Accepted]
                    ↓
               [Rejected]
                    ↓
               [Modified]
```

**Red Flag Alert Status Flow:**
```
[Triggered] → [Acknowledged] → [Resolved]
      ↓              ↓
[Escalated]    [False Positive]
      ↓
[Acknowledged by Senior]
      ↓
[Resolved/Referred]
```

**Drug Alert Status Flow:**
```
[Detected] → [Acknowledged] → [Proceeded]
      ↓
[Overridden (with justification)]
      ↓
[Alternative Selected]
```

---

## 7. Keperluan UI/UX

### 7.1 Layout

- **Jenis Halaman**: Embedded widgets dalam EMR interface + dedicated governance dashboard
- **Navigation**: AI panel sebagai sidebar/widget dalam consultation screen

**AI Integration Points:**
```
📁 Consultation Screen
├── 🤖 AI Summary Panel (collapsible sidebar)
│   ├── Problem List
│   ├── Current Medications
│   ├── Allergies (highlighted)
│   ├── Vital Signs Trend
│   └── Recent Visits
├── 🎯 Triage Widget (for nurses)
│   ├── Symptom Input
│   ├── Severity Score Display
│   └── Red Flag Alerts
├── 💊 Diagnosis Suggestions (inline)
│   ├── Differential List
│   ├── Confidence Scores
│   └── Accept/Reject Buttons
└── ⚠️ Drug Interaction Alerts (modal/toast)
    ├── Alert Details
    ├── Alternatives
    └── Override Option

📁 AI Governance (Admin)
├── 📊 Performance Dashboard
├── 🔄 Model Versions
├── 🚨 Incidents
├── 📈 Analytics
└── ⚙️ Settings
```

### 7.2 Bootstrap 5 + CoreUI Components

- [x] **Card** - Summary panels, diagnosis cards, alert cards
- [x] **Alert** - Red flags (danger), warnings (warning), info (info)
- [x] **Badge** - Confidence levels, severity badges, status badges
- [x] **Progress** - Confidence bars, loading indicators
- [x] **Modal** - Drug interaction details, override justification
- [x] **Toast** - Non-blocking notifications
- [x] **Button Group** - Accept/Reject/Modify actions
- [x] **Collapse** - Expandable reasoning chains
- [x] **Tooltip** - Hover explanations
- [x] **Charts** - Vital signs trends, performance metrics (Chart.js)

### 7.3 Key UI Components

**AI Summary Panel:**
- Collapsible sidebar attached to consultation screen
- Color-coded sections (allergies in red, medications in blue)
- Mini-charts untuk vital signs trends
- Expand/collapse untuk each section
- "Last updated" timestamp

**Triage Result Card:**
- Large severity badge (color-coded)
- Countdown timer untuk wait time
- Red flag indicators (if any)
- Override button dengan justification modal
- Reasoning chain (collapsible)

**Diagnosis Suggestion Card:**
- Rank number dan ICD code
- Confidence bar (visual)
- Supporting evidence bullets
- "Explain" button untuk reasoning chain
- Accept/Reject buttons
- Link ke clinical guidelines

**Drug Alert Modal:**
- Severity indicator (color-coded header)
- Interaction description
- Clinical significance
- Recommended alternatives (clickable to select)
- Override dengan justification text area
- "Learn more" link

**Red Flag Alert Banner:**
- Full-width banner at top of screen
- Pulsing animation untuk critical
- Clear action buttons
- Timer showing seconds since triggered
- Audio icon (click to mute)

### 7.4 Icons

**Custom AI Icons:**
- `heroicon-o-cpu-chip` - AI/ML features
- `heroicon-o-light-bulb` - Suggestions
- `heroicon-o-exclamation-triangle` - Warnings
- `heroicon-o-shield-exclamation` - Red flags
- `heroicon-o-check-circle` - Accepted
- `heroicon-o-x-circle` - Rejected
- `heroicon-o-arrow-path` - Processing
- `heroicon-o-chart-bar` - Analytics
- `heroicon-o-beaker` - Investigations
- `heroicon-o-document-text` - Guidelines

### 7.5 Responsive Design

- **Mobile Support**: Limited - governance dashboard only
- **Tablet Support**: Ya - full functionality
- **Desktop Priority**: Primary interface untuk clinical use

---

## 8. Keperluan Keselamatan

### 8.1 Authentication & Authorization

- **Authentication**: Laravel Breeze (inherited from main system)
- **Middleware**: `auth`, `ai.consent`, `ai.log`
- **Role-based Access**: Doktor, Jururawat, Admin dengan permissions berbeza
- **MMC Verification**: Doktor mesti MMC-registered untuk clinical decision support

### 8.2 Data Protection (PDPA Compliance)

- **On-Premise Processing**: Semua AI processing dalam server klinik, tiada data ke cloud
- **Encryption at Rest**: AES-256 untuk prediction data
- **Encryption in Transit**: TLS 1.3 untuk internal API
- **Data Minimization**: Hanya collect data yang diperlukan untuk predictions
- **Purpose Limitation**: Data hanya digunakan untuk clinical decision support
- **Consent**: Pesakit consent untuk AI-assisted care (dalam general consent form)
- **Data Retention**: 7 tahun untuk audit, de-identified untuk training

### 8.3 AI-Specific Security

- **Model Security**: Models stored encrypted, access controlled
- **Input Validation**: Sanitize semua input sebelum hantar ke AI engine
- **Output Validation**: Validate AI output sebelum display
- **Adversarial Protection**: Basic protection against adversarial inputs
- **Audit Trail**: Complete logging untuk semua AI interactions

### 8.4 Input Validation & Security

- **CSRF Protection**: Semua POST requests
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Prevention**: Blade escaping
- **Rate Limiting**: Limit AI API calls per user

---

## 9. Keperluan Prestasi

### 9.1 Response Time

- **Triage Assessment**: < 3 saat
- **EMR Summary Generation**: < 2 saat
- **Diagnosis Suggestions**: < 5 saat
- **Drug Interaction Check**: < 2 saat
- **Red Flag Detection**: < 1 saat (real-time)

### 9.2 Scalability

- **Concurrent Users**: 20-30 clinicians simultaneously
- **AI Engine**: Dapat handle 100 requests/minute
- **Caching**: Cache knowledge base dalam Redis
- **Queue Processing**: Async processing untuk non-critical tasks
- **Model Optimization**: Optimized models untuk inference speed

### 9.3 Availability

- **AI Engine Uptime**: > 99.5%
- **Fallback Mode**: Manual mode available jika AI engine down
- **Health Checks**: Periodic health checks dengan auto-restart

---

## 10. Keperluan Ujian

### 10.1 Unit Testing

**File: `tests/Unit/Services/TriageServiceTest.php`**

- [x] **Test**: Calculate severity score correctly untuk different symptom combinations
- [x] **Test**: Detect red flags correctly
- [x] **Test**: Handle missing vital signs gracefully
- [x] **Test**: Override logic works correctly

**File: `tests/Unit/Services/DrugInteractionServiceTest.php`**

- [x] **Test**: Detect drug-drug interactions correctly
- [x] **Test**: Detect drug-allergy interactions
- [x] **Test**: Severity classification is correct
- [x] **Test**: Alternative suggestions are relevant

### 10.2 Feature Testing

**File: `tests/Feature/AiTriageTest.php`**

- [x] **Test**: Nurse can perform AI triage
- [x] **Test**: Doctor cannot override without justification
- [x] **Test**: Red flag triggers alert
- [x] **Test**: Audit log is created

**File: `tests/Feature/AiDiagnosisTest.php`**

- [x] **Test**: Doctor can view diagnosis suggestions
- [x] **Test**: Accept/reject workflow works
- [x] **Test**: Feedback is recorded
- [x] **Test**: Low confidence triggers escalation

**File: `tests/Feature/AiDrugCheckTest.php`**

- [x] **Test**: Severe interaction blocks prescription
- [x] **Test**: Override requires justification
- [x] **Test**: Alternatives are displayed

### 10.3 Integration Testing

- [x] **Test**: Laravel ↔ Python AI Engine communication
- [x] **Test**: Knowledge base integration
- [x] **Test**: EMR data retrieval untuk summary
- [x] **Test**: Real-time alert delivery

### 10.4 AI Model Testing

- [x] **Test**: Model accuracy meets threshold (>85% untuk high-confidence)
- [x] **Test**: Model bias testing across demographics
- [x] **Test**: Edge case handling
- [x] **Test**: Explainability outputs are coherent

### 10.5 User Acceptance Testing (UAT)

**Scenario 1**: Nurse Triage Workflow
- Steps: Input symptoms → Get severity → Acknowledge red flag
- Expected Result: Correct severity, alert triggered, logged

**Scenario 2**: Doctor Diagnosis Review
- Steps: View suggestions → Review reasoning → Accept/reject
- Expected Result: Decision recorded, audit trail created

**Scenario 3**: Drug Interaction Override
- Steps: Prescribe → See severe alert → Override with justification
- Expected Result: Justification required, prescription proceeds

**Scenario 4**: Red Flag Escalation
- Steps: Red flag triggered → No acknowledge → Auto-escalate
- Expected Result: Escalation notification sent within 5 minutes

---

## 11. Langkah Implementasi

### 11.1 Fasa 1: Infrastructure Setup (Minggu 1-2)

- [ ] Setup Python AI Engine (FastAPI)
- [ ] Configure internal communication Laravel ↔ Python
- [ ] Setup Redis untuk caching
- [ ] Create database migrations untuk AI tables
- [ ] Setup logging infrastructure
- [ ] Configure encryption untuk sensitive data

### 11.2 Fasa 2: Knowledge Base (Minggu 3-4)

- [ ] Import symptom ontology
- [ ] Import drug database (MIMS equivalent)
- [ ] Import clinical guidelines (MOH, WHO)
- [ ] Import ICD-10 codes
- [ ] Setup knowledge base versioning
- [ ] Create admin interface untuk knowledge base management

### 11.3 Fasa 3: AI Models Development (Minggu 5-8)

- [ ] Develop triage rule engine
- [ ] Train triage ML model (XGBoost)
- [ ] Develop diagnosis suggestion model
- [ ] Develop drug interaction checker
- [ ] Implement explainability (SHAP)
- [ ] Validate models dengan clinical data
- [ ] Package models untuk deployment

### 11.4 Fasa 4: Laravel Integration (Minggu 9-11)

- [ ] Create AI Services (Triage, Diagnosis, DrugCheck)
- [ ] Create AI Engine Client
- [ ] Create Controllers dengan Route Attributes
- [ ] Create FormRequest validations
- [ ] Create Repositories
- [ ] Create Models

### 11.5 Fasa 5: UI Development (Minggu 12-14)

- [ ] Create triage form dan result views
- [ ] Create EMR summary panel
- [ ] Create diagnosis suggestion cards
- [ ] Create drug alert modals
- [ ] Create red flag alert banners
- [ ] Create feedback forms
- [ ] Integrate dengan EMR interface

### 11.6 Fasa 6: Governance Dashboard (Minggu 15-16)

- [ ] Create performance metrics dashboard
- [ ] Create model version management
- [ ] Create incident reporting system
- [ ] Create audit log viewer
- [ ] Create analytics reports

### 11.7 Fasa 7: Testing & Validation (Minggu 17-19)

- [ ] Unit tests untuk all services
- [ ] Feature tests untuk all workflows
- [ ] Integration tests dengan AI engine
- [ ] AI model validation dengan clinical experts
- [ ] Security testing
- [ ] Performance testing
- [ ] UAT dengan doctors dan nurses

### 11.8 Fasa 8: Deployment & Training (Minggu 20)

- [ ] Deploy AI engine ke production server
- [ ] Deploy Laravel updates
- [ ] Clinical staff training
- [ ] Admin training (governance)
- [ ] Soft launch dengan limited users
- [ ] Monitor closely
- [ ] Full rollout

---

## 12. Kriteria Kejayaan

### 12.1 Clinical Metrics

- **Triage Accuracy**: > 90% agreement dengan senior clinician
- **Red Flag Sensitivity**: 100% (zero missed critical cases)
- **Drug Interaction Detection**: > 99% untuk severe interactions
- **Diagnosis Suggestion Utility**: > 80% rated as "helpful" oleh doctors

### 12.2 Operational Metrics

- **Triage Time Reduction**: 30% faster than manual
- **Clinician Adoption**: > 80% using AI features regularly
- **Override Rate**: < 20% (indicates AI accuracy)
- **Critical Alert Response Time**: < 5 minutes average

### 12.3 Technical Metrics

- **AI Engine Uptime**: > 99.5%
- **Response Time**: < 5 saat untuk all predictions
- **Error Rate**: < 1% API errors
- **Model Performance**: Accuracy, Precision, Recall > 85%

### 12.4 Safety Metrics

- **Zero Patient Harm**: No harm attributable to AI recommendations
- **Incident Response**: All incidents resolved within SLA
- **Audit Compliance**: 100% actions logged

---

## 13. Risks & Mitigation

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| AI gives wrong recommendation | Medium | Critical | Human-in-the-loop mandatory, confidence thresholds, escalation |
| Clinicians over-rely on AI | Medium | High | Training, clear disclaimers, regular audits |
| Model bias against demographics | Low | High | Bias monitoring, diverse training data, regular testing |
| AI engine downtime | Low | Medium | Fallback to manual mode, health monitoring, auto-restart |
| Data privacy breach | Low | Critical | On-premise processing, encryption, access controls |
| Resistance from clinicians | Medium | Medium | Gradual rollout, training, demonstrate value |
| Knowledge base outdated | Medium | Medium | Version control, update notifications, review process |
| Red flag missed | Low | Critical | Multiple detection layers, high sensitivity tuning |
| Integration issues dengan EMR | Medium | Medium | Thorough testing, API versioning, fallback modes |
| Regulatory concerns | Low | High | Clear disclaimers, audit trails, human oversight |

---

## 14. Dependencies

### 14.1 External Packages (Laravel)

- [x] **guzzlehttp/guzzle**: ^7.0 - HTTP client untuk AI Engine communication
- [x] **predis/predis**: ^2.0 - Redis client untuk caching
- [x] **laravel/horizon**: ^5.0 - Queue monitoring

### 14.2 Python AI Engine Dependencies

- [x] **fastapi**: ^0.100 - API framework
- [x] **scikit-learn**: ^1.3 - ML algorithms
- [x] **xgboost**: ^2.0 - Gradient boosting
- [x] **shap**: ^0.44 - Explainability
- [x] **pandas**: ^2.0 - Data manipulation
- [x] **numpy**: ^1.24 - Numerical computing
- [x] **pydantic**: ^2.0 - Data validation

### 14.3 Related Features/Modules

**Bergantung Kepada:**
- Modul EMR (patient data, encounters)
- Modul Pendaftaran (patient demographics)
- Modul Farmasi (drug data)
- Modul Tetapan & Keselamatan (authentication, audit)

**Memberi Impak Kepada:**
- Semua clinical workflows (triage, consultation)
- Preskripsi workflow (drug checking)
- Queue management (prioritization)

### 14.4 Third-Party Data Sources

- [x] **Drug Database**: MIMS Malaysia atau equivalent
- [x] **Clinical Guidelines**: MOH Malaysia, WHO
- [x] **ICD Codes**: ICD-10-CM/ICD-11

---

## 15. Acceptance Criteria

### 15.1 Functional Acceptance

- [x] AI triage provides severity score dengan 5 levels
- [x] Red flags are detected dan trigger prominent alerts
- [x] EMR summary is generated automatically
- [x] Diagnosis suggestions include confidence scores dan reasoning
- [x] Drug interactions are checked untuk all prescription combinations
- [x] Severe drug interactions block prescription until override
- [x] All AI recommendations require human review before action
- [x] Override requires justification
- [x] Feedback can be submitted untuk all predictions
- [x] Low confidence predictions are escalated
- [x] All AI interactions are logged untuk audit
- [x] Governance dashboard shows performance metrics

### 15.2 Technical Acceptance

- [x] AI Engine responds within performance targets
- [x] All data processed on-premise (PDPA compliance)
- [x] Encryption implemented untuk sensitive data
- [x] All feature tests lulus
- [x] Integration tests lulus
- [x] No N+1 query problems
- [x] Route cache cleared

### 15.3 Quality Acceptance

- [x] AI model accuracy meets thresholds
- [x] No bias detected across demographics
- [x] UI/UX reviewed oleh clinical staff
- [x] Responsive design berfungsi
- [x] Accessibility considerations addressed

### 15.4 Documentation Acceptance

- [x] PRD complete
- [x] AI model documentation
- [x] User guide untuk clinicians
- [x] Admin guide untuk governance

### 15.5 Ethical Acceptance

- [x] Clear disclaimers bahawa AI adalah decision support, bukan decision maker
- [x] Transparency dalam reasoning (explainable AI)
- [x] Human always in control
- [x] Incident reporting mechanism available
- [x] Regular performance reviews scheduled

---

## 16. Lampiran

### 16.1 Manchester Triage System (MTS) Levels

| Level | Nama | Warna | Masa Sasaran | Contoh Keadaan |
|-------|------|-------|--------------|----------------|
| 1 | Kecemasan | Merah | Segera (0 min) | Cardiac arrest, severe respiratory distress |
| 2 | Segera | Oren | 10 minit | Chest pain, severe pain, high fever with rash |
| 3 | Separa Segera | Kuning | 30 minit | Moderate pain, fever, vomiting |
| 4 | Standard | Hijau | 60 minit | Minor injury, mild symptoms |
| 5 | Tidak Segera | Biru | 120 minit | Chronic complaints, minor ailments |

### 16.2 Red Flag Examples

| Category | Red Flags |
|----------|-----------|
| Cardiovascular | Chest pain, palpitations dengan syncope, severe hypertension |
| Respiratory | Severe dyspnea, stridor, cyanosis, SpO2 < 90% |
| Neurological | Stroke symptoms (FAST), sudden severe headache, altered consciousness |
| Abdominal | Rigid abdomen, significant GI bleeding |
| Pediatric | Limp child, non-blanching rash, severe dehydration |
| Obstetric | Heavy vaginal bleeding, severe pre-eclampsia signs |
| Mental Health | Active suicidal ideation, severe psychosis |

### 16.3 Drug Interaction Severity Classification

| Severity | Action | Example |
|----------|--------|---------|
| Severe | Block prescription, require override | Warfarin + NSAIDs |
| Moderate | Warning, allow proceed | ACE-I + Potassium supplements |
| Mild | Information only | Metformin + alcohol |

### 16.4 AI Ethics Principles Applied

1. **Beneficence**: AI designed to improve patient outcomes
2. **Non-maleficence**: Human oversight prevents AI harm
3. **Autonomy**: Patients informed about AI use, can opt-out
4. **Justice**: Bias monitoring ensures fair treatment
5. **Transparency**: Explainable AI, no black box
6. **Accountability**: Full audit trail, incident reporting

### 16.5 Change Log

| Tarikh | Penulis | Perubahan |
|--------|---------|-----------|
| 14 Januari 2026 | AI Assistant | PRD awal dicipta |

### 16.6 Approval

- [ ] **Product Owner**: _________________ - _________________
- [ ] **Tech Lead**: _________________ - _________________
- [ ] **Clinical Director**: _________________ - _________________
- [ ] **Pengurus Klinik**: _________________ - _________________
- [ ] **Data Protection Officer**: _________________ - _________________

---

**Status Implementasi**: Belum Bermula
**Tarikh Selesai**: TBD

---

**Catatan**: Dokumen ini adalah living document dan akan dikemaskini mengikut keperluan semasa development. Modul AI memerlukan penglibatan pakar klinikal untuk validation dan testing. Semua AI recommendations adalah decision support sahaja dan bukan diagnosis muktamad.

---

## Disclaimer

**PENTING**: Sistem AI ini adalah alat sokongan keputusan klinikal (Clinical Decision Support System) dan BUKAN pengganti kepada penilaian klinikal oleh profesional kesihatan yang berkelayakan. Semua cadangan AI mesti disemak dan disahkan oleh doktor atau jururawat sebelum sebarang tindakan klinikal diambil. Klinik dan pengguna bertanggungjawab sepenuhnya terhadap keputusan klinikal akhir.