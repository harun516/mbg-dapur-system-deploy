<x-app-layout>
    <div class="container-dashboard">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Manajemen Menu & Produksi</h2>
                <p class="text-muted small">Kelola resep standar dan eksekusi rencana produksi harian.</p>
            </div>
            
            {{-- RINGKASAN TARGET --}}
            @php
                $totalTargetHarian = \App\Models\Master_Penerima\Recipient::where('status_enable', 1)->sum('jumlah_porsi') ?? 0;
            @endphp
            <div class="target-summary-card p-2 px-3 bg-white shadow-sm rounded-3 border-start border-primary border-4">
                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Target Riil Hari Ini</small>
                <span class="h4 fw-bold text-primary mb-0">{{ number_format($totalTargetHarian) }}</span> 
                <small class="text-dark fw-semibold">Porsi</small>
            </div>
        </div>

        {{-- NAVBAR MENU --}}
        <ul class="nav nav-tabs border-0 mb-4" id="menuTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold px-4 border-0 text-dark" id="master-tab" data-bs-toggle="tab" data-bs-target="#master-resep" type="button" role="tab">
                    <i class="fas fa-book-open me-2 text-primary"></i> 1. Daftar Master Resep
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold px-4 border-0 text-dark position-relative" id="rencana-tab" data-bs-toggle="tab" data-bs-target="#rencana-produksi" type="button" role="tab">
                    <i class="fas fa-calendar-check me-2 text-success"></i> 2. Rencana Produksi Harian
                    @if($rencanaMasak->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            {{ $rencanaMasak->count() }}
                        </span>
                    @endif
                </button>
            </li>
        </ul>

        <div class="tab-content bg-white p-4 rounded-3 shadow-sm" id="menuTabContent">
            
            {{-- TAB 1: DAFTAR MASTER RESEP --}}
            <div class="tab-pane fade show active" id="master-resep" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Kelola Standar Resep</h5>
                    <a href="{{ route('menu.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Resep Baru
                    </a>
                </div>

                <div class="row">
                    @forelse($menus as $menu)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border shadow-sm rounded-3 overflow-hidden">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0">{{ $menu->nama_menu }}</h6>
                                        <span class="badge {{ $menu->status_enable ? 'bg-success' : 'bg-danger' }}" style="font-size: 9px;">
                                            {{ $menu->status_enable ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-3">Std: {{ number_format($menu->porsi_standar) }} Porsi</p>

                                    <div class="bg-light p-2 rounded mb-3" style="max-height: 100px; overflow-y: auto;">
                                        @foreach($menu->requirements->take(3) as $req)
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="text-muted">{{ $req->item->nama_barang }}</span>
                                                <span class="fw-bold text-dark">{{ $req->qty_per_porsi }} {{ $req->item->satuan }}</span>
                                            </div>
                                        @endforeach
                                        @if($menu->requirements->count() > 3)
                                            <small class="text-primary">+{{ $menu->requirements->count() - 3 }} bahan lagi</small>
                                        @endif
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-edit me-1"></i> Edit Standar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted">Belum ada resep terdaftar.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-3">{{ $menus->links() }}</div>
            </div>

            {{-- TAB 2: RENCANA PRODUKSI HARIAN (DARI ADMIN) --}}
            <div class="tab-pane fade" id="rencana-produksi" role="tabpanel">
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="fas fa-info-circle me-2"></i> Klik <strong>"Mulai Masak"</strong> pada kartu di bawah untuk memproses pesanan dari Admin hari ini.
                </div>

                <div class="row">
                    @forelse($rencanaMasak as $rencana)
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 shadow-sm" style="border-left: 5px solid #007bff !important;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">{{ $rencana->menu->nama_menu }}</h5>
                                        <span class="badge bg-primary">{{ number_format($rencana->total_porsi_target) }} Porsi</span>
                                    </div>
                                    
                                    <div class="p-2 bg-light rounded mb-3">
                                        <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px;">Estimasi Bahan:</small>
                                        @foreach($rencana->menu->requirements->take(3) as $req)
                                            <div class="d-flex justify-content-between small">
                                                <span>{{ $req->item->nama_barang }}</span>
                                                <span class="fw-bold">{{ number_format($req->qty_per_porsi * $rencana->total_porsi_target, 2) }} {{ $req->item->satuan }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <form action="{{ route('production.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan_id" value="{{ $rencana->id }}">
                                        <input type="hidden" name="menu_id" value="{{ $rencana->menu_id }}">
                                        <input type="hidden" name="jumlah_porsi" value="{{ $rencana->total_porsi_target }}">
                                        <button type="submit" class="btn btn-success w-100 fw-bold">
                                            <i class="fas fa-utensils me-2"></i> MULAI MASAK
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076402.png" style="width: 80px; opacity: 0.2;" class="mb-3">
                            <h5 class="text-muted">Tidak ada kiriman rencana produksi dari Admin hari ini.</h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs .nav-link { color: #6c757d; border-bottom: 3px solid transparent; transition: 0.3s; }
        .nav-tabs .nav-link.active { color: #0d6efd !important; border-bottom: 3px solid #0d6efd !important; background: transparent; }
        .card-recipe:hover { transform: translateY(-5px); transition: 0.3s; }
        .bg-purple { background-color: #6f42c1; }
    </style>
</x-app-layout>