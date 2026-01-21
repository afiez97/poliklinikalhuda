@extends('layouts.admin')
@section('title', 'Senarai Dispensing')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Senarai Dispensing</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Farmasi</span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Dispensing</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.dispensing.pending') }}" class="btn btn-warning me-2">
                <i class="mdi mdi-clock-outline me-1"></i> Menunggu ({{ $pendingCount }})
            </a>
            <a href="{{ route('admin.pharmacy.dispensing.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus me-1"></i> Dispens Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $pendingCount }}</h4>
                            <small>Menunggu Dispens</small>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-clock-outline fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $todayCount }}</h4>
                            <small>Dispens Hari Ini</small>
                        </div>
                        <div class="align-self-center">
                            <i class="mdi mdi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pharmacy.dispensing.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="dispensed" {{ ($filters['status'] ?? '') == 'dispensed' ? 'selected' : '' }}>Selesai</option>
                            <option value="partially_dispensed" {{ ($filters['status'] ?? '') == 'partially_dispensed' ? 'selected' : '' }}>Separa</option>
                            <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" placeholder="Dari" value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" placeholder="Hingga" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-magnify me-1"></i> Cari
                        </button>
                        <a href="{{ route('admin.pharmacy.dispensing.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-refresh me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Records Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Dispens</th>
                            <th>Tarikh/Masa</th>
                            <th>Pesakit</th>
                            <th>Item</th>
                            <th class="text-end">Jumlah</th>
                            <th>Status</th>
                            <th>Didispens Oleh</th>
                            <th class="text-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                        <tr>
                            <td>
                                <a href="{{ route('admin.pharmacy.dispensing.show', $record) }}" class="fw-bold text-primary">
                                    {{ $record->dispensing_no }}
                                </a>
                            </td>
                            <td>
                                {{ $record->dispensed_at->format('d/m/Y') }}
                                <br><small class="text-muted">{{ $record->dispensed_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $record->patient->name ?? '-' }}</strong>
                                <br><small class="text-muted">{{ $record->patient->mrn ?? '-' }}</small>
                            </td>
                            <td>{{ $record->items->count() }} item</td>
                            <td class="text-end">
                                <strong>RM {{ number_format($record->total_amount, 2) }}</strong>
                            </td>
                            <td>
                                @switch($record->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                        @break
                                    @case('dispensed')
                                        <span class="badge bg-success">Selesai</span>
                                        @break
                                    @case('partially_dispensed')
                                        <span class="badge bg-info">Separa</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $record->dispensedBy->name ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.pharmacy.dispensing.show', $record) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                @if($record->status === 'dispensed')
                                <a href="{{ route('admin.pharmacy.dispensing.print', $record) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="mdi mdi-printer"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="mdi mdi-pill-off fs-1 text-muted"></i>
                                <p class="text-muted mb-0">Tiada rekod dispens dijumpai.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
