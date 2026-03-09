@extends('layouts.app')

@section('title', 'Manajemen Gaji & Payroll')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-gray-900 mb-1">Payroll & Alokasi Gaji</h1>
            <p class="text-muted mb-0">Kelola standar gaji dan proses pembayaran pegawai dari anggaran proyek.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <ul class="nav nav-pills mb-4" id="salaryTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-4 shadow-sm" id="pay-tab" data-bs-toggle="tab" data-bs-target="#pay" type="button" role="tab">
                <i class="fas fa-money-check-alt me-2"></i>Pembayaran Gaji
            </button>
        </li>
        <li class="nav-item ms-2" role="presentation">
            <button class="nav-link fw-bold px-4 shadow-sm" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab">
                <i class="fas fa-cog me-2"></i>Setting Standar Gaji
            </button>
        </li>
    </ul>

    <div class="tab-content" id="salaryTabContent">
        <div class="tab-pane fade show active" id="pay" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-money-check-alt me-2"></i>Form Pembayaran Gaji Pegawai</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.salary.index') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Pilih Pegawai</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">-- Pilih Nama Pegawai --</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ strtoupper($user->role) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Periode Bulan</label>
                                <select name="periode_bulan" class="form-select" required>
                                    @php
                                        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                        $currentMonth = date('n') - 1;
                                    @endphp
                                    @foreach($months as $index => $month)
                                        <option value="{{ $month }} {{ date('Y') }}" {{ $index == $currentMonth ? 'selected' : '' }}>
                                            {{ $month }} {{ date('Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">Bayar Gaji</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-history me-2"></i>Riwayat Pembayaran Gaji</h6>
                    <form action="{{ route('admin.salary.index') }}" method="GET" class="d-flex align-items-center gap-2">
                        <select name="periode" class="form-select form-select-sm bg-light border-0" onchange="this.form.submit()">
                            <option value="">Semua Periode</option>
                            @php $currentYear = date('Y'); @endphp
                            @foreach(range($currentYear - 1, $currentYear + 1) as $year)
                                @foreach($months as $month)
                                    <option value="{{ $month }} {{ $year }}" {{ request('periode') == "$month $year" ? 'selected' : '' }}>
                                        {{ $month }} {{ $year }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Pegawai</th>
                                    <th>Periode</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Total Diterima</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $pay)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $pay->user->name }}</div>
                                        <small class="text-muted">{{ strtoupper($pay->user->role) }}</small>
                                    </td>
                                    <td>{{ $pay->periode_bulan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pay->tanggal_bayar)->format('d M Y') }}</td>
                                    <td class="fw-bold text-danger">- Rp {{ number_format($pay->total_diterima, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Success</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="config" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-plus-circle me-2"></i>Tambah Standar Gaji</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.salary.storeConfig') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Role</label>
                                    <select name="role_name" class="form-select" required>
                                        <option value="">-- Pilih Role --</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}">{{ strtoupper($role) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Gaji Pokok (Rp)</label>
                                    <input type="number" name="gaji_pokok" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tunjangan (Rp)</label>
                                    <input type="number" name="tunjangan" class="form-control" value="0">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">Simpan Konfigurasi</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-list me-2"></i>Daftar Standar Gaji</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Role</th>
                                            <th>Gaji Pokok</th>
                                            <th>Tunjangan</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($salaryConfigs as $config)
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">{{ strtoupper($config->role_name) }}</td>
                                            <td>Rp {{ number_format($config->gaji_pokok, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($config->tunjangan, 0, ',', '.') }}</td>
                                            <td class="fw-bold text-dark">Rp {{ number_format($config->total_diterima, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $config->status_enable ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2">
                                                    {{ $config->status_enable ? 'Aktif' : 'Non-aktif' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $config->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('admin.salary.destroyConfig', $config->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus konfigurasi ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editModal{{ $config->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Edit Gaji {{ strtoupper($config->role_name) }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.salary.updateConfig', $config->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Gaji Pokok</label>
                                                                <input type="number" name="gaji_pokok" class="form-control" value="{{ $config->gaji_pokok }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Tunjangan</label>
                                                                <input type="number" name="tunjangan" class="form-control" value="{{ $config->tunjangan }}" required>
                                                            </div>
                                                            <div class="form-check form-switch mt-3">
                                                                <input class="form-check-input" type="checkbox" name="status_enable" id="sw{{ $config->id }}" {{ $config->status_enable ? 'checked' : '' }} value="1">
                                                                <label class="form-check-label" for="sw{{ $config->id }}">Aktifkan Standar Gaji</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary px-4">Update Data</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <tr><td colspan="6" class="text-center py-5 text-muted">Data kosong.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Simpan & Muat Tab Aktif dari LocalStorage
        const activeTab = localStorage.getItem('salary_active_tab');
        if (activeTab) {
            const tabTrigger = new bootstrap.Tab(document.querySelector(`button[data-bs-target="${activeTab}"]`));
            tabTrigger.show();
        }

        const tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', (event) => {
                const target = event.target.getAttribute('data-bs-target');
                localStorage.setItem('salary_active_tab', target);
            });
        });
    });
</script>
@endpush
@endsection