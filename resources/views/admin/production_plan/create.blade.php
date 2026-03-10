<x-app-layout>
<style>
/* Theme konsisten dengan halaman lain */

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    color: #333;
}

.card {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background-color: #ffffff;
    margin-bottom: 30px;
}

.card-header {
    background-color: #ffffff;
    border-bottom: 1px solid #dee2e6;
    padding: 18px 24px;
}

.card-header h6 {
    font-size: 18px;
    margin: 0;
    font-weight: 700;
    color: #007bff;
}

.card-body {
    padding: 24px;
}

.form-label {
    font-weight: 600;
    color: #444;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 4px;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.text-primary {
    color: #007bff !important;
}

.input-group-text {
    background-color: #007bff;
    color: white;
    border: 1px solid #007bff;
}
</style>


<div class="card">

    <div class="card-header">
        <h6>Buat Rencana Produksi Harian</h6>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.production_plan.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tanggal Rencana Masak</label>
                <input type="date" name="tanggal_rencana" class="form-control"
                    value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilih Menu Hari Ini</label>
                <select name="menu_id" class="form-select" required>
                    <option value="">-- Pilih Master Resep --</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->nama_menu }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label text-primary">Total Target Porsi</label>

                <div class="input-group">
                    <input type="number"
                        name="total_porsi_target"
                        class="form-control fw-bold text-primary border-primary"
                        value="{{ $suggestedPorsi }}"
                        required>

                    <span class="input-group-text">Porsi</span>
                </div>

                <small class="text-muted">
                    *Angka otomatis mengambil dari total Penerima MBG Aktif.
                </small>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary py-2 fw-bold">
                    Simpan & Kirim ke Dapur
                </button>
            </div>

        </form>

    </div>

</div>

</x-app-layout>