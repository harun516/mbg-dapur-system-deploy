<x-app-layout>
<style>
    /* Custom CSS for professional styling - sama persis dengan halaman Stok Bahan Gudang */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    .container-table {
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #007bff;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }
    .text-muted {
        color: #6c757d !important;
        margin-bottom: 1.5rem;
    }
    .table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }
    .table thead th {
        background-color: #007bff;
        color: #ffffff;
        font-weight: 600;
        text-align: left;
        padding: 12px 10px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
    }
    .table tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        font-size: 0.95rem;
    }
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .table tbody tr:hover {
        background-color: #e9ecef;
    }
    .form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 10px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        width: 100%;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }
    .btn {
        font-size: 14px;
        padding: 8px 16px;
        border-radius: 4px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }
    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-table {
            margin: 20px 10px;
            padding: 20px;
        }
        h2 {
            font-size: 20px;
        }
        .table thead th, .table tbody td {
            padding: 10px 8px;
            font-size: 13px;
        }
        .table thead {
            display: none;
        }
        .table tbody tr {
            display: block;
            margin-bottom: 16px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background-color: #fff;
        }
        .table tbody td {
            display: flex;
            justify-content: space-between;
            border: none;
            border-bottom: 1px solid #eee;
            padding: 12px 16px;
        }
        .table tbody td:last-child {
            border-bottom: none;
            justify-content: center;
            padding: 16px;
        }
        .table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #495057;
            margin-right: 12px;
            flex: 0 0 45%;
        }
    }
</style>

<div class="container-table">
    <h2>Audit Stok Opname</h2>
    <p class="text-muted">Gunakan halaman ini untuk menyesuaikan jumlah fisik barang di gudang.</p>

    <table class="table">
        <thead>
            <tr>
                <th>Item / Batch</th>
                <th>Stok Sistem</th>
                <th>Input Stok Fisik</th>
                <th>Keterangan Selisih</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batches as $batch)
            <form action="{{ route('stok.opname.process', $batch->id) }}" method="POST">
                @csrf
                <tr>
                    <td data-label="Item / Batch">
                        <strong>{{ $batch->item->nama_barang }}</strong><br>
                        <small class="text-muted">{{ $batch->no_batch }}</small>
                    </td>
                    <td data-label="Stok Sistem" class="fw-bold">
                        {{ $batch->qty_sisa }} {{ $batch->item->satuan ?? '' }}
                    </td>
                    <td data-label="Input Stok Fisik">
                        <input type="number" name="qty_fisik" class="form-control" placeholder="Jml Fisik" required min="0">
                    </td>
                    <td data-label="Keterangan Selisih">
                        <input type="text" name="keterangan" class="form-control" placeholder="Alasan (misal: Rusak/Hilang)" required>
                    </td>
                    <td data-label="Aksi">
                        <button type="submit" class="btn btn-danger btn-sm">Update</button>
                    </td>
                </tr>
            </form>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>