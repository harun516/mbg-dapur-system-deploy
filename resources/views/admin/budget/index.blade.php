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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalErrorSaldo'));
            modal.show();
        });
    </script>
    @endif

    <!-- Main Saldo Card -->
    <div class="row">
        <div class="col-12">
            <div class="card anggaran-saldo-card border-0 shadow-lg text-white overflow-hidden" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 24px;">
                <div class="card-body p-5">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-5">
                            <div class="d-flex align-items-center gap-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-white p-4 rounded-4 shadow-lg" style="min-width: 100px; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                                        <div class="text-center">
                                            <div style="font-size: 2.5rem; color: #3b82f6; margin-bottom: 4px;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <small style="color: #6b7280; font-weight: 600; font-size: 0.7rem; display: block;">MBG</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-white text-opacity-75 text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.15em;">Total Modal Proyek</p>
                                    <h2 class="fw-bold mb-0 text-white" style="font-size: 2.8rem;">Rp {{ number_format($budget->saldo_saat_ini ?? 0, 0, ',', '.') }}</h2>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        <span class="badge bg-success border-0 px-2 py-1" style="font-size: 0.7rem;">AKTIF</span>
                                        <small class="text-white text-opacity-75">Saldo Tersedia</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="bg-white bg-opacity-10 p-4 rounded-4" style="backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                        <p class="text-white text-opacity-75 small fw-bold mb-2 text-uppercase" style="font-size: 0.7rem;">Sisa Belum Dialokasikan</p>
                                        <h4 class="fw-bold mb-0 {{ $sisaBebas < 0 ? 'text-warning' : 'text-white' }}" style="font-size: 1.8rem;">
                                            Rp {{ number_format($sisaBebas, 0, ',', '.') }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="bg-white bg-opacity-10 p-4 rounded-4" style="backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                        <p class="text-white text-opacity-75 small fw-bold mb-2 text-uppercase" style="font-size: 0.7rem;">Total Dialokasikan</p>
                                        <h4 class="fw-bold mb-0 text-white" style="font-size: 1.8rem;">Rp {{ number_format($totalAlokasi, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row mb-4 g-4">
        <!-- Saldo Belanja Gudang -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg text-white h-100 overflow-hidden rounded-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="card-body p-5">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div>
                            <p class="text-white text-opacity-75 text-uppercase small fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em;">Saldo Belanja Gudang</p>
                            <h3 class="fw-bold mb-0 text-white" style="font-size: 2.2rem;">Rp {{ number_format($saldoGudang ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-white p-3 rounded-3 shadow-lg" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <div style="font-size: 2rem; color: #10b981;">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top border-white border-opacity-20">
                        <small class="text-white text-opacity-75 d-block">
                            <i class="fas fa-info-circle me-1 text-white"></i> Terpotong otomatis saat Penerimaan Barang
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Dana Dialokasikan -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg text-white h-100 overflow-hidden rounded-4" style="background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);">
                <div class="card-body p-5">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div>
                            <p class="text-white text-opacity-75 text-uppercase small fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.08em;">Alokasi Terpakai</p>
                            <h3 class="fw-bold mb-3 text-white" style="font-size: 2.2rem;">Rp {{ number_format($totalAlokasi, 0, ',', '.') }}</h3>
                            <div class="progress" style="height: 8px; background: rgba(255,255,255,0.2); border-radius: 10px;">
                                <div class="progress-bar bg-white shadow-sm" role="progressbar"
                                    style="width: {{ $persenTerpakai }}%; border-radius: 10px;"
                                    aria-valuenow="{{ $persenTerpakai }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-3 rounded-3 shadow-lg" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <div style="font-size: 2rem; color: #0ea5e9;">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top border-white border-opacity-20">
                        <p class="text-white text-opacity-75 small mb-0">
                            <strong>{{ number_format($persenTerpakai, 1) }}%</strong> dari Total Modal Utama
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5 g-4">
        <!-- Form Create Allocation -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #4e73df, #2452ba);">
                    <h6 class="m-0 fw-bold"><i class="fas fa-plus-circle me-2"></i>Buat Alokasi Dana</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.budget.allocation') }}" method="POST" id="formAlokasi" data-saldo="{{ $sisaBebas }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">Nama Pos (Misal: Gaji, Belanja)</label>
                            <input type="text" name="nama_alokasi" class="form-control border-0 bg-light rounded-3 py-2" placeholder="Masukkan nama pos..." required style="transition: all 0.3s;">
                            <div class="form-text mt-2 small text-muted">
                                <i class="fas fa-lightbulb me-1"></i> Ketik <strong>"Belanja"</strong> untuk mengirim ke Gudang.
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">Nominal (Rp)</label>
                            <input type="text" class="form-control border-0 bg-light rounded-3 py-2 format-rupiah" data-target="#nominal_alokasi" placeholder="0" required id="inputNominal" style="transition: all 0.3s;">
                            <input type="hidden" name="nominal" id="nominal_alokasi">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">Keterangan</label>
                            <textarea name="keterangan" class="form-control border-0 bg-light rounded-3 py-2" rows="2" placeholder="Opsional (catatan tambahan)"></textarea>
                        </div>
                        <button type="submit" class="btn w-100 fw-bold py-2 rounded-3" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; transition: all 0.3s;">
                            <i class="fas fa-check-circle me-2"></i>Simpan Alokasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Allocation List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-dark"><i class="fas fa-list me-2" style="color: #4e73df;"></i>Daftar Alokasi Aktif</h6>
                    <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: linear-gradient(135deg, #4e73df, #2452ba); color: white;">
                        {{ $allocations->count() }} Pos
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                                <tr>
                                    <th class="ps-4 fw-bold text-dark" style="font-size: 0.875rem;">Nama Pos</th>
                                    <th class="fw-bold text-dark" style="font-size: 0.875rem;">Nominal</th>
                                    <th class="fw-bold text-dark" style="font-size: 0.875rem;">Tujuan</th>
                                    <th class="fw-bold text-dark" style="font-size: 0.875rem;">Catatan</th>
                                    <th class="text-center fw-bold text-dark" style="font-size: 0.875rem;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $alloc)
                                <tr style="border-bottom: 1px solid #e9ecef; transition: all 0.2s;">
                                    <td class="ps-4 py-3">
                                        <span class="fw-bold text-dark d-block">{{ $alloc->nama_alokasi }}</span>
                                        <small class="text-muted">{{ $alloc->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-bold" style="color: #4e73df; font-size: 1.05rem;">
                                            Rp {{ number_format($alloc->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @if(str_contains(strtolower($alloc->nama_alokasi), 'belanja'))
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                                            <i class="fas fa-warehouse me-1"></i> GUDANG
                                        </span>
                                        @else
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(107, 114, 128, 0.1); color: #6b7280; border: 1px solid rgba(107, 114, 128, 0.3);">
                                            <i class="fas fa-user-tie me-1"></i> UMUM
                                        </span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <small class="text-muted">{{ Str::limit($alloc->keterangan ?? '-', 30) }}</small>
                                    </td>
                                    <td class="text-center py-3">
                                        <form action="{{ route('admin.budget.destroyAllocation', $alloc->id) }}" method="POST" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-circle" style="width: 36px; height: 36px; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; transition: all 0.2s;" onclick="return confirm('Hapus alokasi ini?')" title="Hapus alokasi">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                                        Belum ada alokasi dana. Silakan buat alokasi baru.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Riwayat Transaksi -->
         <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.budget.index') }}"> {{-- Sesuaikan dengan nama route index kamu --}}
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-dark">Dari Tanggal</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                <input type="date" name="start_date" class="form-control form-control-sm border-start-0" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-dark">Sampai Tanggal</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar-check text-muted"></i></span>
                                <input type="date" name="end_date" class="form-control form-control-sm border-start-0" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-dark">Kategori</label>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">Semua Kategori</option>
                                <option value="anggaran_masuk" {{ request('category') == 'anggaran_masuk' ? 'selected' : '' }}>Anggaran Masuk</option>
                                <option value="kirim_gudang" {{ request('category') == 'kirim_gudang' ? 'selected' : '' }}>Kirim ke Gudang</option>
                                <option value="penerimaan_barang" {{ request('category') == 'penerimaan_barang' ? 'selected' : '' }}>Penerimaan Barang</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill fw-bold">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.budget.index') }}" class="btn btn-outline-secondary btn-sm flex-fill fw-bold">
                                <i class="fas fa-undo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark">
                    <i class="fas fa-history me-2" style="color: #4e73df;"></i>Riwayat Transaksi Anggaran
                </h6>
                <a href="{{ route('admin.budget.export', request()->all()) }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-file-excel me-2"></i>Export Excel
                </a>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                            <tr>
                                <th class="ps-4 fw-bold text-dark" style="font-size: 0.875rem;">Tanggal</th>
                                <th class="fw-bold text-dark" style="font-size: 0.875rem;">Kategori</th>
                                <th class="fw-bold text-dark" style="font-size: 0.875rem;">Sumber Dana</th>
                                <th class="fw-bold text-dark" style="font-size: 0.875rem;">Nominal</th>
                                <th class="fw-bold text-dark" style="font-size: 0.875rem;">Keterangan</th>
                                <th class="text-center fw-bold text-dark" style="font-size: 0.875rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                            <tr style="border-bottom: 1px solid #e9ecef; transition: all 0.2s;">
                                <td class="ps-4 py-3">
                                    <span class="d-block fw-bold text-dark small">{{ $trx->created_at->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $trx->created_at->format('H:i') }} WIB</small>
                                </td>

                                <td class="py-3">
                                    @if(str_contains(strtolower($trx->kategori), 'alokasi') || $trx->kategori == 'Kirim Saldo ke gudang')
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(15, 118, 110, 0.15); color: #0f766e;">Kirim ke Gudang</span>
                                    @elseif($trx->kategori == 'Belanja Bahan Baku' || $trx->kategori == 'penerimaan gudang')
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(180, 83, 9, 0.15); color: #b45309;">Penerimaan Barang</span>
                                    @elseif($trx->kategori == 'Modal Utama' || $trx->tipe == 'masuk')
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">Anggaran Masuk</span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(107, 114, 128, 0.15); color: #6b7280;">{{ $trx->kategori }}</span>
                                    @endif
                                </td>

                                <td class="py-3">
                                    <small class="text-muted"><i class="fas fa-building me-1" style="color: #4e73df;"></i> {{ $trx->sumber_dana }}</small>
                                </td>

                                <td class="py-3">
                                    <span class="fw-bold" style="font-size: 0.95rem;">
                                        @if($trx->tipe == 'keluar' || $trx->kategori == 'Belanja Bahan Baku' || str_contains(strtolower($trx->kategori), 'alokasi'))
                                            <span style="color: #dc2626;">- Rp {{ number_format(abs($trx->nominal), 0, ',', '.') }}</span>
                                        @else
                                            <span style="color: #10b981;">+ Rp {{ number_format(abs($trx->nominal), 0, ',', '.') }}</span>
                                        @endif
                                    </span>
                                </td>

                                <td class="py-3">
                                    <small class="text-muted">
                                        @if($trx->kategori == 'Belanja Bahan Baku')
                                            Belanja stok dari supplier
                                        @else
                                            {{ Str::limit($trx->keterangan ?? '-', 25) }}
                                        @endif
                                    </small>
                                </td>

                                <td class="text-center py-3">
                                    @if($trx->status_enable)
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">✓ Aktif</span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: rgba(107, 114, 128, 0.15); color: #6b7280;">Hidden</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-scroll fa-2x mb-2 d-block text-secondary" style="opacity: 0.5;"></i>
                                    Tidak ada data transaksi ditemukan untuk filter ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($transactions->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $transactions->links() }}
                </div>
            </div>
            @endif
        </div>
</div>

<!-- Modal Input Anggaran Masuk -->
<div class="modal fade" id="modalTambahAnggaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-xl rounded-4 overflow-hidden">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); padding: 1.5rem 2rem; min-height: 90px; display: flex; align-items: center;">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div class="bg-white bg-opacity-20 p-3 rounded-3 mb-2" style="backdrop-filter: blur(10px);">
                        <div class="text-center">
                            <div style="color: #10b981;">
                                <i class="fas fa-coins fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" style="color: rgba(255,255,255,0.8); font-size: 1.3rem; margin: 0;">Input Anggaran Masuk</h5>
                        <small style="color: rgba(255,255,255,0.8); display: block;">Catat modal proyek yang masuk</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.budget.store') }}" method="POST">
                @csrf
                <div class="modal-body p-5">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Nominal Anggaran (Rp)</label>
                        <input type="text" class="form-control border-0 bg-light form-control-lg rounded-3 py-3 format-rupiah" data-target="#nominal_anggaran" placeholder="0" required style="transition: all 0.3s;">
                        <input type="hidden" name="nominal" id="nominal_anggaran">
                        <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i> Nominal akan otomatis diformat</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Kategori</label>
                        <select name="kategori" class="form-select border-0 bg-light form-select-lg rounded-3 py-3" required style="transition: all 0.3s;">
                            <option value="Modal Utama">Modal Utama</option>
                            <option value="Dana Operasional">Dana Operasional</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Sumber Dana</label>
                        <input type="text" name="sumber_dana" class="form-control border-0 bg-light form-control-lg rounded-3 py-3" placeholder="Misal: Bank Mandiri, BNI, Tunai" required style="transition: all 0.3s;">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-dark">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control border-0 bg-light form-control-lg rounded-3 py-3" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light gap-2 p-4">
                    <button type="button" class="btn fw-bold px-4 py-2 rounded-3" style="background: #e5e7eb; color: #374151; border: none;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background: linear-gradient(135deg, #4e73df, #2452ba); border: none;">
                        <i class="fas fa-check-circle me-2"></i>Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Error Saldo Tidak Cukup -->
<div class="modal fade" id="modalErrorSaldo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-4 overflow-hidden">
            <div class="modal-body p-5 text-center" style="background: linear-gradient(135deg, #fef2f2, #fff7ed);">
                <!-- Icon & Title -->
                <div class="mb-4">
                    <div class="display-1 text-danger mb-3" style="animation: pulse 2s infinite;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.3rem;">Saldo Tidak Mencukupi</h5>
                    <p class="text-muted mb-0">Dana alokasi melebihi saldo utama yang tersedia</p>
                </div>

                <!-- Info Boxes - Responsive Layout -->
                <div class="alert border-0 rounded-3 mb-4 p-4" style="background: white; border: 2px solid #fee2e2 !important;">
                    <!-- Desktop Layout: 3 Columns -->
                    <div class="row text-center g-3 d-none d-md-flex">
                        <!-- Saldo Saat Ini -->
                        <div class="col-md-4">
                            <div style="padding: 16px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; border: 2px solid rgba(16, 185, 129, 0.3);">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.75rem; color: #10b981;">SALDO SAAT INI</small>
                                <h6 class="fw-bold mb-0 mt-2" style="color: #10b981; font-size: 1.1rem; word-break: break-word;" id="display-saldo-desktop">Rp 0</h6>
                            </div>
                        </div>

                        <!-- VS Icon -->
                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                            <div style="font-size: 1.5rem; color: #dc2626; font-weight: bold;">VS</div>
                        </div>

                        <!-- Diminta -->
                        <div class="col-md-4">
                            <div style="padding: 16px; background: rgba(220, 38, 38, 0.1); border-radius: 12px; border: 2px solid rgba(220, 38, 38, 0.3);">
                                <small class="text-muted d-block fw-bold" style="font-size: 0.75rem; color: #dc2626;">DIMINTA</small>
                                <h6 class="fw-bold mb-0 mt-2" style="color: #dc2626; font-size: 1.1rem; word-break: break-word;" id="display-nominal-desktop">Rp 0</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Layout: Stacked -->
                    <div class="d-md-none">
                        <!-- Saldo Saat Ini -->
                        <div style="padding: 12px; background: rgba(16, 185, 129, 0.1); border-radius: 12px; border: 2px solid rgba(16, 185, 129, 0.3); margin-bottom: 12px;">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.7rem; color: #10b981;">SALDO SAAT INI</small>
                            <h6 class="fw-bold mb-0 mt-1" style="color: #10b981; font-size: 1.05rem; word-break: break-word;" id="display-saldo-mobile">Rp 0</h6>
                        </div>

                        <!-- Diminta -->
                        <div style="padding: 12px; background: rgba(220, 38, 38, 0.1); border-radius: 12px; border: 2px solid rgba(220, 38, 38, 0.3); margin-bottom: 12px;">
                            <small class="text-muted d-block fw-bold" style="font-size: 0.7rem; color: #dc2626;">DIMINTA</small>
                            <h6 class="fw-bold mb-0 mt-1" style="color: #dc2626; font-size: 1.05rem; word-break: break-word;" id="display-nominal-mobile">Rp 0</h6>
                        </div>
                    </div>

                    <!-- Kurang - Full Width -->
                    <div style="padding: 14px; background: #fffbeb; border-radius: 12px; border-left: 5px solid #fbbf24; margin-top: 16px;">
                        <small class="text-muted d-block fw-bold" style="font-size: 0.75rem; color: #92400e;">TOTAL KURANG</small>
                        <h5 class="fw-bold mb-0 mt-2" style="color: #dc2626; font-size: 1.35rem; word-break: break-word;" id="display-kurang">Rp 0</h5>
                    </div>
                </div>

                <!-- Solusi Box -->
                <div class="alert border-0 rounded-3 p-3" style="background: #eff6ff; border-left: 4px solid #3b82f6 !important;">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-lightbulb me-2" style="color: #3b82f6;"></i>
                        <strong style="color: #1e40af;">Solusi:</strong> Tambahkan anggaran masuk atau kurangi nominal alokasi
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 bg-light gap-2 p-4">
                <button type="button" class="btn fw-bold px-4 py-2 rounded-3 w-100"
                    style="background: #f3f4f6; color: #374151; border: none; transition: all 0.3s;"
                    data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formAlokasi = document.getElementById('formAlokasi');
        const inputNominal = document.getElementById('inputNominal');
        const nominalAlokasiHidden = document.getElementById('nominal_alokasi');
        const saldoSaatIni = parseFloat(formAlokasi.getAttribute('data-saldo')) || 0;

        // Format Rupiah Function
        function formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(num);
        }

        // Validasi sebelum submit
        formAlokasi.addEventListener('submit', function(e) {
            e.preventDefault();

            const nominalDiminta = parseInt(nominalAlokasiHidden.value) || 0;

            // Jika nominal > saldo, tampilkan modal error
            if (nominalDiminta > saldoSaatIni) {
                const kurang = nominalDiminta - saldoSaatIni;

                // Update data di modal untuk Desktop version
                document.getElementById('display-saldo-desktop').textContent = formatRupiah(saldoSaatIni);
                document.getElementById('display-nominal-desktop').textContent = formatRupiah(nominalDiminta);

                // Update data di modal untuk Mobile version
                document.getElementById('display-saldo-mobile').textContent = formatRupiah(saldoSaatIni);
                document.getElementById('display-nominal-mobile').textContent = formatRupiah(nominalDiminta);

                // Update kurang (sama untuk keduanya)
                document.getElementById('display-kurang').textContent = formatRupiah(kurang);

                // Tampilkan modal
                const modal = new bootstrap.Modal(document.getElementById('modalErrorSaldo'));
                modal.show();
            } else {
                // Jika cukup, lanjut submit
                formAlokasi.submit();
            }
        });

        // Format Rupiah Input
        const rupiahInputs = document.querySelectorAll('.format-rupiah');
        rupiahInputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/[^0-9]/g, '');
                let targetSelector = this.getAttribute('data-target');
                let targetInput = document.querySelector(targetSelector);

                if (targetInput) {
                    targetInput.value = value;
                }

                if (value) {
                    this.value = parseInt(value, 10).toLocaleString('id-ID');
                } else {
                    this.value = '';
                }
            });
        });
    });
</script>

@push('scripts')
<style>
    @keyframes pulse {
        0%,100% { opacity:1; }
        50%      { opacity:0.7; }
    }
</style>
@endpush
@endsection
