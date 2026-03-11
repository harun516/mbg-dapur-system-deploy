<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionPlan;
use App\Models\Menu;
use App\Models\Master_Penerima\Recipient;
use App\Models\Production; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductionPlanController extends Controller
{
    public function index()
    {
        $plans = ProductionPlan::with('menu')->latest()->paginate(10);
        return view('admin.production_plan.index', compact('plans'));
    }

    public function create()
    {
        $menus = Menu::where('status_enable', 1)->get();
        $suggestedPorsi = Recipient::where('status_enable', 1)->sum('jumlah_porsi') ?? 0;
        return view('admin.production_plan.create', compact('menus', 'suggestedPorsi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_rencana' => 'required|date',
            'menu_id' => 'required|exists:menus,id',
            'total_porsi_target' => 'required|integer|min:1',
        ]);

        ProductionPlan::create([
            'tanggal_rencana'    => $request->tanggal_rencana,
            'menu_id'            => $request->menu_id,
            'total_porsi_target' => $request->total_porsi_target,
            'catatan_admin'      => $request->catatan_admin,
            'status'             => 'Draft',
            'status_enable'      => 1,
        ]);

        return redirect()->route('admin.production_plan.index')->with('success', 'Rencana produksi baru berhasil dijadwalkan!');
    }

    public function edit($id)
    {
        $plan = ProductionPlan::findOrFail($id);
        $menus = Menu::where('status_enable', 1)->get();
        return view('admin.production_plan.edit', compact('plan', 'menus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_rencana' => 'required|date',
            'menu_id' => 'required|exists:menus,id',
            'total_porsi_target' => 'required|integer|min:1',
            'status' => 'required'
        ]);

        $plan = ProductionPlan::findOrFail($id);
        $plan->update([
            'tanggal_rencana'    => $request->tanggal_rencana,
            'menu_id'            => $request->menu_id,
            'total_porsi_target' => $request->total_porsi_target,
            'catatan_admin'      => $request->catatan_admin,
            'status'             => $request->status,
        ]);

        return redirect()->route('admin.production_plan.index')->with('success', 'Rencana produksi berhasil diperbarui!');
    } 

    /**
     * LOGIKA KIRIM KE DAPUR (FIX DOUBLE DATA)
     */
    public function updateStatus(Request $request, $id)
    {
    $plan = ProductionPlan::findOrFail($id);
    
    DB::beginTransaction();
    try {
        // 1. Update status Admin
        $plan->update(['status' => $request->status]);

        // 2. Jika kirim ke dapur
        if ($request->status == 'Terkirim ke Dapur') {
            // Kita gunakan updateOrCreate untuk memastikan HANYA ADA 1 record per plan_id
            Production::updateOrCreate(
                ['plan_id' => $plan->id], 
                [
                    'menu_id'          => $plan->menu_id,
                    'tanggal_produksi' => $plan->tanggal_rencana,
                    'jumlah_porsi'     => $plan->total_porsi_target,
                    'status'           => 'Proses Masak',
                    'user_id'          => auth()->id() ?? 1
                ]
            );
        }

        DB::commit();
        return back()->with('success', 'Berhasil terkirim ke Dapur!');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
    }

    public function destroy($id)
    {
        $plan = ProductionPlan::findOrFail($id);
        $plan->delete();
        return redirect()->route('admin.production_plan.index')->with('success', 'Rencana produksi berhasil dihapus!');
    }
}