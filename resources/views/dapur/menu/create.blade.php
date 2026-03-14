<x-app-layout>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h4 class="mb-0 text-primary fw-bold">
                        <i class="fas fa-utensils me-2"></i>Buat Master Resep Baru
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('menu.store') }}" method="POST">
                        @csrf
                
                        <div class="row mb-4 bg-light p-3 rounded-3 border">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label for="nama_menu" class="form-label fw-bold">Nama Menu Masakan</label>
                                <input type="text" name="nama_menu" id="nama_menu" class="form-control form-control-lg border-primary" placeholder="Contoh: Nasi Ayam Saus Tiram" required>
                            </div>
                            <div class="col-md-4">
                                <label for="porsi_standar" class="form-label fw-bold">Standar Porsi Harian</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="porsi_standar" id="porsi_standar" class="form-control border-primary text-primary fw-bold" value="1500" min="1" required>
                                    <span class="input-group-text bg-primary text-white border-primary">Porsi</span>
                                </div>
                            </div>
                        </div>
                
                        <h5 class="mb-3 text-dark fw-bold border-bottom pb-2">Komposisi Bahan Baku (Per Porsi)</h5>
                
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle" id="recipeTable">
                                <thead class="table-primary text-primary">
                                    <tr>
                                        <th style="width: 50%;">Bahan Baku</th>
                                        <th style="width: 35%;">Takaran per Porsi (Kg/Ltr)</th>
                                        <th style="width: 15%; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="items[0][item_id]" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih Bahan Baku --</option>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.0001" name="items[0][qty_per_porsi]" class="form-control form-control-sm" placeholder="Contoh: 0.1 untuk 100gr" min="0.0001" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row" disabled>
                                                <i class="fas fa-times me-1"></i>Hapus
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                
                        <div class="mb-5">
                            <button type="button" class="btn btn-info btn-sm text-white fw-bold shadow-sm" id="addIngredient">
                                <i class="fas fa-plus me-1"></i> Tambah Bahan
                            </button>
                        </div>
                
                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('menu.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Master Resep
                            </button>
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
            let ingredientIdx = 1;

            document.getElementById('addIngredient').addEventListener('click', function() {
                let tableBody = document.querySelector('#recipeTable tbody');
                let newRow = `
                    <tr>
                        <td>
                            <select name="items[${ingredientIdx}][item_id]" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Bahan Baku --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.0001" name="items[${ingredientIdx}][qty_per_porsi]" class="form-control form-control-sm" placeholder="Contoh: 0.1 untuk 100gr" min="0.0001" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                <i class="fas fa-times me-1"></i>Hapus
                            </button>
                        </td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', newRow);
                ingredientIdx++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    if (document.querySelectorAll('#recipeTable tbody tr').length > 1) {
                        e.target.closest('tr').remove();
                    } else {
                        alert('Minimal harus ada 1 bahan baku.');
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>