<x-app-layout>
<style>
    /* Custom CSS for professional styling */
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
    }
    .table tbody td {
        padding: 10px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .table-danger {
        background-color: #f8d7da !important; /* Override for expired rows */
    }
    .table-warning {
        background-color: #fff3cd !important; /* Override for warning rows */
    }
    .text-muted {
        color: #6c757d !important;
    }
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }
    .bg-danger {
        background-color: #dc3545 !important;
        color: #fff;
    }
    .btn {
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
    }
    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #0069d9;
    }
    .btn-success {
        background-color: #28a745;
        color: #fff;
    }
    .btn-success:hover {
        background-color: #218838;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
    /* Modal Styles */
    #modalOpname {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #ffffff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1050;
        width: 90%;
        max-width: 500px;
    }
    #modalOpname h4 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #007bff;
    }
    #modalOpname p {
        margin-bottom: 10px;
    }
    .mb-3 {
        margin-bottom: 1rem;
    }
    label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #495057;
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
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-table {
            padding: 15px;
        }
        .table thead th, .table tbody td {
            padding: 8px;
            font-size: 12px;
        }
        #modalOpname {
            padding: 20px;
        }
    }
</style>

<div class="container-table">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2> STOK BAHAN-BAHAN GUDANG</h2>
        <a href="{{ route('stok.opname.index') }}" class="btn btn-outline-primary">Buka Fitur Opname</a>
    </div>
<table class="table">
    <thead>
        <tr>
            <th>Item</th>
            <th>No Batch</th>
            <th>Sisa Stok</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($batches as $batch)
        <tr>
            <td>{{ $batch->item->nama_barang }}</td>
            <td>{{ $batch->no_batch }}</td>
            <td class="fw-bold">
                {{-- Kita gunakan 0 desimal agar 50.0000 jadi 50 --}}
                {{ number_format($batch->qty_sisa, 0, ',', '.') }} {{ $batch->item->satuan }}
            </td>
            <td> {{-- Sediakan pembuka td di sini --}}
                @if($batch->qty_sisa <= 5)
                    <span class="badge bg-danger">Hampir Habis</span>
                @else
                    <span class="badge bg-success">Tersedia</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<div id="modalOpname">
    <form id="formOpname" method="POST">
        @csrf
        <h4>Stok Opname: <span id="namaBarang"></span></h4>
        <p>Stok Sistem: <span id="stokSistem"></span></p>
        <div class="mb-3">
            <label>Jumlah Fisik Sebenarnya:</label>
            <input type="number" name="qty_fisik" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <button type="button" onclick="closeModal()" class="btn btn-secondary">Batal</button>
    </form>
</div>

<script>
    function showOpnameModal(id, nama, stok) {
        document.getElementById('modalOpname').style.display = 'block';
        document.getElementById('namaBarang').innerText = nama;
        document.getElementById('stokSistem').innerText = stok;
        document.getElementById('formOpname').action = "/gudang/stok/opname/" + id;
    }
    function closeModal() {
        document.getElementById('modalOpname').style.display = 'none';
    }
</script>
</x-app-layout>