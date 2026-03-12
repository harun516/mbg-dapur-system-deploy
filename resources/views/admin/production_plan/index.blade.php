<x-app-layout>

<style>
/* ============== CSS Reset & General ============= */
body {
    background-color: #f3f6f9;
}

/* ============== Card Modern Style ============= */
.card-modern {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    overflow: hidden;
    background: #ffffff;
}

.header-title {
    color: #2c3e50;
    letter-spacing: -0.3px;
}

/* Table Design */
.table thead th {
    background-color: #f8f9fa;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding-top: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #ebedf3;
}

.table tbody td {
    padding-top: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f4f6f9;
    color: #464e5f;
    font-size: 0.9rem;
}

/* ==============FILTER ============ */
.filter-wrapper {
    background-color: #ffffff;
    border-radius: 12px;
    border: 1px solid #ebedf3 !important;
}

/* Input & Select Modern Style */
.form-control-modern, 
.form-select-modern {
    height: 40px;
    border: 1px solid #e0e6ed;
    border-radius: 8px;
    padding: 0.45rem 0.75rem;
    font-size: 0.875rem;
    color: #3f4254;
    transition: all 0.2s ease;
}

.form-control-modern:focus, 
.form-select-modern:focus {
    border-color: #3699ff;
    box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.1);
    outline: none;
}

/* Label Style */
.form-label-sm {
    margin-bottom: 0.4rem;
    display: block;
    letter-spacing: 0.2px;
}

/* Button Primary Modern */
.btn-primary-modern {
    background-color: #3699ff;
    border: none;
    color: #ffffff;
    height: 40px;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-primary-modern:hover {
    background-color: #187de4;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(54, 153, 255, 0.2);
    color: #ffffff;
}

/* Button Reset/Undo Modern */
.btn-light-modern {
    background-color: #f3f6f9;
    border: 1px solid #e5eaee;
    color: #7e8299;
    height: 40px;
    width: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-light-modern:hover {
    background-color: #ebedf3;
    color: #3699ff;
    border-color: #3699ff;
}

/* Responsive Fix */
@media (max-width: 768px) {
    .btn-light-modern {
        width: 100%; /* Tombol jadi lebar di mobile */
    }
}

/* ============= Badges================ */
.badge-porsi {
    background-color: #e1f0ff;
    color: #007bff;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 700;
    border: 1px solid #b5d8ff;
}

.status-pill {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

/* Status Colors Custom */
.status-draft { background-color: #f3f6f9; color: #7e8299; }
.status-info { background-color: #e1f0ff; color: #3699ff; }
.status-warning { background-color: #fff4de; color: #ffa800; }
.status-primary { background-color: #e1e9ff; color: #6993ff; }
.status-success { background-color: #c9f7f5; color: #1bc5bd; }
.status-danger { background-color: #ffe2e5; color: #f64e60; }

/* Buttons Custom */
.btn-primary-custom {
    background-color: #007bff;
    border: none;
    padding: 8px 16px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-primary-custom:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

.btn-success-light {
    background-color: #c9f7f5;
    color: #1bc5bd;
    border: none;
    font-weight: 600;
}

.btn-primary-soft {
    background-color: #e1f0ff;
    color: #007bff;
    border: none;
    font-weight: 600;
    font-size: 0.75rem;
}


/* ============Utilities=========== */
.bg-primary-gradient {
    background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
}

.avatar-sm {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: bold;
    font-size: 0.8rem;
}

.bg-soft-primary { background-color: #e1f0ff; }

/* Pulse Animation for Live Status */
.pulse-badge {
    animation: pulse-animation 2s infinite;
}

@keyframes pulse-animation {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.italic { font-style: italic; }
.fw-500 { font-weight: 500; }
.fw-600 { font-weight: 600; }
</style>


<div class="card card-modern mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
        <h6 class="mb-0 fw-bold header-title">
            <i class="fas fa-utensils me-2 text-primary"></i> Kalender Rencana Produksi
        </h6>
        <a href="{{ route('admin.production_plan.create') }}" class="btn btn-primary-custom btn-sm shadow-sm">
            <i class="fas fa-calendar-plus me-2"></i> Buat Rencana Baru
        </a>
    </div>

<!-- Filter Rencana Produksi -->
    <div class="filter-wrapper p-3 bg-white border-bottom">
        <form action="{{ route('admin.production_plan.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label-sm fw-bold text-muted small"><i class="fas fa-calendar-alt me-1"></i> Rencana Produksi</label>
                <input type="date" name="plan_date" class="form-control form-control-modern" value="{{ request('plan_date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label-sm fw-bold text-muted small"><i class="fas fa-utensils me-1"></i> Pilih Menu</label>
                <select name="plan_menu" class="form-select form-select-modern">
                    <option value="">-- Semua Menu --</option>
                    @foreach($menus as $m)
                        <option value="{{ $m->id }}" {{ request('plan_menu') == $m->id ? 'selected' : '' }}>{{ $m->nama_menu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary-modern flex-grow-1 shadow-sm"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.production_plan.index') }}" class="btn btn-light-modern shadow-sm"><i class="fas fa-undo"></i></a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3 alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal Rencana</th>
                        <th>Menu Masakan</th>
                        <th class="text-center">Target Porsi</th>
                        <th class="text-center">Status Produksi</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-rencana-produksi">
                    @forelse($plans as $plan)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ date('d M Y', strtotime($plan->tanggal_rencana)) }}</span>
                                <small class="text-muted">{{ date('l', strtotime($plan->tanggal_rencana)) }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary-dark">{{ $plan->menu->nama_menu }}</div>
                            @if($plan->catatan_admin)
                                <small class="text-muted italic"><i class="fas fa-sticky-note me-1"></i> {{ $plan->catatan_admin }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge-porsi">
                                {{ number_format($plan->total_porsi_target) }} Porsi
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusColor = [
                                    'Draft'             => 'status-draft',
                                    'Terkirim ke Dapur' => 'status-info',
                                    'Proses Masak'      => 'status-warning',
                                    'Packing'           => 'status-primary',
                                    'Selesai'           => 'status-success',
                                    'Dibatalkan'        => 'status-danger'
                                ][$plan->status] ?? 'bg-dark';
                            @endphp
                            <span class="status-pill {{ $statusColor }}">
                                <i class="fas fa-circle me-1" style="font-size: 8px;"></i> {{ $plan->status }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm gap-1">
                                @if($plan->status == 'Selesai')
                                    @php
                                        // Cek apakah rencana ini sudah dikirim ke monitoring kurir
                                        $isAlreadySent = \App\Models\Delivery::where('production_plan_id', $plan->id)->exists();
                                    @endphp

                                    @if($isAlreadySent)
                                        <button type="button" class="btn btn-light text-muted border-0" disabled style="cursor: not-allowed;">
                                            <i class="fas fa-check-double me-1 text-success"></i> Terkirim
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success-light" 
                                                data-bs-toggle="modal" data-bs-target="#modalKirimKurir{{ $plan->id }}">
                                            <i class="fas fa-truck me-1"></i> Kirim
                                        </button>
                                    @endif
                                @endif

                                @if($plan->status == 'Draft')
                                <form action="{{ route('admin.production_plan.update-status', $plan->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="Terkirim ke Dapur">
                                    <button type="submit" class="btn btn-outline-info" title="Kirim ke Dapur">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('admin.production_plan.edit', $plan->id) }}" class="btn btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.production_plan.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Hapus rencana ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <img src="{{ asset('images/empty-state.svg') }}" width="120" class="mb-3 d-block mx-auto opacity-50">
                            Belum ada rencana produksi hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MONITORING CARD DENGAN HEADER BERWARNA --}}
<div class="card card-modern border-0 shadow-sm">
    <div class="card-header bg-primary-gradient text-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="fas fa-shipping-fast me-2"></i> Monitoring Kurir Realtime</h6>
        <span class="badge pulse-badge bg-white text-primary">LIVE UPDATES</span>
    </div>

<!-- FILTERING -->
    <div class="filter-wrapper p-3 bg-white border-bottom mb-3">
    <form action="{{ route('admin.production_plan.index') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label-sm fw-bold text-muted small"><i class="fas fa-calendar-day me-1"></i> Tanggal</label>
            <input type="date" name="delivery_date" class="form-control form-control-modern" value="{{ request('delivery_date') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label-sm fw-bold text-muted small"><i class="fas fa-school me-1"></i> Sekolah</label>
            <select name="delivery_school" class="form-select form-select-modern">
                <option value="">-- Sekolah --</option>
                @foreach($recipients as $r)
                    <option value="{{ $r->id }}" {{ request('delivery_school') == $r->id ? 'selected' : '' }}>{{ $r->nama_lembaga }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label-sm fw-bold text-muted small"><i class="fas fa-user-tag me-1"></i> Kurir</label>
            <select name="delivery_courier" class="form-select form-select-modern">
                <option value="">-- Kurir --</option>
                @foreach($couriers as $c)
                    <option value="{{ $c->id }}" {{ request('delivery_courier') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label-sm fw-bold text-muted small"><i class="fas fa-info-circle me-1"></i> Status</label>
            <select name="delivery_status" class="form-select form-select-modern">
                <option value="">-- Status --</option>
                <option value="Menunggu Kurir" {{ request('delivery_status') == 'Menunggu Kurir' ? 'selected' : '' }}>Menunggu Kurir</option>
                <option value="Proses Antar" {{ request('delivery_status') == 'Proses Antar' ? 'selected' : '' }}>Proses Antar</option>
                <option value="Selesai" {{ request('delivery_status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary-modern flex-grow-1 shadow-sm"><i class="fas fa-search"></i></button>
            <a href="{{ route('admin.production_plan.index') }}" class="btn btn-light-modern shadow-sm"><i class="fas fa-undo"></i></a>
        </div>
    </form>
</div>


    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Sekolah Penerima</th>
                        <th>Menu & Porsi</th>
                        <th>Kurir</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Bukti</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-monitoring-kurir">
                    @forelse($deliveries ?? [] as $delivery)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">
                                {{ $delivery->recipient->nama_lembaga }}
                            </div>
                            
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i> 
                                {{ Str::limit($delivery->recipient->alamat, 40) }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border fw-600">
                                {{ $delivery->productionPlan->menu->nama_menu }}
                                <span class="ms-1 text-dark">({{ $delivery->productionPlan->total_porsi_target }} Porsi)</span>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2 bg-soft-primary text-primary">
                                    {{ substr($delivery->courier->name ?? 'M', 0, 1) }}
                                </div>
                                <span class="text-dark fw-500">{{ $delivery->courier->name ?? 'Mencari Kurir...' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $delivColor = [
                                    'Menunggu Kurir' => 'status-draft',
                                    'Proses Antar'   => 'status-warning',
                                    'Selesai'        => 'status-success',
                                    'Gagal'          => 'status-danger'
                                ][$delivery->status] ?? 'bg-dark';
                            @endphp
                            <span class="status-pill {{ $delivColor }}">
                                {{ $delivery->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($delivery->foto_bukti)
                                <a href="{{ asset('storage/'.$delivery->foto_bukti) }}" target="_blank" class="btn btn-xs btn-primary-soft">
                                    <i class="fas fa-image me-1"></i> Bukti
                                </a>
                            @else
                                <span class="badge bg-light text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($delivery->status_enable)
                            <button class="btn btn-sm btn-icon-only text-danger border-0" title="Batalkan">
                                <i class="fas fa-ban"></i>
                            </button>
                            <a href="{{ route('admin.delivery.print', $delivery->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                <i class="fas fa-print"></i> Surat Jalan
                            </a>                                                    
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small italic">Belum ada pengiriman aktif hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL PENGIRIMAN -->
 @foreach($plans as $plan)
    @if($plan->status == 'Selesai')
    <div class="modal fade" id="modalKirimKurir{{ $plan->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-truck me-2"></i> Kirim ke Kurir</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.delivery.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="production_plan_id" value="{{ $plan->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label fw-600">Pilih Sekolah Penerima</label>
                            <select name="recipient_id" class="form-select shadow-sm" required>
                                <option value="">-- Pilih Lembaga --</option>
                                @foreach($recipients as $recipient)
                                    <option value="{{ $recipient->id }}">{{ $recipient->nama_lembaga }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-light p-3 rounded mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Menu:</span>
                                <span class="fw-bold">{{ $plan->menu->nama_menu }}</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Total Porsi:</span>
                                <span class="fw-bold">{{ $plan->total_porsi_target }} Porsi</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4 fw-bold">Kirim Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<script>
    $(document).ready(function() {
        let isRefreshing = false;

        function refreshDashboards() {
            if (isRefreshing) return;
            isRefreshing = true;

            $.ajax({
                url: window.location.href,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    // Update Tabel Produksi (Atas)
                    var newProduction = $(response).find('#table-rencana-produksi').html();
                    $('#table-rencana-produksi').html(newProduction);

                    // Update Tabel Monitoring Kurir (Bawah)
                    var newDelivery = $(response).find('#table-monitoring-kurir').html();
                    $('#table-monitoring-kurir').html(newDelivery);
                    
                    console.log('Semua Dashboard Sinkron: ' + new Date().toLocaleTimeString());
                },
                complete: function() {
                    isRefreshing = false;
                }
            });
        }

        setInterval(refreshDashboards, 5000); // Sinkronisasi setiap 5 detik
    });
</script>

</x-app-layout>