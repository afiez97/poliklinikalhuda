@extends('layouts.admin')
@section('title', 'Butiran Dispensing: ' . $record->dispensing_no)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>{{ $record->dispensing_no }}</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.pharmacy.dispensing.index') }}">Dispensing</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $record->dispensing_no }}</span>
            </p>
        </div>
        <div>
            @if($record->status === 'dispensed')
            <a href="{{ route('admin.pharmacy.dispensing.print', $record) }}" class="btn btn-outline-primary" target="_blank">
                <i class="mdi mdi-printer me-1"></i> Cetak
            </a>
            @endif
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
        <div class="col-lg-8">
            <!-- Patient & Prescription Info -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="mdi mdi-account me-2"></i>Maklumat Pesakit</h5>
                    <span class="badge bg-{{ $record->status === 'dispensed' ? 'success' : ($record->status === 'pending' ? 'warning' : 'secondary') }} fs-6">
                        {{ $record->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="120">MRN:</td>
                                    <td><strong>{{ $record->patient->mrn ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama:</td>
                                    <td><strong>{{ $record->patient->name ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Umur/Jantina:</td>
                                    <td>{{ $record->patient->formatted_age ?? '-' }} / {{ $record->patient->gender_label ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="120">Tarikh:</td>
                                    <td>{{ $record->dispensed_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($record->prescription)
                                <tr>
                                    <td class="text-muted">Doktor:</td>
                                    <td>{{ $record->prescription->doctor->user->name ?? '-' }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Didispens Oleh:</td>
                                    <td>{{ $record->dispensedBy->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dispensing Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-pill me-2"></i>Senarai Ubat</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ubat</th>
                                    <th class="text-center">Dipreskripsi</th>
                                    <th class="text-center">Didispens</th>
                                    <th class="text-end">Harga Unit</th>
                                    <th class="text-end">Jumlah</th>
                                    @if(in_array($record->status, ['pending', 'partially_dispensed']))
                                    <th class="text-end">Tindakan</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($record->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->medicine->name ?? '-' }}</strong>
                                        @if($item->medicine->strength)
                                        <br><small class="text-muted">{{ $item->medicine->strength }}</small>
                                        @endif
                                        @if($item->dosage_instructions)
                                        <br><small class="text-info"><i class="mdi mdi-information"></i> {{ $item->dosage_instructions }}</small>
                                        @endif
                                        @if($item->batch_no)
                                        <br><small class="text-muted">Batch: {{ $item->batch_no }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity_prescribed }} {{ $item->medicine->unit ?? '' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $item->quantity_dispensed >= $item->quantity_prescribed ? 'success' : ($item->quantity_dispensed > 0 ? 'warning' : 'secondary') }}">
                                            {{ $item->quantity_dispensed }}
                                        </span>
                                    </td>
                                    <td class="text-end">RM {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">RM {{ number_format($item->total_price, 2) }}</td>
                                    @if(in_array($record->status, ['pending', 'partially_dispensed']))
                                    <td class="text-end">
                                        @if($item->quantity_dispensed < $item->quantity_prescribed)
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#dispenseModal{{ $item->id }}">
                                            <i class="mdi mdi-pill"></i> Dispens
                                        </button>
                                        @else
                                        <span class="badge bg-success"><i class="mdi mdi-check"></i> Selesai</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="{{ in_array($record->status, ['pending', 'partially_dispensed']) ? 4 : 4 }}" class="text-end">Jumlah Keseluruhan:</th>
                                    <th class="text-end">RM {{ number_format($record->total_amount, 2) }}</th>
                                    @if(in_array($record->status, ['pending', 'partially_dispensed']))
                                    <th></th>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($record->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-note-text me-2"></i>Nota</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $record->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Status & Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-cog me-2"></i>Tindakan</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(in_array($record->status, ['pending', 'partially_dispensed']))
                        @php
                        $allDispensed = $record->items->every(fn($item) => $item->quantity_dispensed >= $item->quantity_prescribed);
                        @endphp
                        @if($allDispensed)
                        <form action="{{ route('admin.pharmacy.dispensing.complete', $record) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="mdi mdi-check-circle me-2"></i>Selesaikan Dispensing
                            </button>
                        </form>
                        @endif

                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="mdi mdi-close-circle me-2"></i>Batalkan
                        </button>
                        @endif

                        @if($record->status === 'dispensed')
                        <a href="{{ route('admin.pharmacy.dispensing.print', $record) }}" class="btn btn-primary" target="_blank">
                            <i class="mdi mdi-printer me-2"></i>Cetak Resit
                        </a>
                        @endif

                        <a href="{{ route('admin.pharmacy.dispensing.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="mdi mdi-chart-pie me-2"></i>Ringkasan</h5>
                </div>
                <div class="card-body">
                    @php
                    $totalPrescribed = $record->items->sum('quantity_prescribed');
                    $totalDispensed = $record->items->sum('quantity_dispensed');
                    $progressPercent = $totalPrescribed > 0 ? ($totalDispensed / $totalPrescribed) * 100 : 0;
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Progress Dispensing</span>
                            <span>{{ number_format($progressPercent, 0) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $progressPercent >= 100 ? 'success' : 'primary' }}" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Jumlah Item:</span>
                        <strong>{{ $record->items->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Dipreskripsi:</span>
                        <strong>{{ $totalPrescribed }} unit</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Didispens:</span>
                        <strong>{{ $totalDispensed }} unit</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Jumlah Bayaran:</span>
                        <strong class="text-primary fs-5">RM {{ number_format($record->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dispense Modals -->
@foreach($record->items as $item)
@if($item->quantity_dispensed < $item->quantity_prescribed)
<div class="modal fade" id="dispenseModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pharmacy.dispensing.dispenseItem', $record) }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Dispens: {{ $item->medicine->name ?? '-' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Stok Semasa:</strong> {{ $item->medicine->stock_quantity ?? 0 }} {{ $item->medicine->unit ?? 'unit' }}
                    </div>

                    <p class="mb-2">
                        <strong>Dipreskripsi:</strong> {{ $item->quantity_prescribed }} {{ $item->medicine->unit ?? '' }}<br>
                        <strong>Sudah Didispens:</strong> {{ $item->quantity_dispensed }} {{ $item->medicine->unit ?? '' }}<br>
                        <strong>Baki:</strong> {{ $item->quantity_prescribed - $item->quantity_dispensed }} {{ $item->medicine->unit ?? '' }}
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Kuantiti untuk Dispens</label>
                        <input type="number" name="quantity" class="form-control" min="1" max="{{ min($item->quantity_prescribed - $item->quantity_dispensed, $item->medicine->stock_quantity ?? 0) }}" value="{{ $item->quantity_prescribed - $item->quantity_dispensed }}" required>
                    </div>

                    @if($item->medicine && $item->medicine->batches->count() > 0)
                    <div class="mb-3">
                        <label class="form-label">Batch (Pilihan)</label>
                        <select name="batch_no" class="form-select">
                            <option value="">Pilih Batch</option>
                            @foreach($item->medicine->batches as $batch)
                            <option value="{{ $batch->batch_no }}">
                                {{ $batch->batch_no }} - Luput: {{ $batch->expiry_date->format('d/m/Y') }} ({{ $batch->current_quantity }} tersedia)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Dispens</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

<!-- Cancel Modal -->
@if(in_array($record->status, ['pending', 'partially_dispensed']))
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pharmacy.dispensing.cancel', $record) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Dispensing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert me-2"></i>
                        Ubat yang sudah didispens akan dipulangkan ke stok.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sebab Pembatalan <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Batalkan Dispensing</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
