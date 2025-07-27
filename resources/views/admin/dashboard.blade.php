@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Dashboard Admin</h1>
                    @auth
                        <div class="d-flex align-items-center">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Profile" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                            @endif
                            <span class="me-3">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt me-1"></i>{{ __('common.logout') }}
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Selamat datang ke sistem pengurusan Poliklinik Al-Huda.
                    </div>

                    @auth
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5><i class="fas fa-user me-2"></i>User Information</h5>
                                        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                        <p><strong>Google ID:</strong> {{ auth()->user()->google_id ? 'Connected' : 'Not Connected' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5><i class="fas fa-calendar me-2"></i>Quick Actions</h5>
                                        <a href="{{ route('admin.appointments') }}" class="btn btn-light btn-sm me-2">
                                            <i class="fas fa-calendar-alt me-1"></i>{{ __('medical.appointment') }}
                                        </a>
                                        <a href="{{ route('admin.services') }}" class="btn btn-light btn-sm">
                                            <i class="fas fa-cogs me-1"></i>{{ __('common.services') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
