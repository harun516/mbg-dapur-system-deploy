<x-app-layout>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }
    .container-prod {
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    h2 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 25px; }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-masak { background: #fff3cd; color: #856404; }
    .status-packing { background: #cce5ff; color: #004085; }
    .status-siap { background: #d4edda; color: #155724; }

    .btn-update {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        color: white;
        font-weight: 500;
        cursor: pointer;
        transition: 0.3s;
    }
    .bg-next { background-color: #6f42c1; } /* Warna ungu untuk proses lanjut */
    .bg-next:hover { background-color: #59359a; }
</style>
@if(session('error'))
    <div style="padding: 15px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 6px; margin-bottom: 20px;">
        <strong>Gagal:</strong> {{ session('error') }}
    </div>
@endif

<div class="container-prod">
    <h2>Monitoring Produksi Harian</h2>

    @if(session('success'))
        <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #007bff; color: white; text-align: left;">
                <th style="padding: 12px;">Waktu & Menu</th>
                <th style="padding: 12px;">Jumlah Porsi</th>
                <th style="padding: 12px;">Status Saat Ini</th>
                <th style="padding: 12px;">Aksi Perubahan Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productions as $prod)
            <tr style="border-bottom: 1px solid #dee2e6;">
                <td style="padding: 15px;">
                    <div style="font-weight: bold;">{{ $prod->menu->nama_menu }}</div>
                    <small style="color: #6c757d;">{{ $prod->created_at->format('d M Y, H:i') }}</small>
                </td>
                <td style="padding: 15px;">{{ number_format($prod->jumlah_porsi) }} Porsi</td>
                <td style="padding: 15px;">
                    @php
                        $class = '';
                        if($prod->status == 'Proses Masak') $class = 'status-masak';
                        elseif($prod->status == 'Packing') $class = 'status-packing';
                        else $class = 'status-siap';
                    @endphp
                    <span class="status-badge {{ $class }}">{{ $prod->status }}</span>
                </td>
                <td style="padding: 15px;">
                    @if($prod->status != 'Siap Distribusi')
                        <form action="{{ route('production.updateStatus', $prod->id) }}" method="POST">
                            @csrf
                            @php
                                $nextStatus = '';
                                $btnLabel = '';
                                if($prod->status == 'Proses Masak') {
                                    $nextStatus = 'Packing';
                                    $btnLabel = 'Lanjut ke Packing';
                                } elseif($prod->status == 'Packing') {
                                    $nextStatus = 'Siap Distribusi';
                                    $btnLabel = 'Siap Distribusi';
                                }
                            @endphp
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <button type="submit" class="btn-update bg-next">
                                {{ $btnLabel }} &raquo;
                            </button>
                        </form>
                    @else
                        <span style="color: #28a745; font-weight: bold;"><i class="fas fa-check-circle"></i> Selesai di Dapur</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 30px; text-align: center; color: #6c757d;">
                    Belum ada aktivitas produksi hari ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $productions->links() }}
    </div>
</div>
</x-app-layout>