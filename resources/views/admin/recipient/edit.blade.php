<x-app-layout>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Penerima MBG
                    </h5>
                    <a href="{{ route('admin.recipient.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.recipient.update', $recipient->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold text-dark">Nama Sekolah / Yayasan</label>
                                <input type="text" name="nama_lembaga" class="form-control form-control-lg"
                                    value="{{ old('nama_lembaga', $recipient->nama_lembaga) }}" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-dark">Pimpinan / Kepsek</label>
                                <input type="text" name="pimpinan" class="form-control form-control-lg"
                                    value="{{ old('pimpinan', $recipient->pimpinan) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $recipient->alamat) }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Nama Koordinator (PIC)</label>
                                <input type="text" name="nama_pic" class="form-control"
                                    value="{{ old('nama_pic', $recipient->nama_pic) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">No. WhatsApp PIC</label>
                                <input type="text" name="no_hp_pic" class="form-control"
                                    value="{{ old('no_hp_pic', $recipient->no_hp_pic) }}" required>
                            </div>
                        </div>

                        <div class="row mb-4 bg-primary bg-opacity-10 p-3 rounded-3 mx-0 border border-primary border-opacity-25">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-primary fw-bold">Jumlah Porsi Target</label>
                                <div class="input-group">
                                    <input type="number" name="jumlah_porsi"
                                        class="form-control fw-bold border-primary text-primary"
                                        value="{{ old('jumlah_porsi', $recipient->jumlah_porsi) }}" min="1" required>
                                    <span class="input-group-text bg-primary text-white border-primary"><i class="fas fa-utensils me-1"></i> Porsi</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-primary fw-bold">Status Keaktifan</label>
                                <select name="status_enable" class="form-select border-primary text-primary fw-semibold">
                                    <option value="1" {{ $recipient->status_enable == 1 ? 'selected' : '' }}>Aktif Menerima</option>
                                    <option value="0" {{ $recipient->status_enable == 0 ? 'selected' : '' }}>Non-Aktif Sementara</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-warning px-5 py-2 fw-bold text-dark shadow-sm">
                                <i class="fas fa-save me-2"></i> Perbarui Data Penerima
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>