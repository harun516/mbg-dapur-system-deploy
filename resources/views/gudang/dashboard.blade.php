@extends('layouts.app')

@section('title', 'Dashboard Gudang')

@section('content')
    <div class="pb-4 mb-5 border-bottom">
        <h1 class="h3 fw-bold text-gray-900 mb-1">Dashboard Gudang</h1>
        <p class="text-muted mb-0">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <!-- Card Statistik (4 kolom) -->
    <div class="row g-4 mb-5">
        <!-- Total Stok Gudang -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-primary h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-warehouse card-icon text-primary"></i>
                    <h5 class="card-title">Total Stok Gudang</h5>
                    <h2 class="card-value">{{ $totalStok ?? '0' }}</h2>
                    <p class="card-desc">Item tersedia di gudang</p>
                </div>
            </div>
        </div>

        <!-- Permintaan Pending -->
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

        <!-- Penerimaan Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-info h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-truck-loading card-icon text-info"></i>
                    <h5 class="card-title">Penerimaan Hari Ini</h5>
                    <h2 class="card-value">{{ $penerimaanHariIni ?? '0' }}</h2>
                    <p class="card-desc">Barang masuk hari ini</p>
                </div>
            </div>
        </div>

        <!-- Opname Belum Selesai -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard card-accent-left card-danger h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-clipboard-check card-icon text-danger"></i>
                    <h5 class="card-title">Opname Belum Selesai</h5>
                    <h2 class="card-value">{{ $opnamePending ?? '0' }}</h2>
                    <p class="card-desc">Proses opname tertunda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION BARU: Master Barang (Grid Card) -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h4 fw-bold text-gray-900 mb-0">Master Barang Terbaru</h3>
            <a href="{{ route('item.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-boxes me-1"></i> Lihat Semua Barang
            </a>
        </div>

        <div class="row g-4">
            @forelse($items as $item)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-dashboard h-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-3">{{ $item->nama_barang }}</h5>

                            <div class="d-flex align-items-baseline mb-3">
                                <h2 class="card-value mb-0 me-2">—</h2>
                                <span class="text-muted">Satuan: {{ $item->satuan }}</span>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    Aktif
                                </span>

                                <div class="d-flex gap-2">
                                    <form action="{{ route('item.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menonaktifkan bahan ini?')">
                                            Nonaktifkan
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
                        <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                        Belum ada master barang
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection