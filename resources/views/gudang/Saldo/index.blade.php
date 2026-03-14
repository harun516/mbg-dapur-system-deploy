<x-app-layout>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-5 col-lg-4">
            <!-- Saldo Card: Using native BS5 utilities instead of custom CSS -->
            <div class="card border-0 shadow-lg text-white rounded-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); transition: transform 0.3s ease;">
                <!-- Decorative Icon (using absolute positioning) -->
                <i class="fas fa-warehouse position-absolute" style="font-size: 14rem; right: -40px; bottom: -40px; opacity: 0.1; transform: rotate(-15deg);"></i>
                
                <div class="card-body p-4 p-lg-5 text-center position-relative z-index-1">
                    <h6 class="text-white-50 fw-bold mb-3" style="letter-spacing: 1px;">SALDO OPERASIONAL GUDANG</h6>
                    <h2 class="display-4 fw-bold text-white mb-4" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        Rp {{ number_format($saldoGudang, 0, ',', '.') }}
                    </h2>
                    <button class="btn btn-light btn-lg fw-bold text-success shadow-sm rounded-pill px-4 py-2 mt-2 w-100" style="transition: all 0.2s ease;" data-bs-toggle="modal" data-bs-target="#modalRequest">
                        <i class="fas fa-paper-plane me-2"></i> Request Tambah Dana
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom border-light d-flex align-items-center">
                    <i class="fas fa-download text-primary me-2 fs-5"></i>
                    <h6 class="mb-0 fw-bold text-dark">Riwayat Alokasi dari Admin</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Tanggal</th>
                                    <th class="py-3">Keterangan</th>
                                    <th class="text-end pe-4 py-3">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse($riwayatMasuk as $item)
                                <tr>
                                    <td class="ps-4 py-3 text-muted">{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 fw-medium text-dark">{{ $item->nama_alokasi }}</td>
                                    <td class="text-end pe-4 py-3 text-success fw-bold">+Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada riwayat alokasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom border-light d-flex align-items-center">
                    <i class="fas fa-clock text-warning me-2 fs-5"></i>
                    <h6 class="mb-0 fw-bold text-dark">Status Pengajuan Dana</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($riwayatRequest as $req)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-4 border-bottom border-light">
                            <div>
                                <small class="text-muted d-block mb-1"><i class="far fa-calendar-alt me-1"></i>{{ $req->created_at->format('d M Y') }}</small>
                                <strong class="text-dark d-block mb-1">{{ $req->perihal }}</strong>
                                <div class="text-primary fw-bold">Rp {{ number_format($req->nominal, 0, ',', '.') }}</div>
                            </div>
                            @php
                                $badgeColor = $req->status == 'pending' ? 'warning' : ($req->status == 'disetujui' ? 'success' : 'danger');
                            @endphp
                            <span class="badge rounded-pill bg-{{ $badgeColor }} px-3 py-2 text-uppercase fw-semibold shadow-sm">
                                {{ $req->status }}
                            </span>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-5 text-muted">
                            Belum ada pengajuan dana.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Request Anggaran -->
<div class="modal fade" id="modalRequest" tabindex="-1" aria-labelledby="modalRequestLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('gudang.saldo.request') }}" method="POST" class="modal-content border-0 shadow rounded-4 overflow-hidden">
            @csrf
            <div class="modal-header bg-light border-bottom-0 py-3 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalRequestLabel"><i class="fas fa-file-invoice-dollar text-success me-2"></i>Form Pengajuan Anggaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Perihal / Keperluan</label>
                    <input type="text" name="perihal" class="form-control form-control-lg bg-light border-0 shadow-none" placeholder="Contoh: Belanja Stok Beras" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary">Nominal yang Diminta</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-light border-0 text-muted fw-bold">Rp</span>
                        <input type="number" name="nominal" class="form-control bg-light border-0 shadow-none fw-bold text-dark" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary">Alasan Singkat (Opsional)</label>
                    <textarea name="alasan" class="form-control bg-light border-0 shadow-none" rows="3" placeholder="Jelaskan secara singkat..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-4 px-4 bg-white">
                <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                    <i class="fas fa-paper-plane me-1"></i> Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>