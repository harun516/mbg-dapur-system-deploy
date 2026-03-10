<x-app-layout>
<style>
/* Styling konsisten dengan halaman lain */
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

.btn-warning {
    font-weight: 600;
}

.text-primary {
    color: #007bff !important;
}
</style>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Edit Penerima MBG</h6>
        <a href="{{ route('admin.recipient.index') }}" class="btn btn-outline-primary btn-sm">
            Batal
        </a>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.recipient.update', $recipient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-7">
                    <label class="form-label">Nama Sekolah / Yayasan</label>
                    <input type="text" name="nama_lembaga" class="form-control"
                        value="{{ old('nama_lembaga', $recipient->nama_lembaga) }}" required>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Pimpinan / Kepsek</label>
                    <input type="text" name="pimpinan" class="form-control"
                        value="{{ old('pimpinan', $recipient->pimpinan) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Pengiriman</label>
                <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $recipient->alamat) }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Koordinator (PIC)</label>
                    <input type="text" name="nama_pic" class="form-control"
                        value="{{ old('nama_pic', $recipient->nama_pic) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">No. WhatsApp PIC</label>
                    <input type="text" name="no_hp_pic" class="form-control"
                        value="{{ old('no_hp_pic', $recipient->no_hp_pic) }}" required>
                </div>
            </div>

            <div class="row mb-4">

                <div class="col-md-4">
                    <label class="form-label text-primary">Jumlah Porsi</label>
                    <div class="input-group">
                        <input type="number" name="jumlah_porsi"
                            class="form-control fw-bold border-primary text-primary"
                            value="{{ old('jumlah_porsi', $recipient->jumlah_porsi) }}" min="1" required>
                        <span class="input-group-text bg-primary text-white border-primary">Porsi</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status_enable" class="form-select">
                        <option value="1" {{ $recipient->status_enable == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $recipient->status_enable == 0 ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-warning py-2 fw-bold text-dark">
                    Perbarui Data Penerima
                </button>
            </div>

        </form>

    </div>
</div>

</x-app-layout>