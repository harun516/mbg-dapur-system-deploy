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
    h4 {
        font-size: 18px;
        margin: 32px 0 16px;
        color: #495057;
        font-weight: 600;
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
    .btn-danger {
        background-color: #dc3545;
        color: #fff;
        padding: 6px 12px;
        font-size: 13px;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .input-group {
        width: 100%;
    }
    /* Responsive mobile: table jadi card per bahan */
    @media (max-width: 768px) {
        .container-form {
            margin: 20px 10px;
            padding: 20px;
        }
        h2 { font-size: 20px; }
        h4 { margin: 24px 0 12px; font-size: 16px; }
        .row.mb-4 { flex-direction: column; gap: 16px; }
        .col-md-8, .col-md-4 { width: 100%; }
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
        .btn { width: 100%; margin: 8px 0; }
    }
</style>

<div class="container-form">
    <h2>Buat Master Resep Baru</h2>

    <form action="{{ route('menu.store') }}" method="POST">
        @csrf

        <div class="row mb-4">
            <div class="col-md-8">
                <label for="nama_menu">Nama Menu Masakan</label>
                <input type="text" name="nama_menu" id="nama_menu" class="form-control" placeholder="Contoh: Nasi Ayam Saus Tiram" required>
            </div>
            <div class="col-md-4">
                <label for="porsi_standar">Standar Porsi Harian</label>
                <input type="number" name="porsi_standar" id="porsi_standar" class="form-control" value="1500" min="1" required>
            </div>
        </div>

        <h4>Komposisi Bahan Baku (Per Porsi)</h4>

        <table class="table" id="recipeTable">
            <thead>
                <tr>
                    <th>Bahan Baku</th>
                    <th>Takaran per Porsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="Bahan Baku">
                        <select name="items[0][item_id]" class="form-control" required>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td data-label="Takaran per Porsi">
                        <div class="input-group">
                            <input type="number" step="0.0001" name="items[0][qty_per_porsi]" class="form-control" placeholder="Contoh: 0.1 untuk 100gr" min="0.0001" required>
                        </div>
                    </td>
                    <td data-label="Aksi">
                        <button type="button" class="btn btn-danger remove-row">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3 mb-4">
            <button type="button" class="btn btn-info" id="addIngredient">+ Tambah Bahan</button>
        </div>

        <hr class="my-4">

        <button type="submit" class="btn btn-primary btn-lg w-100">Simpan Master Resep</button>
    </form>
</div>

<script>
    let ingredientIdx = 1;

    document.getElementById('addIngredient').addEventListener('click', function() {
        let tableBody = document.querySelector('#recipeTable tbody');
        let newRow = `
            <tr>
                <td data-label="Bahan Baku">
                    <select name="items[${ingredientIdx}][item_id]" class="form-control" required>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_barang }} ({{ $item->satuan }})</option>
                        @endforeach
                    </select>
                </td>
                <td data-label="Takaran per Porsi">
                    <input type="number" step="0.0001" name="items[${ingredientIdx}][qty_per_porsi]" class="form-control" placeholder="Contoh: 0.1 untuk 100gr" min="0.0001" required>
                </td>
                <td data-label="Aksi">
                    <button type="button" class="btn btn-danger remove-row">Hapus</button>
                </td>
            </tr>`;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        ingredientIdx++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            if (document.querySelectorAll('#recipeTable tbody tr').length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('Minimal harus ada 1 bahan baku.');
            }
        }
    });
</script>
</x-app-layout>