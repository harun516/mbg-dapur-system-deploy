<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class CourierDashboardController extends Controller
{
    public function index()
    {
        $availableJobs = Delivery::where('status', 'Menunggu Kurir')->where('status_enable', 1)->get();
        $myActiveJobs = Delivery::where('courier_id', auth()->id())->where('status', 'Proses Antar')->get();

        $totalSelesaiHariIni = Delivery::where('courier_id', auth()->id())
            ->where('status', 'Selesai')
            ->whereDate('updated_at', now()->toDateString())
            ->count()
        ;

        return view('kurir.dashboard', compact('availableJobs', 'myActiveJobs', 'totalSelesaiHariIni'));
    }

    public function takeJob($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->update([
            'courier_id' => auth()->id(),
            'status' => 'Proses Antar',
            'waktu_antar' => now(),
        ]);

        return back()->with('success', 'Tugas diambil! Hati-hati di jalan.');
    }

    public function printSurat($id)
    {
       $delivery = Delivery::with(['productionPlan.menu', 'recipient', 'courier'])->findOrFail($id);

        return view('kurir.riwayat.print', compact('delivery'));
    }

    public function completeJob(Request $request, $id)
    {
        $request->validate([
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $delivery = Delivery::findOrFail($id);

        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('bukti_pengiriman', 'public');

            $delivery->update([
                'status' => 'Selesai',
                'foto_bukti' => $path,
                'waktu_sampai' => now(),
            ]);
        }

        return back()->with('success', 'Alhamdulillah, tugas selesai!');
    }
}
