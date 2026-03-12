<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Production;
use App\Models\Master_Penerima\Recipient;
use App\Models\Menu;
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
        $totalPorsiTarget = Recipient::where('status_enable', 1)->sum('jumlah_porsi') ?? 0;
        $menus = Menu::with('requirements.item')->where('status_enable', 1)->paginate(12);
        
        return view('dapur.production.index', compact('productions', 'menus', 'totalPorsiTarget'));
    }

    /**
     * LOGIKA MULAI MASAK DARI DASHBOARD (STOK FIFO)
     */
   public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah_porsi' => 'required|integer|min:1',
            'plan_id' => 'nullable|exists:production_plans,id', 
        ]);

        DB::beginTransaction();
        try {
            $menu = Menu::with('requirements.item')->findOrFail($request->menu_id);

            // 1. Validasi Kecukupan Stok (Tetap sama)
            foreach ($menu->requirements as $req) {
                $totalKebutuhan = $req->qty_per_porsi * $request->jumlah_porsi;
                $ada = (float) KitchenStock::where('item_id', $req->item_id)
                                    ->where('status_enable', 1)
                                    ->where('qty_sisa', '>', 0)
                                    ->sum('qty_sisa');

                if ($ada < $totalKebutuhan) {
                    throw new \Exception("Bahan '{$req->item->nama_barang}' kurang!");
                }
            }

            // 2. Simpan/Update data produksi
            // Pastikan status HANYA berubah jadi 'Proses Masak' saat tombol ini ditekan
            $production = Production::updateOrCreate(
                ['plan_id' => $request->plan_id],
                [
                    'menu_id' => $request->menu_id,
                    'jumlah_porsi' => $request->jumlah_porsi,
                    'tanggal_produksi' => now(),
                    'status' => 'Proses Masak', // Di sini baru jadi Proses Masak
                    'user_id' => Auth::id()
                ]
            );

            // 3. Sinkronkan status ke ProductionPlan (Admin)
            if ($request->plan_id) {
                // Gunakan status yang konsisten dengan tabel rencana harian
                ProductionPlan::where('id', $request->plan_id)->update(['status' => 'Proses Masak']);
            }

            // 4. Potong Stok FIFO (Tetap sama)
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
            return redirect()->route('production.index')->with('success', "Produksi dimulai! Status sinkron ke Admin.");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Produksi Gagal: " . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * UPDATE STATUS SINKRON KE ADMIN (FIX STATUS TIDAK BERUBAH)
     */
   public function updateStatus(Request $request, $id)
    {
    $request->validate([
        'status' => 'required|in:Proses Masak,Packing,Siap Distribusi,Batal'
    ]);

    DB::beginTransaction();
    try {
        $production = Production::findOrFail($id);
        
        // 1. Update status di tabel Dapur
        $production->update(['status' => $request->status]);

        // 2. Sinkronisasi ke tabel Admin
        if ($production->plan_id) {
            $statusMap = [
                'Proses Masak'    => 'Proses Masak',
                'Packing'         => 'Packing',
                'Siap Distribusi' => 'Selesai', // Mapping ke ENUM Admin
                'Batal'           => 'Dibatalkan'
            ];

            $adminStatus = $statusMap[$request->status] ?? $request->status;

            // Update menggunakan Eloquent agar trigger update berjalan baik
            ProductionPlan::where('id', $production->plan_id)->update([
                'status' => $adminStatus
            ]);
        }

        DB::commit();
        return redirect()->back()->with('success', "Status diperbarui ke {$request->status}");
    } catch (\Exception $e) {
        DB::rollback();
        Log::error("Gagal Update Status: " . $e->getMessage());
        return redirect()->back()->with('error', "Gagal: " . $e->getMessage());
    }
    }
}