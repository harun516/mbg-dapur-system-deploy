@extends('layouts.app')

@section('title', 'Kelola Anggaran')

@section('content')
<div class="anggaran-container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="anggaran-header h3 fw-bold text-gray-900 mb-1">Manajemen Anggaran & Alokasi</h1>
            <p class="text-muted mb-0">Catat modal masuk dan bagi ke pos anggaran spesifik.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <button type="button" class="btn btn-primary shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahAnggaran">
                <i class="fas fa-plus-circle me-2"></i>Tambah Anggaran Masuk
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-5">
        <div class="col-12">
            <div class="card anggaran-saldo-card border-0 shadow-lg text-white" style="background: linear-gradient(45deg, #4e73df, #224abe); border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-white text-primary p-3 rounded-4 shadow-sm">
                                <i class="fas fa-wallet fa-3x"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="text-white-50 text-uppercase small fw-bold mb-1">Total Saldo Proyek (Modal)</h6>
                            <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">Rp {{ number_format($budget->modal_awal ?? 0, 0, ',', '.') }}</h2>
                        </div>
                        <div class="col-md-3 text-md-end">
                            <small class="d-block text-white-50">Sisa Saldo Belum Dialokasikan:</small>
                            <h5 class="fw-bold mb-0 {{ $sisaBebas < 0 ? 'text-warning' : '' }}">
                                Rp {{ number_format($sisaBebas, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Saldo Gudang -->
 <div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #1cc88a, #13855c); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-white text-success p-3 rounded-circle me-3">
                        <i class="fas fa-warehouse fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 small fw-bold mb-0">SALDO BELANJA GUDANG (OPERASIONAL)</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($saldoGudang ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-white-50">* Dana ini akan terpotong otomatis saat ada Penerimaan Barang.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
    <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #36b9cc, #258391); border-radius: 15px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center">
                <div class="bg-white text-info p-3 rounded-circle me-3">
                    <i class="fas fa-file-invoice-dollar fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-white-50 small fw-bold mb-0">TOTAL DANA DIALOKASIKAN</h6>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalAlokasi, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="mt-3">
                <div class="progress" style="height: 8px; background: rgba(255,255,255,0.2);">
                    <div class="progress-bar bg-white" role="progressbar" 
                         style="width: {{ $persenTerpakai }}%" 
                         aria-valuenow="{{ $persenTerpakai }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                <small class="text-white-50 mt-1 d-block">{{ number_format($persenTerpakai, 1) }}% dari Modal Utama</small>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Alokasi Dana -->

   <div class="row mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-divide me-2 text-primary"></i>Buat Alokasi Dana</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.budget.allocation') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Pos (Misal: Gaji, Belanja)</label>
                        <input type="text" name="nama_alokasi" class="form-control border-0 bg-light" placeholder="Ketik bebas..." required>
                        <div class="form-text mt-1 small text-muted">
                            <i class="fas fa-info-circle me-1"></i> Gunakan kata <strong>"Belanja"</strong> untuk mengirim ke Gudang.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nominal Alokasi (Rp)</label>
                        <input type="number" name="nominal" class="form-control border-0 bg-light" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control border-0 bg-light" rows="2" placeholder="Opsional..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm py-2">
                        <i class="fas fa-save me-2"></i>Simpan Alokasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-chart-pie me-2 text-primary"></i>Daftar Alokasi Saat Ini</h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                    Total: {{ $allocations->count() }} Pos
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Pos</th>
                                <th>Nominal</th>
                                <th>Tujuan Dana</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allocations as $alloc)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark d-block">{{ $alloc->nama_alokasi }}</span>
                                    <small class="text-muted">{{ $alloc->created_at->format('d M Y') }}</small>
                                </td>
                                <td class="fw-bold text-primary">
                                    Rp {{ number_format($alloc->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if(str_contains(strtolower($alloc->nama_alokasi), 'belanja'))
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2">
                                            <i class="fas fa-warehouse me-1"></i> GUDANG
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2">
                                            <i class="fas fa-user-tie me-1"></i> ADMIN
                                        </span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $alloc->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.budget.destroyAllocation', $alloc->id) }}" method="POST" onsubmit="return confirm('Hapus alokasi ini? Saldo akan dikembalikan ke Utama.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" style="width: 32px; height: 32px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted italic">Belum ada dana yang dialokasikan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat anggaran -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-dark"><i class="fas fa-history me-2 text-primary"></i>Riwayat Transaksi Anggaran</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Kategori</th>
                            <th>Sumber Dana</th>
                            <th>Nominal</th>
                            <th>Keterangan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                   <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td class="ps-4">
                            <span class="d-block fw-bold">{{ $trx->created_at->format('d M Y') }}</span>
                            <small class="text-muted">{{ $trx->created_at->format('H:i') }} WIB</small>
                        </td>

                        {{-- KOLOM KATEGORI (Penyesuaian Label Jenis Transaksi) --}}
                        <td>
                            @if(str_contains(strtolower($trx->kategori), 'alokasi') || $trx->kategori == 'Kirim Saldo ke gudang')
                                <span class="badge bg-info-subtle text-info rounded-pill px-3">Kirim Saldo ke gudang</span>
                            @elseif($trx->kategori == 'Belanja Bahan Baku' || $trx->kategori == 'penerimaan gudang')
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3">penerimaan gudang</span>
                            @elseif($trx->kategori == 'Modal Utama' || $trx->tipe == 'masuk')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3">Anggaran Masuk</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">{{ $trx->kategori }}</span>
                            @endif
                        </td>
                        
                        {{-- KOLOM SUMBER DANA --}}
                        <td>
                            <i class="fas fa-university me-1 text-muted"></i> {{ $trx->sumber_dana }}
                        </td>
                        
                        {{-- KOLOM NOMINAL (Warna & Minus/Plus) --}}
                        <td class="fw-bold">
                            @if($trx->tipe == 'keluar' || $trx->kategori == 'Belanja Bahan Baku' || str_contains(strtolower($trx->kategori), 'alokasi'))
                                <span class="text-danger">- Rp {{ number_format(abs($trx->nominal), 0, ',', '.') }}</span>
                            @else
                                <span class="text-success">+ Rp {{ number_format(abs($trx->nominal), 0, ',', '.') }}</span>
                            @endif
                        </td>

                        {{-- KOLOM KETERANGAN --}}
                        <td class="small text-muted">
                            @if($trx->kategori == 'Belanja Bahan Baku')
                                Belanja stok dari supplier
                            @else
                                {{ $trx->keterangan ?? '-' }}
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="badge {{ $trx->status_enable ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill px-3 py-2">
                                {{ $trx->status_enable ? 'Aktif' : 'Hidden' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada transaksi anggaran terekam.</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

<!-- Input Anggaran -->
<div class="modal fade" id="modalTambahAnggaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Input Anggaran Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.budget.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal Anggaran (Rp)</label>
                        <input type="number" name="nominal" class="form-control form-control-lg bg-light border-0" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="kategori" class="form-select bg-light border-0" required>
                            <option value="Modal Utama">Modal Utama</option>
                            <option value="Dana Operasional">Dana Operasional</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sumber Dana</label>
                        <input type="text" name="sumber_dana" class="form-control bg-light border-0" placeholder="Misal: Bank Mandiri" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control bg-light border-0" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection