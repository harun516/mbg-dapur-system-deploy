<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionPlan;
use App\Models\Menu;
use App\Models\Master_Penerima\Recipient;
use App\Models\Production; 
use App\Models\User;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductionPlanController extends Controller
{
    public function index(Request $request)
    {
    // --- Filter Rencana Produksi (Atas) ---
    $queryPlan = ProductionPlan::with('menu');

    if ($request->filled('plan_date')) {
        $queryPlan->whereDate('tanggal_rencana', $request->plan_date);
    }
    if ($request->filled('plan_menu')) {
        $queryPlan->where('menu_id', $request->plan_menu);
    }
    
    $plans = $queryPlan->latest()->paginate(10)->withQueryString();

    // --- Filter Monitoring Kurir (Bawah) ---
    $queryDelivery = Delivery::with(['productionPlan.menu', 'recipient', 'courier'])->where('status_enable', 1);

    if ($request->filled('delivery_date')) {
        $queryDelivery->whereDate('created_at', $request->delivery_date);
    }
    if ($request->filled('delivery_school')) {
        $queryDelivery->where('recipient_id', $request->delivery_school);
    }
    if ($request->filled('delivery_courier')) {
        $queryDelivery->where('courier_id', $request->delivery_courier);
    }
    if ($request->filled('delivery_status')) {
        $queryDelivery->where('status', $request->delivery_status);
    }

    $deliveries = $queryDelivery->latest()->get();

    // Data untuk Dropdown Filter
    $menus = \App\Models\Menu::all();
    $recipients = \App\Models\Master_Penerima\Recipient::where('status_enable', 1)->orderBy('nama_lembaga', 'asc')->get();
    $couriers = \App\Models\User::where('role', 'kurir')->get();

    return view('admin.production_plan.index', compact('plans', 'deliveries', 'menus', 'recipients', 'couriers'));
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
        // 1. Update status di tabel Rencana (Admin)
        $plan->update(['status' => $request->status]);

        // 2. Jika Admin memilih "Terkirim ke Dapur"
        if ($request->status == 'Terkirim ke Dapur') {
            Production::updateOrCreate(
                ['plan_id' => $plan->id], 
                [
                    'menu_id'          => $plan->menu_id,
                    'tanggal_produksi' => $plan->tanggal_rencana,
                    'jumlah_porsi'     => $plan->total_porsi_target,
                    'status'           => 'Menunggu Dapur', // STATUS AWAL DI DAPUR
                    'user_id'          => Auth::id() ?? 1
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
}