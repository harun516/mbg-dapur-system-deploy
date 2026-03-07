<x-app-layout>
    <style>
        /* === STYLE HALAMAN SALDO GUDANG (GUDANG DASHBOARD) === */

/* Container utama */
.container-fluid.py-4 {
    max-width: 1400px;
    margin: 0 auto;
}

/* Card Saldo Operasional Gudang - gradient lebih soft & jelas */
.card[style*="background: linear-gradient(45deg, #1cc88a, #13855c)"] {
    border: none !important;
    border-radius: 16px !important;
    background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important; /* emerald soft, kontras tinggi */
    box-shadow: 0 12px 35px rgba(16, 185, 129, 0.35) !important; /* shadow hijau lembut + dalam */
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important; /* bouncy hover */
    position: relative;
    overflow: hidden;
}

/* Hover efek dramatis & bouncy */
.card[style*="background: linear-gradient(45deg, #1cc88a, #13855c)"]:hover {
    transform: translateY(-12px) scale(1.02) !important;
    box-shadow: 0 25px 55px rgba(16, 185, 129, 0.5) !important;
}

/* Ikon warehouse transparan di background */
.card[style*="background: linear-gradient(45deg, #1cc88a, #13855c)"]::before {
    content: '\f6c1'; /* fa-warehouse */
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    right: -40px;
    bottom: -40px;
    font-size: 14rem;
    opacity: 0.07;
    color: white;
    transform: rotate(-15deg);
    pointer-events: none;
}

/* Teks judul & nominal - dibuat super jelas */
.card-body.text-center h6 {
    font-size: 1rem !important;
    font-weight: 700 !important;
    color: rgba(255,255,255,0.92) !important;
    letter-spacing: 0.8px !important;
    text-transform: uppercase !important;
    text-shadow: 0 1px 4px rgba(0,0,0,0.4) !important;
    margin-bottom: 0.8rem !important;
}

.card-body.text-center h2 {
    font-size: 3.2rem !important;
    font-weight: 900 !important;
    color: white !important;
    line-height: 1 !important;
    text-shadow: 0 3px 10px rgba(0,0,0,0.4) !important;
    margin: 0.5rem 0 1.2rem !important;
}

/* Tombol Request Tambah Dana - lebih besar, kontras tinggi, hover premium */
.btn-light.btn-sm.fw-bold.text-success {
    background: rgba(255,255,255,0.98) !important;
    color: #047857 !important;
    border: 1px solid rgba(4,120,87,0.35) !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 10px !important;
    box-shadow: 0 4px 12px rgba(4,120,87,0.25) !important;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
    min-width: 220px !important;
}

.btn-light.btn-sm.fw-bold.text-success:hover {
    background: #047857 !important;
    color: white !important;
    transform: translateY(-4px) scale(1.05) !important;
    box-shadow: 0 12px 30px rgba(4,120,87,0.45) !important;
    border-color: transparent !important;
}

/* Card Riwayat Alokasi & Status Pengajuan */
.card.border-0.shadow-sm.rounded-4 {
    border-radius: 16px !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08) !important;
    transition: all 0.3s ease !important;
}

.card.border-0.shadow-sm.rounded-4:hover {
    transform: translateY(-6px) !important;
    box-shadow: 0 16px 40px rgba(0,0,0,0.15) !important;
}

/* Header Card */
.card-header.bg-white.py-3.border-bottom {
    background: white !important;
    border-bottom: 1px solid #e5e7eb !important;
    padding: 1rem 1.25rem !important;
}

.card-header h6 {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    color: #1f2937 !important;
}

/* Tabel Riwayat Alokasi & Status */
.table {
    border-collapse: separate !important;
    border-spacing: 0 10px !important;
}

.table thead th {
    background: #f8fafc !important;
    color: #374151 !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    font-size: 0.85rem !important;
    padding: 12px 16px !important;
    border: none !important;
}

.table tbody td {
    background: white !important;
    padding: 16px !important;
    border: none !important;
    vertical-align: middle !important;
    font-size: 0.95rem !important;
}

.table tbody tr {
    border-radius: 12px !important;
    box-shadow: 0 3px 10px rgba(0,0,0,0.04) !important;
    transition: all 0.25s ease !important;
}

.table tbody tr:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
}

/* Badge Status Pengajuan */
.badge.rounded-pill {
    font-weight: 600 !important;
    padding: 0.5em 1em !important;
}

/* Modal Request */
.modal-content {
    border: none !important;
    border-radius: 16px !important;
    box-shadow: 0 12px 35px rgba(0,0,0,0.18) !important;
}

.modal-header {
    border-bottom: none !important;
    padding: 1.5rem 1.5rem 0 !important;
}

.modal-title {
    font-weight: 700 !important;
    color: #1f2937 !important;
}

.modal-body .form-label {
    font-weight: 600 !important;
    color: #374151 !important;
}

.modal-body .form-control {
    background: #f8fafc !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 0.75rem 1rem !important;
}

/* Responsive Mobile */
@media (max-width: 992px) {
    .card-body { padding: 1.5rem !important; }
    .table th, .table td { padding: 12px !important; font-size: 0.9rem !important; }
    .card-body.text-center h2 { font-size: 2.4rem !important; }
    .btn-light.btn-sm.fw-bold.text-success {
        padding: 0.65rem 1.25rem !important;
        font-size: 0.95rem !important;
        min-width: 100% !important;
    }
}
    </style>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #1cc88a, #13855c); border-radius: 15px;">
                <div class="card-body p-4 text-center">
                    <h6 class="text-white-50 small fw-bold">SALDO OPERASIONAL GUDANG</h6>
                    <h2 class="fw-bold mb-0">Rp {{ number_format($saldoGudang, 0, ',', '.') }}</h2>
                    <button class="btn btn-light btn-sm mt-3 fw-bold text-success" data-bs-toggle="modal" data-bs-target="#modalRequest">
                        <i class="fas fa-paper-plane me-1"></i> Request Tambah Dana
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 font-weight-bold text-primary">
                    <i class="fas fa-download me-2"></i> Riwayat Alokasi dari Admin
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="text-end">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatMasuk as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d/m/y') }}</td>
                                <td>{{ $item->nama_alokasi }}</td>
                                <td class="text-end text-success fw-bold">+Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 font-weight-bold text-warning">
                    <i class="fas fa-clock me-2"></i> Status Pengajuan Dana
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($riwayatRequest as $req)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">{{ $req->created_at->format('d M Y') }}</small>
                                <strong>{{ $req->perihal }}</strong>
                                <div class="small">Rp {{ number_format($req->nominal, 0, ',', '.') }}</div>
                            </div>
                            <span class="badge rounded-pill bg-{{ $req->status == 'pending' ? 'warning' : ($req->status == 'disetujui' ? 'success' : 'danger') }}">
                                {{ strtoupper($req->status) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRequest" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('gudang.saldo.request') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Form Pengajuan Anggaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Perihal / Keperluan</label>
                    <input type="text" name="perihal" class="form-control" placeholder="Contoh: Belanja Stok Beras" required>
                </div>
                <div class="mb-3">
                    <label>Nominal yang Diminta</label>
                    <input type="number" name="nominal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Alasan Singkat</label>
                    <textarea name="alasan" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success w-100">Kirim Permintaan ke Admin</button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>