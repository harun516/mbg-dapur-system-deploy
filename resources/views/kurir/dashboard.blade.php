<x-app-layout>
    {{-- ============ HEADER ============ --}}
    <div class="pb-4 mb-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">Dashboard Kurir</h1>
            <p class="text-muted mb-0">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <span class="badge bg-white border text-dark px-3 py-2 rounded-3 shadow-sm fs-6">
            <i class="fas fa-calendar-alt text-primary me-1"></i> {{ date('d M Y') }}
        </span>
    </div>

    {{-- ============ STATS CARDS ============ --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-info h-100 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 mb-3"
                         style="width:52px;height:52px;">
                        <i class="fas fa-motorcycle text-primary fs-4"></i>
                    </div>
                    <h6 class="text-muted fw-bold">Tugas Aktif</h6>
                    <h2 class="fw-bold mb-0 card-value">{{ $myActiveJobs->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-warning h-100 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 mb-3"
                         style="width:52px;height:52px;">
                        <i class="fas fa-clock text-warning fs-4"></i>
                    </div>
                    <h6 class="text-muted fw-bold">Siap Antar</h6>
                    <h2 class="fw-bold mb-0 card-value">{{ $availableJobs->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-success h-100 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 mb-3"
                         style="width:52px;height:52px;">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                    <h6 class="text-muted fw-bold">Selesai Hari Ini</h6>
                    <h2 class="fw-bold mb-0 card-value">{{ $totalSelesaiHariIni ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ MAIN SECTION ============ --}}
    <div class="row g-4">
        {{-- Active Jobs --}}
        <div class="col-lg-7">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-route text-primary me-2"></i> Tugas Sedang Diantar
            </h5>

            @forelse($myActiveJobs as $job)
                <div class="card border-0 shadow-sm rounded-4 mb-3" style="border-left:5px solid #2563eb !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $job->recipient->nama_sekolah }}</h6>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                    {{ $job->recipient->alamat }}
                                </p>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 small">
                                <i class="fas fa-shipping-fast me-1"></i> Diantar
                            </span>
                        </div>

                        <div class="row text-center bg-light rounded-3 p-3 mb-3 g-0">
                            <div class="col-6 border-end">
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size:0.65rem;letter-spacing:1px;">Menu</small>
                                <span class="fw-bold text-dark small">{{ $job->productionPlan->menu->nama_menu }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block fw-bold text-uppercase" style="font-size:0.65rem;letter-spacing:1px;">Porsi</small>
                                <span class="fw-bold text-primary small">{{ $job->productionPlan->total_porsi_target }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <a href="{{ route('kurir.delivery.print', $job->id) }}" target="_blank"
                               class="btn btn-outline-secondary btn-sm w-100">
                                <i class="fas fa-print me-1"></i> Cetak Surat Jalan
                            </a>
                        </div>

                        <form action="{{ route('kurir.delivery.complete', $job->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Unggah Bukti (Foto)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-camera text-muted"></i></span>
                                    <input type="file" name="foto_bukti" class="form-control" accept="image/*" capture="camera" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                <i class="fas fa-check-circle me-1"></i> SELESAI DISTRIBUSI
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm bg-white rounded-4">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-5 text-center">
                        <i class="fas fa-motorcycle fa-3x text-muted mb-3" style="opacity:0.3;"></i>
                        <p class="text-muted mb-0">Anda belum memiliki tugas aktif.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Available Queue --}}
        <div class="col-lg-5">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-list-ul text-warning me-2"></i> Antrean Siap Antar
            </h5>

            @forelse($availableJobs as $available)
                <div class="card border-0 shadow-sm rounded-3 mb-2">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <h6 class="mb-1 fw-bold text-dark">{{ $available->recipient->nama_lembaga }}</h6>
                                <p class="mb-2 small text-muted">
                                    <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                    {{ Str::limit($available->recipient->alamat, 40) }}
                                </p>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-bold"
                                      style="font-size:0.75rem;">
                                    {{ $available->productionPlan->total_porsi_target }} Porsi
                                </span>
                            </div>
                            <form action="{{ route('kurir.delivery.take', $available->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold">Ambil</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm rounded-4 bg-light text-center py-5">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted small mb-0">Belum ada paket baru saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>