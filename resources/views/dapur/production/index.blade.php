<x-app-layout>
<div class="container-fluid py-4">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Gagal:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-tasks me-2"></i>Monitoring Produksi Harian
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary text-primary">
                        <tr>
                            <th class="ps-4">Waktu & Menu</th>
                            <th>Jumlah Porsi</th>
                            <th>Status Saat Ini</th>
                            <th class="text-end pe-4">Aksi Perubahan Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productions as $prod)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block">{{ $prod->menu->nama_menu }}</span>
                                <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $prod->created_at->format('d M Y, H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                                    {{ number_format($prod->jumlah_porsi) }} Porsi
                                </span>
                            </td>
                            <td>
                                @php
                                    $bgClass = '';
                                    if($prod->status == 'Proses Masak') $bgClass = 'bg-warning text-dark';
                                    elseif($prod->status == 'Packing') $bgClass = 'bg-info text-white';
                                    else $bgClass = 'bg-success text-white';
                                @endphp
                                <span class="badge {{ $bgClass }} px-3 py-2 rounded-pill fw-semibold shadow-sm">
                                    {{ $prod->status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                @if($prod->status != 'Siap Distribusi')
                                    <form action="{{ route('production.updateStatus', $prod->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @php
                                            $nextStatus = '';
                                            $btnLabel = '';
                                            $btnIcon = '';
                                            if($prod->status == 'Menunggu Dapur') {
                                                $nextStatus = 'Proses Masak';
                                                $btnLabel = 'Mulai Masak';
                                                $btnIcon = 'fas fa-fire';
                                            } elseif($prod->status == 'Proses Masak') {
                                                $nextStatus = 'Packing';
                                                $btnLabel = 'Lanjut ke Packing';
                                                $btnIcon = 'fas fa-box';
                                            } elseif($prod->status == 'Packing') {
                                                $nextStatus = 'Siap Distribusi';
                                                $btnLabel = 'Siap Distribusi';
                                                $btnIcon = 'fas fa-truck-loading';
                                            }
                                        @endphp
                                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold">
                                            <i class="{{ $btnIcon }} me-1"></i> {{ $btnLabel }} &raquo;
                                        </button>
                                    </form>
                                @else
                                    <span class="text-success fw-bold">
                                        <i class="fas fa-check-circle me-1"></i> Selesai di Dapur
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x d-block mb-3" style="opacity: 0.2;"></i>
                                Belum ada aktivitas produksi hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($productions->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $productions->links() }}
        </div>
        @endif
    </div>
</div>
</x-app-layout>
