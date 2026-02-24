<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Production;
use App\Models\Request as PermintaanBarang;
use App\Models\KitchenStock;



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

    // 2. Ambil daftar menu terbaru
    $menus = Menu::with('requirements.item')
                ->where('status_enable', true)
                ->latest()
                ->take(8)
                ->get();

    // 3. Ambil data stok dan KELOMPOKKAN berdasarkan item_id
    // Ini penting agar di View kita bisa melooping $kitchenStocks
    $kitchenStocks = KitchenStock::with('item')
                        ->where('status_enable', true)
                        ->where('qty_sisa', '>', 0)
                        ->get()
                        ->groupBy('item_id'); // Mengelompokkan batch berdasarkan barang yang sama

    return view('dapur.dashboard', array_merge($data, [
        'menus' => $menus, 
        'kitchenStocks' => $kitchenStocks // Nama variabel disamakan dengan View
    ]));
    }
}