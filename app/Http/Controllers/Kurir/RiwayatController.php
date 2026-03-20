<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
   public function index(Request $request)
    {
    // Eager load relasi: productionPlan (beserta menunya) dan recipient
    $query = Delivery::with(['productionPlan.menu', 'recipient'])
        ->where('courier_id', auth()->id());

    if ($request->filled('tanggal')) {
        $query->whereDate('created_at', $request->tanggal);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $riwayat = $query->latest()->paginate(10);

    return view('kurir.riwayat.index', compact('riwayat'));
    }
}