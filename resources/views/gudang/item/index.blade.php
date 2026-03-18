<x-app-layout>
<div class="container-table">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h4 fw-bold mb-1">
                <i class="fas fa-boxes text-primary me-2"></i> Master Bahan Baku
            </h1>
            <p class="text-muted small mb-0">Kelola daftar bahan baku yang digunakan dalam sistem</p>
        </div>
    </div>

    {{-- Add Item Form --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-plus-circle me-2"></i> Tambah Bahan Baku Baru
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('item.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-5">
                    <label class="form-label fw-semibold small">Nama Bahan</label>
                    <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Beras Ramos, Minyak Goreng" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Satuan</label>
                    <input type="text" name="satuan" class="form-control" placeholder="kg / liter / pcs" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="fas fa-plus me-2"></i> Tambah Bahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Item Table --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="m-0 fw-bold text-primary">
                <i class="fas fa-list me-2"></i> Daftar Bahan Baku
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="font-size:0.85rem;">Nama Bahan</th>
                        <th style="font-size:0.85rem;">Satuan</th>
                        <th style="font-size:0.85rem;">Status</th>
                        <th class="text-center" style="font-size:0.85rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="ps-4 fw-semibold" data-label="Nama Bahan">{{ $item->nama_barang }}</td>
                        <td data-label="Satuan">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1 rounded-pill">{{ $item->satuan }}</span>
                        </td>
                        <td data-label="Status">
                            <span class="badge bg-success rounded-pill px-3 py-1">Aktif</span>
                        </td>
                        <td class="text-center" data-label="Aksi">
                            <form action="{{ route('item.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menonaktifkan bahan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="fas fa-ban me-1"></i> Nonaktifkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                            Belum ada bahan baku terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>