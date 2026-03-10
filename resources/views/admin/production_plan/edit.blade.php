<x-app-layout>

<style>
/* Theme Dashboard Biru */

.card {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background-color: #ffffff;
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

.form-label {
    font-weight: 600;
}

.form-control, .form-select {
    border-radius: 6px;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
}

</style>


<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card">

                <div class="card-header">
                    <h6>Edit Rencana Produksi</h6>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('admin.production_plan.update', $plan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Tanggal Rencana</label>
                            <input type="date"
                                   name="tanggal_rencana"
                                   class="form-control"
                                   value="{{ $plan->tanggal_rencana }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Menu</label>
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
                            <label class="form-label">Target Porsi</label>
                            <input type="number"
                                   name="total_porsi_target"
                                   class="form-control"
                                   value="{{ $plan->total_porsi_target }}"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Status Rencana</label>
                            <select name="status" class="form-select">
                                <option value="Draft" {{ $plan->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Terkirim ke Dapur" {{ $plan->status == 'Terkirim ke Dapur' ? 'selected' : '' }}>Kirim ke Dapur</option>
                                <option value="Selesai" {{ $plan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('admin.production_plan.index') }}"
                               class="btn btn-outline-primary">
                                Kembali
                            </a>

                            <button type="submit"
                                    class="btn btn-primary fw-bold">
                                Simpan Perubahan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</x-app-layout>