<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pusat Laporan Admin') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4 px-3 px-lg-5">
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1">📊 Manajemen Laporan</h4>
            <p class="text-muted small">Pilih kategori laporan untuk melihat detail atau ekspor data ke Excel.</p>
        </div>

        <div class="row g-4">
            {{-- 1. Laporan Anggaran Masuk --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100 card-report">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-primary-subtle text-primary mb-3">
                            <i class="fas fa-file-invoice-dollar fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Laporan Anggaran</h5>
                        
                        <div class="my-3">
                            <h4 class="fw-black text-primary mb-0">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h4>
                            <small class="text-muted uppercase fw-bold" style="font-size: 0.65rem;">Total Saldo Aktif</small>
                        </div>

                        <p class="text-muted small mb-4">Rekapitulasi dana masuk dan alokasi anggaran operasional proyek.</p>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.budget.index') }}" class="btn btn-outline-primary fw-bold btn-sm rounded-3">Lihat Detail</a>
                            <a href="{{ route('admin.laporan.anggaran.export') }}" class="btn btn-success fw-bold btn-sm rounded-3">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Laporan Salary --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100 card-report">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-info-subtle text-info mb-3">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Laporan Salary</h5>

                        <div class="my-3">
                            <h4 class="fw-black text-info mb-0">Rp {{ number_format($totalSalary, 0, ',', '.') }}</h4>
                            <small class="text-muted uppercase fw-bold" style="font-size: 0.65rem;">Gaji Terbayar (Bulan Ini)</small>
                        </div>

                        <p class="text-muted small mb-4">Data penggajian karyawan, bonus, dan potongan periode berjalan.</p>
                        
                        <div class="d-grid mt-auto">
                            <a href="{{ route('admin.salary.index') }}" class="btn btn-info text-white fw-bold btn-sm rounded-3">
                                <i class="fas fa-external-link-alt me-1"></i> Buka Manajemen Salary
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Laporan Penerimaan & Belanja Barang --}}
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100 card-report">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-warning-subtle text-warning mb-3">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Laporan Gudang</h5>

                        <div class="my-3">
                            <h4 class="fw-black text-warning mb-0">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</h4>
                            <small class="text-muted uppercase fw-bold" style="font-size: 0.65rem;">Belanja Barang (Bulan Ini)</small>
                        </div>

                        <p class="text-muted small mb-4">Log penerimaan barang masuk dan pengeluaran belanja dapur/gudang.</p>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.gudang.penerimaan.index') }}" class="btn btn-outline-warning fw-bold btn-sm rounded-3">Detail Belanja</a>
                            <a href="{{ route('admin.gudang.penerimaan.export') }}" class="btn btn-success fw-bold btn-sm rounded-3">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Info Tambahan --}}
        <div class="mt-5 p-4 bg-white shadow-sm rounded-4 border-start border-primary border-4">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-info-circle fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Informasi Sinkronisasi Data</h6>
                    <p class="text-muted small mb-0">Data nominal di atas diambil secara realtime dari modul <strong>Budget</strong>, <strong>Salary Payment</strong>, dan <strong>Budget Transactions</strong>.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fw-black { font-weight: 900; }
        .card-report {
            transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            border-radius: 1.5rem;
        }
        .card-report:hover {
            transform: translateY(-10px);
            box-shadow: 0 1.5rem 3rem rgba(0,0,0,0.08) !important;
        }
        .icon-box {
            width: 70px;
            height: 70px;
            line-height: 70px;
            border-radius: 1.25rem;
            display: inline-block;
        }
        .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
        .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1) !important; }
        .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1) !important; }
        .uppercase { text-transform: uppercase; }
    </style>
</x-app-layout>