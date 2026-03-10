<x-app-layout>
    <div class="container-dashboard">
        <h2>Daftar Master Resep (Modul Dapur)</h2>

        <div class="action-wrapper">
            <p class="header-description mb-0">
                Kelola resep standar untuk kebutuhan produksi dapur.
            </p>
            <a href="{{ route('menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Resep Baru
            </a>
        </div>

        <div class="row">
            @forelse($menus as $menu)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card card-recipe h-100">
                        <div class="card-body">
                            <h5 class="recipe-title">{{ $menu->nama_menu }}</h5>

                            <div class="target-porsi">{{ number_format($menu->porsi_standar) }}</div>
                            <div class="unit">Porsi</div>

                            <hr class="my-3">

                            <div class="bahan-list">
                                @if($menu->requirements->isNotEmpty())
                                    @foreach($menu->requirements as $req)
                                        <div class="bahan-item">
                                            <span>{{ $req->item->nama_barang }}</span>
                                            <span class="fw-bold">{{ $req->qty_per_porsi }} {{ $req->item->satuan }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-3 small">
                                        Belum ada bahan baku
                                    </div>
                                @endif
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="status-badge {{ $menu->status_enable ? 'badge-success' : 'badge-danger' }}">
                                    {{ $menu->status_enable ? 'Aktif' : 'Non-Aktif' }}
                                </span>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-action btn-edit btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('production.store') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <input type="hidden" name="jumlah_porsi" value="{{ $menu->porsi_standar }}">
                                        <button type="submit" class="btn btn-action btn-produksi btn-sm" onclick="return confirm('Mulai proses produksi untuk menu ini?')">
                                            <i class="fas fa-play"></i> Produksi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-utensils"></i>
                        <h5 class="mb-3">Belum ada master resep</h5>
                        <p class="mb-4">Tambahkan resep baru untuk memulai proses produksi dapur.</p>
                        <a href="{{ route('menu.create') }}" class="btn btn-primary px-5 py-3">
                            <i class="fas fa-plus me-2"></i> Tambah Resep Baru
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    </div>
</x-app-layout><x-app-layout>
    <div class="container-dashboard">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Master Resep (Modul Dapur)</h2>
            
            {{-- HITUNG TOTAL TARGET DARI PENERIMA AKTIF --}}
            @php
                $totalTargetHarian = \App\Models\Master_Penerima\Recipient::where('status_enable', 1)->sum('jumlah_porsi') ?? 0;
            @endphp

            <div class="target-summary-card p-2 px-3 bg-white shadow-sm rounded-3 border-start border-primary border-4">
                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Target Distribusi Hari Ini</small>
                <span class="h4 fw-bold text-primary mb-0">{{ number_format($totalTargetHarian) }}</span> 
                <small class="text-dark fw-semibold">Porsi</small>
            </div>
        </div>

        <div class="action-wrapper">
            <p class="header-description mb-0">
                Kelola resep standar. Angka produksi otomatis menyesuaikan total porsi dari Daftar Penerima MBG.
            </p>
            <a href="{{ route('menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Resep Baru
            </a>
        </div>

        <div class="row">
            @forelse($menus as $menu)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card card-recipe h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="recipe-title fw-bold text-dark mb-3">{{ $menu->nama_menu }}</h5>

                            {{-- Tampilkan Porsi Standar Resep sebagai referensi --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="bg-light p-2 rounded text-center" style="min-width: 60px;">
                                    <span class="d-block fw-bold text-primary">{{ number_format($menu->porsi_standar) }}</span>
                                    <small class="text-muted" style="font-size: 10px;">Std Porsi</small>
                                </div>
                                <i class="fas fa-arrow-right text-muted"></i>
                                <div class="bg-primary p-2 rounded text-center text-white" style="min-width: 60px;">
                                    <span class="d-block fw-bold">{{ number_format($totalTargetHarian) }}</span>
                                    <small style="font-size: 10px; opacity: 0.8;">Target Riil</small>
                                </div>
                            </div>

                            <hr class="my-3 opacity-50">

                            <div class="bahan-list" style="height: 120px; overflow-y: auto;">
                                @if($menu->requirements->isNotEmpty())
                                    @foreach($menu->requirements as $req)
                                        <div class="bahan-item d-flex justify-content-between mb-1" style="font-size: 13px;">
                                            <span class="text-muted">{{ $req->item->nama_barang }}</span>
                                            <span class="fw-bold">{{ $req->qty_per_porsi }} {{ $req->item->satuan }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-3 small italic">
                                        Belum ada bahan baku
                                    </div>
                                @endif
                            </div>

                            <hr class="my-3 opacity-50">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge {{ $menu->status_enable ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 10px;">
                                    {{ $menu->status_enable ? 'Aktif' : 'Non-Aktif' }}
                                </span>

                                <div class="d-flex gap-1">
                                    <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- FORM PRODUKSI DENGAN JUMLAH PORSI DARI RECIPIENT --}}
                                    <form action="{{ route('production.store') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        
                                        {{-- INI KUNCINYA: Mengirim target riil dari tabel recipient --}}
                                        <input type="hidden" name="jumlah_porsi" value="{{ $totalTargetHarian }}">
                                        
                                        <button type="submit" class="btn btn-primary btn-sm fw-bold" 
                                                onclick="return confirm('Mulai produksi {{ $menu->nama_menu }} sebanyak {{ number_format($totalTargetHarian) }} porsi?')">
                                            <i class="fas fa-play me-1"></i> Masak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="empty-state text-muted">
                        <i class="fas fa-utensils fa-3x mb-3"></i>
                        <h5>Belum ada master resep</h5>
                        <a href="{{ route('menu.create') }}" class="btn btn-primary mt-3">Tambah Resep</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    </div>
</x-app-layout>

@php
    $successMsg = session('success');
    $errorMsg = session('error');
@endphp

