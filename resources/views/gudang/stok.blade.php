<x-app-layout>
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-boxes me-2"></i> STOK BAHAN-BAHAN GUDANG
            </h5>
            <a href="{{ route('stok.opname.index') }}" class="btn btn-outline-primary btn-sm px-3 shadow-sm fw-bold">
                <i class="fas fa-clipboard-check me-1"></i> Buka Fitur Opname
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-primary">
                        <tr>
                            <th class="ps-4">Item</th>
                            <th>No Batch</th>
                            <th>Sisa Stok</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">{{ $batch->item->nama_barang }}</td>
                            <td><span class="badge bg-secondary rounded-pill bg-opacity-10 text-secondary border border-secondary">{{ $batch->no_batch }}</span></td>
                            <td>
                                <span class="fw-bold fs-6 text-primary">{{ number_format($batch->qty_sisa, 0, ',', '.') }}</span>
                                <span class="text-muted small ms-1">{{ $batch->item->satuan }}</span>
                            </td>
                            <td class="pe-4">
                                @if($batch->qty_sisa <= 5)
                                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">Hampir Habis</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">Tersedia</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($batches->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x d-block mb-3" style="opacity: 0.2;"></i>
                                Belum ada data stok gudang.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Opname (Bootstrap 5 Standard) -->
<div class="modal fade" id="modalOpname" tabindex="-1" aria-labelledby="modalOpnameLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form id="formOpname" method="POST">
                @csrf
                <div class="modal-header bg-light border-bottom-0 rounded-top-4">
                    <h5 class="modal-title fw-bold text-primary" id="modalOpnameLabel">
                        <i class="fas fa-clipboard-list me-2"></i>Stok Opname: <span id="namaBarang"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                        <span class="fw-bold text-primary">Stok Sistem Saat Ini</span>
                        <span class="fs-4 fw-bold text-primary" id="stokSistem"></span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Jumlah Fisik Sebenarnya:</label>
                        <input type="number" name="qty_fisik" class="form-control form-control-lg border-primary" placeholder="Masukkan jumlah fisik..." required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showOpnameModal(id, nama, stok) {
        document.getElementById('namaBarang').innerText = nama;
        document.getElementById('stokSistem').innerText = stok;
        document.getElementById('formOpname').action = "/gudang/stok/opname/" + id;
        
        // Show Bootstrap 5 modal properly
        var myModal = new bootstrap.Modal(document.getElementById('modalOpname'));
        myModal.show();
    }
</script>
@endpush
</x-app-layout>