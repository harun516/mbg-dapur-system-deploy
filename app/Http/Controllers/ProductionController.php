<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Master_Penerima\Recipient;
use App\Models\Menu;
use App\Models\Item;
use App\Models\KitchenStock;
use App\Models\ProductionPlan; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with('menu')->latest()->paginate(10);
        
        // AMBIL TOTAL PORSI DARI PENERIMA AKTIF
        $totalPorsiTarget = Recipient::where('status_enable', 1)->sum('jumlah_porsi') ?? 0;

        $menus = Menu::with('requirements.item')->where('status_enable', 1)->paginate(12);
        
        return view('dapur.production.index', compact('productions', 'menus', 'totalPorsiTarget'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah_porsi' => 'required|integer|min:1',
            'plan_id' => 'nullable|exists:production_plans,id', // Validasi plan_id
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::with('requirements.item')->findOrFail($request->menu_id);

            // 1. Validasi Kecukupan Stok (Dry Run)
            foreach ($menu->requirements as $req) {
                $totalKebutuhan = $req->qty_per_porsi * $request->jumlah_porsi;
                $ada = (float) KitchenStock::where('item_id', $req->item_id)
                                    ->where('status_enable', 1)
                                    ->where('qty_sisa', '>', 0)
                                    ->sum('qty_sisa');

                if ($ada < $totalKebutuhan) {
                    $satuan = $req->item->satuan ?? 'Unit';
                    throw new \Exception("Bahan '{$req->item->nama_barang}' kurang! Butuh: " . (float)$totalKebutuhan . " {$satuan}, Tersedia: {$ada} {$satuan}.");
                }
            }

            // 2. Simpan data produksi (Sekarang membawa plan_id)
            $production = Production::create([
                'plan_id' => $request->plan_id, // Simpan ID rencana di sini
                'menu_id' => $request->menu_id,
                'jumlah_porsi' => $request->jumlah_porsi,
                'tanggal_produksi' => now(),
                'status' => 'Proses Masak',
                'user_id' => Auth::id()
            ]);

            // 3. Update Status ProductionPlan (Jika produksi berasal dari rencana Admin)
            if ($request->plan_id) {
                ProductionPlan::where('id', $request->plan_id)->update([
                    'status' => 'Selesai' // Status di Admin berubah jadi Selesai
                ]);
            }

            // 4. Eksekusi Potong Stok (FIFO)
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
            
            // Jika lewat Dashboard, redirect ke Monitoring Produksi biar user lihat perubahannya
            return redirect()->route('production.index')->with('success', "Produksi {$menu->nama_menu} dimulai! Stok dapur dipotong otomatis.");

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