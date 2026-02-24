<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    public function create()
    {
        // $items = Item::all(); // Untuk dropdown pilihan barang
        $items = Item::aktif()->orderBy('nama_barang', 'asc')->get(); // Hanya tampilkan yang aktif
        return view('gudang.penerimaan.input', compact('items'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'tanggal' => 'required|date',
        'supplier' => 'required|string',
        'items' => 'required|array',
        'items.*.item_id' => 'required|exists:items,id',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.harga_satuan' => 'required|numeric|min:0', // Validasi harga
        'items.*.no_batch' => 'required|string',
        'items.*.expired_date' => 'required|date',
    ]);

    try {
        DB::beginTransaction();

        // 1. Simpan Header Penerimaan
        $penerimaan = Penerimaan::create([
            'tanggal' => $request->tanggal,
            'supplier' => $request->supplier,
            'user_id' => Auth::id(),
        ]);

       // 2. Simpan Detail dan Update Tabel Stock
        foreach ($request->items as $item) {
            // Simpan ke detail penerimaan (untuk arsip transaksi)
            $detail = $penerimaan->details()->create([
                'item_id'      => $item['item_id'],
                'no_batch'     => $item['no_batch'],
                'qty'          => $item['qty'],
                'harga_satuan' => $item['harga_satuan'],
                'expired_date' => $item['expired_date'],
            ]);

            // --- BAGIAN PENYESUAIAN STOK ---
            // Simpan ke tabel stocks (Pusat Saldo Barang)
            Stock::create([
                'item_id'      => $item['item_id'],
                'no_batch'     => $item['no_batch'],
                'qty_masuk'    => $item['qty'],
                'qty_sisa'     => $item['qty'], // Saat baru masuk, sisa = masuk
                'expired_date' => $item['expired_date'],
            ]);

            // Optional: Tetap update total stok di tabel items jika ingin sinkron
            $masterItem = Item::find($item['item_id']);
            $masterItem->increment('stok', $item['qty']);
            // -------------------------------
        }

        DB::commit();
        return redirect()->route('penerimaan.index')->with('success', 'Penerimaan barang berhasil dicatat!');
        
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
    }

    public function index(Request $request)
    {
    // Menggunakan eager loading agar query efisien (mencegah N+1)
    $query = Penerimaan::with(['details.item', 'user'])->latest();

    // Filter Tanggal
    if ($request->filled('start_date')) {
        $query->whereDate('tanggal', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('tanggal', '<=', $request->end_date);
    }

    // Filter Nama Barang (melalui detail)
    if ($request->filled('item_id')) {
        $query->whereHas('details', function($q) use ($request) {
            $q->where('item_id', $request->item_id);
        });
    }

    // Menggunakan paginate agar loading halaman tidak berat jika data sudah ribuan
    $penerimaans = $query->paginate(10)->withQueryString(); 

    // Menggunakan scopeAktif yang kita buat tadi agar dropdown rapi
    $items = Item::aktif()->orderBy('nama_barang')->get();

    return view('gudang.penerimaan.index', compact('penerimaans', 'items'));
    }
}