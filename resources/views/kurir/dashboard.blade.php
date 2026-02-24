@extends('layouts.app')

@section('title', 'Dashboard Kurir')

@section('content')
    <div class="pb-4 mb-5 border-bottom">
        <h1 class="h3 fw-bold text-gray-900 mb-1">Dashboard Kurir</h1>
        <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong></p>
    </div>

    <div class="row g-4">
        <!-- Card 1: Pengiriman Hari Ini -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-info h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-motorcycle card-icon text-info"></i>
                    <h5 class="card-title">Pengiriman Hari Ini</h5>
                    <h2 class="card-value">0</h2>
                    <p class="card-desc">Total paket dikirim hari ini</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Pending -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-warning h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-clock card-icon text-warning"></i>
                    <h5 class="card-title">Pending</h5>
                    <h2 class="card-value">0</h2>
                    <p class="card-desc">Paket menunggu pengiriman</p>
                </div>
            </div>
        </div>

        <!-- Card 3: Selesai Hari Ini -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-success h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-check-circle card-icon text-success"></i>
                    <h5 class="card-title">Selesai Hari Ini</h5>
                    <h2 class="card-value">0</h2>
                    <p class="card-desc">Paket berhasil dikirim</p>
                </div>
            </div>
        </div>
    </div>
@endsection