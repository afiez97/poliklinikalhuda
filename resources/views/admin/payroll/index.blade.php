@extends('layouts.admin')
@section('title', 'Pengurusan Gaji')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Gaji</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Gaji</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.payroll.period.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Tempoh Gaji Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Payroll Periods -->
    <div class="row">
        @forelse($periods as $period)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $period->period_name }}</h5>
                    @switch($period->status)
                        @case('draft')
                            <span class="badge bg-secondary">Draf</span>
                            @break
                        @case('processing')
                            <span class="badge bg-warning">Memproses</span>
                            @break
                        @case('finalized')
                            <span class="badge bg-info">Dimuktamadkan</span>
                            @break
                        @case('paid')
                            <span class="badge bg-success">Dibayar</span>
                            @break
                    @endswitch
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Tempoh</small>
                            <p class="mb-0">{{ $period->start_date->format('d/m') }} - {{ $period->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Tarikh Bayaran</small>
                            <p class="mb-0">{{ $period->payment_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Bil. Kakitangan</small>
                            <p class="mb-0 fw-bold">{{ $period->payroll_records_count ?? 0 }}</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Jumlah Bersih</small>
                            <p class="mb-0 fw-bold text-success">RM {{ number_format($period->payroll_records_sum_net_salary ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('admin.payroll.period.show', $period) }}" class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-eye"></i> Lihat
                    </a>
                    @if($period->status === 'draft')
                    <form action="{{ route('admin.payroll.period.generate', $period) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Jana gaji untuk semua kakitangan?')">
                            <i class="mdi mdi-cog"></i> Jana
                        </button>
                    </form>
                    @endif
                    @if($period->status === 'processing')
                    <form action="{{ route('admin.payroll.period.finalize', $period) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Muktamadkan tempoh gaji ini?')">
                            <i class="mdi mdi-check-all"></i> Muktamad
                        </button>
                    </form>
                    @endif
                    @if($period->status === 'finalized')
                    <form action="{{ route('admin.payroll.period.pay', $period) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Tandakan sebagai dibayar?')">
                            <i class="mdi mdi-cash-check"></i> Bayar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-cash-multiple mdi-48px text-muted"></i>
                    <p class="text-muted mb-3">Tiada tempoh gaji dijumpai</p>
                    <a href="{{ route('admin.payroll.period.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Cipta Tempoh Gaji
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $periods->links() }}
    </div>
</div>
@endsection
