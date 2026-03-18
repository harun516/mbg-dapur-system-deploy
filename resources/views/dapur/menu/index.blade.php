<x-app-layout>
    <div class="container-dashboard">
        {{-- HEADER + TARGET RINGKASAN --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-primary mb-1">Manajemen Menu & Produksi</h2>
                <p class="text-muted small">Kelola resep standar dan eksekusi rencana produksi harian.</p>
            </div>
            
            @php
                $totalTargetHarian = \App\Models\Master_Penerima\Recipient::where('status_enable', 1)->sum('jumlah_porsi') ?? 0;
            @endphp
            <div class="target-summary-card d-flex align-items-center gap-3 bg-white p-3 rounded-3 shadow-sm border-start border-primary border-4">
                <div>
                    <span class="text-muted text-uppercase fw-bold small d-block">Target Riil Hari Ini</span>
                    <span class="h3 fw-bold text-primary mb-0">{{ number_format($totalTargetHarian) }}</span>
                    <small class="text-dark fw-semibold ms-1">Porsi</small>
                </div>
                <i class="fas fa-utensils fa-2x text-primary opacity-50"></i>
            </div>
        </div>

        {{-- NAVIGASI TAB --}}
        <ul class="nav nav-tabs border-0 mb-4" id="menuTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold px-4 py-2 border-0 text-dark" id="master-tab" data-bs-toggle="tab" data-bs-target="#master-resep" type="button" role="tab">
                    <i class="fas fa-book-open me-2 text-primary"></i> 1. Daftar Master Resep
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold px-4 py-2 border-0 text-dark position-relative" id="rencana-tab" data-bs-toggle="tab" data-bs-target="#rencana-produksi" type="button" role="tab">
                    <i class="fas fa-calendar-check me-2 text-success"></i> 2. Rencana Produksi Harian
                    @if($rencanaMasak->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            {{ $rencanaMasak->count() }}
                        </span>
                    @endif
                </button>
            </li>
        </ul>

        {{-- KONTEN TAB --}}
        <div class="tab-content" id="menuTabContent">
            
            {{-- TAB 1: MASTER RESEP --}}
            <div class="tab-pane fade show active" id="master-resep" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Kelola Standar Resep</h5>
                    <a href="{{ route('menu.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-plus me-1"></i> Tambah Resep Baru
                    </a>
                </div>

                <div class="row g-4">
                    @forelse($menus as $menu)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 recipe-card">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="fw-bold text-dark mb-0">{{ $menu->nama_menu }}</h6>
                                        <span class="badge {{ $menu->status_enable ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $menu->status_enable ? 'success' : 'danger' }} px-3 py-1 rounded-pill">
                                            {{ $menu->status_enable ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex align-items-baseline gap-1 mb-3">
                                        <span class="fw-bold text-primary" style="font-size: 1.8rem;">{{ number_format($menu->porsi_standar) }}</span>
                                        <span class="text-muted small">Porsi</span>
                                    </div>

                                    <div class="bg-light p-3 rounded-3 mb-3" style="max-height: 110px; overflow-y: auto;">
                                        @foreach($menu->requirements->take(3) as $req)
                                            <div class="d-flex justify-content-between small mb-2">
                                                <span class="text-muted">{{ $req->item->nama_barang }}</span>
                                                <span class="fw-semibold text-dark">{{ $req->qty_per_porsi }} {{ $req->item->satuan }}</span>
                                            </div>
                                        @endforeach
                                        @if($menu->requirements->count() > 3)
                                            <small class="text-primary d-block mt-1">+{{ $menu->requirements->count() - 3 }} bahan lain</small>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="fas fa-edit me-1"></i> Edit Standar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076402.png" style="width: 80px; opacity: 0.2;" class="mb-3">
                            <p class="text-muted">Belum ada resep terdaftar. Silakan tambah resep baru.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $menus->links() }}</div>
            </div>

            {{-- TAB 2: RENCANA PRODUKSI HARIAN --}}
            <div class="tab-pane fade" id="rencana-produksi" role="tabpanel">
                <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info-emphasis rounded-3 mb-4">
                    <i class="fas fa-info-circle me-2"></i> Klik <strong>"Mulai Masak"</strong> untuk memproses rencana produksi dari Admin.
                </div>

                <div class="row g-4">
                    @forelse($rencanaMasak as $rencana)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #0d6efd !important;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="fw-bold mb-0">{{ $rencana->menu->nama_menu }}</h5>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                            {{ number_format($rencana->total_porsi_target) }} Porsi
                                        </span>
                                    </div>
                                    
                                    <div class="bg-light p-3 rounded-3 mb-3">
                                        <small class="text-muted text-uppercase fw-bold small d-block mb-2">Estimasi Bahan:</small>
                                        @foreach($rencana->menu->requirements->take(3) as $req)
                                            <div class="d-flex justify-content-between small mb-2">
                                                <span>{{ $req->item->nama_barang }}</span>
                                                <span class="fw-semibold">{{ number_format($req->qty_per_porsi * $rencana->total_porsi_target, 2) }} {{ $req->item->satuan }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <form action="{{ route('production.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan_id" value="{{ $rencana->id }}">
                                        <input type="hidden" name="menu_id" value="{{ $rencana->menu_id }}">
                                        <input type="hidden" name="jumlah_porsi" value="{{ $rencana->total_porsi_target }}">
                                        <button type="submit" class="btn btn-success w-100 fw-semibold rounded-pill">
                                            <i class="fas fa-fire me-2"></i> MULAI MASAK
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076402.png" style="width: 80px; opacity: 0.2;" class="mb-3">
                            <h5 class="text-muted">Tidak ada rencana produksi hari ini.</h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

   
</x-app-layout>