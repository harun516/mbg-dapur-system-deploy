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

<!======================== STOK =======================!>
<!-- <div class="container-dashboard mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h4 fw-bold text-gray-900 mb-0">Stok Bahan di Dapur</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('dapur.request.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-history me-1"></i> Riwayat
            </a>
            <a href="{{ route('dapur.request.create') }}" class="btn btn-sm btn-warning">
                <i class="fas fa-plus me-1"></i> Minta Barang
            </a>
        </div>
    </div>

    <div class="row g-3">
        @forelse($kitchenStocks as $itemId => $batches)
            @php 
                $itemInfo = $batches->first()->item;
                $totalSisa = $batches->sum('qty_sisa'); 
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card card-dashboard border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small fw-bold text-uppercase">Bahan Baku</span>
                            <span class="badge {{ $totalSisa <= 5 ? 'bg-danger' : 'bg-success' }} rounded-pill">
                                {{ $totalSisa <= 5 ? 'Kritis' : 'Aman' }}
                            </span>
                        </div>
                        <h5 class="fw-bold mb-3">{{ $itemInfo->nama_barang }}</h5>
                        
                        <div class="d-flex align-items-baseline">
                            <h2 class="fw-bold mb-0 me-2 text-primary">{{ number_format($totalSisa, 2, ',', '.') }}</h2>
                            <span class="text-muted">{{ $itemInfo->satuan }}</span>
                        </div>

                        <div class="mt-3 pt-3 border-top">
                            <p class="small text-muted mb-2">Detail Batch:</p>
                            @foreach($batches->take(2) as $b) {{-- Tampilkan 2 batch terlama saja agar ringkas --}}
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-truncate" style="max-width: 100px;">#{{ $b->no_batch }}</span>
                                    <span class="fw-bold">{{ (float)$b->qty_sisa }}</span>
                                </div>
                            @endforeach
                            @if($batches->count() > 2)
                                <div class="text-center small text-primary mt-2">+ {{ $batches->count() - 2 }} Batch lainnya</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Tidak ada stok bahan di dapur.</p>
            </div>
        @endforelse
    </div>
</div> -->

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