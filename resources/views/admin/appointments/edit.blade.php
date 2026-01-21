@extends('layouts.admin')
@section('title', 'Edit Temujanji')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Edit Temujanji</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.appointments') }}">Temujanji</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>{{ $appointment->appointment_no }}</span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Edit</span>
            </p>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Kemaskini Maklumat Temujanji</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Patient Info (Read Only) -->
                        <div class="mb-4">
                            <label class="form-label">Pesakit</label>
                            <div class="alert alert-secondary mb-0">
                                <strong>{{ $appointment->patient->name }}</strong>
                                <br><small>MRN: {{ $appointment->patient->mrn }} | IC: {{ $appointment->patient->ic_number }}</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Doctor -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Doktor <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                    <option value="">Pilih doktor...</option>
                                    @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doc->id ? 'selected' : '' }}>
                                        {{ $doc->user->name ?? $doc->staff_no }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Appointment Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Temujanji <span class="text-danger">*</span></label>
                                <select name="appointment_type" class="form-select @error('appointment_type') is-invalid @enderror" required>
                                    <option value="">Pilih jenis...</option>
                                    <option value="consultation" {{ old('appointment_type', $appointment->appointment_type) == 'consultation' ? 'selected' : '' }}>Konsultasi</option>
                                    <option value="follow_up" {{ old('appointment_type', $appointment->appointment_type) == 'follow_up' ? 'selected' : '' }}>Susulan</option>
                                    <option value="procedure" {{ old('appointment_type', $appointment->appointment_type) == 'procedure' ? 'selected' : '' }}>Prosedur</option>
                                    <option value="medical_checkup" {{ old('appointment_type', $appointment->appointment_type) == 'medical_checkup' ? 'selected' : '' }}>Pemeriksaan Kesihatan</option>
                                    <option value="vaccination" {{ old('appointment_type', $appointment->appointment_type) == 'vaccination' ? 'selected' : '' }}>Vaksinasi</option>
                                    <option value="other" {{ old('appointment_type', $appointment->appointment_type) == 'other' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                                @error('appointment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Date -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tarikh <span class="text-danger">*</span></label>
                                <input type="date" name="appointment_date" id="appointment_date"
                                       class="form-control @error('appointment_date') is-invalid @enderror"
                                       value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Time -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Masa <span class="text-danger">*</span></label>
                                <select name="start_time" id="start_time" class="form-select @error('start_time') is-invalid @enderror" required>
                                    <option value="">Pilih masa...</option>
                                </select>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted" id="slot-status"></small>
                            </div>

                            <!-- Duration -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tempoh (minit) <span class="text-danger">*</span></label>
                                <select name="duration_minutes" class="form-select @error('duration_minutes') is-invalid @enderror" required>
                                    <option value="15" {{ old('duration_minutes', $appointment->duration_minutes) == 15 ? 'selected' : '' }}>15 minit</option>
                                    <option value="30" {{ old('duration_minutes', $appointment->duration_minutes) == 30 ? 'selected' : '' }}>30 minit</option>
                                    <option value="45" {{ old('duration_minutes', $appointment->duration_minutes) == 45 ? 'selected' : '' }}>45 minit</option>
                                    <option value="60" {{ old('duration_minutes', $appointment->duration_minutes) == 60 ? 'selected' : '' }}>60 minit</option>
                                </select>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label class="form-label">Sebab Lawatan</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="2" placeholder="Nyatakan sebab lawatan...">{{ old('reason', $appointment->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label">Nota Tambahan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" placeholder="Nota untuk staf...">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check me-1"></i> Kemaskini Temujanji
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Current Appointment Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Maklumat Semasa</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th>No. Temujanji</th>
                            <td>{{ $appointment->appointment_no }}</td>
                        </tr>
                        <tr>
                            <th>Tarikh Asal</th>
                            <td>{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Masa Asal</th>
                            <td>{{ $appointment->formatted_time }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @switch($appointment->status)
                                    @case('scheduled')
                                        <span class="badge bg-secondary">Dijadualkan</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-info">Disahkan</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorSelect = document.getElementById('doctor_id');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('start_time');
    const slotStatus = document.getElementById('slot-status');
    const currentTime = '{{ \Carbon\Carbon::parse($appointment->start_time)->format("H:i") }}';

    function loadAvailableSlots() {
        const doctorId = doctorSelect.value;
        const date = dateInput.value;

        if (!doctorId || !date) {
            timeSelect.innerHTML = '<option value="">Pilih doktor dan tarikh dahulu...</option>';
            return;
        }

        slotStatus.textContent = 'Memuatkan slot...';
        timeSelect.disabled = true;

        fetch(`{{ route('admin.appointments.availableSlots') }}?doctor_id=${doctorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '<option value="">Pilih masa...</option>';

                if (data.length === 0) {
                    slotStatus.textContent = 'Tiada slot tersedia';
                    return;
                }

                let availableCount = 0;
                data.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.time;

                    // Mark current time as available (it's this appointment's slot)
                    const isCurrentSlot = slot.time === currentTime && date === '{{ $appointment->appointment_date->format("Y-m-d") }}';
                    const isAvailable = slot.available || isCurrentSlot;

                    option.textContent = slot.time + (isAvailable ? '' : ' (Ditempah)');
                    option.disabled = !isAvailable;
                    option.selected = slot.time === currentTime;
                    if (isAvailable) availableCount++;
                    timeSelect.appendChild(option);
                });

                slotStatus.textContent = availableCount + ' slot tersedia';
                timeSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                slotStatus.textContent = 'Ralat memuatkan slot';
                timeSelect.disabled = false;
            });
    }

    doctorSelect.addEventListener('change', loadAvailableSlots);
    dateInput.addEventListener('change', loadAvailableSlots);

    // Load slots on page load
    loadAvailableSlots();
});
</script>
@endpush
