@extends('layouts.admin')
@section('title', 'Edit Encounter ' . $encounter->encounter_no)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Edit Encounter</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.emr.encounters.index') }}">EMR</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $encounter->encounter_no }}</span>
            </p>
        </div>
        <div>
            <span class="badge bg-{{ $encounter->status === 'in_progress' ? 'warning' : 'secondary' }} me-2 fs-6">
                {{ $encounter->status_label }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.emr.encounters.update', $encounter) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Patient Info Card -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-account me-2"></i>Maklumat Pesakit</h5>
                        <span>{{ $encounter->patient->mrn }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" width="120">Nama:</td>
                                        <td><strong>{{ $encounter->patient->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. KP:</td>
                                        <td>{{ $encounter->patient->ic_number ?? $encounter->patient->passport_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Umur/Jantina:</td>
                                        <td>{{ $encounter->patient->formatted_age }} / {{ $encounter->patient->gender_label }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted" width="120">Jenis Darah:</td>
                                        <td>{{ $encounter->patient->blood_type ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Alahan:</td>
                                        <td class="{{ $encounter->patient->allergies ? 'text-danger fw-bold' : '' }}">{{ $encounter->patient->allergies ?? 'Tiada' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Penyakit Kronik:</td>
                                        <td>{{ $encounter->patient->chronic_diseases ?? 'Tiada' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SOAP Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-clipboard-text me-2"></i>Nota SOAP</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Aduan Utama (Chief Complaint) <span class="text-danger">*</span></label>
                            <textarea name="chief_complaint" class="form-control @error('chief_complaint') is-invalid @enderror" rows="3" required>{{ old('chief_complaint', $encounter->chief_complaint) }}</textarea>
                            @error('chief_complaint')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sejarah Penyakit Semasa (HPI)</label>
                            <textarea name="history_present_illness" class="form-control @error('history_present_illness') is-invalid @enderror" rows="4">{{ old('history_present_illness', $encounter->history_present_illness) }}</textarea>
                            @error('history_present_illness')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Subjektif (S)</label>
                                    <textarea name="subjective" class="form-control @error('subjective') is-invalid @enderror" rows="6">{{ old('subjective', $encounter->subjective) }}</textarea>
                                    @error('subjective')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Objektif (O)</label>
                                    <textarea name="objective" class="form-control @error('objective') is-invalid @enderror" rows="6">{{ old('objective', $encounter->objective) }}</textarea>
                                    @error('objective')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Penilaian (A)</label>
                                    <textarea name="assessment" class="form-control @error('assessment') is-invalid @enderror" rows="6">{{ old('assessment', $encounter->assessment) }}</textarea>
                                    @error('assessment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pelan (P)</label>
                                    <textarea name="plan" class="form-control @error('plan') is-invalid @enderror" rows="6">{{ old('plan', $encounter->plan) }}</textarea>
                                    @error('plan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota Klinikal Tambahan</label>
                            <textarea name="clinical_notes" class="form-control" rows="3">{{ old('clinical_notes', $encounter->clinical_notes) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota Peribadi (Tidak dipaparkan kepada pesakit)</label>
                            <textarea name="private_notes" class="form-control" rows="2">{{ old('private_notes', $encounter->private_notes) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Vital Signs -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-heart-pulse me-2"></i>Tanda Vital</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addVitalSignModal">
                            <i class="mdi mdi-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="card-body">
                        @if($encounter->vitalSigns->count() > 0)
                        @foreach($encounter->vitalSigns as $vital)
                        <div class="border rounded p-3 mb-3 {{ $vital->hasAbnormalVitals() ? 'border-warning' : '' }}">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">{{ $vital->recorded_at->format('d/m/Y H:i') }}</small>
                                <small class="text-muted">Oleh: {{ $vital->recordedBy->name ?? '-' }}</small>
                            </div>
                            <div class="row">
                                @if($vital->temperature)<div class="col-md-3 col-6 mb-2"><small class="text-muted d-block">Suhu</small><strong>{{ $vital->temperature }}°C</strong></div>@endif
                                @if($vital->blood_pressure)<div class="col-md-3 col-6 mb-2"><small class="text-muted d-block">BP</small><strong>{{ $vital->blood_pressure }}</strong></div>@endif
                                @if($vital->pulse_rate)<div class="col-md-3 col-6 mb-2"><small class="text-muted d-block">Nadi</small><strong>{{ $vital->pulse_rate }} bpm</strong></div>@endif
                                @if($vital->respiratory_rate)<div class="col-md-3 col-6 mb-2"><small class="text-muted d-block">RR</small><strong>{{ $vital->respiratory_rate }}/min</strong></div>@endif
                                @if($vital->spo2)<div class="col-md-3 col-6 mb-2"><small class="text-muted d-block">SpO2</small><strong>{{ $vital->spo2 }}%</strong></div>@endif
                                @if($vital->weight)<div class="col-md-3 col-6 mb-2"><small class="text-muted d-block">Berat</small><strong>{{ $vital->weight }} kg</strong></div>@endif
                                @if($vital->bmi)<div class="col-md-3 col-6 mb-2"><small class="text-muted d-block">BMI</small><strong>{{ $vital->bmi }}</strong></div>@endif
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p class="text-muted mb-0">Tiada tanda vital direkod.</p>
                        @endif
                    </div>
                </div>

                <!-- Diagnoses -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="mdi mdi-stethoscope me-2"></i>Diagnosis</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addDiagnosisModal">
                            <i class="mdi mdi-plus"></i> Tambah
                        </button>
                    </div>
                    <div class="card-body">
                        @if($encounter->diagnoses->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kod ICD-10</th>
                                    <th>Diagnosis</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($encounter->diagnoses as $diagnosis)
                                <tr>
                                    <td>{{ $diagnosis->icd10_code ?? '-' }}</td>
                                    <td>{{ $diagnosis->diagnosis_text }}</td>
                                    <td><span class="badge bg-{{ $diagnosis->type === 'primary' ? 'primary' : 'secondary' }}">{{ $diagnosis->type_label }}</span></td>
                                    <td><span class="badge bg-{{ $diagnosis->status === 'active' ? 'success' : 'secondary' }}">{{ $diagnosis->status_label }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.emr.encounters.diagnoses.destroy', [$encounter, $diagnosis]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Padam diagnosis ini?')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p class="text-muted mb-0">Tiada diagnosis direkod.</p>
                        @endif
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
                                    <input type="date" name="follow_up_date" class="form-control" value="{{ old('follow_up_date', $encounter->follow_up_date?->format('Y-m-d')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Arahan Susulan</label>
                                    <input type="text" name="follow_up_instructions" class="form-control" value="{{ old('follow_up_instructions', $encounter->follow_up_instructions) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="needs_referral" value="1" class="form-check-input" id="needsReferral" {{ old('needs_referral', $encounter->needs_referral) ? 'checked' : '' }}>
                            <label class="form-check-label" for="needsReferral">Perlu Rujukan</label>
                        </div>

                        <div id="referralFields" style="{{ old('needs_referral', $encounter->needs_referral) ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Kepakaran Rujukan</label>
                                        <input type="text" name="referral_specialty" class="form-control" value="{{ old('referral_specialty', $encounter->referral_specialty) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nota Rujukan</label>
                                        <textarea name="referral_notes" class="form-control" rows="2">{{ old('referral_notes', $encounter->referral_notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-cog me-2"></i>Tindakan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Doktor</label>
                            <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                                @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $encounter->doctor_id) == $doctor->id ? 'selected' : '' }}>
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
                                <option value="{{ $template->id }}" {{ old('template_id', $encounter->template_id) == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save me-2"></i>Simpan
                            </button>
                            <a href="{{ route('admin.emr.encounters.show', $encounter) }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>

                        <hr>

                        <form action="{{ route('admin.emr.encounters.complete', $encounter) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Selesaikan encounter ini?')">
                                <i class="mdi mdi-check-circle me-2"></i>Selesaikan Encounter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Patient History -->
                @if(!empty($patientHistory) && isset($patientHistory['chronic_conditions']) && $patientHistory['chronic_conditions']->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-alert-circle me-2 text-danger"></i>Keadaan Kronik</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($patientHistory['chronic_conditions'] as $condition)
                            <li class="mb-1"><i class="mdi mdi-circle-small text-danger"></i> {{ $condition->diagnosis_text }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Add Vital Sign Modal -->
<div class="modal fade" id="addVitalSignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.emr.encounters.vitalSigns.store', $encounter) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rekod Tanda Vital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Suhu (°C)</label>
                                <input type="number" name="temperature" step="0.1" min="30" max="45" class="form-control" placeholder="36.5">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Kadar Nadi (bpm)</label>
                                <input type="number" name="pulse_rate" min="20" max="250" class="form-control" placeholder="72">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Kadar Pernafasan (/min)</label>
                                <input type="number" name="respiratory_rate" min="5" max="60" class="form-control" placeholder="16">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tekanan Darah (mmHg)</label>
                                <div class="input-group">
                                    <input type="number" name="systolic_bp" min="50" max="300" class="form-control" placeholder="120">
                                    <span class="input-group-text">/</span>
                                    <input type="number" name="diastolic_bp" min="30" max="200" class="form-control" placeholder="80">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">SpO2 (%)</label>
                                <input type="number" name="spo2" min="50" max="100" class="form-control" placeholder="98">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Glukosa Darah (mmol/L)</label>
                                <input type="number" name="blood_glucose" step="0.1" min="1" max="50" class="form-control" placeholder="5.5">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Berat Badan (kg)</label>
                                <input type="number" name="weight" step="0.1" min="0.5" max="500" class="form-control" placeholder="70">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ketinggian (cm)</label>
                                <input type="number" name="height" step="0.1" min="20" max="300" class="form-control" placeholder="170">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Skor Kesakitan (0-10)</label>
                                <select name="pain_score" class="form-select">
                                    <option value="">Pilih...</option>
                                    @for($i = 0; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lokasi Kesakitan</label>
                                <input type="text" name="pain_location" class="form-control" placeholder="cth: Kepala, Perut">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nota</label>
                                <input type="text" name="notes" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Diagnosis Modal -->
<div class="modal fade" id="addDiagnosisModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.emr.encounters.diagnoses.store', $encounter) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kod ICD-10</label>
                        <input type="text" name="icd10_code" class="form-control" placeholder="cth: J06.9">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                        <textarea name="diagnosis_text" class="form-control" rows="2" required placeholder="cth: Upper respiratory tract infection"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="primary">Utama</option>
                                    <option value="secondary">Sekunder</option>
                                    <option value="provisional">Sementara</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="active">Aktif</option>
                                    <option value="resolved">Selesai</option>
                                    <option value="chronic">Kronik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nota</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const needsReferral = document.getElementById('needsReferral');
    const referralFields = document.getElementById('referralFields');

    needsReferral.addEventListener('change', function() {
        referralFields.style.display = this.checked ? 'block' : 'none';
    });
});
</script>
@endpush
