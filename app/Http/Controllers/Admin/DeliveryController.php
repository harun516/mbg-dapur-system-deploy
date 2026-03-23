<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\ProductionPlan;
use App\Models\User;
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
            // 1. Cek apakah sekolah ini sudah dikirim untuk plan ini (cegah double)
            $exists = Delivery::where('production_plan_id', $request->production_plan_id)
                ->where('recipient_id', $request->recipient_id)
                ->where('status_enable', 1)
                ->first()
            ;

            if ($exists) {
                return back()->with('error', 'Sekolah ini sudah masuk dalam daftar pengiriman.');
            }

            // 2. LOGIKA PILIH KURIR OTOMATIS
            // Kita ambil kurir yang bertugas (role: kurir)
            $courier = User::where('role', 'kurir')->inRandomOrder()->first();

            if (!$courier) {
                return back()->with('error', 'Gagal: Tidak ada user dengan role kurir yang tersedia!');
            }

            // 3. Simpan data ke tabel deliveries dengan courier_id
            Delivery::create([
                'production_plan_id' => $request->production_plan_id,
                'recipient_id' => $request->recipient_id,
                'courier_id' => $courier->id, // WAJIB ADA agar masuk ke akun kurir
                'status' => 'Menunggu Kurir',
                'status_enable' => 1,
            ]);

            // 4. Update status rencana produksi (Opsional)
            // Kita update jadi 'Dalam Pengiriman' jika porsi sudah teralokasi semua
            ProductionPlan::where('id', $request->production_plan_id)->update(['status' => 'Selesai']);

            DB::commit();

            return back()->with('success', 'Data berhasil dikirim ke Kurir: '.$courier->name);
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
