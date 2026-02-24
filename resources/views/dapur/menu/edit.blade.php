<x-app-layout>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }
    .container-form {
        max-width: 900px;
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
    .form-label { font-weight: 600; margin-bottom: 8px; display: block; }
    .form-control {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 10px;
        width: 100%;
    }
    .table-recipe thead th {
        background-color: #f1f3f5;
        color: #495057;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    .btn-add { background-color: #17a2b8; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
    .btn-save { background-color: #007bff; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: 20px; }
    .btn-remove { background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
    .input-group-text { background: #e9ecef; border: 1px solid #dee2e6; padding: 10px; border-radius: 0 6px 6px 0; }
</style>

<div class="container-form">
    <h2>Edit Master Resep: {{ $menu->nama_menu }}</h2>

    <form action="{{ route('menu.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 2;">
                <label class="form-label">Nama Menu Masakan</label>
                <input type="text" name="nama_menu" class="form-control" value="{{ $menu->nama_menu }}" required>
            </div>
            <div style="flex: 1;">
                <label class="form-label">Standar Porsi</label>
                <input type="number" name="porsi_standar" class="form-control" value="{{ $menu->porsi_standar }}" required>
            </div>
        </div>

        <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0;">Komposisi Bahan Baku</h4>
            <button type="button" id="addIngredient" class="btn-add">+ Tambah Bahan</button>
        </div>

        <table class="table-recipe" id="recipeTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 10px; border-bottom: 2px solid #dee2e6;">Bahan Baku</th>
                    <th style="text-align: left; padding: 10px; border-bottom: 2px solid #dee2e6;">Takaran (Kg/Ltr)</th>
                    <th style="text-align: center; padding: 10px; border-bottom: 2px solid #dee2e6;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menu->requirements as $index => $requirement)
                <tr>
                    <td style="padding: 10px;">
                        <select name="items[{{ $index }}][item_id]" class="form-control" required>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $requirement->item_id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} ({{ $item->satuan }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding: 10px;">
                        <input type="number" step="0.0001" name="items[{{ $index }}][qty_per_porsi]" class="form-control" value="{{ $requirement->qty_per_porsi }}" required>
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        <button type="button" class="btn-remove remove-row">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn-save">Simpan Perubahan Resep</button>
        <a href="{{ route('menu.index') }}" style="display: block; text-align: center; margin-top: 15px; color: #6c757d; text-decoration: none;">Batal</a>
    </form>
</div>

<script>
    // Gunakan tanda kutip agar VSCode membacanya sebagai string saat validasi, 
    // tapi tetap menjadi angka saat dirender Laravel
    let ingredientIdx = parseInt("{{ $menu->requirements->count() }}");
    
    
    document.getElementById('addIngredient').addEventListener('click', function() {
        let tableBody = document.querySelector('#recipeTable tbody');
        
        // Template literal menggunakan backticks (`) sudah benar
        let newRow = `
            <tr>
                <td style="padding: 10px;">
                    <select name="items[${ingredientIdx}][item_id]" class="form-control" required>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                        @endforeach
                    </select>
                </td>
                <td style="padding: 10px;">
                    <input type="number" step="0.0001" name="items[${ingredientIdx}][qty_per_porsi]" class="form-control" required>
                </td>
                <td style="padding: 10px; text-align: center;">
                    <button type="button" class="btn-remove remove-row">Hapus</button>
                </td>
            </tr>`;
            
        tableBody.insertAdjacentHTML('beforeend', newRow);
        ingredientIdx++;
    });

    // Delegasi event untuk tombol hapus
    document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        if (document.querySelectorAll('#recipeTable tbody tr').length > 1) {
            e.target.closest('tr').remove();
        } else {
            alert('Resep harus memiliki minimal 1 bahan baku.');
        }
    }
});
</script>
</x-app-layout>