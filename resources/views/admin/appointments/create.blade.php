@extends('layouts.admin')
@section('title', 'Buat Temujanji Baru')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper breadcrumb-contacts">
        <div>
            <h1>Buat Temujanji Baru</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.appointments') }}">Temujanji</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Buat Baru</span>
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
                    <h5 class="mb-0">Maklumat Temujanji</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.appointments.store') }}" method="POST">
                        @csrf

                        <!-- Patient Selection -->
                        <div class="mb-4">
                            <label class="form-label">Pesakit <span class="text-danger">*</span></label>
                            @if($patient)
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $patient->name }}</strong>
                                            <br><small>MRN: {{ $patient->mrn }} | IC: {{ $patient->ic_number }}</small>
                                        </div>
                                        <a href="{{ route('admin.appointments.create') }}" class="btn btn-sm btn-outline-secondary">
                                            Tukar Pesakit
                                        </a>
                                    </div>
                                </div>
                            @else
                                <select name="patient_id" id="patient_id" class="form-select select2 @error('patient_id') is-invalid @enderror" required>
                                    <option value="">Pilih pesakit...</option>
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Taip nama atau IC untuk cari pesakit</small>
                            @endif
                        </div>

                        <div class="row">
                            <!-- Doctor -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Doktor <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                    <option value="">Pilih doktor...</option>
                                    @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
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
                                    <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>Konsultasi</option>
                                    <option value="follow_up" {{ old('appointment_type') == 'follow_up' ? 'selected' : '' }}>Susulan</option>
                                    <option value="procedure" {{ old('appointment_type') == 'procedure' ? 'selected' : '' }}>Prosedur</option>
                                    <option value="medical_checkup" {{ old('appointment_type') == 'medical_checkup' ? 'selected' : '' }}>Pemeriksaan Kesihatan</option>
                                    <option value="vaccination" {{ old('appointment_type') == 'vaccination' ? 'selected' : '' }}>Vaksinasi</option>
                                    <option value="other" {{ old('appointment_type') == 'other' ? 'selected' : '' }}>Lain-lain</option>
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
                                       value="{{ old('appointment_date', request('date', date('Y-m-d'))) }}"
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
                                    <option value="15" {{ old('duration_minutes', 15) == 15 ? 'selected' : '' }}>15 minit</option>
                                    <option value="30" {{ old('duration_minutes') == 30 ? 'selected' : '' }}>30 minit</option>
                                    <option value="45" {{ old('duration_minutes') == 45 ? 'selected' : '' }}>45 minit</option>
                                    <option value="60" {{ old('duration_minutes') == 60 ? 'selected' : '' }}>60 minit</option>
                                </select>
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Priority -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Keutamaan <span class="text-danger">*</span></label>
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Segera</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Booking Source -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sumber Tempahan <span class="text-danger">*</span></label>
                                <select name="booking_source" class="form-select @error('booking_source') is-invalid @enderror" required>
                                    <option value="counter" {{ old('booking_source', 'counter') == 'counter' ? 'selected' : '' }}>Kaunter</option>
                                    <option value="phone" {{ old('booking_source') == 'phone' ? 'selected' : '' }}>Telefon</option>
                                    <option value="online" {{ old('booking_source') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="mobile_app" {{ old('booking_source') == 'mobile_app' ? 'selected' : '' }}>Aplikasi Mudah Alih</option>
                                </select>
                                @error('booking_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Panel Patient -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_panel" value="1" class="form-check-input" id="is_panel" {{ old('is_panel') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_panel">Pesakit Panel/Korporat</label>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label class="form-label">Sebab Lawatan</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="2" placeholder="Nyatakan sebab lawatan...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label">Nota Tambahan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" placeholder="Nota untuk staf...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.appointments') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check me-1"></i> Simpan Temujanji
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Quick Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Panduan</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="mdi mdi-clock-outline text-primary me-2"></i>
                            Waktu operasi: 9:00 - 17:00
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-food text-warning me-2"></i>
                            Waktu rehat: 13:00 - 14:00
                        </li>
                        <li class="mb-2">
                            <i class="mdi mdi-calendar-check text-success me-2"></i>
                            Slot: Setiap 15 minit
                        </li>
                        <li>
                            <i class="mdi mdi-information text-info me-2"></i>
                            Sila pilih doktor dan tarikh untuk melihat slot yang tersedia
                        </li>
                    </ul>
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

    @if(!$patient)
    // Initialize Select2 for patient search
    $('#patient_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Cari pesakit...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("admin.patients.index") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    per_page: 10,
                    format: 'json'
                };
            },
            processResults: function(data) {
                return {
                    results: data.data.map(function(patient) {
                        return {
                            id: patient.id,
                            text: patient.name + ' (' + patient.mrn + ')'
                        };
                    })
                };
            }
        }
    });
    @endif

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
                    option.textContent = slot.time + (slot.available ? '' : ' (Ditempah)');
                    option.disabled = !slot.available;
                    if (slot.available) availableCount++;
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

    // Load slots on page load if doctor and date are pre-filled
    if (doctorSelect.value && dateInput.value) {
        loadAvailableSlots();
    }
});
</script>
@endpush
