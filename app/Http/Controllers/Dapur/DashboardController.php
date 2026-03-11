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
    // 1. Statistik tetap sama
    $data = [
        'stokKritisDapur'   => KitchenStock::where('status_enable', true)
                                ->where('qty_sisa', '<', 5)
                                ->count(),
        'resepAktif'        => Menu::where('status_enable', true)->count(),
        'produksiHariIni'   => Production::whereDate('created_at', today())->count(),
        'permintaanPending' => PermintaanBarang::where('status', 'pending')->count(),
    ];

    // 2. PERBAIKAN QUERY RENCANA MASAK
    // Kita ambil yang statusnya 'Terkirim ke Dapur' 
    // DAN tanggal rencananya adalah <= hari ini (agar yang kemarin belum beres tetap muncul)
    $rencanaMasak = ProductionPlan::with(['menu.requirements.item', 'productions']) 
                ->where('status', 'Terkirim ke Dapur')
                ->where('status_enable', true)
                ->whereDate('tanggal_rencana', '<=', now()->format('Y-m-d')) // Ubah jadi <=
                ->orderBy('tanggal_rencana', 'asc') // Urutkan dari yang paling lama
                ->get();

    // 3. Sisanya tetap sama
    $menus = Menu::with('requirements.item')
                ->where('status_enable', true)
                ->latest()->take(8)->get();

    $kitchenStocks = KitchenStock::with('item')
                        ->where('status_enable', true)
                        ->where('qty_sisa', '>', 0)
                        ->get()->groupBy('item_id');

    return view('dapur.dashboard', array_merge($data, [
        'rencanaMasak' => $rencanaMasak,
        'menus' => $menus, 
        'kitchenStocks' => $kitchenStocks 
    ]));
    }
}