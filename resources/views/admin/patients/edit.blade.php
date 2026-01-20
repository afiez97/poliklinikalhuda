@extends('layouts.admin')
@section('title', 'Kemaskini Pesakit - ' . $patient->name)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Kemaskini Pesakit</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.patients.index') }}">Pesakit</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.patients.show', $patient) }}">{{ $patient->mrn }}</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Kemaskini</span>
            </p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-account me-2"></i>Maklumat Peribadi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Jenis Pengenalan <span class="text-danger">*</span></label>
                                <select name="id_type" class="form-select" required>
                                    <option value="ic" {{ old('id_type', $patient->id_type) == 'ic' ? 'selected' : '' }}>Kad Pengenalan</option>
                                    <option value="passport" {{ old('id_type', $patient->id_type) == 'passport' ? 'selected' : '' }}>Pasport</option>
                                    <option value="birth_cert" {{ old('id_type', $patient->id_type) == 'birth_cert' ? 'selected' : '' }}>Sijil Lahir</option>
                                    <option value="military" {{ old('id_type', $patient->id_type) == 'military' ? 'selected' : '' }}>Kad Tentera</option>
                                    <option value="police" {{ old('id_type', $patient->id_type) == 'police' ? 'selected' : '' }}>Kad Polis</option>
                                    <option value="other" {{ old('id_type', $patient->id_type) == 'other' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. Kad Pengenalan</label>
                                <input type="text" name="ic_number" class="form-control" value="{{ old('ic_number', $patient->ic_number) }}" placeholder="000000-00-0000">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. Pasport</label>
                                <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $patient->passport_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Penuh <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $patient->name) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tarikh Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Jantina <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Pilih...</option>
                                    <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Lelaki</option>
                                    <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Warganegara</label>
                                <input type="text" name="nationality" class="form-control" value="{{ old('nationality', $patient->nationality) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Bangsa</label>
                                <select name="race" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="Melayu" {{ old('race', $patient->race) == 'Melayu' ? 'selected' : '' }}>Melayu</option>
                                    <option value="Cina" {{ old('race', $patient->race) == 'Cina' ? 'selected' : '' }}>Cina</option>
                                    <option value="India" {{ old('race', $patient->race) == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="Lain-lain" {{ old('race', $patient->race) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Agama</label>
                                <select name="religion" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="Islam" {{ old('religion', $patient->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Buddha" {{ old('religion', $patient->religion) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Hindu" {{ old('religion', $patient->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Kristian" {{ old('religion', $patient->religion) == 'Kristian' ? 'selected' : '' }}>Kristian</option>
                                    <option value="Lain-lain" {{ old('religion', $patient->religion) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status Perkahwinan</label>
                                <select name="marital_status" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="single" {{ old('marital_status', $patient->marital_status) == 'single' ? 'selected' : '' }}>Bujang</option>
                                    <option value="married" {{ old('marital_status', $patient->marital_status) == 'married' ? 'selected' : '' }}>Berkahwin</option>
                                    <option value="divorced" {{ old('marital_status', $patient->marital_status) == 'divorced' ? 'selected' : '' }}>Bercerai</option>
                                    <option value="widowed" {{ old('marital_status', $patient->marital_status) == 'widowed' ? 'selected' : '' }}>Balu/Duda</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="occupation" class="form-control" value="{{ old('occupation', $patient->occupation) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-phone me-2"></i>Maklumat Hubungan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">No. Telefon</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $patient->phone) }}" placeholder="01X-XXXXXXX">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. Telefon Alternatif</label>
                                <input type="tel" name="phone_alt" class="form-control" value="{{ old('phone_alt', $patient->phone_alt) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Emel</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $patient->email) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address', $patient->address) }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Poskod</label>
                                <input type="text" name="postcode" class="form-control" value="{{ old('postcode', $patient->postcode) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bandar</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $patient->city) }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Negeri</label>
                                <select name="state" class="form-select">
                                    <option value="">Pilih...</option>
                                    @foreach(['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'W.P. Kuala Lumpur', 'W.P. Labuan', 'W.P. Putrajaya'] as $state)
                                    <option value="{{ $state }}" {{ old('state', $patient->state) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-alert-circle me-2"></i>Hubungan Kecemasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Nama</label>
                                <input type="text" name="emergency_name" class="form-control" value="{{ old('emergency_name', $patient->emergency_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. Telefon</label>
                                <input type="tel" name="emergency_phone" class="form-control" value="{{ old('emergency_phone', $patient->emergency_phone) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hubungan</label>
                                <select name="emergency_relationship" class="form-select">
                                    <option value="">Pilih...</option>
                                    <option value="Suami/Isteri" {{ old('emergency_relationship', $patient->emergency_relationship) == 'Suami/Isteri' ? 'selected' : '' }}>Suami/Isteri</option>
                                    <option value="Ibu/Bapa" {{ old('emergency_relationship', $patient->emergency_relationship) == 'Ibu/Bapa' ? 'selected' : '' }}>Ibu/Bapa</option>
                                    <option value="Anak" {{ old('emergency_relationship', $patient->emergency_relationship) == 'Anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="Adik-beradik" {{ old('emergency_relationship', $patient->emergency_relationship) == 'Adik-beradik' ? 'selected' : '' }}>Adik-beradik</option>
                                    <option value="Rakan" {{ old('emergency_relationship', $patient->emergency_relationship) == 'Rakan' ? 'selected' : '' }}>Rakan</option>
                                    <option value="Lain-lain" {{ old('emergency_relationship', $patient->emergency_relationship) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-medical-bag me-2"></i>Maklumat Perubatan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Jenis Darah</label>
                                <select name="blood_type" class="form-select">
                                    <option value="">Tidak Diketahui</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                    <option value="{{ $type }}" {{ old('blood_type', $patient->blood_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alahan</label>
                                <textarea name="allergies" class="form-control" rows="2" placeholder="Senaraikan semua alahan yang diketahui...">{{ old('allergies', $patient->allergies) }}</textarea>
                                @if($patient->allergies)
                                <small class="text-danger"><i class="mdi mdi-alert"></i> Sila sahkan maklumat alahan dengan pesakit</small>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">Penyakit Kronik</label>
                                <textarea name="chronic_diseases" class="form-control" rows="2" placeholder="Cth: Kencing manis, Darah tinggi...">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ubat Semasa</label>
                                <textarea name="current_medications" class="form-control" rows="2" placeholder="Senaraikan ubat-ubatan yang sedang diambil...">{{ old('current_medications', $patient->current_medications) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- MRN Card -->
                <div class="card mb-4 bg-primary text-white">
                    <div class="card-body text-center">
                        <h6 class="mb-2">No. Rekod Perubatan (MRN)</h6>
                        <h2 class="mb-0">{{ $patient->mrn }}</h2>
                        <small>Didaftar: {{ $patient->created_at->format('d/m/Y') }}</small>
                    </div>
                </div>

                <!-- Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-account-check me-2"></i>Status Pesakit</h5>
                    </div>
                    <div class="card-body">
                        <select name="status" class="form-select" required>
                            @foreach(\App\Models\Patient::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $patient->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($patient->status === 'inactive')
                        <small class="text-warning mt-2 d-block"><i class="mdi mdi-alert"></i> Pesakit ini tidak aktif</small>
                        @elseif($patient->status === 'deceased')
                        <small class="text-danger mt-2 d-block"><i class="mdi mdi-alert"></i> Pesakit ini telah meninggal dunia</small>
                        @endif
                    </div>
                </div>

                <!-- Panel Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-shield-account me-2"></i>Maklumat Panel</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="has_panel" value="0">
                            <input type="checkbox" name="has_panel" value="1" class="form-check-input" id="hasPanel" {{ old('has_panel', $patient->has_panel) ? 'checked' : '' }}>
                            <label class="form-check-label" for="hasPanel">Pesakit Panel</label>
                        </div>
                        <div id="panelFields" style="{{ old('has_panel', $patient->has_panel) ? '' : 'display: none;' }}">
                            <div class="mb-3">
                                <label class="form-label">Syarikat Panel</label>
                                <input type="text" name="panel_company" class="form-control" value="{{ old('panel_company', $patient->panel_company) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Ahli</label>
                                <input type="text" name="panel_member_id" class="form-control" value="{{ old('panel_member_id', $patient->panel_member_id) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tarikh Tamat</label>
                                <input type="date" name="panel_expiry_date" class="form-control" value="{{ old('panel_expiry_date', $patient->panel_expiry_date?->format('Y-m-d')) }}">
                            </div>
                            @if($patient->has_panel && $patient->panel_expiry_date)
                                @if($patient->panel_expiry_date->isPast())
                                <div class="alert alert-danger py-2">
                                    <small><i class="mdi mdi-alert"></i> Panel telah tamat tempoh!</small>
                                </div>
                                @elseif($patient->panel_expiry_date->diffInDays(now()) <= 30)
                                <div class="alert alert-warning py-2">
                                    <small><i class="mdi mdi-alert"></i> Panel akan tamat dalam {{ $patient->panel_expiry_date->diffInDays(now()) }} hari</small>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- PDPA Information (Read-only) -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-file-document-check me-2"></i>Persetujuan PDPA</h5>
                    </div>
                    <div class="card-body">
                        @if($patient->pdpa_consent)
                        <div class="alert alert-success mb-0">
                            <i class="mdi mdi-check-circle"></i> Pesakit telah memberikan persetujuan PDPA
                            <br>
                            <small>Tarikh: {{ $patient->pdpa_consent_at?->format('d/m/Y H:i') }}</small>
                            <br>
                            <small>Disaksikan oleh: {{ $patient->pdpa_consent_by }}</small>
                        </div>
                        @else
                        <div class="alert alert-warning mb-0">
                            <i class="mdi mdi-alert"></i> Pesakit belum memberikan persetujuan PDPA
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Registration Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="mdi mdi-information me-2"></i>Maklumat Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted">Didaftar oleh:</td>
                                <td>{{ $patient->registrar?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tarikh daftar:</td>
                                <td>{{ $patient->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kemaskini terakhir:</td>
                                <td>{{ $patient->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="mdi mdi-check"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.patients.show', $patient) }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Batal
                    </a>
                </div>

                <!-- Danger Zone -->
                <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0"><i class="mdi mdi-alert me-2"></i>Zon Bahaya</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">Tindakan ini tidak boleh dibatalkan. Sila berhati-hati.</p>
                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="mdi mdi-delete"></i> Padam Pesakit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Sahkan Pemadaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Adakah anda pasti mahu memadam pesakit ini?</p>
                <div class="alert alert-warning">
                    <strong>MRN:</strong> {{ $patient->mrn }}<br>
                    <strong>Nama:</strong> {{ $patient->name }}<br>
                    <strong>IC:</strong> {{ $patient->ic_number ?? '-' }}
                </div>
                <p class="text-danger"><strong>Amaran:</strong> Semua rekod berkaitan pesakit ini akan dipadam.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-delete"></i> Ya, Padam
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('hasPanel').addEventListener('change', function() {
    document.getElementById('panelFields').style.display = this.checked ? '' : 'none';
});
</script>
@endsection
