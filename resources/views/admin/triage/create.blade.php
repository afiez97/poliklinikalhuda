@extends('layouts.admin')

@section('title', 'Triage Baru')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1><i class="bi bi-heart-pulse me-2"></i>Penilaian Triage Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">AI Triage</a></li>
                    <li class="breadcrumb-item active">Penilaian Baru</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.triage.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.triage.store') }}" id="triageForm">
        @csrf

        <div class="row">
            <!-- Left Column - Patient & Symptoms -->
            <div class="col-lg-8">
                <!-- Patient Selection -->
                <div class="card card-default mb-4">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="bi bi-person me-2"></i>Maklumat Pesakit</h2>
                    </div>
                    <div class="card-body">
                        @if($patient)
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        @if($queue)
                        <input type="hidden" name="queue_id" value="{{ $queue->id }}">
                        @endif
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;">
                                    <i class="bi bi-person fs-3 text-white"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h5 class="mb-1">{{ $patient->name }}</h5>
                                <p class="text-muted mb-0">
                                    MRN: <code>{{ $patient->mrn }}</code> |
                                    {{ $patient->age ?? '-' }} tahun |
                                    {{ $patient->gender == 'male' ? 'Lelaki' : 'Perempuan' }}
                                </p>
                            </div>
                            @if($queue)
                            <div class="col-auto">
                                <span class="badge bg-info fs-6">Giliran #{{ $queue->queue_number }}</span>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="mb-3">
                            <label for="patient_search" class="form-label">Cari Pesakit <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_search" class="form-select" required>
                                <option value="">-- Cari pesakit --</option>
                            </select>
                            @error('patient_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Chief Complaint -->
                <div class="card card-default mb-4">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Aduan Utama</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="chief_complaint" class="form-label">Apakah aduan utama pesakit? <span class="text-danger">*</span></label>
                            <textarea name="chief_complaint" id="chief_complaint" class="form-control @error('chief_complaint') is-invalid @enderror"
                                      rows="2" placeholder="Contoh: Demam dan batuk sejak 3 hari" required>{{ old('chief_complaint') }}</textarea>
                            @error('chief_complaint')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Symptom Checker -->
                <div class="card card-default mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0"><i class="bi bi-clipboard2-pulse me-2"></i>Pemeriksaan Simptom</h2>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSymptomModal">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Simptom
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Quick Symptom Buttons -->
                        <div class="mb-4">
                            <label class="form-label">Simptom Biasa (Klik untuk tambah):</label>
                            <div class="d-flex flex-wrap gap-2" id="quickSymptoms">
                                @foreach($symptoms as $symptom)
                                <button type="button" class="btn btn-sm {{ isset($symptom['is_red_flag']) && $symptom['is_red_flag'] ? 'btn-outline-danger' : 'btn-outline-secondary' }} quick-symptom"
                                        data-code="{{ $symptom['code'] }}"
                                        data-name="{{ $symptom['name'] }}"
                                        data-category="{{ $symptom['category'] }}"
                                        data-region="{{ $symptom['body_region'] }}"
                                        data-redflag="{{ isset($symptom['is_red_flag']) && $symptom['is_red_flag'] ? '1' : '0' }}">
                                    {{ $symptom['name'] }}
                                    @if(isset($symptom['is_red_flag']) && $symptom['is_red_flag'])
                                    <i class="bi bi-exclamation-triangle-fill ms-1"></i>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Selected Symptoms List -->
                        <div id="selectedSymptoms">
                            <label class="form-label">Simptom yang dipilih:</label>
                            <div class="list-group" id="symptomsList">
                                <!-- Symptoms will be added here dynamically -->
                            </div>
                            <p class="text-muted small mt-2" id="noSymptomsText">
                                <i class="bi bi-info-circle me-1"></i>
                                Klik simptom di atas atau tambah simptom baru untuk mula
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pain Assessment -->
                <div class="card card-default mb-4">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="bi bi-lightning me-2"></i>Penilaian Sakit</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pain_score" class="form-label">Skala Sakit (0-10)</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="range" name="pain_score" id="pain_score" class="form-range flex-grow-1"
                                           min="0" max="10" value="{{ old('pain_score', 0) }}">
                                    <span class="badge bg-secondary fs-5" id="painScoreDisplay">0</span>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mt-1">
                                    <span>Tiada sakit</span>
                                    <span>Sakit teruk</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pain_location" class="form-label">Lokasi Sakit</label>
                                <input type="text" name="pain_location" id="pain_location" class="form-control"
                                       value="{{ old('pain_location') }}" placeholder="Contoh: Bahagian perut bawah">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card card-default mb-4">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="bi bi-pencil me-2"></i>Catatan Tambahan</h2>
                    </div>
                    <div class="card-body">
                        <textarea name="additional_notes" id="additional_notes" class="form-control"
                                  rows="3" placeholder="Sebarang maklumat tambahan...">{{ old('additional_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column - Vital Signs & Actions -->
            <div class="col-lg-4">
                <!-- Vital Signs -->
                <div class="card card-default mb-4">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="bi bi-activity me-2"></i>Tanda Vital</h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small">Tekanan Darah (Sistolik)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="vital_signs[bp_systolic]" class="form-control"
                                           placeholder="120" min="50" max="300" value="{{ old('vital_signs.bp_systolic') }}">
                                    <span class="input-group-text">mmHg</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Tekanan Darah (Diastolik)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="vital_signs[bp_diastolic]" class="form-control"
                                           placeholder="80" min="30" max="200" value="{{ old('vital_signs.bp_diastolic') }}">
                                    <span class="input-group-text">mmHg</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Kadar Nadi</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="vital_signs[heart_rate]" class="form-control"
                                           placeholder="72" min="30" max="250" value="{{ old('vital_signs.heart_rate') }}">
                                    <span class="input-group-text">bpm</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Suhu</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="vital_signs[temperature]" class="form-control"
                                           placeholder="37.0" step="0.1" min="30" max="45" value="{{ old('vital_signs.temperature') }}">
                                    <span class="input-group-text">°C</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Kadar Pernafasan</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="vital_signs[respiratory_rate]" class="form-control"
                                           placeholder="16" min="5" max="60" value="{{ old('vital_signs.respiratory_rate') }}">
                                    <span class="input-group-text">/min</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">SpO2</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="vital_signs[spo2]" class="form-control"
                                           placeholder="98" min="50" max="100" value="{{ old('vital_signs.spo2') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Severity Levels Reference -->
                <div class="card card-default mb-4">
                    <div class="card-header">
                        <h2 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Tahap Keterukan</h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($severityLevels as $key => $level)
                            <div class="list-group-item d-flex align-items-center">
                                <span class="badge bg-{{ $level['color'] }} me-2" style="width: 80px;">{{ $level['label'] }}</span>
                                <small class="text-muted">{{ $level['description'] }}</small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="bi bi-cpu me-2"></i>Jana Penilaian AI
                    </button>
                </div>

                <div class="alert alert-info mt-3">
                    <small>
                        <i class="bi bi-robot me-1"></i>
                        <strong>AI Triage</strong> akan menganalisis simptom dan tanda vital untuk mencadangkan tahap keterukan. Semua cadangan AI memerlukan semakan oleh kakitangan klinikal.
                    </small>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Add Symptom Modal -->
<div class="modal fade" id="addSymptomModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Simptom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="customSymptomName" class="form-label">Nama Simptom</label>
                    <input type="text" id="customSymptomName" class="form-control" placeholder="Contoh: Sakit telinga">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="customSymptomRegion" class="form-label">Bahagian Badan</label>
                        <select id="customSymptomRegion" class="form-select">
                            @foreach($bodyRegions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="customSymptomSeverity" class="form-label">Keterukan</label>
                        <select id="customSymptomSeverity" class="form-select">
                            <option value="mild">Ringan</option>
                            <option value="moderate">Sederhana</option>
                            <option value="severe">Teruk</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="customSymptomDuration" class="form-label">Tempoh</label>
                    <input type="text" id="customSymptomDuration" class="form-control" placeholder="Contoh: 2 hari">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="addCustomSymptom">Tambah</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let symptoms = [];
    let symptomIndex = 0;

    // Pain score display
    const painScore = document.getElementById('pain_score');
    const painDisplay = document.getElementById('painScoreDisplay');
    painScore.addEventListener('input', function() {
        painDisplay.textContent = this.value;
        if (this.value >= 7) {
            painDisplay.className = 'badge bg-danger fs-5';
        } else if (this.value >= 4) {
            painDisplay.className = 'badge bg-warning fs-5';
        } else {
            painDisplay.className = 'badge bg-secondary fs-5';
        }
    });

    // Quick symptom buttons
    document.querySelectorAll('.quick-symptom').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.dataset.code;
            const name = this.dataset.name;
            const category = this.dataset.category;
            const region = this.dataset.region;
            const isRedFlag = this.dataset.redflag === '1';

            // Check if already added
            if (symptoms.find(s => s.code === code)) {
                alert('Simptom ini sudah ditambah');
                return;
            }

            addSymptom({
                code: code,
                name: name,
                category: category,
                body_region: region,
                severity: 'moderate',
                duration: '',
                is_red_flag: isRedFlag
            });

            this.classList.add('active');
        });
    });

    // Add custom symptom
    document.getElementById('addCustomSymptom').addEventListener('click', function() {
        const name = document.getElementById('customSymptomName').value.trim();
        if (!name) {
            alert('Sila masukkan nama simptom');
            return;
        }

        const code = 'custom_' + Date.now();
        addSymptom({
            code: code,
            name: name,
            category: 'other',
            body_region: document.getElementById('customSymptomRegion').value,
            severity: document.getElementById('customSymptomSeverity').value,
            duration: document.getElementById('customSymptomDuration').value,
            is_red_flag: false
        });

        // Reset modal
        document.getElementById('customSymptomName').value = '';
        document.getElementById('customSymptomDuration').value = '';
        bootstrap.Modal.getInstance(document.getElementById('addSymptomModal')).hide();
    });

    function addSymptom(symptom) {
        symptoms.push(symptom);
        renderSymptoms();
    }

    function removeSymptom(code) {
        symptoms = symptoms.filter(s => s.code !== code);

        // Reset quick symptom button
        const btn = document.querySelector(`.quick-symptom[data-code="${code}"]`);
        if (btn) btn.classList.remove('active');

        renderSymptoms();
    }

    function updateSymptomSeverity(code, severity) {
        const symptom = symptoms.find(s => s.code === code);
        if (symptom) symptom.severity = severity;
    }

    function renderSymptoms() {
        const list = document.getElementById('symptomsList');
        const noText = document.getElementById('noSymptomsText');

        if (symptoms.length === 0) {
            list.innerHTML = '';
            noText.style.display = 'block';
            return;
        }

        noText.style.display = 'none';

        list.innerHTML = symptoms.map((s, i) => `
            <div class="list-group-item ${s.is_red_flag ? 'list-group-item-danger' : ''}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${s.name}</strong>
                        ${s.is_red_flag ? '<span class="badge bg-danger ms-2"><i class="bi bi-exclamation-triangle"></i> Red Flag</span>' : ''}
                        ${s.duration ? '<small class="text-muted ms-2">(' + s.duration + ')</small>' : ''}
                        <input type="hidden" name="symptoms[${i}][code]" value="${s.code}">
                        <input type="hidden" name="symptoms[${i}][name]" value="${s.name}">
                        <input type="hidden" name="symptoms[${i}][category]" value="${s.category}">
                        <input type="hidden" name="symptoms[${i}][body_region]" value="${s.body_region}">
                        <input type="hidden" name="symptoms[${i}][duration]" value="${s.duration}">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" style="width:120px;" onchange="window.updateSeverity('${s.code}', this.value)" name="symptoms[${i}][severity]">
                            <option value="mild" ${s.severity === 'mild' ? 'selected' : ''}>Ringan</option>
                            <option value="moderate" ${s.severity === 'moderate' ? 'selected' : ''}>Sederhana</option>
                            <option value="severe" ${s.severity === 'severe' ? 'selected' : ''}>Teruk</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.removeSymptom('${s.code}')">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Expose functions globally
    window.removeSymptom = removeSymptom;
    window.updateSeverity = updateSymptomSeverity;

    // Form validation
    document.getElementById('triageForm').addEventListener('submit', function(e) {
        if (symptoms.length === 0) {
            e.preventDefault();
            alert('Sila tambah sekurang-kurangnya satu simptom');
            return;
        }
    });
});
</script>
@endpush
