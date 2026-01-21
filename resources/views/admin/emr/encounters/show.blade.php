@extends('layouts.admin')
@section('title', 'Encounter ' . $encounter->encounter_no)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>{{ $encounter->encounter_no }}</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.emr.encounters.index') }}">EMR</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $encounter->encounter_no }}</span>
            </p>
        </div>
        <div>
            @if($encounter->status !== 'completed')
            <a href="{{ route('admin.emr.encounters.edit', $encounter) }}" class="btn btn-primary me-2">
                <i class="mdi mdi-pencil"></i> Edit
            </a>
            <form action="{{ route('admin.emr.encounters.complete', $encounter) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Adakah anda pasti untuk menyelesaikan encounter ini?')">
                    <i class="mdi mdi-check"></i> Selesaikan
                </button>
            </form>
            @endif
            <a href="{{ route('admin.emr.encounters.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
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

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Patient Info -->
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="mdi mdi-account me-2"></i>Maklumat Pesakit</h5>
                    <span class="badge bg-light text-primary">{{ $encounter->patient->mrn }}</span>
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
                    <h5 class="mb-0"><i class="mdi mdi-clipboard-text me-2"></i>Nota Klinikal (SOAP)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-primary">Aduan Utama</h6>
                        <p class="mb-0">{{ $encounter->chief_complaint ?? '-' }}</p>
                    </div>

                    @if($encounter->history_present_illness)
                    <div class="mb-4">
                        <h6 class="text-muted">Sejarah Penyakit Semasa (HPI)</h6>
                        <p class="mb-0">{!! nl2br(e($encounter->history_present_illness)) !!}</p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-success">S - Subjektif</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $encounter->subjective ? nl2br(e($encounter->subjective)) : '<span class="text-muted">Tiada data</span>' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-info">O - Objektif</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $encounter->objective ? nl2br(e($encounter->objective)) : '<span class="text-muted">Tiada data</span>' !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-warning">A - Penilaian</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $encounter->assessment ? nl2br(e($encounter->assessment)) : '<span class="text-muted">Tiada data</span>' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-danger">P - Pelan</h6>
                                <div class="border rounded p-3 bg-light">
                                    {!! $encounter->plan ? nl2br(e($encounter->plan)) : '<span class="text-muted">Tiada data</span>' !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($encounter->clinical_notes)
                    <div class="mb-4">
                        <h6 class="text-secondary">Nota Klinikal Tambahan</h6>
                        <p class="mb-0">{!! nl2br(e($encounter->clinical_notes)) !!}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Vital Signs -->
            @if($encounter->vitalSigns->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-heart-pulse me-2"></i>Tanda Vital</h5>
                </div>
                <div class="card-body">
                    @foreach($encounter->vitalSigns as $vital)
                    <div class="border rounded p-3 mb-3 {{ $vital->hasAbnormalVitals() ? 'border-warning' : '' }}">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Direkod: {{ $vital->recorded_at->format('d/m/Y H:i') }}</small>
                            @if($vital->recordedBy)
                            <small class="text-muted">Oleh: {{ $vital->recordedBy->name }}</small>
                            @endif
                        </div>
                        <div class="row">
                            @if($vital->temperature)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Suhu</small>
                                <strong class="{{ $vital->isTemperatureFever() ? 'text-danger' : '' }}">{{ $vital->temperature }}°C</strong>
                            </div>
                            @endif
                            @if($vital->systolic_bp && $vital->diastolic_bp)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Tekanan Darah</small>
                                <strong class="{{ $vital->isHypertensive() ? 'text-danger' : ($vital->isHypotensive() ? 'text-warning' : '') }}">{{ $vital->blood_pressure }} mmHg</strong>
                            </div>
                            @endif
                            @if($vital->pulse_rate)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Kadar Nadi</small>
                                <strong>{{ $vital->pulse_rate }} bpm</strong>
                            </div>
                            @endif
                            @if($vital->respiratory_rate)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Kadar Pernafasan</small>
                                <strong>{{ $vital->respiratory_rate }}/min</strong>
                            </div>
                            @endif
                            @if($vital->spo2)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">SpO2</small>
                                <strong class="{{ $vital->spo2 < 95 ? 'text-danger' : '' }}">{{ $vital->spo2 }}%</strong>
                            </div>
                            @endif
                            @if($vital->weight)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Berat</small>
                                <strong>{{ $vital->weight }} kg</strong>
                            </div>
                            @endif
                            @if($vital->height)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Ketinggian</small>
                                <strong>{{ $vital->height }} cm</strong>
                            </div>
                            @endif
                            @if($vital->bmi)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">BMI</small>
                                <strong>{{ $vital->bmi }} <small>({{ $vital->bmi_category }})</small></strong>
                            </div>
                            @endif
                            @if($vital->blood_glucose)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Glukosa Darah</small>
                                <strong>{{ $vital->blood_glucose }} mmol/L</strong>
                            </div>
                            @endif
                            @if($vital->pain_score !== null)
                            <div class="col-md-3 col-6 mb-2">
                                <small class="text-muted d-block">Skor Kesakitan</small>
                                <strong class="{{ $vital->pain_score > 6 ? 'text-danger' : ($vital->pain_score > 3 ? 'text-warning' : '') }}">{{ $vital->pain_score }}/10</strong>
                                @if($vital->pain_location)
                                <small class="text-muted">({{ $vital->pain_location }})</small>
                                @endif
                            </div>
                            @endif
                        </div>
                        @if($vital->notes)
                        <div class="mt-2 text-muted">
                            <small><strong>Nota:</strong> {{ $vital->notes }}</small>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Diagnoses -->
            @if($encounter->diagnoses->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-stethoscope me-2"></i>Diagnosis</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kod ICD-10</th>
                                <th>Diagnosis</th>
                                <th>Jenis</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($encounter->diagnoses as $index => $diagnosis)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $diagnosis->icd10_code ?? '-' }}</td>
                                <td>{{ $diagnosis->diagnosis_text }}</td>
                                <td>
                                    <span class="badge bg-{{ $diagnosis->type === 'primary' ? 'primary' : 'secondary' }}">
                                        {{ $diagnosis->type_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $diagnosis->status === 'active' ? 'success' : ($diagnosis->status === 'chronic' ? 'warning' : 'secondary') }}">
                                        {{ $diagnosis->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Prescriptions -->
            @if($encounter->prescriptions->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-pill me-2"></i>Preskripsi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Ubat</th>
                                <th>Dos</th>
                                <th>Kekerapan</th>
                                <th>Tempoh</th>
                                <th>Kuantiti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($encounter->prescriptions as $prescription)
                            <tr>
                                <td>{{ $prescription->prescription_no }}</td>
                                <td colspan="5">
                                    @foreach($prescription->items as $item)
                                    <div>{{ $item->medicine_name }} - {{ $item->dosage }} - {{ $item->frequency }} - {{ $item->duration }} hari ({{ $item->quantity }})</div>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Follow-up -->
            @if($encounter->follow_up_date || $encounter->needs_referral)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-calendar-check me-2"></i>Susulan & Rujukan</h5>
                </div>
                <div class="card-body">
                    @if($encounter->follow_up_date)
                    <div class="mb-3">
                        <strong>Tarikh Susulan:</strong> {{ $encounter->follow_up_date->format('d/m/Y') }}
                        @if($encounter->follow_up_instructions)
                        <br><small class="text-muted">{{ $encounter->follow_up_instructions }}</small>
                        @endif
                    </div>
                    @endif

                    @if($encounter->needs_referral)
                    <div class="alert alert-info mb-0">
                        <strong>Perlu Rujukan:</strong> {{ $encounter->referral_specialty }}
                        @if($encounter->referral_notes)
                        <br><small>{{ $encounter->referral_notes }}</small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Encounter Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-information me-2"></i>Maklumat Encounter</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">No. Encounter:</td>
                            <td><strong>{{ $encounter->encounter_no }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tarikh:</td>
                            <td>{{ $encounter->encounter_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Doktor:</td>
                            <td>{{ $encounter->doctor->user->name ?? $encounter->doctor->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                @switch($encounter->status)
                                    @case('draft')
                                        <span class="badge bg-secondary">Draf</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-warning">Sedang Rawatan</span>
                                        @break
                                    @case('pending_review')
                                        <span class="badge bg-info">Menunggu Semakan</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        @if($encounter->started_at)
                        <tr>
                            <td class="text-muted">Dimulakan:</td>
                            <td>{{ $encounter->started_at->format('H:i') }}</td>
                        </tr>
                        @endif
                        @if($encounter->completed_at)
                        <tr>
                            <td class="text-muted">Diselesaikan:</td>
                            <td>{{ $encounter->completed_at->format('H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tempoh:</td>
                            <td>{{ $encounter->duration_minutes }} minit</td>
                        </tr>
                        @endif
                        @if($encounter->completedBy)
                        <tr>
                            <td class="text-muted">Diselesaikan Oleh:</td>
                            <td>{{ $encounter->completedBy->name }}</td>
                        </tr>
                        @endif
                    </table>

                    @if($encounter->patientVisit)
                    <hr>
                    <h6 class="mb-2">Lawatan</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">No. Lawatan:</td>
                            <td>{{ $encounter->patientVisit->visit_no }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. Giliran:</td>
                            <td>{{ $encounter->patientVisit->full_queue_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis:</td>
                            <td>{{ $encounter->patientVisit->visit_type_label }}</td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>

            <!-- Patient History -->
            @if(!empty($patientHistory))
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

                    @if(isset($patientHistory['recent_encounters']) && $patientHistory['recent_encounters']->count() > 0)
                    <h6 class="mb-2">Encounter Sebelumnya</h6>
                    <div class="list-group list-group-flush">
                        @foreach($patientHistory['recent_encounters']->where('id', '!=', $encounter->id)->take(5) as $enc)
                        @if($enc && $enc->id)
                        <a href="{{ route('admin.emr.encounters.show', ['encounter' => $enc->id]) }}" class="list-group-item list-group-item-action px-0">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">{{ $enc->encounter_date->format('d/m/Y') }}</small>
                                <small class="text-muted">{{ $enc->doctor->user->name ?? '' }}</small>
                            </div>
                            <div class="text-truncate">{{ Str::limit($enc->chief_complaint, 40) }}</div>
                        </a>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-cog me-2"></i>Tindakan</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($encounter->status !== 'completed')
                        <a href="{{ route('admin.emr.encounters.edit', $encounter) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil me-2"></i>Edit Encounter
                        </a>
                        @endif
                        <a href="{{ route('admin.patients.show', $encounter->patient) }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-account me-2"></i>Profil Pesakit
                        </a>
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="mdi mdi-printer me-2"></i>Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
