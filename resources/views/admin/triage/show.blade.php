@extends('layouts.admin')

@section('title', 'Hasil Triage - ' . $triage->patient->name)

@php
    $severityInfo = $severityLevels[$triage->final_severity] ?? $severityLevels['standard'];
    $originalInfo = $severityLevels[$triage->severity_level] ?? $severityLevels['standard'];
@endphp

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1><i class="bi bi-heart-pulse me-2"></i>Hasil Penilaian Triage</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.triage.index') }}">AI Triage</a></li>
                    <li class="breadcrumb-item active">Hasil</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if($triage->status !== 'completed')
            <form method="POST" action="{{ route('admin.triage.complete', $triage) }}" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-success" onclick="return confirm('Tandakan sebagai selesai?')">
                    <i class="bi bi-check-circle me-1"></i> Selesai
                </button>
            </form>
            @endif
            <a href="{{ route('admin.triage.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Red Flag Alert -->
    @if($triage->hasRedFlags())
    <div class="alert alert-danger d-flex align-items-center mb-4">
        <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
        <div>
            <h5 class="alert-heading mb-1">Red Flags Dikesan!</h5>
            <p class="mb-0">
                @foreach($triage->red_flags_detected as $flag)
                <strong>{{ $flag['name'] }}</strong>{{ !$loop->last ? ' • ' : '' }}
                @endforeach
            </p>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Severity Result -->
            <div class="card card-default mb-4" style="border-left: 5px solid {{ $severityInfo['bg_color'] }};">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center border-end">
                            <h6 class="text-muted mb-2">Tahap Keterukan</h6>
                            <span class="badge fs-4 p-3 bg-{{ $severityInfo['color'] }}">
                                {{ $severityInfo['label'] }}
                            </span>
                            @if($triage->override_level)
                            <div class="mt-2">
                                <small class="text-muted">
                                    AI: <span class="badge bg-{{ $originalInfo['color'] }} bg-opacity-50">{{ $originalInfo['label'] }}</span>
                                </small>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-center border-end">
                            <h6 class="text-muted mb-2">Skor Keterukan</h6>
                            <div class="position-relative d-inline-block">
                                <svg width="100" height="100" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#e9ecef" stroke-width="8"/>
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="{{ $severityInfo['bg_color'] }}"
                                            stroke-width="8" stroke-linecap="round"
                                            stroke-dasharray="{{ 283 * $triage->severity_score / 100 }} 283"
                                            transform="rotate(-90 50 50)"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h3 class="mb-0">{{ $triage->severity_score }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <h6 class="text-muted mb-2">Keyakinan AI</h6>
                            <h2 class="mb-1">{{ $triage->ai_confidence }}%</h2>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $triage->ai_confidence >= 70 ? 'success' : ($triage->ai_confidence >= 50 ? 'warning' : 'danger') }}"
                                     style="width: {{ $triage->ai_confidence }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-{{ $severityInfo['color'] }} bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-arrow-right-circle me-2"></i>
                        <strong>Tindakan:</strong>
                        <span class="ms-2">{{ $severityInfo['action'] }}</span>
                        @if($severityInfo['max_wait'] > 0)
                        <span class="badge bg-secondary ms-2">Max tunggu: {{ $severityInfo['max_wait'] }} minit</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- AI Reasoning -->
            @if($triage->ai_reasoning)
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0"><i class="bi bi-robot me-2"></i>Penjelasan AI</h2>
                </div>
                <div class="card-body">
                    @if(isset($triage->ai_reasoning['reasons']))
                    <h6>Faktor Penilaian:</h6>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($triage->ai_reasoning['reasons'] as $reason)
                        <li class="list-group-item d-flex align-items-center">
                            <span class="badge bg-{{ $reason['weight'] == 'high' ? 'danger' : ($reason['weight'] == 'medium' ? 'warning' : 'secondary') }} me-2">
                                {{ $reason['weight'] == 'high' ? 'Tinggi' : ($reason['weight'] == 'medium' ? 'Sederhana' : 'Rendah') }}
                            </span>
                            <div>
                                <strong>{{ $reason['factor'] }}:</strong>
                                {{ $reason['description'] }}
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if(isset($triage->ai_reasoning['score_breakdown']))
                    <h6>Pecahan Skor:</h6>
                    <div class="row text-center">
                        @foreach($triage->ai_reasoning['score_breakdown'] as $component => $value)
                        <div class="col">
                            <div class="p-2 border rounded">
                                <h5 class="mb-0">{{ $value }}</h5>
                                <small class="text-muted">{{ ucfirst($component) }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Differential Diagnoses -->
            @if($triage->differential_diagnoses && count($triage->differential_diagnoses) > 0)
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Cadangan Diagnosis (AI)</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kod ICD</th>
                                    <th>Diagnosis</th>
                                    <th class="text-center">Keyakinan</th>
                                    <th>Bukti Sokongan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($triage->differential_diagnoses as $diagnosis)
                                <tr>
                                    <td><code>{{ $diagnosis['code'] ?? '-' }}</code></td>
                                    <td><strong>{{ $diagnosis['name'] }}</strong></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-{{ $diagnosis['confidence'] >= 70 ? 'success' : ($diagnosis['confidence'] >= 50 ? 'warning' : 'secondary') }}"
                                                     style="width: {{ $diagnosis['confidence'] }}%"></div>
                                            </div>
                                            <small>{{ $diagnosis['confidence'] }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if(isset($diagnosis['supporting_evidence']))
                                        @foreach($diagnosis['supporting_evidence'] as $evidence)
                                        <span class="badge bg-light text-dark me-1">{{ $evidence }}</span>
                                        @endforeach
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-warning bg-opacity-10">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Cadangan diagnosis ini adalah untuk rujukan sahaja. Diagnosis akhir mesti ditentukan oleh doktor.
                    </small>
                </div>
            </div>
            @endif

            <!-- Symptoms Detail -->
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0"><i class="bi bi-clipboard2-pulse me-2"></i>Simptom Direkodkan</h2>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($triage->symptoms_data ?? [] as $symptom)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $symptom['name'] }}</strong>
                                @if(isset($symptom['duration']) && $symptom['duration'])
                                <small class="text-muted ms-2">({{ $symptom['duration'] }})</small>
                                @endif
                            </div>
                            <span class="badge bg-{{ $symptom['severity'] == 'severe' ? 'danger' : ($symptom['severity'] == 'moderate' ? 'warning' : 'secondary') }}">
                                {{ $symptom['severity'] == 'severe' ? 'Teruk' : ($symptom['severity'] == 'moderate' ? 'Sederhana' : 'Ringan') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Review Section -->
            @if($triage->status === 'pending')
            <div class="card card-default border-primary">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-white mb-0"><i class="bi bi-check2-square me-2"></i>Semakan Klinikal</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.triage.review', $triage) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Tindakan:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="action" id="actionAccept" value="accept" checked>
                                    <label class="form-check-label" for="actionAccept">
                                        <i class="bi bi-check-circle text-success me-1"></i> Terima Cadangan AI
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="action" id="actionOverride" value="override">
                                    <label class="form-check-label" for="actionOverride">
                                        <i class="bi bi-pencil text-warning me-1"></i> Ubah Tahap
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="overrideSection" style="display: none;">
                            <div class="mb-3">
                                <label for="override_level" class="form-label">Tahap Keterukan Baru:</label>
                                <select name="override_level" id="override_level" class="form-select">
                                    @foreach($severityLevels as $key => $level)
                                    <option value="{{ $key }}">{{ $level['label'] }} - {{ $level['description'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="override_reason" class="form-label">Sebab Pengubahan:</label>
                                <textarea name="override_reason" id="override_reason" class="form-control" rows="2"
                                          placeholder="Nyatakan sebab mengubah tahap keterukan..."></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Sahkan Semakan
                        </button>
                    </form>
                </div>
            </div>
            @elseif($triage->status === 'reviewed')
            <div class="alert alert-info">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Disemak oleh {{ $triage->reviewedBy->name ?? 'N/A' }}</strong>
                pada {{ $triage->reviewed_at->format('d/m/Y H:i') }}
                @if($triage->override_level)
                <br><small>Override: {{ $originalInfo['label'] }} → {{ $severityInfo['label'] }}</small>
                <br><small>Sebab: {{ $triage->override_reason }}</small>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Patient Info -->
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Maklumat Pesakit</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto"
                             style="width:80px;height:80px;">
                            <i class="bi bi-person fs-1 text-white"></i>
                        </div>
                        <h5 class="mt-2 mb-0">{{ $triage->patient->name }}</h5>
                        <small class="text-muted">MRN: {{ $triage->patient->mrn }}</small>
                    </div>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Umur</td>
                            <td class="text-end">{{ $triage->patient->age ?? '-' }} tahun</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jantina</td>
                            <td class="text-end">{{ $triage->patient->gender == 'male' ? 'Lelaki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alahan</td>
                            <td class="text-end">
                                @if($triage->patient->allergies)
                                <span class="badge bg-danger">{{ $triage->patient->allergies }}</span>
                                @else
                                <span class="text-muted">Tiada</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Vital Signs -->
            @if($triage->vital_signs)
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Tanda Vital</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if(isset($triage->vital_signs['bp_systolic']))
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <small class="text-muted d-block">Tekanan Darah</small>
                                <strong>{{ $triage->vital_signs['bp_systolic'] }}/{{ $triage->vital_signs['bp_diastolic'] ?? '-' }}</strong>
                            </div>
                        </div>
                        @endif
                        @if(isset($triage->vital_signs['heart_rate']))
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <small class="text-muted d-block">Nadi</small>
                                <strong>{{ $triage->vital_signs['heart_rate'] }} bpm</strong>
                            </div>
                        </div>
                        @endif
                        @if(isset($triage->vital_signs['temperature']))
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <small class="text-muted d-block">Suhu</small>
                                <strong>{{ $triage->vital_signs['temperature'] }}°C</strong>
                            </div>
                        </div>
                        @endif
                        @if(isset($triage->vital_signs['spo2']))
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <small class="text-muted d-block">SpO2</small>
                                <strong>{{ $triage->vital_signs['spo2'] }}%</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Pain Score -->
            @if($triage->pain_score !== null)
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Skala Sakit</h2>
                </div>
                <div class="card-body text-center">
                    <h1 class="mb-0 text-{{ $triage->pain_score >= 7 ? 'danger' : ($triage->pain_score >= 4 ? 'warning' : 'success') }}">
                        {{ $triage->pain_score }}/10
                    </h1>
                    @if($triage->pain_location)
                    <small class="text-muted">Lokasi: {{ $triage->pain_location }}</small>
                    @endif
                </div>
            </div>
            @endif

            <!-- Assessment Info -->
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2 class="mb-0">Maklumat Penilaian</h2>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Aduan Utama</td>
                            <td>{{ $triage->chief_complaint }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dinilai oleh</td>
                            <td>{{ $triage->assessedBy->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tarikh/Masa</td>
                            <td>{{ $triage->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                @switch($triage->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu Semakan</span>
                                    @break
                                    @case('reviewed')
                                        <span class="badge bg-info">Disemak</span>
                                    @break
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @break
                                @endswitch
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const actionAccept = document.getElementById('actionAccept');
    const actionOverride = document.getElementById('actionOverride');
    const overrideSection = document.getElementById('overrideSection');

    if (actionAccept && actionOverride && overrideSection) {
        actionAccept.addEventListener('change', function() {
            overrideSection.style.display = 'none';
        });

        actionOverride.addEventListener('change', function() {
            overrideSection.style.display = 'block';
        });
    }
});
</script>
@endpush
