<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Item;
use App\Models\MenuRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index() {
       $menus = Menu::with('requirements.item')->latest()->paginate(10); 
      return view('dapur.menu.index', compact('menus'));
    }

    public function create() {
        $items = Item::aktif()->orderBy('nama_barang')->get();
        return view('dapur.menu.create', compact('items'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'porsi_standar' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty_per_porsi' => 'required|numeric|min:0.0001',
        ]);

        try {
            DB::beginTransaction();
            
            $menu = Menu::create([
                'nama_menu' => $request->nama_menu,
                'porsi_standar' => $request->porsi_standar,
            ]);

            foreach ($request->items as $item) {
                $menu->requirements()->create([
                    'item_id' => $item['item_id'],
                    'qty_per_porsi' => $item['qty_per_porsi'],
                ]);
            }

            DB::commit();
            return redirect()->route('menu.index')->with('success', 'Master Resep berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        // Ambil data menu beserta bahannya
        $menu = Menu::with('requirements.item')->findOrFail($id);
        
        // Ambil semua item aktif untuk pilihan di form (gunakan scope 'aktif' yang sudah kamu buat)
        $items = Item::aktif()->orderBy('nama_barang')->get();
        
        return view('dapur.menu.edit', compact('menu', 'items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'porsi_standar' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty_per_porsi' => 'required|numeric|min:0.0001',
        ]);

        try {
            DB::beginTransaction();

            $menu = Menu::findOrFail($id);
            $menu->update([
                'nama_menu' => $request->nama_menu,
                'porsi_standar' => $request->porsi_standar,
            ]);

            // Hapus detail resep lama
            $menu->requirements()->delete();

            // Masukkan detail resep yang baru dari form edit
            foreach ($request->items as $item) {
                $menu->requirements()->create([
                    'item_id' => $item['item_id'],
                    'qty_per_porsi' => $item['qty_per_porsi'],
                ]);
            }

            DB::commit();
            return redirect()->route('menu.index')->with('success', 'Master Resep berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }
}