<x-app-layout>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h4 class="mb-0 text-primary fw-bold">
                        <i class="fas fa-edit me-2"></i>Edit Master Resep: {{ $menu->nama_menu }}
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('menu.update', $menu->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4 bg-light p-3 rounded-3 border">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Nama Menu Masakan</label>
                                <input type="text" name="nama_menu" class="form-control form-control-lg border-primary" value="{{ $menu->nama_menu }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Standar Porsi</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="porsi_standar" class="form-control text-primary fw-bold border-primary" value="{{ $menu->porsi_standar }}" required>
                                    <span class="input-group-text bg-primary text-white border-primary">Porsi</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <h5 class="mb-0 text-dark fw-bold">Komposisi Bahan Baku</h5>
                            <button type="button" id="addIngredient" class="btn btn-info btn-sm text-white fw-bold shadow-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Bahan
                            </button>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle" id="recipeTable">
                                <thead class="table-primary text-primary">
                                    <tr>
                                        <th style="width: 50%;">Bahan Baku</th>
                                        <th style="width: 35%;">Takaran (Kg/Ltr)</th>
                                        <th style="width: 15%; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menu->requirements as $index => $requirement)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $index }}][item_id]" class="form-select form-select-sm" required>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}" {{ $item->id == $requirement->item_id ? 'selected' : '' }}>
                                                        {{ $item->nama_barang }} ({{ $item->satuan }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.0001" name="items[{{ $index }}][qty_per_porsi]" class="form-control form-control-sm" value="{{ $requirement->qty_per_porsi }}" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                <i class="fas fa-times me-1"></i>Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-column gap-2 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary py-3 fw-bold shadow-sm w-100 fs-5">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan Resep
                            </button>
                            <a href="{{ route('menu.index') }}" class="btn btn-light py-2 text-muted fw-semibold">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let ingredientIdx = parseInt("{{ $menu->requirements->count() }}");
    
    document.getElementById('addIngredient').addEventListener('click', function() {
        let tableBody = document.querySelector('#recipeTable tbody');
        
        let newRow = `
            <tr>
                <td>
                    <select name="items[${ingredientIdx}][item_id]" class="form-select form-select-sm" required>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="0.0001" name="items[${ingredientIdx}][qty_per_porsi]" class="form-control form-control-sm" required>
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
                alert('Resep harus memiliki minimal 1 bahan baku.');
            }
        }
    });
</script>
@endpush
</x-app-layout>