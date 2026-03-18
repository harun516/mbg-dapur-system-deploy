<x-app-layout>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold text-primary"><i class="fas fa-truck-loading me-2"></i> Daftar Penerimaan Barang</h4>
            <a href="{{ route('penerimaan.input') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="fas fa-plus me-1"></i> Tambah Penerimaan
            </a>
        </div>

        <!-- FILTER -->
        <form method="GET" action="{{ route('penerimaan.index') }}" class="bg-light p-3 rounded-3 mb-4 border">
            <div class="row g-3 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="end_date">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 col-sm-12">
                <label for="item_id">Nama Barang</label>
                <select name="item_id" id="item_id" class="form-control">
                    <option value="">-- Semua Barang --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_barang }} ({{ $item->satuan }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary flex-fill text-center">Reset</a>
            </div>
        </div>
    </form>

    <!-- TABEL (sama seperti sebelumnya, tidak diubah lagi) -->
    <table class="table">
        <thead>
            <tr>
                <th rowspan="2">No. Penerimaan</th>
                <th rowspan="2">Tanggal & Waktu</th>
                <th rowspan="2">Supplier</th>
                <th rowspan="2">Petugas</th>
                <th colspan="6" class="text-center">Detail Barang</th>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <th>No. Batch</th>
                <th>Jumlah (Qty)</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <th>Expired</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penerimaans as $p)
                @if($p->details->isNotEmpty())
                    @foreach($p->details as $key => $d)
                        <tr @if($key == 0) data-group-start="true" @endif>
                            @if($key == 0)
                                <td rowspan="{{ count($p->details) }}" data-label="No. Penerimaan">{{ $p->no_penerimaan }}</td>
                                <td rowspan="{{ count($p->details) }}" data-label="Tanggal & Waktu">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}<br>
                                    <small class="text-muted">{{ $p->created_at->format('H:i') }} WIB</small>
                                </td>
                                <td rowspan="{{ count($p->details) }}" data-label="Supplier">{{ $p->supplier }}</td>
                                <td rowspan="{{ count($p->details) }}" data-label="Petugas">{{ $p->user->name ?? 'N/A' }}</td>
                            @endif
                            <td data-label="Nama Barang">{{ $d->item->nama_barang }}</td>
                            <td data-label="No. Batch"><span class="badge bg-light">{{ $d->no_batch }}</span></td>
                            <td data-label="Jumlah (Qty)">{{ number_format($d->qty, 0, ',', '.') }} {{ $d->item->satuan }}</td>
                            <td data-label="Harga Satuan">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td data-label="Subtotal" class="fw-bold text-primary">
                                Rp {{ number_format($d->qty * $d->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td data-label="Expired">{{ $d->expired_date ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-secondary">
                        <td colspan="8" class="text-end fw-bold" data-label="Total Pembelanjaan">Total Pembelanjaan {{ $p->no_penerimaan }}:</td>
                        <td colspan="2" class="fw-bold text-primary">
                            Rp {{ number_format($p->details->sum(fn($item) => $item->qty * $item->harga_satuan), 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Belum ada riwayat penerimaan barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="mt-4">
        {{ $penerimaans->links() }}
    </div>
    </div>
</div>
</x-app-layout>