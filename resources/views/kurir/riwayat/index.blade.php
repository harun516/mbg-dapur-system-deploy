<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pengiriman') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4 px-3 px-lg-5">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">📦 Riwayat Pengiriman Saya</h4>
                <p class="text-muted small mb-0">Pantau semua distribusi yang telah Anda selesaikan.</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                    Total: {{ $riwayat->total() }} Data
                </span>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 1rem;">
            <div class="card-body p-4">
                <form action="{{ route('kurir.riwayat.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-secondary" style="letter-spacing: 0.5px;">Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="tanggal" class="form-control border-start-0 ps-0 shadow-none" value="{{ request('tanggal') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-secondary" style="letter-spacing: 0.5px;">Status</label>
                        <select name="status" class="form-select shadow-none">
                            <option value="">Semua Status</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                            <option value="Proses Antar" {{ request('status') == 'Proses Antar' ? 'selected' : '' }}>🚚 Proses</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-5 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                        <a href="{{ route('kurir.riwayat.index') }}" class="btn btn-light border w-100 fw-bold text-secondary shadow-sm">
                            <i class="fas fa-undo me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card shadow-sm border-0" style="border-radius: 1rem; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-black text-secondary">Waktu</th>
                                <th class="py-3 text-uppercase small fw-black text-secondary">Menu & Porsi</th>
                                <th class="py-3 text-uppercase small fw-black text-secondary">Tujuan</th>
                                <th class="py-3 text-uppercase small fw-black text-secondary">Status</th>
                                <th class="text-center py-3 text-uppercase small fw-black text-secondary">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($riwayat as $data)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $data->created_at->translatedFormat('d M Y') }}</div>
                                    <div class="text-muted x-small"><i class="far fa-clock me-1"></i>{{ $data->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    @if($data->productionPlan)
                                        <div class="fw-bold text-dark mb-0">
                                            {{ $data->productionPlan->menu->nama_menu ?? 'Menu Tidak Terdaftar' }}
                                        </div>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill" style="font-size: 0.7rem;">
                                            {{ $data->productionPlan->total_porsi_target ?? 0 }} Porsi
                                        </span>
                                    @else
                                        <span class="text-danger small"><i class="fas fa-exclamation-triangle me-1"></i>Data Plan Hilang</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-warning-subtle text-warning rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                            <i class="fas fa-school fs-6"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small text-truncate" style="max-width: 150px;">
                                                {{ $data->recipient->nama_lembaga ?? 'Nama Tujuan Kosong' }}
                                            </div>
                                            <div class="text-muted x-small">Tujuan Utama</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($data->status == 'Selesai')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Selesai
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
                                            <i class="fas fa-truck me-1"></i>Proses
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-white border shadow-sm dropdown-toggle px-3" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-cog me-1"></i> Opsi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                                            <li>
                                                <a class="dropdown-item rounded-2 py-2" href="{{ route('kurir.print-surat', $data->id) }}" target="_blank">
                                                    <i class="fas fa-file-invoice text-danger me-2"></i>Cetak Surat
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item rounded-2 py-2" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $data->id }}">
                                                    <i class="fas fa-camera text-primary me-2"></i>Bukti Foto
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                                    <p class="text-muted">Belum ada riwayat pengiriman.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $riwayat->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- SECTION MODAL: Ditaruh di luar semua elemen Card/Table --}}
    @foreach($riwayat as $modalData)
    <div class="modal fade" id="modalFoto{{ $modalData->id }}" tabindex="-1" aria-labelledby="label{{ $modalData->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                <div class="modal-header border-0 pb-0 px-4 pt-4 text-center">
                    <h5 class="fw-bold text-dark w-100" id="label{{ $modalData->id }}">
                        <i class="fas fa-image me-2 text-primary"></i>Bukti Pengiriman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    @if($modalData->foto_bukti)
                        <img src="{{ asset('storage/' . $modalData->foto_bukti) }}" class="img-fluid rounded-4 shadow-sm w-100 border" style="max-height: 400px; object-fit: contain; background: #f8f9fa;">
                    @else
                        <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                            <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">Foto bukti belum tersedia.</p>
                        </div>
                    @endif
                    
                    <div class="mt-4 p-3 bg-light rounded-3 border">
                        <div class="row text-start g-2 small">
                            <div class="col-6">
                                <span class="text-muted d-block uppercase x-small fw-bold">Waktu Sampai</span>
                                <span class="fw-bold text-dark">{{ $modalData->waktu_sampai ?? '-' }} WIB</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-muted d-block uppercase x-small fw-bold">Lokasi/Penerima</span>
                                <span class="fw-bold text-dark text-truncate d-block">{{ $modalData->recipient->nama_lembaga ?? 'Penerima Umum' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-secondary w-100 fw-bold py-2 rounded-3 shadow-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- CSS Custom --}}
    <style>
        .fw-black { font-weight: 800; }
        .x-small { font-size: 0.65rem; letter-spacing: 0.5px; }
        .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
        .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
        .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1) !important; }
        .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1) !important; }
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.015); transition: 0.2s; }
        .border-dashed { border-style: dashed !important; }
        .dropdown-item:active { background-color: #0d6efd; }
    </style>
</x-app-layout>