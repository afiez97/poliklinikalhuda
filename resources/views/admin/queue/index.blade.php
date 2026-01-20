@extends('layouts.admin')
@section('title', 'Pengurusan Giliran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Pengurusan Giliran</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Giliran</span>
            </p>
        </div>
        <div>
            <a href="{{ route('admin.queue.display') }}" target="_blank" class="btn btn-outline-primary me-2">
                <i class="mdi mdi-monitor"></i> Paparan Awam
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

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-mini bg-warning text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $statistics['total_waiting'] }}</h2>
                    <small>Jumlah Menunggu</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-mini bg-success text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $statistics['total_served'] }}</h2>
                    <small>Selesai Hari Ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-mini bg-info text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ number_format($statistics['avg_wait_time'], 0) }} min</h2>
                    <small>Purata Masa Tunggu</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Counter Selection -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kaunter</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($counters as $counter)
                        <a href="{{ route('admin.queue.index', ['counter_id' => $counter->id]) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $currentCounter && $currentCounter->id === $counter->id ? 'active' : '' }}">
                            <div>
                                <strong>{{ $counter->name }}</strong>
                                <br><small class="{{ $currentCounter && $currentCounter->id === $counter->id ? 'text-white-50' : 'text-muted' }}">{{ $counter->code }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $currentCounter && $currentCounter->id === $counter->id ? 'light text-dark' : 'warning' }}">{{ $counter->waiting_count }}</span>
                                @if($counter->serving_count > 0)
                                <span class="badge bg-{{ $currentCounter && $currentCounter->id === $counter->id ? 'light text-success' : 'success' }}">{{ $counter->serving_count }}</span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            Tiada kaunter aktif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Queue Management -->
        <div class="col-lg-9">
            @if($currentCounter)
            <!-- Currently Serving -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-voice me-2"></i>
                        Sedang Dilayan - {{ $currentCounter->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($currentServing)
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="display-3 fw-bold text-primary">{{ $currentServing->queue_number }}</div>
                            <span class="badge bg-{{ $currentServing->status === 'calling' ? 'warning' : 'success' }}">
                                {{ $currentServing->status === 'calling' ? 'Memanggil' : 'Sedang Dilayan' }}
                            </span>
                        </div>
                        <div class="col-md-5">
                            <h5 class="mb-1">{{ $currentServing->patientVisit?->patient?->name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-1">
                                MRN: {{ $currentServing->patientVisit?->patient?->mrn ?? '-' }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="mdi mdi-clock-outline me-1"></i>
                                Masa tunggu: {{ $currentServing->wait_time_minutes ?? 0 }} minit
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            @if($currentServing->status === 'calling')
                            <form action="{{ route('admin.queue.serve', $currentServing) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-play"></i> Mula Layan
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('admin.queue.complete', $currentServing) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-check"></i> Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-account-clock mdi-48px text-muted"></i>
                        <p class="text-muted mb-0">Tiada pesakit sedang dilayan</p>
                        @if($queue->isNotEmpty())
                        <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#callNextModal">
                            <i class="mdi mdi-phone"></i> Panggil Seterusnya
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Waiting Queue -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-multiple me-2"></i>
                        Senarai Menunggu ({{ $queue->count() }})
                    </h5>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addQueueModal">
                        <i class="mdi mdi-plus"></i> Tambah Giliran
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="100">No. Giliran</th>
                                    <th>Pesakit</th>
                                    <th>Keutamaan</th>
                                    <th>Masa Tunggu</th>
                                    <th class="text-end">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($queue as $entry)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary fs-6">{{ $entry->queue_number }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $entry->patientVisit?->patient?->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $entry->patientVisit?->patient?->mrn ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $priorityColors = [
                                                'emergency' => 'danger',
                                                'urgent' => 'warning',
                                                'pregnant' => 'info',
                                                'disabled' => 'purple',
                                                'elderly' => 'primary',
                                                'normal' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $priorityColors[$entry->priority] ?? 'secondary' }}">
                                            {{ $entry->priority_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ $entry->created_at->diffForHumans(null, true) }}
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.queue.call', $entry) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="counter_number" value="{{ $currentCounter->code }}">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="mdi mdi-phone"></i> Panggil
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.queue.skip', $entry) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Langkau giliran ini?')">
                                                <i class="mdi mdi-skip-next"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.queue.cancel', $entry) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan giliran ini?')">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="mdi mdi-check-circle mdi-48px text-success"></i>
                                        <p class="text-muted mb-0">Tiada giliran dalam barisan</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-counter mdi-72px text-muted"></i>
                    <h4 class="text-muted">Sila pilih kaunter</h4>
                    <p class="text-muted">Pilih kaunter dari senarai di sebelah kiri untuk mula mengurus giliran</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($currentCounter)
<!-- Add Queue Modal -->
<div class="modal fade" id="addQueueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.queue.add') }}" method="POST">
                @csrf
                <input type="hidden" name="queue_counter_id" value="{{ $currentCounter->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Giliran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lawatan Pesakit <span class="text-danger">*</span></label>
                        <select name="patient_visit_id" class="form-select" required>
                            <option value="">-- Pilih Lawatan --</option>
                            @php
                                $todayVisits = \App\Models\PatientVisit::with('patient')
                                    ->whereDate('visit_date', today())
                                    ->whereIn('status', ['registered', 'waiting'])
                                    ->orderBy('created_at')
                                    ->get();
                            @endphp
                            @foreach($todayVisits as $visit)
                            <option value="{{ $visit->id }}">
                                {{ $visit->visit_no }} - {{ $visit->patient->name }} ({{ $visit->patient->mrn }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keutamaan <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select" required>
                            <option value="normal">Biasa</option>
                            <option value="elderly">Warga Emas (60+)</option>
                            <option value="disabled">OKU</option>
                            <option value="pregnant">Wanita Mengandung</option>
                            <option value="urgent">Segera</option>
                            <option value="emergency">Kecemasan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Tambah Giliran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Call Next Modal -->
<div class="modal fade" id="callNextModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.queue.callNext', $currentCounter) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Panggil Giliran Seterusnya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombor Kaunter</label>
                        <input type="text" name="counter_number" class="form-control" value="{{ $currentCounter->code }}" required>
                    </div>
                    <p class="text-muted mb-0">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Giliran seterusnya akan dipanggil dan mula dilayan secara automatik.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Panggil Seterusnya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Auto-refresh every 30 seconds
setTimeout(function() {
    window.location.reload();
}, 30000);
</script>
@endpush
