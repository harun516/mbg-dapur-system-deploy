<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h4 class="mb-0 text-primary fw-bold">
                            <i class="fas fa-boxes me-2"></i>Form Permintaan Barang ke Gudang
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('dapur.request.store') }}" method="POST">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="itemTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Pilih Bahan Baku</th>
                                            <th>Jumlah (Kg/Satuan)</th>
                                            <th class="text-center" style="width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="items[0][item_id]" class="form-select form-select-sm" required>
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][qty]" step="0.0001" class="form-control form-control-sm" placeholder="0.00" min="0.0001" required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-row" disabled>
                                                    <i class="fas fa-times me-1"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-4">
                                <button type="button" class="btn btn-info btn-sm text-white fw-semibold" id="addRow">
                                    <i class="fas fa-plus me-1"></i> Tambah Baris
                                </button>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('dapur.stok.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary px-5 fw-bold"><i class="fas fa-paper-plane me-2"></i>Kirim Permintaan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowIdx = 1;

            document.getElementById('addRow').addEventListener('click', function() {
                let tableBody = document.querySelector('#itemTable tbody');
                let newRow = `
                    <tr>
                        <td>
                            <select name="items[${rowIdx}][item_id]" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[${rowIdx}][qty]" step="0.0001" class="form-control form-control-sm" placeholder="0.00" min="0.0001" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                <i class="fas fa-times me-1"></i> Hapus
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
        });
    </script>
    @endpush
</x-app-layout>