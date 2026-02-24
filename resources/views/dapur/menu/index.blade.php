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

@php
    $successMsg = session('success');
    $errorMsg = session('error');
@endphp

</x-app-layout>