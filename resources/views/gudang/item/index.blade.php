<x-app-layout>
<div class="container-table">
    <h2>Master Bahan Baku (Items)</h2>

    <div class="card mb-4 p-3">
        <form action="{{ route('item.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-5">
                <input type="text" name="nama_barang" class="form-control" placeholder="Nama Bahan (ex: Beras Ramos)" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="satuan" class="form-control" placeholder="Satuan (kg/liter/pcs)" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Tambah Bahan</button>
            </div>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Bahan</th>
                <th>Satuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td data-label="Nama Bahan">{{ $item->nama_barang }}</td>
                <td data-label="Satuan">{{ $item->satuan }}</td>
                <td data-label="Status">
                    <span class="badge bg-success">Aktif</span>
                </td>
                <td data-label="Aksi">
                    <form action="{{ route('item.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menonaktifkan bahan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Nonaktifkan</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>