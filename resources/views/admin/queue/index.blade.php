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
            <a href="{{ route('admin.queue.kiosk') }}" target="_blank" class="btn btn-outline-success">
                <i class="mdi mdi-tablet"></i> Kiosk
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

    <!-- My Assignment Banner -->
    @if($myAssignment)
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <div>
            <i class="mdi mdi-account-check me-2"></i>
            Anda ditugaskan di: <strong>{{ $myAssignment->counter->display_name }}</strong>
            ({{ $myAssignment->counter->queueType->name }})
        </div>
        <form action="{{ route('admin.queue.leaveCounter') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Keluar dari kaunter ini?')">
                <i class="mdi mdi-logout"></i> Keluar Kaunter
            </button>
        </form>
    </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-mini bg-warning text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $statistics['waiting'] }}</h2>
                    <small>Menunggu</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-primary text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $statistics['serving'] }}</h2>
                    <small>Sedang Dilayan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-success text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $statistics['completed'] }}</h2>
                    <small>Selesai Hari Ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-mini bg-info text-white">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $statistics['avg_wait_time'] ?? 0 }} min</h2>
                    <small>Purata Masa Tunggu</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Queue Type Selection -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Jenis Giliran</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($queueTypes as $queueType)
                        <a href="{{ route('admin.queue.index', ['queue_type_id' => $queueType->id]) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $currentQueueType && $currentQueueType->id === $queueType->id ? 'active' : '' }}">
                            <div>
                                <strong>{{ $queueType->name }}</strong>
                                <br><small class="{{ $currentQueueType && $currentQueueType->id === $queueType->id ? 'text-white-50' : 'text-muted' }}">{{ $queueType->code }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $currentQueueType && $currentQueueType->id === $queueType->id ? 'light text-dark' : 'warning' }}">
                                    {{ $queueType->waiting_count }}
                                </span>
                                @if($queueType->serving_count > 0)
                                <span class="badge bg-{{ $currentQueueType && $currentQueueType->id === $queueType->id ? 'light text-success' : 'success' }}">
                                    {{ $queueType->serving_count }}
                                </span>
                                @endif
                            </div>
                        </a>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            Tiada jenis giliran aktif
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Counters -->
            @if($currentQueueType && $counters->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kaunter</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($counters as $counter)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $counter->code }}</strong> - {{ $counter->name }}
                                @if($counter->assigned_user)
                                <br><small class="text-success">
                                    <i class="mdi mdi-account"></i> {{ $counter->assigned_user->name }}
                                </small>
                                @else
                                <br><small class="text-muted">Tiada tugasan</small>
                                @endif
                            </div>
                            @if(!$myAssignment || $myAssignment->counter_id !== $counter->id)
                            <form action="{{ route('admin.queue.assignCounter', $counter) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-login"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Queue Management -->
        <div class="col-lg-9">
            @if($currentQueueType)
            <!-- Currently Serving -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-voice me-2"></i>
                        Sedang Dilayan - {{ $currentQueueType->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($currentServing->isNotEmpty())
                    <div class="row">
                        @foreach($currentServing as $ticket)
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h3 class="mb-1 text-primary">{{ $ticket->ticket_number }}</h3>
                                            <p class="mb-1">{{ $ticket->patient?->name ?? 'Walk-in' }}</p>
                                            <small class="text-muted">
                                                {{ $ticket->currentCounter?->display_name ?? 'N/A' }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $ticket->status_color }}">
                                            {{ $ticket->status_label }}
                                        </span>
                                    </div>
                                    <div class="mt-3">
                                        @if($ticket->status === 'called')
                                        <form action="{{ route('admin.queue.serve', $ticket) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="mdi mdi-play"></i> Mula Layan
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.queue.recall', $ticket) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="counter_id" value="{{ $ticket->current_counter_id }}">
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="mdi mdi-refresh"></i> Panggil Lagi
                                            </button>
                                        </form>
                                        @endif
                                        @if($ticket->status === 'serving')
                                        <form action="{{ route('admin.queue.complete', $ticket) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="mdi mdi-check"></i> Selesai
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.queue.noShow', $ticket) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tandakan tidak hadir?')">
                                                <i class="mdi mdi-account-off"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-account-clock mdi-48px text-muted"></i>
                        <p class="text-muted mb-0">Tiada pesakit sedang dilayan</p>
                        @if($myAssignment && $waitingList->isNotEmpty())
                        <form action="{{ route('admin.queue.callNext') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="mdi mdi-phone"></i> Panggil Seterusnya
                            </button>
                        </form>
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
                        Senarai Menunggu ({{ $waitingList->count() }})
                    </h5>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#issueTicketModal">
                        <i class="mdi mdi-plus"></i> Keluarkan Tiket
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="100">No. Tiket</th>
                                    <th>Pesakit</th>
                                    <th>Keutamaan</th>
                                    <th>Masa Tunggu</th>
                                    <th>Anggaran</th>
                                    <th class="text-end">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($waitingList as $ticket)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary fs-6">{{ $ticket->ticket_number }}</span>
                                        @if($ticket->status === 'called')
                                        <br><small class="text-primary">Dipanggil</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $ticket->patient?->name ?? 'Walk-in' }}</strong>
                                        @if($ticket->patient)
                                        <br><small class="text-muted">{{ $ticket->patient->mrn }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->priority_color }}">
                                            {{ $ticket->priority_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="mdi mdi-clock-outline me-1"></i>
                                        {{ $ticket->issued_at->diffForHumans(null, true) }}
                                    </td>
                                    <td>
                                        ~{{ $ticket->calculateEstimatedWaitTime() }} min
                                    </td>
                                    <td class="text-end">
                                        @if($myAssignment)
                                        <form action="{{ route('admin.queue.call', $ticket) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="counter_id" value="{{ $myAssignment->counter_id }}">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="mdi mdi-phone"></i> Panggil
                                            </button>
                                        </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#transferModal{{ $ticket->id }}">
                                            <i class="mdi mdi-swap-horizontal"></i>
                                        </button>
                                        <form action="{{ route('admin.queue.cancel', $ticket) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan tiket ini?')">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Transfer Modal -->
                                <div class="modal fade" id="transferModal{{ $ticket->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.queue.transfer', $ticket) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Pindah Tiket {{ $ticket->ticket_number }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Pindah ke</label>
                                                        <select name="to_queue_type_id" class="form-select" required>
                                                            @foreach($queueTypes as $type)
                                                            @if($type->id !== $currentQueueType->id)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Sebab</label>
                                                        <input type="text" name="reason" class="form-control" placeholder="Pilihan">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Pindah</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
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
                    <h4 class="text-muted">Sila pilih jenis giliran</h4>
                    <p class="text-muted">Pilih jenis giliran dari senarai di sebelah kiri untuk mula mengurus</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($currentQueueType)
<!-- Issue Ticket Modal -->
<div class="modal fade" id="issueTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.queue.issueTicket') }}" method="POST">
                @csrf
                <input type="hidden" name="queue_type_id" value="{{ $currentQueueType->id }}">
                <input type="hidden" name="source" value="counter">
                <div class="modal-header">
                    <h5 class="modal-title">Keluarkan Tiket - {{ $currentQueueType->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pesakit (Pilihan)</label>
                        <select name="patient_id" class="form-select">
                            <option value="">-- Walk-in (Tanpa Pesakit) --</option>
                            @php
                                $todayVisits = \App\Models\PatientVisit::with('patient')
                                    ->whereDate('visit_date', today())
                                    ->whereIn('status', ['registered', 'waiting'])
                                    ->orderBy('created_at')
                                    ->get();
                            @endphp
                            @foreach($todayVisits as $visit)
                            <option value="{{ $visit->patient_id }}" data-visit-id="{{ $visit->id }}">
                                {{ $visit->patient->name }} ({{ $visit->patient->mrn }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keutamaan</label>
                        <select name="priority_level" class="form-select">
                            <option value="6">Normal</option>
                            <option value="5">Wanita Mengandung</option>
                            <option value="4">Warga Emas (60+)</option>
                            <option value="3">OKU</option>
                            <option value="2">VIP</option>
                            <option value="1">Kecemasan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sebab Keutamaan</label>
                        <input type="text" name="priority_reason" class="form-control" placeholder="Pilihan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Keluarkan Tiket</button>
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
