<!DOCTYPE html>
<html>
<head>
    <title>Surat Jalan - {{ $delivery->recipient->nama_lembaga }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12pt; padding: 20px; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 16pt; font-weight: bold; text-decoration: underline; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 10px; text-align: left; }
        .footer-table { width: 100%; text-align: center; margin-top: 50px; }
        .signature-space { height: 80px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #fff3cd; padding: 10px; margin-bottom: 20px; border: 1px solid #ffeeba;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">KLIK UNTUK CETAK (CTRL+P)</button>
        <p><small>*Pastikan Printer Terhubung</small></p>
    </div>

    <div class="header">
        <h2 style="margin:0;">MBG DAPUR 01</h2>
        <p style="margin:5px;">Sistem Integrasi Produksi & Distribusi Makanan</p>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <span class="title">SURAT JALAN & TANDA TERIMA</span><br>
        <span>Nomor: SJ/{{ date('Ymd') }}/{{ $delivery->id }}</span>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Penerima</td>
            <td width="2%">:</td>
            <td><strong>{{ $delivery->recipient->nama_lembaga }}</strong></td>
            <td width="15%">Tanggal</td>
            <td width="2%">:</td>
            <td>{{ date('d/m/Y', strtotime($delivery->created_at)) }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $delivery->recipient->alamat }}</td>
            <td>Kurir</td>
            <td>:</td>
            <td>{{ $delivery->courier->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Deskripsi Menu Masakan</th>
                <th>Jumlah (Porsi)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $delivery->productionPlan->menu->nama_menu }}</td>
                <td>{{ $delivery->productionPlan->total_porsi_target }} Box</td>
                <td>Kondisi Baik & Fresh</td>
            </tr>
        </tbody>
    </table>

    <p>Mohon kiranya barang tersebut diperiksa dan diterima dengan baik.</p>

    <table class="footer-table">
        <tr>
            <td width="33%">Pengirim (Admin),</td>
            <td width="33%">Kurir,</td>
            <td width="33%">Penerima (Mitra),</td>
        </tr>
        <tr>
            <td class="signature-space"></td>
            <td class="signature-space"></td>
            <td class="signature-space"></td>
        </tr>
        <tr>
            <td>( ________________ )</td>
            <td>( {{ $delivery->courier->name ?? '........' }} )</td>
            <td>( ________________ )</td>
        </tr>
    </table>

</body>
</html>