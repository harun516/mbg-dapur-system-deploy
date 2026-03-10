<x-app-layout>
<style>
    /* Custom CSS for professional styling - konsisten dengan halaman sebelumnya */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    .card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        margin-bottom: 30px;
    }
    .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #dee2e6;
        padding: 18px 24px;
    }
    .card-header h6 {
        font-size: 18px;
        margin: 0;
        font-weight: 700;
        color: #007bff;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0069d9;
    }
    .btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
        font-size: 13px;
        padding: 6px 10px;
    }
    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }
    .table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 0;
    }
    .table thead th {
        background-color: #007bff;
        color: #ffffff;
        font-weight: 600;
        text-align: left;
        padding: 12px 16px;
        border: none;
        vertical-align: middle;
    }
    .table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }
    .table tbody tr:hover {
        background-color: #f1f3f5;
    }
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 85%;
        padding: 6px 10px;
        border-radius: 4px;
        font-weight: 500;
    }
    .bg-success {
        background-color: #28a745 !important;
    }
    .bg-secondary {
        background-color: #6c757d !important;
    }
    .text-muted {
        color: #6c757d !important;
    }
    .text-success {
        color: #28a745 !important;
    }
    .text-primary {
        color: #007bff !important;
    }
    .small {
        font-size: 85%;
        line-height: 1.4;
    }
    .fw-bold {
        font-weight: 600;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    /* Responsive */
    @media (max-width: 768px) {
        .card-header {
            padding: 14px 18px;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .card-header h6 {
            font-size: 16px;
        }
        .table thead th, .table tbody td {
            padding: 10px 12px;
            font-size: 13px;
        }
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Daftar Penerima MBG</h6>
        <a href="{{ route('admin.recipient.create') }}" class="btn btn-primary btn-sm">Tambah Penerima</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Lembaga & Pimpinan</th>
                        <th>Alamat</th>
                        <th>PIC (Koordinator)</th>
                        <th class="text-center">Porsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recipients as $r)
                    <tr>
                        <td>
                            <span class="fw-bold d-block">{{ $r->nama_lembaga }}</span>
                            <small class="text-muted">Pimpinan: {{ $r->pimpinan ?? '-' }}</small>
                        </td>
                        <td class="small" style="max-width: 220px; word-break: break-word;">
                            {{ $r->alamat }}
                        </td>
                        <td>
                            <span class="d-block fw-bold">{{ $r->nama_pic }}</span>
                            <small class="text-success">{{ $r->no_hp_pic ?? '-' }}</small>
                        </td>
                        <td class="text-center fw-bold text-primary">{{ number_format($r->jumlah_porsi, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $r->status_enable ? 'bg-success' : 'bg-secondary' }}">
                                {{ $r->status_enable ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.recipient.edit', $r->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>