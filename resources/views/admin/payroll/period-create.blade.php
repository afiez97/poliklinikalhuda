@extends('layouts.admin')

@section('title', 'Cipta Tempoh Gaji')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Cipta Tempoh Gaji Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.payroll.index') }}">Gaji</a></li>
                    <li class="breadcrumb-item active">Cipta Tempoh</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.payroll.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card card-default">
                <div class="card-header">
                    <h2 class="mb-0">
                        <i class="bi bi-calendar-plus me-2"></i>
                        Maklumat Tempoh Gaji
                    </h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.payroll.period.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="year" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <select name="year" id="year" class="form-select @error('year') is-invalid @enderror" required>
                                    @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ (old('year', $nextMonth->year) == $y) ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                    @endfor
                                </select>
                                @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="month" class="form-label">Bulan <span class="text-danger">*</span></label>
                                <select name="month" id="month" class="form-select @error('month') is-invalid @enderror" required>
                                    @foreach(['Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember'] as $index => $bulan)
                                    <option value="{{ $index + 1 }}" {{ (old('month', $nextMonth->month) == $index + 1) ? 'selected' : '' }}>
                                        {{ $bulan }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Tarikh Mula <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', $nextMonth->copy()->startOfMonth()->format('Y-m-d')) }}" required>
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Tarikh Akhir <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', $nextMonth->copy()->endOfMonth()->format('Y-m-d')) }}" required>
                                @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="payment_date" class="form-label">Tarikh Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" id="payment_date"
                                   class="form-control @error('payment_date') is-invalid @enderror"
                                   value="{{ old('payment_date', $nextMonth->copy()->endOfMonth()->format('Y-m-d')) }}" required>
                            @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tarikh gaji akan dibayar kepada kakitangan</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Langkah seterusnya:</strong>
                            <ol class="mb-0 mt-2">
                                <li>Cipta tempoh gaji</li>
                                <li>Jana rekod gaji untuk semua kakitangan aktif</li>
                                <li>Semak dan kemaskini rekod individu jika perlu</li>
                                <li>Muktamadkan gaji untuk kelulusan</li>
                                <li>Tandakan sebagai dibayar selepas pembayaran</li>
                            </ol>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-plus-circle me-1"></i> Cipta Tempoh Gaji
                            </button>
                            <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-update dates when month/year changes
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const paymentDate = document.getElementById('payment_date');

    function updateDates() {
        const year = yearSelect.value;
        const month = monthSelect.value.padStart(2, '0');
        const lastDay = new Date(year, monthSelect.value, 0).getDate();

        startDate.value = `${year}-${month}-01`;
        endDate.value = `${year}-${month}-${lastDay.toString().padStart(2, '0')}`;
        paymentDate.value = endDate.value;
    }

    yearSelect.addEventListener('change', updateDates);
    monthSelect.addEventListener('change', updateDates);
});
</script>
@endpush
