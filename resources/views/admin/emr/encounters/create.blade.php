@extends('layouts.admin')
@section('title', 'Encounter Baru')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Encounter Baru</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.emr.encounters.index') }}">EMR</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Encounter Baru</span>
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.emr.encounters.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Patient Selection -->
                @if(!$patient)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-account-search me-2"></i>Pilih Pesakit</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Cari Pesakit</label>
                            <select name="patient_id" id="patientSelect" class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">Cari dengan MRN, No. KP, atau Nama...</option>
                            </select>
                            @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                @else
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                @if($patientVisit)
                <input type="hidden" name="patient_visit_id" value="{{ $patientVisit->id }}">
                @endif

                <!-- Patient Info Card -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="mdi mdi-account me-2"></i>Maklumat Pesakit</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" width="120">MRN:</td>
                                        <td><strong>{{ $patient->mrn }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nama:</td>
                                        <td><strong>{{ $patient->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. KP:</td>
                                        <td>{{ $patient->ic_number ?? $patient->passport_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Umur/Jantina:</td>
                                        <td>{{ $patient->formatted_age }} / {{ $patient->gender_label }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" width="120">Telefon:</td>
                                        <td>{{ $patient->phone ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jenis Darah:</td>
                                        <td>{{ $patient->blood_type ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Alahan:</td>
                                        <td class="{{ $patient->allergies ? 'text-danger fw-bold' : '' }}">{{ $patient->allergies ?? 'Tiada' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Penyakit Kronik:</td>
                                        <td>{{ $patient->chronic_diseases ?? 'Tiada' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- SOAP Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-clipboard-text me-2"></i>Nota SOAP</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Aduan Utama (Chief Complaint) <span class="text-danger">*</span></label>
                            <textarea name="chief_complaint" class="form-control @error('chief_complaint') is-invalid @enderror" rows="3" required>{{ old('chief_complaint', $patientVisit->chief_complaint ?? '') }}</textarea>
                            @error('chief_complaint')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan aduan utama pesakit dalam kata-kata pesakit sendiri.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sejarah Penyakit Semasa (HPI)</label>
                            <textarea name="history_present_illness" class="form-control @error('history_present_illness') is-invalid @enderror" rows="4">{{ old('history_present_illness') }}</textarea>
                            @error('history_present_illness')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Subjektif (S)</label>
                                    <textarea name="subjective" class="form-control @error('subjective') is-invalid @enderror" rows="5">{{ old('subjective') }}</textarea>
                                    @error('subjective')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Simptom yang disampaikan pesakit</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Objektif (O)</label>
                                    <textarea name="objective" class="form-control @error('objective') is-invalid @enderror" rows="5">{{ old('objective') }}</textarea>
                                    @error('objective')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Penemuan pemeriksaan fizikal</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Penilaian (A)</label>
                                    <textarea name="assessment" class="form-control @error('assessment') is-invalid @enderror" rows="5">{{ old('assessment') }}</textarea>
                                    @error('assessment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Diagnosis atau masalah yang dikenal pasti</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pelan (P)</label>
                                    <textarea name="plan" class="form-control @error('plan') is-invalid @enderror" rows="5">{{ old('plan') }}</textarea>
                                    @error('plan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Pelan rawatan, ubat, dan arahan</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota Klinikal Tambahan</label>
                            <textarea name="clinical_notes" class="form-control @error('clinical_notes') is-invalid @enderror" rows="3">{{ old('clinical_notes') }}</textarea>
                            @error('clinical_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota Peribadi (Tidak dipaparkan kepada pesakit)</label>
                            <textarea name="private_notes" class="form-control @error('private_notes') is-invalid @enderror" rows="2">{{ old('private_notes') }}</textarea>
                            @error('private_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-heart-pulse me-2"></i>Tanda Vital</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Suhu Badan (°C)</label>
                                    <input type="number" name="vital_signs[temperature]" step="0.1" min="30" max="45" class="form-control" value="{{ old('vital_signs.temperature') }}" placeholder="36.5">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kadar Nadi (bpm)</label>
                                    <input type="number" name="vital_signs[pulse_rate]" min="20" max="250" class="form-control" value="{{ old('vital_signs.pulse_rate') }}" placeholder="72">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Kadar Pernafasan (/min)</label>
                                    <input type="number" name="vital_signs[respiratory_rate]" min="5" max="60" class="form-control" value="{{ old('vital_signs.respiratory_rate') }}" placeholder="16">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tekanan Darah (mmHg)</label>
                                    <div class="input-group">
                                        <input type="number" name="vital_signs[systolic_bp]" min="50" max="300" class="form-control" value="{{ old('vital_signs.systolic_bp') }}" placeholder="120">
                                        <span class="input-group-text">/</span>
                                        <input type="number" name="vital_signs[diastolic_bp]" min="30" max="200" class="form-control" value="{{ old('vital_signs.diastolic_bp') }}" placeholder="80">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">SpO2 (%)</label>
                                    <input type="number" name="vital_signs[spo2]" min="50" max="100" class="form-control" value="{{ old('vital_signs.spo2') }}" placeholder="98">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Glukosa Darah (mmol/L)</label>
                                    <input type="number" name="vital_signs[blood_glucose]" step="0.1" min="1" max="50" class="form-control" value="{{ old('vital_signs.blood_glucose') }}" placeholder="5.5">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Berat Badan (kg)</label>
                                    <input type="number" name="vital_signs[weight]" step="0.1" min="0.5" max="500" class="form-control" value="{{ old('vital_signs.weight') }}" placeholder="70">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ketinggian (cm)</label>
                                    <input type="number" name="vital_signs[height]" step="0.1" min="20" max="300" class="form-control" value="{{ old('vital_signs.height') }}" placeholder="170">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Skor Kesakitan (0-10)</label>
                                    <select name="vital_signs[pain_score]" class="form-select">
                                        <option value="">Pilih...</option>
                                        @for($i = 0; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('vital_signs.pain_score') == $i ? 'selected' : '' }}>{{ $i }} - {{ $i == 0 ? 'Tiada kesakitan' : ($i <= 3 ? 'Ringan' : ($i <= 6 ? 'Sederhana' : 'Teruk')) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Kesakitan</label>
                            <input type="text" name="vital_signs[pain_location]" class="form-control" value="{{ old('vital_signs.pain_location') }}" placeholder="cth: Kepala, Perut, Dada">
                        </div>
                    </div>
                </div>

                <!-- Follow-up & Referral -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-calendar-check me-2"></i>Susulan & Rujukan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tarikh Susulan</label>
                                    <input type="date" name="follow_up_date" class="form-control @error('follow_up_date') is-invalid @enderror" value="{{ old('follow_up_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    @error('follow_up_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Arahan Susulan</label>
                                    <input type="text" name="follow_up_instructions" class="form-control @error('follow_up_instructions') is-invalid @enderror" value="{{ old('follow_up_instructions') }}" placeholder="cth: Jumpa jika simptom tidak berkurang">
                                    @error('follow_up_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="needs_referral" value="1" class="form-check-input" id="needsReferral" {{ old('needs_referral') ? 'checked' : '' }}>
                            <label class="form-check-label" for="needsReferral">Perlu Rujukan</label>
                        </div>

                        <div id="referralFields" style="{{ old('needs_referral') ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kepakaran Rujukan</label>
                                        <input type="text" name="referral_specialty" class="form-control" value="{{ old('referral_specialty') }}" placeholder="cth: Kardiologi, Ortopedik">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nota Rujukan</label>
                                        <textarea name="referral_notes" class="form-control" rows="2">{{ old('referral_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Doctor & Template -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-doctor me-2"></i>Tetapan Encounter</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Doktor <span class="text-danger">*</span></label>
                            <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                <option value="">Pilih Doktor...</option>
                                @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $patientVisit->doctor_id ?? '') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->name ?? $doctor->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Templat Klinikal</label>
                            <select name="template_id" class="form-select">
                                <option value="">Tiada Templat</option>
                                @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save me-2"></i>Simpan Encounter
                            </button>
                            <a href="{{ route('admin.emr.encounters.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>

                @if($patient && !empty($patientHistory))
                <!-- Patient History -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-history me-2"></i>Sejarah Pesakit</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($patientHistory['chronic_conditions']) && $patientHistory['chronic_conditions']->count() > 0)
                        <h6 class="text-danger mb-2">Keadaan Kronik</h6>
                        <ul class="list-unstyled mb-3">
                            @foreach($patientHistory['chronic_conditions'] as $condition)
                            <li><i class="mdi mdi-alert-circle text-danger me-1"></i> {{ $condition->diagnosis_text }}</li>
                            @endforeach
                        </ul>
                        @endif

                        @if(isset($patientHistory['last_vital_signs']) && $patientHistory['last_vital_signs'])
                        <h6 class="mb-2">Tanda Vital Terakhir</h6>
                        <small class="text-muted">{{ $patientHistory['last_vital_signs']->recorded_at->format('d/m/Y H:i') }}</small>
                        <table class="table table-sm mt-2">
                            @if($patientHistory['last_vital_signs']->blood_pressure)
                            <tr>
                                <td>BP:</td>
                                <td><strong>{{ $patientHistory['last_vital_signs']->blood_pressure }}</strong> mmHg</td>
                            </tr>
                            @endif
                            @if($patientHistory['last_vital_signs']->pulse_rate)
                            <tr>
                                <td>Nadi:</td>
                                <td><strong>{{ $patientHistory['last_vital_signs']->pulse_rate }}</strong> bpm</td>
                            </tr>
                            @endif
                            @if($patientHistory['last_vital_signs']->temperature)
                            <tr>
                                <td>Suhu:</td>
                                <td><strong>{{ $patientHistory['last_vital_signs']->temperature }}</strong> °C</td>
                            </tr>
                            @endif
                        </table>
                        @endif

                        @if(isset($patientHistory['encounters']) && $patientHistory['encounters']->count() > 0)
                        <h6 class="mb-2 mt-3">Encounter Terkini</h6>
                        <div class="list-group list-group-flush">
                            @foreach($patientHistory['encounters']->take(3) as $enc)
                            @if($enc && $enc->id)
                            <a href="{{ route('admin.emr.encounters.show', ['encounter' => $enc->id]) }}" class="list-group-item list-group-item-action px-0">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $enc->encounter_date->format('d/m/Y') }}</small>
                                    <span class="badge bg-{{ $enc->status === 'completed' ? 'success' : 'secondary' }}">{{ $enc->status_label }}</span>
                                </div>
                                <div class="text-truncate">{{ $enc->chief_complaint }}</div>
                            </a>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle referral fields
    const needsReferral = document.getElementById('needsReferral');
    const referralFields = document.getElementById('referralFields');

    needsReferral.addEventListener('change', function() {
        referralFields.style.display = this.checked ? 'block' : 'none';
    });

    // Patient search (if needed)
    const patientSelect = document.getElementById('patientSelect');
    if (patientSelect) {
        // You can integrate Select2 or similar library here for AJAX search
    }
});
</script>
@endpush
