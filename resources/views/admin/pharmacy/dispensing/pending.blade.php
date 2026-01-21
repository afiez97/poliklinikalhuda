@extends('layouts.admin')
@section('title', 'Dispensing Menunggu')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Dispensing Menunggu</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.pharmacy.dispensing.index') }}">Dispensing</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Menunggu</span>
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="mdi mdi-clock-outline me-2 text-warning"></i>
                {{ $records->count() }} Dispensing Menunggu
            </h5>
        </div>
        <div class="card-body">
            @if($records->count() > 0)
            <div class="row">
                @foreach($records as $record)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-warning">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <strong>{{ $record->dispensing_no }}</strong>
                            <small>{{ $record->dispensed_at->format('H:i') }}</small>
                        </div>
                        <div class="card-body">
                            <h6 class="mb-1">{{ $record->patient->name ?? '-' }}</h6>
                            <small class="text-muted">{{ $record->patient->mrn ?? '-' }}</small>

                            <hr class="my-2">

                            <p class="mb-1"><strong>{{ $record->items->count() }} item ubat:</strong></p>
                            <ul class="list-unstyled small mb-0">
                                @foreach($record->items->take(3) as $item)
                                <li>
                                    <i class="mdi mdi-pill text-primary me-1"></i>
                                    {{ Str::limit($item->medicine->name ?? '-', 25) }}
                                    <span class="badge bg-secondary ms-1">{{ $item->quantity_prescribed }}</span>
                                </li>
                                @endforeach
                                @if($record->items->count() > 3)
                                <li class="text-muted">...dan {{ $record->items->count() - 3 }} lagi</li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-primary">RM {{ number_format($record->items->sum(fn($i) => $i->quantity_prescribed * $i->unit_price), 2) }}</strong>
                                <a href="{{ route('admin.pharmacy.dispensing.show', $record) }}" class="btn btn-sm btn-primary">
                                    <i class="mdi mdi-pill me-1"></i> Dispens
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="mdi mdi-check-circle-outline fs-1 text-success"></i>
                <h5 class="mt-3">Tiada Dispensing Menunggu</h5>
                <p class="text-muted">Semua preskripsi telah didispens.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
