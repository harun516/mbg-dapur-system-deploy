<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - {{ $delivery->id }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; mb: 20px; }
        .info-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; vertical-align: top; }
        .content-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 10px; text-align: left; }
        .footer { margin-top: 50px; width: 100%; }
        .signature { text-align: center; width: 30%; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #fff3cd; padding: 10px; margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px;">Tutup Halaman</button>
    </div>

    <div class="header">
        <h1 style="margin:0;">DAPURKU - MBG</h1>
        <p style="margin:5px 0;">Sistem Manajemen Distribusi Makanan</p>
    </div>

    <h3 style="text-align: center; text-decoration: underline;">SURAT JALAN / TANDA TERIMA</h3>

    <table class="info-table">
        <tr>
            <td width="15%">No. Pengiriman</td>
            <td width="2%">:</td>
            <td><strong>#DEL-{{ str_pad($delivery->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
            <td width="15%">Tanggal Antar</td>
            <td width="2%">:</td>
            <td>{{ $delivery->created_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Kurir</td>
            <td>:</td>
            <td>{{ $delivery->courier->name ?? 'N/A' }}</td>
            <td>Waktu Keluar</td>
            <td>:</td>
            <td>{{ $delivery->waktu_antar ? \Carbon\Carbon::parse($delivery->waktu_antar)->format('H:i') : '-' }} WIB</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr style="background: #eee;">
                <th width="50%">Deskripsi Menu / Item</th>
                <th>Jumlah (Porsi)</th>
                <th>Tujuan Pengiriman</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $delivery->productionPlan->menu->nama_menu ?? 'Menu tidak ditemukan' }}</td>
                <td>{{ $delivery->productionPlan->total_porsi_target ?? '0' }} Porsi</td>
                <td>{{ $delivery->recipient->nama_lembaga ?? 'Sekolah/Penerima' }}</td>
            </tr>
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td class="signature">
                <p>Pengirim,</p>
                <br><br><br>
                <p>( ____________________ )</p>
            </td>
            <td width="40%"></td>
            <td class="signature">
                <p>Penerima,</p>
                <br><br><br>
                <p>( ____________________ )</p>
            </td>
        </tr>
    </table>

    <div style="margin-top: 30px; font-style: italic; color: #888; font-size: 10px;">
        Dicetak otomatis oleh Sistem MBG-Dapur pada {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>