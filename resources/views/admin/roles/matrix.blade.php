@extends('layouts.admin')
@section('title', 'Matriks Kebenaran')

@section('content')
<div class="content">
    <div class="breadcrumb-wrapper">
        <div>
            <h1>Matriks Kebenaran</h1>
            <p class="breadcrumbs">
                <span><a href="{{ route('admin.dashboard') }}">Dashboard</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span><a href="{{ route('admin.roles.index') }}">Peranan</a></span>
                <span><i class="mdi mdi-chevron-right"></i></span>
                <span>Matriks</span>
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th class="bg-light" style="position: sticky; left: 0; z-index: 1;">Kebenaran</th>
                            @foreach($roles as $role)
                            <th class="text-center">
                                {{ $role->name }}
                                @if($role->name === 'super-admin')
                                <i class="mdi mdi-shield-crown text-warning"></i>
                                @endif
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $group => $perms)
                        <tr class="table-secondary">
                            <td colspan="{{ $roles->count() + 1 }}" class="fw-bold text-uppercase">
                                <i class="mdi mdi-folder-outline me-1"></i> {{ $group }}
                            </td>
                        </tr>
                        @foreach($perms as $permission)
                        <tr>
                            <td style="position: sticky; left: 0; background: white; z-index: 1;">
                                {{ str_replace($group . '.', '', $permission->name) }}
                            </td>
                            @foreach($roles as $role)
                            <td class="text-center">
                                @if($role->hasPermissionTo($permission->name))
                                <i class="mdi mdi-check-circle text-success"></i>
                                @else
                                <i class="mdi mdi-close-circle text-danger opacity-25"></i>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Legenda</h5>
        </div>
        <div class="card-body">
            <span class="me-4"><i class="mdi mdi-check-circle text-success"></i> Dibenarkan</span>
            <span><i class="mdi mdi-close-circle text-danger opacity-25"></i> Tidak dibenarkan</span>
        </div>
    </div>
</div>
@endsection
