@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">Manajemen Akses User</h4>
            <p class="text-muted small mb-0">Kelola dan tentukan peran pengguna dalam sistem DAPURKU</p>
        </div>
        <span class="badge bg-soft-primary text-primary px-3 py-2">Total: {{ $users->count() }} User</span>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold">Pengguna</th>
                            <th class="py-3 text-uppercase small fw-bold">Email</th>
                            <th class="py-3 text-uppercase small fw-bold">Role Saat Ini</th>
                            <th class="text-center py-3 text-uppercase small fw-bold">Aksi Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle fw-bold" style="width: 38px; height: 38px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Terdaftar: {{ $user->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-secondary">{{ $user->email }}</td>
                            <td>
                                @php
                                    $badgeClass = match($user->role) {
                                        'admin' => 'bg-danger',
                                        'gudang' => 'bg-success',
                                        'dapur' => 'bg-warning text-dark',
                                        'kurir' => 'bg-info text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-inline-flex gap-2 justify-content-center align-items-center">
                                    @csrf 
                                    @method('PATCH')
                                    <div class="input-group input-group-sm" style="width: 180px;">
                                        <label class="input-group-text bg-white border-end-0" for="roleSelect{{$user->id}}">
                                            <i class="fas fa-user-tag text-muted"></i>
                                        </label>
                                        <select name="role" class="form-select" id="roleSelect{{$user->id}}" style="font-size: 0.8rem;">
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="gudang" {{ $user->role == 'gudang' ? 'selected' : '' }}>Gudang</option>
                                            <option value="dapur" {{ $user->role == 'dapur' ? 'selected' : '' }}>Dapur</option>
                                            <option value="kurir" {{ $user->role == 'kurir' ? 'selected' : '' }}>Kurir</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styling tambahan */
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .table thead th { border-top: none; }
    .table tbody tr:last-child td { border-bottom: none; }
    .form-select:focus { border-color: #0d6efd; box-shadow: none; }
    .btn-primary { background-color: #0d6efd; border: none; }
    .btn-primary:hover { background-color: #0b5ed7; }
</style>
@endsection