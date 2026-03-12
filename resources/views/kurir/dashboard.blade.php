<x-app-layout>
    <style>
        /* 1. Dashboard & Layout General */
        .icon-box {
            width: 32px; height: 32px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 8px; font-size: 0.9rem;
        }
        .bg-primary-soft { background: #e1f0ff; color: #007bff; }
        .bg-warning-soft { background: #fff4de; color: #ffa800; }
        .bg-success-soft { background: #c9f7f5; color: #1bc5bd; }

        /* 2. Card Dashboard (Statistik) */
        .card-dashboard {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        .card-accent-left.card-info { border-left: 5px solid #11cdef; }
        .card-accent-left.card-warning { border-left: 5px solid #fb6340; }
        .card-accent-left.card-success { border-left: 5px solid #2dce89; }
        .card-value { font-size: 2rem; color: #32325d; }

        /* 3. Filter Wrapper (Modern Style) */
        .filter-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #ebedf3 !important;
        }
        .form-control-modern, .form-select-modern {
            height: 40px;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #3f4254;
        }
        .form-control-modern:focus, .form-select-modern:focus {
            border-color: #3699ff;
            box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
        }

        /* 4. Active Job Card */
        .card-delivery { border-radius: 16px; transition: transform 0.2s; }
        .active-job { border-left: 5px solid #0d6efd !important; }
        .info-job-box { background-color: #f8f9fa; border: 1px solid #edf2f7; }
        .small-header { font-size: 0.65rem; letter-spacing: 1px; margin-bottom: 2px; }

        /* 5. Buttons (Modern & Rounded) */
        .btn-primary-modern { background: #3699ff; border: none; color: white; border-radius: 8px; font-weight: 600; height: 40px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; }
        .btn-primary-modern:hover { background: #187de4; transform: translateY(-1px); }
        
        .btn-success-modern { background: #1bc5bd; border: none; color: white; border-radius: 10px; font-weight: 700; transition: 0.3s; }
        .btn-success-modern:hover { background: #17a8a2; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(27, 197, 189, 0.3); }

        .btn-light-modern { background: #f3f6f9; border: 1px solid #e5eaee; color: #7e8299; width: 40px; height: 40px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; }

        /* 6. Empty States (Center Aligned) */
        .empty-task-container { min-height: 250px; text-align: center; }
        .img-empty-task { width: 150px; height: auto; opacity: 0.6; display: block; margin: 0 auto 15px auto; }
        
        .empty-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; padding: 60px 20px; background: #ffffff;
            border-radius: 16px; border: 2px dashed #e5e7eb; margin: 20px auto; max-width: 800px;
        }
        .empty-state img { width: 250px; height: auto; margin-bottom: 20px; display: block; }

        /* 7. Queue List & Status */
        .queue-item { border-radius: 12px; transition: all 0.2s; }
        .queue-item:hover { background-color: #fcfcfc; transform: translateX(5px); }
        .badge-porsi-sm { background: #e1e9ff; color: #3699ff; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; }
        .status-pill { padding: 5px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .status-warning { background: #fff4de; color: #ffa800; }

        @media (max-width: 768px) {
            .btn-success-modern { padding: 12px; }
            .card-value { font-size: 1.5rem; }
        }
    </style>

    <div class="pb-4 mb-4 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-bold text-gray-900 mb-1">Dashboard Kurir</h1>
            <p class="text-muted mb-0">Selamat datang, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
        <span class="badge bg-white border text-dark p-2 rounded-3 shadow-sm">
            <i class="fas fa-calendar-alt text-primary me-1"></i> {{ date('d M Y') }}
        </span>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-info h-100 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-primary-soft mb-3 mx-auto" style="width: 50px; height: 50px;">
                        <i class="fas fa-motorcycle fa-lg"></i>
                    </div>
                    <h6 class="text-muted fw-bold">Tugas Aktif</h6>
                    <h2 class="card-value fw-bold mb-0">{{ $myActiveJobs->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-warning h-100 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-warning-soft mb-3 mx-auto" style="width: 50px; height: 50px;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <h6 class="text-muted fw-bold">Siap Antar</h6>
                    <h2 class="card-value fw-bold mb-0">{{ $availableJobs->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-dashboard card-accent-left card-success h-100 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-success-soft mb-3 mx-auto" style="width: 50px; height: 50px;">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <h6 class="text-muted fw-bold">Selesai Hari Ini</h6>
                    <h2 class="card-value fw-bold mb-0">{{ $totalSelesaiHariIni ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                <span class="icon-box bg-primary-soft text-primary me-2"><i class="fas fa-route"></i></span>
                Tugas Sedang Diantar
            </h5>

            @forelse($myActiveJobs as $job)
                <div class="card card-delivery active-job border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $job->recipient->nama_sekolah }}</h6>
                                <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $job->recipient->alamat }}</p>
                            </div>
                            <span class="status-pill status-warning"><i class="fas fa-shipping-fast me-1"></i> Diantar</span>
                        </div>
                        
                        <div class="info-job-box p-3 rounded-3 mb-3">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <small class="text-muted d-block text-uppercase small-header">Menu</small>
                                    <span class="fw-bold text-dark">{{ $job->productionPlan->menu->nama_menu }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block text-uppercase small-header">Porsi</small>
                                    <span class="fw-bold text-primary">{{ $job->productionPlan->total_porsi_target }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <a href="{{ route('kurir.delivery.print', $job->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="fas fa-print me-1"></i> Cetak Surat Jalan
                            </a>
                        </div>

                        <form action="{{ route('kurir.delivery.complete', $job->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Unggah Bukti (Foto)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-camera text-muted"></i></span>
                                    <input type="file" name="foto_bukti" class="form-control form-control-modern border-start-0" accept="image/*" capture="camera" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success-modern w-100 py-2 shadow-sm">
                                <i class="fas fa-check-circle me-1"></i> SELESAI DISTRIBUSI
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm bg-white rounded-4 empty-task-container">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                        <img src="{{ asset('images/icons1/empty-task.svg') }}" class="img-empty-task">
                        <p class="text-muted mb-0 italic">Anda belum memiliki tugas aktif.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="col-lg-5">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                <span class="icon-box bg-warning-soft text-warning me-2"><i class="fas fa-list-ul"></i></span>
                Antrean Siap Antar
            </h5>
            
            <div class="job-queue">
                @forelse($availableJobs as $available)
                    <div class="card mb-2 border-0 shadow-sm queue-item">
                        <div class="card-body p-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="me-3">
                                    <h6 class="mb-1 fw-bold text-dark">{{ $available->recipient->nama_lembaga }}</h6>
                                    <p class="mb-2 small text-muted lh-sm"><i class="fas fa-map-marker-alt me-1 text-primary"></i> {{ Str::limit($available->recipient->alamat, 40) }}</p>
                                    <span class="badge-porsi-sm">{{ $available->productionPlan->total_porsi_target }} Porsi</span>
                                </div>
                                <form action="{{ route('kurir.delivery.take', $available->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary-modern btn-sm px-4 shadow-sm">Ambil</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border-2 border-dashed">
                        <i class="fas fa-box-open fa-2x text-light mb-2"></i>
                        <p class="text-muted small mb-0">Belum ada paket baru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>