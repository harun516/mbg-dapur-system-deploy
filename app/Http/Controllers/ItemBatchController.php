<?php

namespace App\Http\Controllers;

use App\Models\Stock; 
use Illuminate\Http\Request;

class ItemBatchController extends Controller
{
    // HANYA UNTUK MONITORING (VIEW)
    public function index()
    {
        // Sekarang membaca dari tabel 'stocks' yang sudah dipotong produksi
        $batches = Stock::with('item')
            ->where('qty_sisa', '>', 0)
            ->orderBy('expired_date', 'asc')
            ->get();
            
        return view('gudang.stok', compact('batches'));
    }

    // HALAMAN KHUSUS AUDIT OPNAME
    public function opnameIndex()
    {
        // Menampilkan semua dari tabel stocks
        $batches = Stock::with('item')->latest()->get();
        return view('gudang.stok_opname', compact('batches'));
    }

    public function processOpname(Request $request, $id)
    {
        $request->validate([
            'qty_fisik' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255'
        ]);

        $batch = Stock::findOrFail($id); // Ganti ke Stock
        $stokLama = $batch->qty_sisa;

        $batch->update([
            'qty_sisa' => $request->qty_fisik,
        ]);

        // Opsional: Update juga total stok di tabel Item agar sinkron
        $diff = $request->qty_fisik - $stokLama;
        $batch->item->increment('stok', $diff);

        return redirect()->back()->with('success', "Audit berhasil: {$batch->item->nama_barang} disesuaikan.");
    }
}