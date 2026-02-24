<x-app-layout>
    <style>
        /* Theme konsisten: biru #007bff, shadow halus, Segoe UI */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container-form {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 24px;
            margin-bottom: 24px;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #495057;
        }
        .form-control,
        .form-select {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 10px 12px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }
        .table {
            margin-top: 16px;
            border-collapse: collapse;
            width: 100%;
        }
        .table thead th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 12px 10px;
            border: 1px solid #dee2e6;
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
        .btn {
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 4px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-info {
            background-color: #17a2b8;
            color: #fff;
        }
        .btn-info:hover {
            background-color: #138496;
            transform: translateY(-1px);
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        /* Responsive mobile: table jadi card per permintaan */
        @media (max-width: 768px) {
            .container-form {
                margin: 20px 10px;
                padding: 20px;
            }
            h2 { font-size: 20px; }
            .table thead { display: none; }
            .table tbody tr {
                display: block;
                margin-bottom: 16px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            }
            .table tbody td {
                display: flex;
                flex-direction: column;
                border: none;
                border-bottom: 1px solid #eee;
                padding: 12px 16px;
                gap: 6px;
            }
            .table tbody td:last-child {
                border-bottom: none;
                justify-content: center;
                background: #f8f9fa;
                padding: 16px;
            }
            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #495057;
                font-size: 0.95rem;
            }
            .d-grid.d-md-flex { flex-direction: column; gap: 12px; }
            .btn { width: 100%; margin: 6px 0; }
        }
    </style>

    <div class="container-form">
        <h2>Form Permintaan Barang ke Gudang</h2>

        {{-- PERBAIKAN: Route diarahkan ke dapur.request.store --}}
        <form action="{{ route('dapur.request.store') }}" method="POST">
            @csrf

            <table class="table" id="itemTable">
                <thead>
                    <tr>
                        <th>Pilih Bahan Baku</th>
                        <th>Jumlah (Kg/Satuan)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Pilih Bahan Baku">
                            <select name="items[0][item_id]" class="form-control" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td data-label="Jumlah (Kg/Satuan)">
                            <input type="number" name="items[0][qty]" step="0.0001" class="form-control" placeholder="0.00" min="0.0001" required>
                        </td>
                        <td data-label="Aksi" class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-row" disabled>
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3 mb-4">
                <button type="button" class="btn btn-info" id="addRow">
                    <i class="fas fa-plus me-1"></i> Tambah Baris
                </button>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                {{-- PERBAIKAN: Tombol Batal diarahkan kembali ke Index Stok Dapur --}}
                <a href="{{ route('dapur.stok.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-5">Kirim Permintaan</button>
            </div>
        </form>
    </div>

    <script>
        let rowIdx = 1;

        document.getElementById('addRow').addEventListener('click', function() {
            let tableBody = document.querySelector('#itemTable tbody');
            let newRow = `
                <tr>
                    <td data-label="Pilih Bahan Baku">
                        <select name="items[${rowIdx}][item_id]" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td data-label="Jumlah (Kg/Satuan)">
                        <input type="number" name="items[${rowIdx}][qty]" step="0.0001" class="form-control" placeholder="0.00" min="0.0001" required>
                    </td>
                    <td data-label="Aksi" class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    </td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            rowIdx++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                let rows = document.querySelectorAll('#itemTable tbody tr');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Minimal harus ada 1 bahan yang diminta.');
                }
            }
        });
    </script>
</x-app-layout>