<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 border-bottom pb-3">
            <div>
                <h4 class="fw-bold text-primary mb-1">
                    <i class="fas fa-boxes me-2"></i>Stok Bahan di Dapur
                </h4>
                <p class="text-muted mb-0">
                    Pantau sisa bahan baku sebelum memulai produksi.
                </p>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('dapur.request.index') }}" class="btn btn-outline-primary shadow-sm btn-sm px-3 d-flex align-items-center">
                    <i class="fas fa-list me-2"></i> Riwayat Permintaan
                </a>
                
                <a href="{{ route('dapur.request.create') }}" class="btn btn-warning shadow-sm btn-sm px-3 d-flex align-items-center fw-bold">
                    <i class="fas fa-plus me-2"></i> Minta Barang ke Gudang
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($kitchenStocks as $itemId => $batches)
                @php 
                    $totalSisa = $batches->sum('qty_sisa'); 
                    $itemInfo = $batches->first()->item;
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="fw-bold text-dark text-truncate mb-0" title="{{ $itemInfo->nama_barang }}">{{ $itemInfo->nama_barang }}</h6>
                                <span class="badge rounded-pill {{ $totalSisa <= 5 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $totalSisa <= 5 ? 'Kritis' : 'Tersedia' }}
                                </span>
                            </div>

                            <div class="d-flex align-items-baseline gap-1">
                                <span class="fs-2 fw-bold text-primary">{{ number_format($totalSisa, 0, ',', '.') }}</span>
                                <span class="text-muted fw-semibold">{{ $itemInfo->satuan }}</span>
                            </div>

                            <hr class="my-3 text-muted">

                            <div class="d-flex flex-column gap-2 mb-0">
                                @foreach($batches as $b)
                                    <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded-3 border">
                                        <span class="badge bg-secondary">Batch: {{ $b->no_batch }}</span>
                                        <span class="fw-semibold small text-dark">{{ number_format($b->qty_sisa, 2, ',', '.') }} {{ $itemInfo->satuan }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4 p-5 text-center bg-light">
                        <div class="mb-4">
                            <i class="fas fa-box-open fa-5x text-muted" style="opacity: 0.3;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Belum ada stok di dapur</h5>
                        <p class="text-muted mx-auto" style="max-width: 400px;">
                            Silakan buat permintaan ke gudang untuk menambah bahan baku produksi.
                        </p>
                        <div>
                            <a href="{{ route('dapur.request.create') }}" class="btn btn-warning px-4 py-2 fw-bold shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Buat Permintaan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>