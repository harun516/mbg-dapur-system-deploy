<table>
    <thead>
        <tr>
            <th colspan="10" style="font-weight: bold; text-align: center;">LAPORAN PENERIMAAN BARANG</th>
        </tr>
        <tr>
            <th>No. Penerimaan</th>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Petugas</th>
            <th>Nama Barang</th>
            <th>No. Batch</th>
            <th>Jumlah (Qty)</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
            <th>Expired</th>
        </tr>
    </thead>
    <tbody>
        @foreach($penerimaans as $p)
            @foreach($p->details as $key => $d)
                <tr>
                    @if($key == 0)
                        <td rowspan="{{ count($p->details) }}">{{ $p->no_penerimaan }}</td>
                        <td rowspan="{{ count($p->details) }}">{{ \Carbon\Carbon::parse($p->tanggal)->format('d-m-Y') }}</td>
                        <td rowspan="{{ count($p->details) }}">{{ $p->supplier }}</td>
                        <td rowspan="{{ count($p->details) }}">{{ $p->user->name ?? 'N/A' }}</td>
                    @endif
                    <td>{{ $d->item->nama_barang }}</td>
                    <td>{{ $d->no_batch }}</td>
                    <td>{{ $d->qty }} {{ $d->item->satuan }}</td>
                    <td>{{ $d->harga_satuan }}</td>
                    <td>{{ $d->qty * $d->harga_satuan }}</td>
                    <td>{{ $d->expired_date ?? '-' }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="8" style="text-align: right; font-weight: bold;">Total Pembelanjaan {{ $p->no_penerimaan }}:</td>
                <td colspan="2" style="font-weight: bold;">
                    {{ $p->details->sum(fn($item) => $item->qty * $item->harga_satuan) }}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8" style="text-align: right; font-weight: bold; background-color: #000000; color: #ffffff;">GRAND TOTAL:</td>
            <td colspan="2" style="font-weight: bold; background-color: #000000; color: #ffffff;">
                {{ $grandTotal }}
            </td>
        </tr>
    </tfoot>
</table>