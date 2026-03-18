<x-app-layout>
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('penerimaan.store') }}" method="POST" id="formPenerimaan">
                @csrf
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <h4 class="fw-bold text-primary mb-0 border-bottom border-primary border-3 pb-2">
                        <i class="fas fa-dolly-flatbed me-2"></i> Form Penerimaan Barang
                    </h4>
                    
                    <div class="p-3 file-balance-card rounded-4 shadow-sm" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                        <div class="d-flex align-items-center">
                            <div class="me-3 bg-white bg-opacity-25 text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-wallet fs-4"></i>
                            </div>
                            <div>
                                <small class="text-white-50 fw-bold d-block text-uppercase" style="letter-spacing: 0.5px;">Sisa Saldo Belanja</small>
                                <h4 class="text-white mb-0 fw-bold">Rp {{ number_format($saldoGudang, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 g-3">
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label fw-bold text-secondary">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control form-control-lg bg-light" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-8">
                        <label for="supplier" class="form-label fw-bold text-secondary">Supplier</label>
                        <input type="text" name="supplier" id="supplier" class="form-control form-control-lg bg-light" placeholder="Nama Supplier / Kode Supplier" required>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-hover align-middle border mb-0" id="itemTable">
                        <thead class="table-primary text-primary">
                            <tr>
                                <th>Nama Barang</th>
                                <th>No. Batch</th>
                                <th style="width: 120px;">Qty</th>
                                <th style="width: 180px;">Harga Satuan</th>
                                <th style="width: 180px;">Subtotal</th>
                                <th>Expired Date</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][item_id]" class="form-select bg-light" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="items[0][no_batch]" class="form-control text-secondary bg-secondary bg-opacity-10" readonly required>
                                </td>
                                <td>
                                    <input type="number" name="items[0][qty]" class="form-control calc qty bg-light" min="1" required>
                                </td>
                                <td>
                                    <input type="number" name="items[0][harga_satuan]" class="form-control calc harga bg-light" min="0" step="0.01" required>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted">Rp</span>
                                        <input type="text" class="form-control subtotal text-primary fw-bold bg-white" readonly value="0">
                                    </div>
                                </td>
                                <td>
                                    <input type="date" name="items[0][expired_date]" class="form-control bg-light" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row rounded-pill"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-end mt-4 flex-wrap gap-3 bg-light p-4 rounded-4 border">
                    <button type="button" class="btn btn-info text-white rounded-pill px-4 fw-bold shadow-sm" id="addRow">
                        <i class="fas fa-plus me-1"></i> Tambah Baris Barang
                    </button>
                    <div class="text-end">
                        <h6 class="mb-1 text-muted text-uppercase fw-bold">Total Belanja</h6>
                        <h3 class="fw-bold text-primary mb-1" id="totalWrapper" style="letter-spacing: -0.5px;">Rp <span id="grandTotal">0</span></h3>
                        <div id="errorSaldo" class="badge bg-danger rounded-pill px-3 py-2 d-none shadow-sm"><i class="fas fa-exclamation-triangle me-1"></i> Saldo Tidak Mencukupi!</div>
                    </div>
                </div>

                <hr class="my-5 border-light">

                <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg w-100 shadow rounded-pill py-3 fw-bold fs-5 text-uppercase" style="letter-spacing: 1px;">
                    <i class="fas fa-save me-2"></i> Simpan Penerimaan Barang
                </button>
            </form>
        </div>
    </div>
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
                    <select name="items[${rowIdx}][item_id]" class="form-select bg-light" required>
                        <option value="">Pilih Barang</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                        @endforeach
                    </select>
                </td>
                <td data-label="No. Batch">
                    <input type="text" name="items[${rowIdx}][no_batch]" class="form-control text-secondary bg-secondary bg-opacity-10" readonly value="${newBatch}" required>
                </td>
                <td data-label="Qty">
                    <input type="number" name="items[${rowIdx}][qty]" class="form-control calc qty bg-light" min="1" required>
                </td>
                <td data-label="Harga Satuan">
                    <input type="number" name="items[${rowIdx}][harga_satuan]" class="form-control calc harga bg-light" min="0" step="0.01" required>
                </td>
                <td data-label="Subtotal">
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">Rp</span>
                        <input type="text" class="form-control subtotal text-primary fw-bold bg-white" readonly value="0">
                    </div>
                </td>
                <td data-label="Expired Date">
                    <input type="date" name="items[${rowIdx}][expired_date]" class="form-control bg-light" required>
                </td>
                <td class="text-center" data-label="Aksi">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row rounded-pill"><i class="fas fa-trash"></i></button>
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