<x-app-layout>
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-school me-2"></i>Daftar Penerima MBG
            </h5>
            <a href="{{ route('admin.recipient.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Penerima
            </a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Lembaga & Pimpinan</th>
                            <th>Alamat</th>
                            <th>PIC (Koordinator)</th>
                            <th class="text-center">Porsi</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recipients as $r)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block">{{ $r->nama_lembaga }}</span>
                                <small class="text-muted"><i class="fas fa-user-tie me-1"></i> Pimpinan: {{ $r->pimpinan ?? '-' }}</small>
                            </td>
                            <td class="small text-muted" style="max-width: 250px; white-space: normal;">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $r->alamat }}
                            </td>
                            <td>
                                <span class="d-block fw-semibold text-dark">{{ $r->nama_pic }}</span>
                                <small class="text-success fw-bold"><i class="fas fa-phone-alt me-1"></i> {{ $r->no_hp_pic ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold" style="font-size: 0.9rem;">
                                    {{ number_format($r->jumlah_porsi, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($r->status_enable)
                                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="fas fa-minus-circle me-1"></i> Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.recipient.edit', $r->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-school fa-3x mb-3 d-block" style="opacity: 0.2;"></i>
                                Belum ada data penerima terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</x-app-layout>