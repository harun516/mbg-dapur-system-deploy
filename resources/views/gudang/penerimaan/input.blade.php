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
        margin: 24px auto;
        padding: 28px;
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .container-form:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    h2 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 28px;
        color: #1e40af;
        border-bottom: 3px solid #3b82f6;
        padding-bottom: 12px;
        letter-spacing: -0.5px;
    }

    label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
        color: #374151;
        font-size: 0.95rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 12px 16px;
        font-size: 1rem;
        background-color: #f9fafb;
        transition: all 0.3s ease;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15);
        background-color: #ffffff;
    }

    .form-control[readonly] {
        background-color: #e5e7eb;
        cursor: not-allowed;
    }

    /* Card Saldo Terkini - tema biru premium */
    .card-saldo {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .card-saldo:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 45px rgba(59, 130, 246, 0.4);
    }

    .card-saldo::before {
        content: '\f058'; /* fa-wallet */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: -40px;
        bottom: -40px;
        font-size: 12rem;
        opacity: 0.12;
        color: white;
        transform: rotate(-15deg);
        pointer-events: none;
    }

    .card-saldo .saldo-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        font-weight: 600;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .card-saldo .saldo-value {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -1px;
        text-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        margin-top: 24px;
    }

    .table-custom thead th {
        background-color: #1e40af;
        color: #ffffff;
        font-weight: 600;
        text-align: left;
        padding: 14px 16px;
        border: none;
        border-radius: 10px 10px 0 0;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }

    .table-custom tbody td {
        padding: 16px;
        vertical-align: middle;
        background: white;
        border: 1px solid #e5e7eb;
        font-size: 0.98rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .table-custom tbody tr:hover td {
        background: #f1f5f9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .btn {
        font-size: 14px;
        padding: 10px 20px;
        border-radius: 10px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-info {
        background-color: #0ea5e9;
        color: #fff;
    }

    .btn-info:hover {
        background-color: #0284c7;
        transform: translateY(-2px);
    }

    .btn-primary {
        background-color: #3b82f6;
        color: #fff;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: #ef4444;
        color: #fff;
        padding: 8px 16px;
        font-size: 13px;
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }

    .text-total {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e40af;
        letter-spacing: -0.5px;
    }

    .text-danger.fw-bold {
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    /* RESPONSIVE MOBILE: FULL CARD MODE */
    @media (max-width: 768px) {
        .container-form {
            margin: 16px 8px;
            padding: 20px;
            border-radius: 12px;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 24px;
        }

        .row.mb-4 {
            flex-direction: column;
            gap: 16px;
        }

        .col-md-4, .col-md-8 {
            width: 100%;
        }

        .table-custom thead {
            display: none;
        }

        .table-custom tbody tr {
            display: block;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .table-custom tbody td {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            border: none;
            border-bottom: 1px solid #f0f0f0;
            padding: 14px 16px;
            gap: 6px;
        }

        .table-custom tbody td:last-child {
            border-bottom: none;
            padding: 16px;
            background: #f8fafc;
            text-align: center;
        }

        .table-custom tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #374151;
            font-size: 0.95rem;
            margin-bottom: 6px;
            width: 100%;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 20px;
        }

        .text-end {
            text-align: center !important;
        }

        .btn {
            width: 100%;
            margin: 8px 0;
        }

        .card-saldo {
            padding: 1.2rem;
        }

        .card-saldo .saldo-value {
            font-size: 1.8rem;
        }
    }
</style>
    <div class="container-form">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2>Form Penerimaan Barang</h2>
        
        <div class="p-3 rounded-4 bg-primary-subtle border border-primary-content d-flex align-items-center">
            <div class="me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <small class="text-primary fw-bold d-block" style="font-size: 0.7rem; text-transform: uppercase;">Sisa Saldo Belanja</small>
                <h4 class="text-primary mb-0 fw-bold">Rp {{ number_format($saldoGudang, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <form id="formPenerimaan" action="{{ route('penerimaan.store') }}" method="POST">
        @csrf
        <div class="row mb-4" style="display: flex; flex-wrap: wrap; gap: 20px;">
            <div class="col-md-4" style="flex: 0 0 35%; max-width: 35%;">
                <label for="tanggal" class="fw-bold mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required style="max-width: 220px;" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-8" style="flex: 1; min-width: 300px;">
                <label for="supplier" class="fw-bold mb-1">Supplier</label>
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
                        <button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
            <button type="button" class="btn btn-info" id="addRow">+ Tambah Baris Barang</button>
            <div class="text-end">
                <h5 class="mb-1">Total Belanja</h5>
                <h4 class="text-total" id="totalWrapper">Rp <span id="grandTotal">0</span></h4>
                <small id="errorSaldo" class="text-danger fw-bold d-none">! Saldo tidak mencukupi</small>
            </div>
        </div>

        <hr class="my-4">

        <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg w-100 shadow-sm">Simpan Penerimaan Barang</button>
    </form>
</div>

<script>
    let rowIdx = 1;
    let batchCounter = 1;
    const saldoTersedia = Number("{{ $saldoGudang ?? 0 }}");

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
        
        const grandTotalElement = document.getElementById('grandTotal');
        const totalWrapper = document.getElementById('totalWrapper');
        const errorSaldo = document.getElementById('errorSaldo');
        const btnSubmit = document.getElementById('btnSubmit');

        grandTotalElement.innerText = new Intl.NumberFormat('id-ID').format(grandTotal);

        // Validasi Saldo
        if (grandTotal > saldoTersedia) {
            totalWrapper.classList.add('text-danger');
            errorSaldo.classList.remove('d-none');
            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Saldo Tidak Mencukupi';
            btnSubmit.classList.replace('btn-primary', 'btn-secondary');
        } else {
            totalWrapper.classList.remove('text-danger');
            errorSaldo.classList.add('d-none');
            btnSubmit.disabled = false;
            btnSubmit.innerText = 'Simpan Penerimaan Barang';
            btnSubmit.classList.replace('btn-secondary', 'btn-primary');
        }
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
                    <button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        tableBody.insertAdjacentHTML('beforeend', newRowHtml);
        rowIdx++;
        batchCounter++;
        calculateTotal();
    });

    document.addEventListener('click', e => {
        if (e.target.classList.contains('remove-row') || e.target.parentElement.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('#itemTable tbody tr').length > 1) {
                row.remove();
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