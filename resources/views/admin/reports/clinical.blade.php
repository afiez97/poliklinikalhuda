@extends('layouts.admin')

@section('title', 'Dashboard Klinikal')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Dashboard Klinikal</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Klinikal</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.executive') }}" class="btn btn-outline-secondary">
                <i class="bi bi-graph-up me-1"></i> Eksekutif
            </a>
            <a href="{{ route('admin.reports.pharmacy') }}" class="btn btn-outline-primary">
                <i class="bi bi-capsule me-1"></i> Farmasi
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card card-default mb-4">
        <div class="card-body py-3">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Dari Tarikh</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hingga Tarikh</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Tapis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Consultation Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-journal-medical fs-4"></i>
                    </div>
                    <h2 class="mb-0">{{ $data['consultation_stats']['total'] ?? 0 }}</h2>
                    <p class="text-muted mb-0">Jumlah Konsultasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <h2 class="mb-0">{{ $data['consultation_stats']['completed'] ?? 0 }}</h2>
                    <p class="text-muted mb-0">Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-clock fs-4"></i>
                    </div>
                    <h2 class="mb-0">{{ number_format($data['consultation_stats']['avg_duration'] ?? 0, 0) }} min</h2>
                    <p class="text-muted mb-0">Purata Tempoh</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Doctor Performance -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-person-badge me-2"></i>Prestasi Doktor</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Doktor</th>
                                    <th class="text-center">Konsultasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['doctor_performance'] ?? [] as $index => $doctor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $doctor['doctor'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $doctor['encounters'] }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Tiada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescription Stats -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-prescription2 me-2"></i>Statistik Preskripsi</h2>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="mb-1">{{ $data['prescription_stats']['with_prescription'] ?? 0 }}</h3>
                            <small class="text-muted">Dengan Preskripsi</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-1">{{ $data['prescription_stats']['prescription_rate'] ?? 0 }}%</h3>
                            <small class="text-muted">Kadar Preskripsi</small>
                        </div>
                    </div>
                    <hr>
                    <canvas id="prescriptionChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Diagnosis Distribution -->
    <div class="card card-default">
        <div class="card-header">
            <h2><i class="bi bi-clipboard2-pulse me-2"></i>Taburan Diagnosis Teratas</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <canvas id="diagnosisChart" height="300"></canvas>
                </div>
                <div class="col-lg-4">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ICD-10</th>
                                    <th>Diagnosis</th>
                                    <th class="text-end">Bil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['diagnosis_distribution'] ?? [] as $diagnosis)
                                <tr>
                                    <td><code>{{ $diagnosis->icd10_code }}</code></td>
                                    <td>{{ Str::limit($diagnosis->description, 30) }}</td>
                                    <td class="text-end">{{ $diagnosis->count }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tiada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Diagnosis Chart
const diagnosisData = @json($data['diagnosis_distribution'] ?? []);
const diagnosisCtx = document.getElementById('diagnosisChart').getContext('2d');
new Chart(diagnosisCtx, {
    type: 'bar',
    data: {
        labels: diagnosisData.map(d => d.icd10_code),
        datasets: [{
            label: 'Bilangan Kes',
            data: diagnosisData.map(d => d.count),
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)',
                'rgba(199, 199, 199, 0.8)',
                'rgba(83, 102, 255, 0.8)',
                'rgba(255, 99, 255, 0.8)',
                'rgba(99, 255, 132, 0.8)',
            ],
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: { display: false }
        }
    }
});

// Prescription Chart
const prescriptionCtx = document.getElementById('prescriptionChart').getContext('2d');
new Chart(prescriptionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Dengan Preskripsi', 'Tanpa Preskripsi'],
        datasets: [{
            data: [
                {{ $data['prescription_stats']['with_prescription'] ?? 0 }},
                {{ ($data['prescription_stats']['total_encounters'] ?? 0) - ($data['prescription_stats']['with_prescription'] ?? 0) }}
            ],
            backgroundColor: ['rgb(75, 192, 192)', 'rgb(201, 203, 207)']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endpush
