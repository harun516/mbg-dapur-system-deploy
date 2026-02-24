<x-app-layout>
    <div class="container-table">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2>Daftar Permintaan Barang (Dapur ke Gudang)</h2>

        </div>

        <table class="table">
            <thead>
                <tr>
                    <th class="col-no">No Request</th>
                    <th class="col-tgl">Tanggal</th>
                    <th class="col-status">Status</th>
                    <th class="col-detail">Detail Permintaan</th>
                    <th class="col-aksi text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
    @forelse($requests as $req)
    <tr>
        <td data-label="No Request"><strong>{{ $req->no_request }}</strong></td>
        <td data-label="Tanggal">{{ date('d M Y', strtotime($req->tanggal_request)) }}</td>
        <td data-label="Status">
            @if($req->status == 'Pending')
                <span class="badge-status badge-pending">Menunggu Gudang</span>
            @elseif($req->status == 'Approved')
                <span class="badge-status badge-approved">Disetujui & Dikirim</span>
            @else
                <span class="badge-status badge-finished">Selesai</span>
            @endif
        </td>
        <td data-label="Detail Permintaan">
            @if($req->details->isNotEmpty())
                <div class="d-flex flex-wrap gap-1">
                    @foreach($req->details as $detail)
                        @php
                            // Menghilangkan nol mubazir di belakang koma (misal 10.0000 -> 10)
                            $qty = floatval($detail->qty_diminta); 
                            $satuan = $detail->item->satuan ?? '';
                        @endphp
                        <span class="detail-badge">
                            {{ $detail->item->nama_barang ?? 'N/A' }} 
                            <strong>{{ $qty }} {{ $satuan }}</strong>
                        </span>
                    @endforeach
                </div>
            @else
                <span class="text-muted small">Tidak ada item</span>
            @endif
        </td>
        <td data-label="Aksi" class="text-center">
    @if($req->status == 'Pending')
        {{-- Hanya munculkan tombol Approve jika user adalah admin gudang --}}
        @if(Auth::user()->role == 'gudang') 
            <form action="{{ route('request.approve', $req->id) }}" method="POST" class="d-inline form-approve">
                @csrf
                <button type="submit" class="btn btn-action btn-success btn-sm">
                    Approve & Kirim
                </button>
            </form>
        @else
            <span class="badge bg-light text-dark">Menunggu Konfirmasi</span>
        @endif
    @else
        <button class="btn btn-action btn-disabled btn-sm" disabled>Selesai</button>
    @endif
</td>
    </tr>
    @empty
    ...
    @endforelse
</tbody>
        </table>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>

    <!-- SweetAlert konfirmasi approve -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Menangkap semua form dengan class .form-approve
        const forms = document.querySelectorAll('.form-approve');
        
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Stop form submit otomatis

                Swal.fire({
                    title: 'Konfirmasi Approval',
                    text: "Stok gudang akan otomatis dipotong sesuai batch expired terdekat. Lanjutkan?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Approve!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading saat proses database berlangsung
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang memindahkan stok...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                        form.submit(); // Lanjutkan submit form
                    }
                });
            });
        });
        
    });
</script>
</x-app-layout>