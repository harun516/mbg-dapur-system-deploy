@extends('layouts.app')

@section('title', 'Dashboard Dapur')

@section('content')
    <div class="pb-4 mb-5 border-bottom">
        <h1 class="h3 fw-bold text-gray-900 mb-1">Dashboard Dapur</h1>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

<!=======================CARD STATIS============================>
    <div class="row g-4 mb-5">
        <!-- Stok Kritis -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-danger h-100">
                <div class="card-body text-center p-5">
                    <i class="fas fa-exclamation-triangle card-icon text-danger"></i>
                    <h5 class="card-title">Stok Kritis</h5>
                    <h2 class="card-value">{{ $stokKritisDapur ?? '0' }}</h2>
                    <p class="card-desc">Item di bawah batas aman</p>
                </div>
            </div>
        </div>

        <!-- Resep Aktif -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-success h-100">
                <div class="card-body text-center p-5">
                    <i class="fas fa-utensils card-icon text-success"></i>
                    <h5 class="card-title">Resep Aktif</h5>
                    <h2 class="card-value">{{ $resepAktif ?? '0' }}</h2>
                    <p class="card-desc">Resep siap produksi</p>
                </div>
            </div>
        </div>

        <!-- Produksi Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-info h-100">
                <div class="card-body text-center p-5">
                    <i class="fas fa-industry card-icon text-info"></i>
                    <h5 class="card-title">Produksi Hari Ini</h5>
                    <h2 class="card-value">{{ $produksiHariIni ?? '0' }}</h2>
                    <p class="card-desc">Jumlah produksi hari ini</p>
                </div>
            </div>
        </div>

        <!-- Permintaan Pending -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-warning h-100">
                <div class="card-body text-center p-5">
                    <i class="fas fa-paper-plane card-icon text-warning"></i>
                    <h5 class="card-title">Permintaan Pending</h5>
                    <h2 class="card-value">{{ $permintaanPending ?? '0' }}</h2>
                    <p class="card-desc">Menunggu approval gudang</p>
                </div>
            </div>
        </div>
    </div>

<!======================== RENCANA MASAK =======================!>
<div class="mb-5">
    <h3 class="fw-bold text-dark mb-4"><i class="fas fa-clipboard-list text-primary me-2"></i> Order Produksi Hari Ini</h3>
    <div class="row">
        @forelse($rencanaMasak as $rencana)
            @php
                // Cek apakah data produksi sudah ada DAN statusnya sudah 'Proses Masak'
                $prod = $rencana->productions->first();
                $sudahDimasak = ($prod && $prod->status !== 'Menunggu Dapur');
            @endphp
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-left: 5px solid {{ $sudahDimasak ? '#6f42c1' : '#007bff' }} !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0">{{ $rencana->menu->nama_menu }}</h5>
                            <span class="badge {{ $sudahDimasak ? 'bg-purple text-white' : 'bg-primary' }}" style="{{ $sudahDimasak ? 'background-color: #6f42c1;' : '' }}">
                                {{ number_format($rencana->total_porsi_target) }} Porsi
                            </span>
                        </div>
                        <p class="text-muted small mb-3">Diterima: {{ $rencana->updated_at->format('H:i') }} WIB</p>
                        
                        <div class="p-2 bg-light rounded mb-3" style="font-size: 0.85rem;">
                            <strong class="d-block mb-1 small text-uppercase text-muted">Bahan Utama:</strong>
                            @foreach($rencana->menu->requirements->take(3) as $req)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $req->item->nama_barang }}</span>
                                    <span class="fw-bold">{{ number_format($req->qty_per_porsi * $rencana->total_porsi_target, 2) }} {{ $req->item->satuan }}</span>
                                </div>
                            @endforeach
                            @if($rencana->menu->requirements->count() > 3)
                                <small class="text-primary">+ {{ $rencana->menu->requirements->count() - 3 }} bahan lainnya</small>
                            @endif
                        </div>

                        @if($sudahDimasak)
                            <div class="d-grid">
                                <div class="alert alert-info py-2 px-3 mb-2 border-0 text-center" style="font-size: 0.8rem; background-color: #e7e1f5; color: #59359a;">
                                    <i class="fas fa-spinner fa-spin me-2"></i> Sedang diproses di dapur
                                </div>
                                <a href="{{ route('production.index') }}" class="btn btn-outline-purple w-100 fw-bold" style="color: #6f42c1; border-color: #6f42c1;">
                                    <i class="fas fa-eye me-2"></i> LIHAT PROGRES
                                </a>
                            </div>
                        @else
                            <form action="{{ route('production.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $rencana->id }}">
                                <input type="hidden" name="menu_id" value="{{ $rencana->menu_id }}">
                                <input type="hidden" name="jumlah_porsi" value="{{ $rencana->total_porsi_target }}">
                                
                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                    <i class="fas fa-utensils me-2"></i> MULAI MASAK
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4 bg-white rounded shadow-sm">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076402.png" style="width: 60px; opacity: 0.3;" class="mb-3">
                <p class="text-muted mb-0">Belum ada order masuk dari Admin hari ini.</p>
            </div>
        @endforelse
    </div>
</div>

<!============================MENU==============================>
    <div class="pb-4 mb-5 border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h4 fw-bold text-gray-900 mb-0">Resep Aktif Terbaru</h3>
            <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-utensils me-1"></i> Lihat Semua Resep
            </a>
        </div>
        <div class="row g-4">
            @forelse($menus as $menu)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-dashboard h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3">{{ $menu->nama_menu }}</h5>

                            <div class="d-flex align-items-baseline mb-3">
                                <h2 class="card-value mb-0 me-2">{{ number_format($menu->porsi_standar) }}</h2>
                                <span class="text-muted">Porsi</span>
                            </div>

                            <div class="bahan-list small">
                                @if($menu->requirements->isNotEmpty())
                                    @foreach($menu->requirements->take(4) as $req) <!-- limit 4 bahan -->
                                        <div class="bahan-item">
                                            <span>{{ $req->item->nama_barang }}</span>
                                            <span class="fw-bold">{{ $req->qty_per_porsi }} {{ $req->item->satuan }}</span>
                                        </div>
                                    @endforeach
                                    @if($menu->requirements->count() > 4)
                                        <div class="text-muted mt-1">+ {{ $menu->requirements->count() - 4 }} bahan lainnya</div>
                                    @endif
                                @else
                                    <div class="text-muted">Belum ada bahan</div>
                                @endif
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    Aktif
                                </span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('production.store') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <input type="hidden" name="jumlah_porsi" value="{{ $menu->porsi_standar }}">
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mulai produksi menu ini?')">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-utensils fa-3x mb-3 d-block"></i>
                        Belum ada resep aktif
                    </div>
                </div>
            @endforelse
        </div>
    </div>

@endsection