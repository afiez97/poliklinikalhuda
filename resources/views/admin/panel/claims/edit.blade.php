@extends('layouts.admin')

@section('title', 'Edit Tuntutan: ' . $claim->claim_number)

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
        <div>
            <h1>Edit Tuntutan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.claims.index') }}">Tuntutan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.panel.claims.show', $claim) }}">{{ $claim->claim_number }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Maklumat Tuntutan</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.panel.claims.update', $claim) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Tuntutan</label>
                                <input type="text" class="form-control" value="{{ $claim->claim_number }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tarikh Tuntutan <span class="text-danger">*</span></label>
                                <input type="date" name="claim_date" class="form-control @error('claim_date') is-invalid @enderror"
                                       value="{{ old('claim_date', $claim->claim_date->format('Y-m-d')) }}" required>
                                @error('claim_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Panel</label>
                                <input type="text" class="form-control" value="{{ $claim->panel->panel_name }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pesakit</label>
                                <input type="text" class="form-control" value="{{ $claim->patient->name ?? '-' }} ({{ $claim->patient->mrn ?? '-' }})" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Invois</label>
                                <input type="text" class="form-control" value="{{ $claim->invoice->invoice_no ?? '-' }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guarantee Letter</label>
                                <select name="guarantee_letter_id" class="form-select @error('guarantee_letter_id') is-invalid @enderror">
                                    <option value="">-- Tiada GL --</option>
                                    @foreach($availableGLs as $gl)
                                    <option value="{{ $gl->id }}" {{ old('guarantee_letter_id', $claim->guarantee_letter_id) == $gl->id ? 'selected' : '' }}>
                                        {{ $gl->gl_number }} - Baki: RM {{ number_format($gl->amount_balance, 2) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('guarantee_letter_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Amaun Tuntutan (RM)</label>
                                <input type="text" class="form-control" value="{{ number_format($claim->claim_amount, 2) }}" disabled>
                                <small class="text-muted">Amaun tidak boleh diubah selepas tuntutan dicipta.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tarikh Rawatan</label>
                                <input type="date" name="treatment_date" class="form-control @error('treatment_date') is-invalid @enderror"
                                       value="{{ old('treatment_date', $claim->treatment_date?->format('Y-m-d')) }}">
                                @error('treatment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Diagnosis</label>
                            <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror"
                                      rows="2">{{ old('diagnosis', $claim->diagnosis) }}</textarea>
                            @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rawatan</label>
                            <textarea name="treatment" class="form-control @error('treatment') is-invalid @enderror"
                                      rows="2">{{ old('treatment', $claim->treatment) }}</textarea>
                            @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nota</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                      rows="2">{{ old('notes', $claim->notes) }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Tambah Dokumen Sokongan</label>
                            <input type="file" name="documents[]" class="form-control @error('documents') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png" multiple>
                            <small class="text-muted">Format: PDF, JPG, PNG. Maks: 5MB setiap fail.</small>
                            @error('documents')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($claim->documents->count())
                        <div class="mb-3">
                            <label class="form-label">Dokumen Sedia Ada</label>
                            <ul class="list-group">
                                @foreach($claim->documents as $doc)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $doc->original_name }}</span>
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.panel.claims.show', $claim) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Kemaskini
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card card-default">
                <div class="card-header">
                    <h2><i class="bi bi-info-circle me-2"></i>Maklumat</h2>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'submitted' => 'info',
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'paid' => 'primary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$claim->status] ?? 'secondary' }}">
                                    {{ $claim->status_name }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dicipta:</td>
                            <td>{{ $claim->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dikemaskini:</td>
                            <td>{{ $claim->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>

                    @if($claim->status == 'draft')
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Tuntutan ini masih dalam status draf. Anda boleh mengedit dan menghantar apabila siap.
                    </div>
                    @else
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Tuntutan ini telah dihantar. Hanya maklumat tertentu sahaja boleh dikemaskini.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
