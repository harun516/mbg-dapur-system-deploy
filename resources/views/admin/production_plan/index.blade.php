<x-app-layout>

<style>
/* Theme Dashboard Biru */

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

.btn-primary {
    background-color: #007bff;
    border: none;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 0;
}

.table thead th {
    background-color: #007bff;
    color: #ffffff;
    font-weight: 600;
    padding: 12px 16px;
    border: none;
}

.table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f1f3f5;
}

.badge {
    font-size: 85%;
    padding: 6px 10px;
    border-radius: 4px;
}

.bg-success { background-color: #28a745 !important; }
.bg-secondary { background-color: #6c757d !important; }
.bg-info { background-color: #17a2b8 !important; }

.text-primary { color: #007bff !important; }

</style>


<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Kalender Rencana Produksi</h6>

        <a href="{{ route('admin.production_plan.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-calendar-plus me-2"></i> Buat Rencana Baru
        </a>
    </div>

    <div class="card-body p-0">

        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead>
                    <tr>
                        <th>Tanggal Rencana</th>
                        <th>Menu Masakan</th>
                        <th class="text-center">Target Porsi</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>

                <tbody id="table-rencana-produksi">

                    @forelse($plans as $plan)

                    <tr>

                        <td>
                            <span class="fw-bold text-primary">
                                {{ date('d M Y', strtotime($plan->tanggal_rencana)) }}
                            </span>
                        </td>

                        <td>
                            <span class="fw-bold">
                                {{ $plan->menu->nama_menu }}
                            </span>

                            @if($plan->catatan_admin)
                                <small class="d-block text-muted">
                                    {{ $plan->catatan_admin }}
                                </small>
                            @endif
                        </td>

                        <td class="text-center">
                            <span class="badge bg-light text-primary fw-bold">
                                {{ number_format($plan->total_porsi_target) }} Porsi
                            </span>
                        </td>

                        <td class="text-center">
                            @php
                                $statusColor = [
                                    'Draft'             => 'bg-secondary',
                                    'Terkirim ke Dapur' => 'bg-info',
                                    'Sedang Dimasak'    => 'bg-warning text-dark',
                                    'Proses Masak'      => 'bg-warning text-dark', // Backup jika teks ini yang masuk
                                    'Packing'           => 'bg-primary',
                                    'Siap Distribusi'   => 'bg-info',
                                    'Selesai'           => 'bg-success',
                                    'Dibatalkan'        => 'bg-danger'
                                ][$plan->status] ?? 'bg-dark';
                            @endphp

                            <span class="badge {{ $statusColor }}">
                                {{ $plan->status }}
                            </span>
                        </td>

                        <td class="text-end">

                            <div class="btn-group">

                                @if($plan->status == 'Draft')

                                <form action="{{ route('admin.production_plan.update-status', $plan->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status" value="Terkirim ke Dapur">

                                    <button type="submit" class="btn btn-sm btn-outline-info" title="Kirim ke Dapur">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>

                                @endif

                                <a href="{{ route('admin.production_plan.edit', $plan->id) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.production_plan.destroy', $plan->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus rencana ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">

                            <i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i>

                            <p>Belum ada rencana produksi yang dibuat.</p>

                            <a href="{{ route('admin.production_plan.create') }}"
                               class="btn btn-sm btn-primary">
                                Buat Rencana Sekarang
                            </a>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @if($plans->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $plans->links() }}
    </div>
    @endif


</div>
<script>
    $(document).ready(function() {
        let isRefreshing = false;

        function refreshStatus() {
            // Cek agar tidak ada request ganda yang berjalan bersamaan
            if (isRefreshing) return;
            
            isRefreshing = true;

            $.ajax({
                url: window.location.href,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    // Hanya ambil bagian body tabel agar tidak merusak header/ID tabel utama
                    // Strategi ini menjaga agar event listener lain tidak hilang
                    var newContent = $(response).find('#table-rencana-produksi').html();
                    
                    if (newContent) {
                        $('#table-rencana-produksi').html(newContent);
                        console.log('Dashboard Admin Berhasil Disinkronkan: ' + new Date().toLocaleTimeString());
                    }
                },
                error: function(xhr) {
                    console.error('Koneksi terputus atau gagal sinkronisasi.');
                },
                complete: function() {
                    isRefreshing = false;
                }
            });
        }

        // Jalankan setiap 5 detik (lebih responsif untuk memantau dapur)
        setInterval(refreshStatus, 5000);
    });
</script>

</x-app-layout>