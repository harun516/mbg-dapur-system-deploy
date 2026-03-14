<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Hanya tampilkan yang aktif
        $items = Item::aktif()->latest()->get();

        return view('gudang.item.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        Item::create([
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'status_enable' => 1,
        ]);

        return back()->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    // Fitur "Hapus" (Sembunyikan)
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status_enable' => 0]);

        return back()->with('success', 'Bahan baku berhasil dinonaktifkan.');
    }
}
