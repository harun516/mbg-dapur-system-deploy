<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Menu;
use App\Models\Item;
use App\Models\KitchenStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    public function index()
    {
        // Ambil riwayat produksi untuk tabel riwayat
        $productions = Production::with('menu')->latest()->paginate(10);
        
        // Ambil daftar Menu (Master Resep) untuk ditampilkan sebagai Card Produksi
        $menus = Menu::with('requirements.item')->where('status_enable', 1)->paginate(12);
        
        return view('dapur.production.index', compact('productions', 'menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah_porsi' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::with('requirements.item')->findOrFail($request->menu_id);

            // 1. Validasi Kecukupan Stok (Dry Run)
            foreach ($menu->requirements as $req) {
                $totalKebutuhan = $req->qty_per_porsi * $request->jumlah_porsi;
                $ada = (float) KitchenStock::where('item_id', $req->item_id)
                                ->where('status_enable', 1)
                                ->sum('qty_sisa');

                if ($ada < $totalKebutuhan) {
                    $satuan = $req->item->satuan ?? 'Unit';
                    throw new \Exception("Bahan '{$req->item->nama_barang}' kurang! Butuh: " . (float)$totalKebutuhan . " {$satuan}, Tersedia: {$ada} {$satuan}.");
                }
            }

            // 2. Simpan data produksi
            Production::create([
                'menu_id' => $request->menu_id,
                'jumlah_porsi' => $request->jumlah_porsi,
                'tanggal_produksi' => now(),
                'status' => 'Proses Masak',
                'user_id' => Auth::id()
            ]);

            // 3. Eksekusi Potong Stok (FIFO)
            foreach ($menu->requirements as $req) {
                $remainingToDeduct = $req->qty_per_porsi * $request->jumlah_porsi;
                $batches = KitchenStock::where('item_id', $req->item_id)
                            ->where('status_enable', 1)
                            ->where('qty_sisa', '>', 0)
                            ->orderBy('created_at', 'asc')
                            ->get();

                foreach ($batches as $batch) {
                    if ($remainingToDeduct <= 0) break;
                    if ($batch->qty_sisa >= $remainingToDeduct) {
                        $batch->decrement('qty_sisa', $remainingToDeduct);
                        $remainingToDeduct = 0;
                    } else {
                        $remainingToDeduct -= $batch->qty_sisa;
                        $batch->update(['qty_sisa' => 0]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "Produksi {$menu->nama_menu} dimulai! Stok dapur berhasil dipotong.");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Produksi Gagal: " . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Proses Masak,Packing,Siap Distribusi,Batal'
        ]);

        try {
            $production = Production::findOrFail($id);
            $production->update(['status' => $request->status]);
            return redirect()->back()->with('success', "Status berhasil diperbarui ke {$request->status}");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Gagal update status: " . $e->getMessage());
        }
    }
}