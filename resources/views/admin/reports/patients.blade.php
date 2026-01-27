@extends('layouts.admin')

@section('title', 'Laporan Pesakit')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Laporan Pesakit</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Pesakit</li>
                </ol>
            </nav>
        </div>
        <div>
            <button class="btn btn-outline-primary" onclick="exportReport('excel')">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </button>
        </div>
    </div>

    <!-- Filters -->
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
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Tapis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle p-3 mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-plus fs-3"></i>
                    </div>
                    <h2 class="mb-0">{{ $data['new_patients'] ?? 0 }}</h2>
                    <p class="text-muted mb-0">Pesakit Baru Didaftar</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-default h-100">
                <div class="card-body text-center">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle p-3 mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-hospital fs-3"></i>
                    </div>
                    <h2 class="mb-0">{{ $data['total_visits'] ?? 0 }}</h2>
                    <p class="text-muted mb-0">Jumlah Lawatan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Age Distribution -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-people me-2"></i>Taburan Umur</h2>
                </div>
                <div class="card-body">
                    <canvas id="ageChart" height="250"></canvas>
                </div>
                <div class="card-footer">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($data['age_distribution'] ?? [] as $age)
                                <tr>
                                    <td>{{ $age->age_group }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ $age->count }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-lg-6">
            <div class="card card-default mb-4">
                <div class="card-header">
                    <h2><i class="bi bi-gender-ambiguous me-2"></i>Taburan Jantina</h2>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" height="250"></canvas>
                </div>
                <div class="card-footer">
                    <div class="row text-center">
                        @foreach($data['gender_distribution'] ?? [] as $gender)
                        <div class="col">
                            <h4 class="mb-0">{{ $gender->count }}</h4>
                            <small class="text-muted">{{ $gender->gender == 'male' ? 'Lelaki' : ($gender->gender == 'female' ? 'Perempuan' : $gender->gender) }}</small>
                        </div>
                        @endforeach
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
// Age Distribution Chart
const ageData = @json($data['age_distribution'] ?? []);
const ageCtx = document.getElementById('ageChart').getContext('2d');
new Chart(ageCtx, {
    type: 'bar',
    data: {
        labels: ageData.map(a => a.age_group),
        datasets: [{
            label: 'Bilangan',
            data: ageData.map(a => a.count),
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
            ],
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        }
    }
});

// Gender Distribution Chart
const genderData = @json($data['gender_distribution'] ?? []);
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: genderData.map(g => g.gender == 'male' ? 'Lelaki' : (g.gender == 'female' ? 'Perempuan' : g.gender)),
        datasets: [{
            data: genderData.map(g => g.count),
            backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                'rgb(201, 203, 207)',
            ]
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

function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    window.location.href = window.location.pathname + '?' + params.toString();
}
</script>
@endpush
