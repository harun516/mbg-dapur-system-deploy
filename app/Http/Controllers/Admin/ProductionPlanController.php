<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionPlan;
use App\Models\Menu;
use App\Models\Master_Penerima\Recipient;
use Illuminate\Http\Request;

class ProductionPlanController extends Controller
{
    /**
     * Tampilkan daftar rencana produksi
     */
    public function index()
    {
        // Tetap pakai with('menu') agar tidak kena error N+1 query
        $plans = ProductionPlan::with('menu')->latest()->paginate(10);
        return view('admin.production_plan.index', compact('plans'));
    }

    /**
     * Form buat rencana baru
     */
    public function create()
    {
        $menus = Menu::where('status_enable', 1)->get();
        // Hitung otomatis porsi dari penerima aktif
        $suggestedPorsi = Recipient::where('status_enable', 1)->sum('jumlah_porsi') ?? 0;
        
        return view('admin.production_plan.create', compact('menus', 'suggestedPorsi'));
    }

    /**
     * Simpan rencana baru ke database
     */
    public function store(Request $request)
    {
    // 1. Validasi Input
    $request->validate([
        'tanggal_rencana' => 'required|date',
        'menu_id' => 'required|exists:menus,id',
        'total_porsi_target' => 'required|integer|min:1',
    ]);

    // 2. Simpan Data dengan Status Default
    // Kita tambahkan status 'Draft' dan 'status_enable' secara otomatis 
    // agar Admin tidak perlu repot isi di form.
    ProductionPlan::create([
        'tanggal_rencana'    => $request->tanggal_rencana,
        'menu_id'            => $request->menu_id,
        'total_porsi_target' => $request->total_porsi_target,
        'catatan_admin'      => $request->catatan_admin,
        'status'             => 'Draft', // Default awal adalah Draft
        'status_enable'      => 1,       // Default aktif
    ]);

    // 3. Redirect ke Index
    return redirect()->route('admin.production_plan.index')
                     ->with('success', 'Rencana produksi baru berhasil dijadwalkan!');
    }

    /**
     * Form edit rencana
     */
    public function edit($id)
    {
        $plan = ProductionPlan::findOrFail($id);
        $menus = Menu::where('status_enable', 1)->get();
        
        return view('admin.production_plan.edit', compact('plan', 'menus'));
    }

    /**
     * Update data rencana
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_rencana' => 'required|date',
            'menu_id' => 'required|exists:menus,id',
            'total_porsi_target' => 'required|integer|min:1',
            'status' => 'required'
        ]);

        $plan = ProductionPlan::findOrFail($id);
        $plan->update($request->all());

        return redirect()->route('admin.production_plan.index')
                         ->with('success', 'Rencana produksi berhasil diperbarui!');
    } 

    /**
     * Update status cepat (Draft -> Terkirim)
     */
    public function updateStatus(Request $request, $id)
    {
        $plan = ProductionPlan::findOrFail($id);
        $plan->update([
            'status' => $request->status 
        ]);

        return back()->with('success', 'Status rencana telah diperbarui!');
    }

    /**
     * Hapus rencana (Gunakan delete() atau set status_enable = 0)
     */
    public function destroy($id)
    {
        $plan = ProductionPlan::findOrFail($id);
        $plan->delete(); // Atau pakai $plan->update(['status_enable' => 0]);

        return redirect()->route('admin.production_plan.index')
                         ->with('success', 'Rencana produksi berhasil dihapus!');
    }
}