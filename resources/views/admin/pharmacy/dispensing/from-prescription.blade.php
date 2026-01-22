@extends('layouts.admin')

@section('title', 'Dispens dari Preskripsi - Farmasi')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Dispens dari Preskripsi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.medicines.index') }}">Farmasi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.dispensing.index') }}">Dispensing</a></li>
                    <li class="breadcrumb-item active">Preskripsi #{{ $prescription->prescription_no }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.pharmacy.dispensing.pending') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Maklumat Preskripsi -->
            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-file-medical me-2"></i>Maklumat Preskripsi</h2>
                    <span class="badge bg-info">{{ $prescription->prescription_no }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="40%">No. Preskripsi:</td>
                                    <td><code>{{ $prescription->prescription_no }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tarikh:</td>
                                    <td>{{ $prescription->prescribed_date?->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Doktor:</td>
                                    <td>{{ $prescription->doctor?->user?->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                @if($prescription->encounter)
                                <tr>
                                    <td class="text-muted" width="40%">No. Encounter:</td>
                                    <td><code>{{ $prescription->encounter->encounter_no }}</code></td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'dispensed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$prescription->status] ?? 'secondary' }}">
                                            {{ ucfirst($prescription->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Senarai Ubat -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-capsule me-2"></i>Senarai Ubat</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Ubat</th>
                                    <th>Dos</th>
                                    <th>Kuantiti</th>
                                    <th>Arahan</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prescription->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->medicine?->name ?? 'Ubat tidak dijumpai' }}</strong>
                                        @if($item->medicine?->strength)
                                        <br><small class="text-muted">{{ $item->medicine->strength }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->dosage ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->quantity }} {{ $item->medicine?->unit ?? 'unit' }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $item->instructions ?? $item->medicine?->dosage_instructions ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($item->medicine)
                                            @if($item->medicine->stock_quantity >= $item->quantity)
                                            <span class="badge bg-success">{{ $item->medicine->stock_quantity }}</span>
                                            @elseif($item->medicine->stock_quantity > 0)
                                            <span class="badge bg-warning">{{ $item->medicine->stock_quantity }}</span>
                                            @else
                                            <span class="badge bg-danger">Habis</span>
                                            @endif
                                        @else
                                        <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Tiada item dalam preskripsi ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($prescription->notes)
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-sticky me-2"></i>Nota Preskripsi</h2>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $prescription->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Maklumat Pesakit -->
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-person me-2"></i>Maklumat Pesakit</h2>
                </div>
                <div class="card-body">
                    @if($prescription->patient)
                    <div class="text-center mb-3">
                        <div class="avatar avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <span class="fs-4">{{ strtoupper(substr($prescription->patient->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td class="fw-semibold">{{ $prescription->patient->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. MRN:</td>
                            <td><code>{{ $prescription->patient->mrn }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">No. IC:</td>
                            <td>{{ $prescription->patient->ic_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Umur:</td>
                            <td>{{ $prescription->patient->age ?? '-' }} tahun</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jantina:</td>
                            <td>{{ $prescription->patient->gender == 'male' ? 'Lelaki' : ($prescription->patient->gender == 'female' ? 'Perempuan' : '-') }}</td>
                        </tr>
                    </table>

                    @if($prescription->patient->allergies)
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong><i class="bi bi-exclamation-triangle me-1"></i> Alahan:</strong><br>
                        {{ $prescription->patient->allergies }}
                    </div>
                    @endif
                    @else
                    <p class="text-muted mb-0">Maklumat pesakit tidak tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Tindakan -->
            <div class="card card-default mt-4">
                <div class="card-header">
                    <h2><i class="bi bi-lightning me-2"></i>Tindakan</h2>
                </div>
                <div class="card-body">
                    @php
                        $hasStock = true;
                        foreach($prescription->items as $item) {
                            if ($item->medicine && $item->medicine->stock_quantity < $item->quantity) {
                                $hasStock = false;
                                break;
                            }
                        }
                    @endphp

                    @if(!$hasStock)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Terdapat item dengan stok tidak mencukupi. Sila semak inventori.
                    </div>
                    @endif

                    <form action="{{ route('admin.pharmacy.dispensing.createFromPrescription', $prescription) }}" method="POST">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" {{ $prescription->items->isEmpty() ? 'disabled' : '' }}>
                                <i class="bi bi-check-lg me-1"></i> Mula Dispensing
                            </button>
                            <a href="{{ route('admin.pharmacy.dispensing.pending') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info -->
            <div class="card bg-light mt-4">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-info-circle me-1"></i> Maklumat</h6>
                    <p class="card-text small text-muted mb-0">
                        Klik "Mula Dispensing" untuk mencipta rekod dispensing dari preskripsi ini.
                        Anda boleh mendispens setiap item secara berasingan selepas rekod dicipta.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
