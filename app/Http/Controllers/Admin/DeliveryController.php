<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'production_plan_id' => 'required|exists:production_plans,id',
            'recipient_id' => 'required|exists:recipients,id',
        ]);

        DB::beginTransaction();

        try {
            // 1. Cek apakah pengiriman untuk plan ini sudah pernah dibuat
            $exists = Delivery::where('production_plan_id', $request->production_plan_id)
                ->where('status_enable', 1)
                ->first()
            ;

            if ($exists) {
                return back()->with('error', 'Rencana produksi ini sudah dalam proses pengiriman.');
            }

            // 2. Simpan data ke tabel deliveries
            Delivery::create([
                'production_plan_id' => $request->production_plan_id,
                'recipient_id' => $request->recipient_id,
                'status' => 'Menunggu Kurir',
                'status_enable' => $request->status_enable ?? 1,
            ]);

            // 3. Opsional: Update status di production_plans jika ingin dibedakan
            // ProductionPlan::where('id', $request->production_plan_id)->update(['status' => 'Selesai']);

            DB::commit();

            return back()->with('success', 'Data berhasil dikirim ke Dashboard Kurir!');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Gagal memproses pengiriman: '.$e->getMessage());
        }
    }

    // Fungsi untuk membatalkan pengiriman (Status Enable = 0)
    public function disable($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->update(['status_enable' => 0]);

        return back()->with('success', 'Pengiriman berhasil dinonaktifkan.');
    }
}
