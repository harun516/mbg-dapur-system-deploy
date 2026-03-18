<x-app-layout>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Buat Rencana Produksi Harian
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.production_plan.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Tanggal Rencana Masak</label>
                            <input type="date" name="tanggal_rencana" class="form-control form-control-lg"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Pilih Menu Hari Ini</label>
                            <select name="menu_id" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Master Resep --</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->nama_menu }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5 p-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3">
                            <label class="form-label text-primary fw-bold mb-2">Total Target Porsi</label>
                            
                            <div class="input-group input-group-lg shadow-sm">
                                <input type="number"
                                    name="total_porsi_target"
                                    class="form-control fw-bold text-primary border-primary"
                                    value="{{ $suggestedPorsi }}"
                                    required>
                                <span class="input-group-text bg-primary text-white border-primary"><i class="fas fa-utensils me-1"></i> Porsi</span>
                            </div>

                            <small class="text-primary mt-2 d-block fw-semibold">
                                <i class="fas fa-info-circle me-1"></i> Angka otomatis mengambil dari total Penerima MBG Aktif.
                            </small>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.production_plan.index') }}" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                <i class="fas fa-paper-plane me-2"></i> Simpan & Kirim ke Dapur
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>