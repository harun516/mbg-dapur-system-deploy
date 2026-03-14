<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Anggaran\Budget;
use App\Models\Anggaran\BudgetAllocation;
use App\Models\Anggaran\BudgetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GudangSaldoController extends Controller
{
    public function index()
    {
        // 1. Ambil saldo yang sudah dialokasikan admin ke gudang
        $budget = Budget::first();
        $saldoGudang = $budget->saldo_belanja_gudang ?? 0;

        // 2. Ambil riwayat dana masuk (Alokasi dari Admin)
        $riwayatMasuk = BudgetAllocation::where('nama_alokasi', 'like', '%belanja%')
            ->orderBy('created_at', 'desc')
            ->get()
        ;

        // 3. Ambil riwayat pengajuan dana ke Admin
        $riwayatRequest = BudgetRequest::orderBy('created_at', 'desc')->get();

        return view('gudang.Saldo.index', compact('saldoGudang', 'riwayatMasuk', 'riwayatRequest'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'perihal' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1000',
            'alasan' => 'nullable|string',
        ]);

        // Tambahkan Auth::id() supaya user_id terisi otomatis
        BudgetRequest::create([
            'perihal' => $request->perihal,
            'nominal' => $request->nominal,
            'catatan' => $request->alasan, // Di model kita tadi namanya 'catatan', sesuaikan ya
            'user_id' => Auth::id(),    // ID user yang login (Gudang/Dapur)
            'status' => 'pending',
            'is_enable' => true,
        ]);

        return back()->with('success', 'Permintaan anggaran telah dikirim ke Admin!');
    }
}
