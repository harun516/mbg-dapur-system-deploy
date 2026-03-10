<x-app-layout>
    <div class="container-dashboard">
        <h2>Stok Bahan di Dapur</h2>
        
        <div class="action-wrapper">
            <p class="header-description mb-0">
                Pantau sisa bahan baku sebelum memulai produksi.
            </p>
            <div class="d-flex gap-2">
                <a href="{{ route('dapur.request.index') }}" class="btn btn-outline-primary d-flex align-items-center">
                    <i class="fas fa-list me-2"></i> Riwayat Permintaan
                </a>
                
                <a href="{{ route('dapur.request.create') }}" class="btn btn-warning d-flex align-items-center">
                    <i class="fas fa-plus me-2"></i> Minta Barang ke Gudang
                </a>
            </div>
        </div>

        <div class="row">
            @forelse($kitchenStocks as $itemId => $batches)
                @php 
                    $totalSisa = $batches->sum('qty_sisa'); 
                    $itemInfo = $batches->first()->item;
                @endphp
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="card-stock">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="item-title">{{ $itemInfo->nama_barang }}</h5>
                                <span class="status-badge {{ $totalSisa <= 5 ? 'badge-danger' : 'badge-success' }}">
                                    {{ $totalSisa <= 5 ? 'Kritis' : 'Tersedia' }}
                                </span>
                            </div>

                            <div class="total-qty">{{ number_format($totalSisa, 0, ',', '.') }}</div>
                            <div class="unit">{{ $itemInfo->satuan }}</div>

                            <hr class="my-3">

                            <div class="batch-list">
                                @foreach($batches as $b)
                                    <div class="batch-item">
                                        <span class="batch-no text-muted small">Batch: {{ $b->no_batch }}</span>
                                        <span class="batch-qty">{{ number_format($b->qty_sisa, 2, ',', '.') }} {{ $itemInfo->satuan }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <img 
                            src="{{ asset('images/icons1/empty-box.svg') }}" 
                            alt="Tidak ada stok di dapur" 
                        >
                        <h5>Belum ada stok di dapur</h5>
                        <p>
                            Silakan buat permintaan ke gudang untuk menambah bahan baku produksi.
                        </p>
                        {{-- Class px-5 py-3 dihapus agar mengikuti CSS custom --}}
                        <a href="{{ route('dapur.request.create') }}" class="btn btn-warning">
                            <i class="fas fa-paper-plane me-2"></i> Buat Permintaan Sekarang
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>