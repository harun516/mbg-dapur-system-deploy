<x-app-layout>
<form action="{{ route('penerimaan.store') }}" method="POST" id="formPenerimaan">
    @csrf
      <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container-form {
            max-width: 1200px;
            width: 100%;
            margin: 20px auto;
            padding: 24px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
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
        .form-control {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 10px 12px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table-custom thead th {
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
        .table-custom tbody td {
            padding: 12px 10px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
        }
        .table-custom tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .table-custom tbody tr:hover {
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
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0069d9;
        }
        .btn-danger {
            background-color: #dc3545;
            color: #fff;
            padding: 8px 16px;
            font-size: 13px;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .text-total {
            font-size: 1.5rem;
            font-weight: 700;
            color: #007bff;
        }

        /* RESPONSIVE MOBILE: FULL CARD MODE - PASTIKAN KONTEN MASUK KE CARD */
        @media (max-width: 768px) {
            .container-form {
                margin: 16px 8px;
                padding: 16px;
                border-radius: 6px;
            }
            h2 {
                font-size: 20px;
                margin-bottom: 20px;
            }
            .row.mb-4 {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .col-md-6 {
                width: 100%;
            }
            /* Hilangkan scroll horizontal sepenuhnya */
            .table-custom {
                border: none;
                min-width: 100%;
            }
            .table-custom thead {
                display: none;
            }
            .table-custom tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                overflow: hidden;
            }
            .table-custom tbody td {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                border: none;
                border-bottom: 1px solid #f0f0f0;
                padding: 12px 16px;
                gap: 4px;
            }
            .table-custom tbody td:last-child {
                border-bottom: none;
                padding: 16px;
                background: #f8f9fa;
                text-align: center;
            }
            .table-custom tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #495057;
                font-size: 0.95rem;
                margin-bottom: 4px;
                width: 100%;
            }
            .table-custom tbody td input,
            .table-custom tbody td select {
                width: 100%;
                text-align: left;
                margin: 0;
            }
            /* Field numerik align right */
            .table-custom tbody td[data-label="Qty"] input,
            .table-custom tbody td[data-label="Harga Satuan"] input,
            .table-custom tbody td[data-label="Subtotal"] input {
                text-align: right;
            }
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 16px;
            }
            .text-end {
                text-align: center !important;
            }
            .btn {
                width: 100%;
                margin: 6px 0;
            }
            .btn-danger.remove-row {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
    <div class="container-form">
        <h2>Form Penerimaan Barang</h2>

        <div class="row mb-4" style="display: flex; flex-wrap: wrap; gap: 20px;">
            <div class="col-md-4" style="flex: 0 0 35%; max-width: 35%;">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required style="max-width: 220px;">
             </div>
                <div class="col-md-8" style="flex: 1; min-width: 300px;">
                    <label for="supplier">Supplier</label>
                    <input type="text" name="supplier" id="supplier" class="form-control" placeholder="Nama Supplier / Kode Supplier" required>
                </div>
        </div>

        <table class="table-custom" id="itemTable">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>No. Batch</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                    <th>Expired Date</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Nama Barang">
                        <select name="items[0][item_id]" class="form-control" required>
                            <option value="">Pilih Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td data-label="No. Batch">
                        <input type="text" name="items[0][no_batch]" class="form-control bg-light" readonly required>
                    </td>
                    <td data-label="Qty">
                        <input type="number" name="items[0][qty]" class="form-control calc qty" min="1" required>
                    </td>
                    <td data-label="Harga Satuan">
                        <input type="number" name="items[0][harga_satuan]" class="form-control calc harga" min="0" step="0.01" required>
                    </td>
                    <td data-label="Subtotal">
                        <input type="text" class="form-control subtotal" readonly value="0">
                    </td>
                    <td data-label="Expired Date">
                        <input type="date" name="items[0][expired_date]" class="form-control" required>
                    </td>
                    <td data-label="Aksi">
                        <button type="button" class="btn btn-danger remove-row">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
            <button type="button" class="btn btn-info" id="addRow">+ Tambah Baris Barang</button>
            <div class="text-end">
                <h5 class="mb-1">Total Belanja</h5>
                <h4 class="text-total">Rp <span id="grandTotal">0</span></h4>
            </div>
        </div>

        <hr class="my-4">

        <button type="submit" class="btn btn-primary btn-lg w-100">Simpan Penerimaan Barang</button>
    </div>

    <!-- Script tetap sama seperti sebelumnya -->
    <script>
        let rowIdx = 1;
        let batchCounter = 1;

        function generateBatchNumber() {
            return 'BATCH-' + String(batchCounter).padStart(3, '0');
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('input[name="items[0][no_batch]"]').value = generateBatchNumber();
            batchCounter++;
            calculateTotal();
        });

        function calculateTotal() {
            let grandTotal = 0;
            document.querySelectorAll('#itemTable tbody tr').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty').value) || 0;
                const harga = parseFloat(row.querySelector('.harga').value) || 0;
                const subtotal = qty * harga;
                row.querySelector('.subtotal').value = new Intl.NumberFormat('id-ID').format(subtotal);
                grandTotal += subtotal;
            });
            document.getElementById('grandTotal').innerText = new Intl.NumberFormat('id-ID').format(grandTotal);
        }

        document.addEventListener('input', e => {
            if (e.target.classList.contains('calc')) calculateTotal();
        });

        document.getElementById('addRow').addEventListener('click', () => {
            const tableBody = document.querySelector('#itemTable tbody');
            const newBatch = generateBatchNumber();
            const newRowHtml = `
                <tr>
                    <td data-label="Nama Barang">
                        <select name="items[${rowIdx}][item_id]" class="form-control" required>
                            <option value="">Pilih Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td data-label="No. Batch">
                        <input type="text" name="items[${rowIdx}][no_batch]" class="form-control bg-light" readonly value="${newBatch}" required>
                    </td>
                    <td data-label="Qty">
                        <input type="number" name="items[${rowIdx}][qty]" class="form-control calc qty" min="1" required>
                    </td>
                    <td data-label="Harga Satuan">
                        <input type="number" name="items[${rowIdx}][harga_satuan]" class="form-control calc harga" min="0" step="0.01" required>
                    </td>
                    <td data-label="Subtotal">
                        <input type="text" class="form-control subtotal" readonly value="0">
                    </td>
                    <td data-label="Expired Date">
                        <input type="date" name="items[${rowIdx}][expired_date]" class="form-control" required>
                    </td>
                    <td data-label="Aksi">
                        <button type="button" class="btn btn-danger remove-row">Hapus</button>
                    </td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', newRowHtml);
            rowIdx++;
            batchCounter++;
            calculateTotal();
        });

        document.addEventListener('click', e => {
            if (e.target.classList.contains('remove-row')) {
                if (document.querySelectorAll('#itemTable tbody tr').length > 1) {
                    e.target.closest('tr').remove();
                    calculateTotal();
                } else {
                    alert('Minimal harus ada 1 barang.');
                }
            }
        });

        document.getElementById('formPenerimaan').addEventListener('submit', e => {
            if (document.querySelectorAll('#itemTable tbody tr').length === 0) {
                e.preventDefault();
                alert('Harap tambahkan minimal 1 barang.');
            }
        });
    </script>
</form>
</x-app-layout>