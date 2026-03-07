<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggaran\Budget;
use App\Models\Anggaran\BudgetAllocation;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Production;

class DashboardController extends Controller
{
    public function index()
    {
    // 1. Ambil data budget (Proteksi jika kosong)
    $budget = Budget::first() ?? new Budget([
        'modal_awal' => 0, 
        'saldo_saat_ini' => 0, 
        'saldo_belanja_gudang' => 0
    ]);

    // 2. Hitung Total Alokasi yang sedang aktif
    $totalAlokasi = BudgetAllocation::where('status_enable', 1)->sum('nominal');
    $modalAwal = $budget->modal_awal ?? 0;
    $persenTerpakai = ($modalAwal > 0) ? ($totalAlokasi / $modalAwal) * 100 : 0;
    $sisaBebas = $budget->saldo_saat_ini;

    // variabel ini untuk saldo gudang
    $saldoGudang = $budget->saldo_belanja_gudang;

    return view('admin.dashboard', compact(
        'budget', 
        'sisaBebas', 
        'persenTerpakai', 
        'totalAlokasi',
        'saldoGudang'
    ));
    }
}