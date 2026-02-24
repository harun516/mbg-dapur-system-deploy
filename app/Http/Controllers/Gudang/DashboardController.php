<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Request; 
use App\Models\Penerimaan; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Mengambil data statistik
        // Pastikan kolom 'stok' ada di tabel items, jika tidak ada sesuaikan namanya
        $totalStok = Item::sum('stok') ?? 0; 
        
        // Menghitung permintaan dari dapur yang statusnya masih 'Pending'
        // Kita gunakan model Request.php yang kamu miliki
        $permintaanPending = Request::where('status', 'Pending')->count();
        
        // Menghitung jumlah transaksi penerimaan barang hari ini
        $penerimaanHariIni = Penerimaan::whereDate('created_at', Carbon::today())->count();
        
        // Untuk Opname sementara kita set 0 karena belum ada modelnya di list kamu
        $opnamePending = 0; 

        // 2. Mengambil 8 Master Barang terbaru untuk ditampilkan di grid dashboard
        $items = Item::latest()->take(8)->get();

        return view('gudang.dashboard', compact(
            'totalStok', 
            'permintaanPending', 
            'penerimaanHariIni', 
            'opnamePending', 
            'items'
        ));
    }
}