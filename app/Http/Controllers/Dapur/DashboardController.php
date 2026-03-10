<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Production;
use App\Models\Request as PermintaanBarang;
use App\Models\KitchenStock;
use App\Models\ProductionPlan; 

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data statistik
        $data = [
            'stokKritisDapur'   => KitchenStock::where('status_enable', true)
                                    ->where('qty_sisa', '<', 5)
                                    ->count(),
            'resepAktif'        => Menu::where('status_enable', true)->count(),
            'produksiHariIni'   => Production::whereDate('created_at', today())->count(),
            'permintaanPending' => PermintaanBarang::where('status', 'pending')->count(),
        ];

        // 2. AMBIL RENCANA PRODUKSI DARI ADMIN (Baru)
        $rencanaMasak = ProductionPlan::with(['menu.requirements.item', 'productions']) 
                    ->where('status', 'Terkirim ke Dapur')
                    ->where('status_enable', true)
                    ->whereDate('tanggal_rencana', now()->format('Y-m-d'))
                    ->get();

        // 3. Ambil daftar menu terbaru (untuk referensi resep)
        $menus = Menu::with('requirements.item')
                    ->where('status_enable', true)
                    ->latest()
                    ->take(8)
                    ->get();

        // 4. Data stok dapur
        $kitchenStocks = KitchenStock::with('item')
                            ->where('status_enable', true)
                            ->where('qty_sisa', '>', 0)
                            ->get()
                            ->groupBy('item_id');

        return view('dapur.dashboard', array_merge($data, [
            'rencanaMasak' => $rencanaMasak, // Kirim ke view
            'menus' => $menus, 
            'kitchenStocks' => $kitchenStocks 
        ]));
    }
}