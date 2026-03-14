<x-app-layout>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Rencana Produksi
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.production_plan.update', $plan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Tanggal Rencana</label>
                            <input type="date"
                                   name="tanggal_rencana"
                                   class="form-control"
                                   value="{{ $plan->tanggal_rencana }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Menu</label>
                            <select name="menu_id" class="form-select" required>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}"
                                        {{ $plan->menu_id == $menu->id ? 'selected' : '' }}>
                                        {{ $menu->nama_menu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Target Porsi</label>
                            <div class="input-group">
                                <input type="number"
                                       name="total_porsi_target"
                                       class="form-control fw-bold border-primary text-primary"
                                       value="{{ $plan->total_porsi_target }}"
                                       required>
                                <span class="input-group-text bg-primary text-white border-primary">Porsi</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Status Rencana</label>
                            <select name="status" class="form-select">
                                <option value="Draft" {{ $plan->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Terkirim ke Dapur" {{ $plan->status == 'Terkirim ke Dapur' ? 'selected' : '' }}>Kirim ke Dapur</option>
                                <option value="Selesai" {{ $plan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('admin.production_plan.index') }}"
                               class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>

                            <button type="submit"
                                    class="btn btn-primary fw-bold px-4 shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>