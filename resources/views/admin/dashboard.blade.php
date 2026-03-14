@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="pb-4 mb-5 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">Dashboard Admin</h1>
            <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
        <div class="text-end">
            <span class="admin-header-date">
                <i class="fas fa-calendar-alt me-1"></i> {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>
<!-- Card Saldo Anggaran -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card admin-budget-card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="admin-budget-gradient">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h5 class="text-white-75 fw-light mb-2">Sisa Saldo Bebas (Sisa Modal)</h5>
                        <h1 class="display-4 fw-bold mb-3 text-white">
                            Rp {{ number_format($budget->saldo_saat_ini ?? 0, 0, ',', '.') }}
                        </h1>

                        <div class="d-flex align-items-center gap-4 mt-3">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between small mb-2 text-white-75">
                                    <span>Total Dana Teralokasi</span>
                                    <span class="fw-bold">{{ number_format($persenTerpakai ?? 0, 1) }}%</span>
                                </div>
                                <div class="admin-progress mb-2">
                                    <div class="admin-progress-bar" style="width: {{ ($persenTerpakai ?? 0) > 100 ? 100 : ($persenTerpakai ?? 0) }}%"></div>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-store-alt text-white-50 small"></i>
                                    <small class="text-white-75">
                                        Saldo di Gudang: <strong>Rp {{ number_format($budget->saldo_belanja_gudang ?? 0, 0, ',', '.') }}</strong>
                                    </small>
                                </div>
                            </div>
                            <div class="text-end ps-4 border-start border-white-50">
                                <small class="text-white-75 d-block">Total Modal Masuk</small>
                                <span class="fw-bold text-white">Rp {{ number_format($budget->saldo_saat_ini ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center d-none d-lg-block position-relative">
                        <i class="fas fa-wallet fa-10x text-white opacity-10 position-absolute top-50 end-0 translate-middle-y"></i>
                        <div class="position-relative z-1">
                            <div class="h4 mb-2 text-white">Status Proyek</div>
                            <a href="{{ route('admin.budget.index') }}" class="badge bg-white text-primary px-4 py-2 rounded-pill fw-bold shadow text-decoration-none d-inline-block transition-hover">
                                <i class="fas fa-chart-line fa-sm me-1"></i> BERJALAN
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4 Card Statistik -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-primary h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-box card-icon text-primary"></i>
                    <h5 class="card-title">Master Barang</h5>
                    <h2 class="card-value">{{ $totalBarang ?? '0' }}</h2>
                    <p class="card-desc">Item terdaftar</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-info h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-book-open card-icon text-info"></i>
                    <h5 class="card-title">Master Resep</h5>
                    <h2 class="card-value">{{ $totalResep ?? '0' }}</h2>
                    <p class="card-desc">Resep standar dapur</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-success h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-history card-icon text-success"></i>
                    <h5 class="card-title">Total Produksi</h5>
                    <h2 class="card-value">{{ $totalProduksi ?? '0' }}</h2>
                    <p class="card-desc">Batch selesai diproses</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-danger h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-exclamation-triangle card-icon text-danger"></i>
                    <h5 class="card-title">Stok Kritis</h5>
                    <h2 class="card-value">{{ $stokKritis ?? '0' }}</h2>
                    <p class="card-desc">Perlu re-stock segera</p>
                </div>
            </div>
        </div>
    </div>

<!-- Quick Links Admin -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-rocket me-2"></i>Akses Cepat Admin</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 text-center">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.budget.index') }}" class="btn btn-dashboard-outline w-100">
                                <i class="fas fa-file-invoice-dollar fa-2x mb-3"></i>
                                Budget
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.production_plan.index') }}" class="btn btn-dashboard-outline w-100">
                                <i class="fas fa-utensils fa-2x mb-3"></i>
                                Rencana Produksi
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.recipient.index') }}" class="btn btn-dashboard-outline w-100">
                                <i class="fas fa-school fa-2x mb-3"></i>
                                Sekolah Penerima
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.salary.index') }}" class="btn btn-dashboard-outline w-100">
                                <i class="fas fa-money-check-alt fa-2x mb-3"></i>
                                Gaji Pegawai
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
