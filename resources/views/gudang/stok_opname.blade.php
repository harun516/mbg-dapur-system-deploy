<x-app-layout>
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom py-3">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-clipboard-check me-2"></i> Audit Stok Opname</h4>
            <p class="text-muted mb-0 mt-1 small">Gunakan halaman ini untuk menyesuaikan jumlah fisik barang di gudang.</p>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-primary">
                        <tr>
                            <th class="ps-4">Item / Batch</th>
                            <th>Stok Sistem</th>
                            <th>Input Stok Fisik</th>
                            <th>Keterangan Selisih</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                        <form action="{{ route('stok.opname.process', $batch->id) }}" method="POST">
                            @csrf
                            <tr>
                                <td class="ps-4">
                                    <strong class="text-dark d-block">{{ $batch->item->nama_barang }}</strong>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill mt-1">{{ $batch->no_batch }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary fs-6">{{ $batch->qty_sisa }}</span> <span class="text-muted small">{{ $batch->item->satuan ?? '' }}</span>
                                </td>
                                <td>
                                    <input type="number" name="qty_fisik" class="form-control bg-light" placeholder="Jml Fisik" required min="0" style="max-width: 150px;">
                                </td>
                                <td>
                                    <input type="text" name="keterangan" class="form-control bg-light" placeholder="Alasan (misal: Rusak/Hilang)" required>
                                </td>
                                <td class="pe-4 text-center">
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold shadow-sm"><i class="fas fa-sync-alt me-1"></i> Update</button>
                                </td>
                            </tr>
                        </form>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data batch stok untuk diopname.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>