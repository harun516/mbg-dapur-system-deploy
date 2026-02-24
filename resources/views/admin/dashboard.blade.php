@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="pb-4 mb-5 border-bottom">
        <h1 class="h3 fw-bold text-gray-900 mb-1">Dashboard Admin</h1>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <div class="row g-4">
        <!-- Card 1: Total Pengguna -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-primary h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-users card-icon text-primary"></i>
                    <h5 class="card-title">Total Pengguna</h5>
                    <h2 class="card-value">{{ $totalUsers ?? '0' }}</h2>
                    <p class="card-desc">Akun aktif di sistem</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Stok Gudang Kritis -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-danger h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-exclamation-triangle card-icon text-danger"></i>
                    <h5 class="card-title">Stok Kritis</h5>
                    <h2 class="card-value">{{ $stokKritis ?? '0' }}</h2>
                    <p class="card-desc">Item di bawah batas aman</p>
                </div>
            </div>
        </div>

        <!-- Card 3: Permintaan Pending -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-warning h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-paper-plane card-icon text-warning"></i>
                    <h5 class="card-title">Permintaan Pending</h5>
                    <h2 class="card-value">{{ $permintaanPending ?? '0' }}</h2>
                    <p class="card-desc">Menunggu approval gudang</p>
                </div>
            </div>
        </div>

        <!-- Card 4: Produksi Aktif -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-success h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-utensils card-icon text-success"></i>
                    <h5 class="card-title">Produksi Aktif</h5>
                    <h2 class="card-value">{{ $produksiAktif ?? '0' }}</h2>
                    <p class="card-desc">Produksi sedang berjalan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Admin -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-bold text-primary">Akses Cepat Admin</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 text-center">
                        <div class="col-md-3">
                            <a href="#" class="btn btn-dashboard-outline btn-lg w-100">
                                <i class="fas fa-users fa-2x mb-3"></i>
                                Kelola User
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-dashboard-outline btn-lg w-100">
                                <i class="fas fa-box-open fa-2x mb-3"></i>
                                Master Barang
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-dashboard-outline btn-lg w-100">
                                <i class="fas fa-chart-line fa-2x mb-3"></i>
                                Laporan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-dashboard-outline btn-lg w-100">
                                <i class="fas fa-cog fa-2x mb-3"></i>
                                Pengaturan Sistem
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection