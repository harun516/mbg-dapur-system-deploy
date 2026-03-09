<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggaran\Budget;
use App\Models\Anggaran\BudgetRequest;
use App\Models\Anggaran\BudgetAllocation;
use App\Models\Anggaran\BudgetTransaction;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
   public function index()
    {
    $budget = Budget::firstOrCreate(
        ['id' => 1],
        [
            'nama_proyek' => 'Program Makan Bergizi Gratis 2026',
            'modal_awal' => 0,
            'saldo_saat_ini' => 0,
            'saldo_belanja_gudang' => 0,
            'status_enable' => 1
        ]
    );

    $transactions = BudgetTransaction::where('status_enable', 1)
                                     ->orderBy('created_at', 'desc')
                                     ->get();
    
    // Ambil semua alokasi yang aktif
    $allocations = BudgetAllocation::where('status_enable', 1)->get();

    // 1. HITUNG TOTAL ALOKASI: Ambil dari sum nominal tabel allocations
    $totalAlokasi = $allocations->sum('nominal'); 
    
    // 2. MODAL AWAL
    $modalAwal = $budget->modal_awal ?? 0;
    
    // 3. PERSENTASE: Berapa persen dari modal awal yang sudah dialokasikan
    $persenTerpakai = ($modalAwal > 0) ? ($totalAlokasi / $modalAwal) * 100 : 0;
    
    // 4. SALDO SISA (Sisa di pusat yang belum dialokasikan sama sekali)
    $sisaBebas = $budget->saldo_saat_ini;
    
    // 5. SALDO GUDANG (Yang sudah dialokasikan khusus belanja)
    $saldoGudang = $budget->saldo_belanja_gudang;
    
    return view('admin.budget.index', compact(
        'budget', 
        'transactions', 
        'allocations', 
        'sisaBebas', 
        'persenTerpakai',
        'saldoGudang',
        'totalAlokasi' // Pastikan ini terkirim
    ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'kategori' => 'required|string',
            'sumber_dana' => 'required|string', 
            'keterangan' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $budget = Budget::lockForUpdate()->firstOrCreate(['id' => 1]);

            BudgetTransaction::create([
                'budget_id' => $budget->id,
                'tipe' => 'masuk',
                'kategori' => $request->kategori,
                'sumber_dana' => $request->sumber_dana,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status_enable' => 1 
            ]);

            // Update Saldo Utama
            $budget->increment('saldo_saat_ini', $request->nominal);
            
            // Set Modal Awal jika masih 0 (pertama kali input)
            if ($budget->modal_awal == 0) {
                $budget->update(['modal_awal' => $request->nominal]);
            }
        });

        return back()->with('success', 'Anggaran berhasil ditambahkan!');
    }

   public function storeAllocation(Request $request)
{
    $request->validate([
        'nama_alokasi' => 'required|string',
        'nominal' => 'required|numeric|min:1',
        'keterangan' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();

        $budget = Budget::lockForUpdate()->first();

        if ($request->nominal > $budget->saldo_saat_ini) {
            return redirect()->back()->with('error', 'Saldo utama tidak mencukupi untuk alokasi ini!');
        }

        // 1. Simpan Data Alokasi
        BudgetAllocation::create([
            'nama_alokasi' => $request->nama_alokasi,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'status_enable' => 1
        ]);

        // 2. Tentukan Jenis Transaksi & Update Saldo
        if (str_contains(strtolower($request->nama_alokasi), 'belanja')) {
            $budget->decrement('saldo_saat_ini', $request->nominal);
            $budget->increment('saldo_belanja_gudang', $request->nominal);
            $kategori = 'Alokasi Gudang';
            $pesan = 'Dana berhasil dialokasikan dan dipindahkan ke Saldo Belanja Gudang!';
        } else {
            $budget->decrement('saldo_saat_ini', $request->nominal);
            $kategori = 'Alokasi Umum';
            $pesan = 'Alokasi dana berhasil disimpan!';
        }

        // 3. PENTING: Simpan ke Riwayat Transaksi agar muncul di tabel bawah
        \App\Models\Anggaran\BudgetTransaction::create([
            'budget_id'     => $budget->id, // Tambahkan budget_id agar relasi aman
            'tipe'          => 'keluar',    // Tambahkan tipe jika kolom ini ada di migrasi
            'kategori'      => $kategori,
            'sumber_dana'   => $request->nama_alokasi,
            'nominal'       => $request->nominal, // Simpan angka positif, nanti di Blade baru beri minus (-)
            'keterangan'    => $request->keterangan ?? "Alokasi dana untuk " . $request->nama_alokasi,
            'status_enable' => 1 // WAJIB diisi 1 agar lolos filter di fungsi index()
        ]);

        DB::commit();
        return redirect()->back()->with('success', $pesan);

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function destroyAllocation($id)
    {
        try {
            DB::beginTransaction();
            $allocation = BudgetAllocation::findOrFail($id);
            $budget = Budget::first();

            // Refund Saldo saat alokasi dihapus
            if (str_contains(strtolower($allocation->nama_alokasi), 'belanja')) {
                $budget->decrement('saldo_belanja_gudang', $allocation->nominal);
                $budget->increment('saldo_saat_ini', $allocation->nominal);
            } else {
                $budget->increment('saldo_saat_ini', $allocation->nominal);
            }

            $allocation->delete();
            
            DB::commit();
            return redirect()->back()->with('success', 'Alokasi berhasil dihapus dan saldo dikembalikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus alokasi.');
        }
    }

// ==================== PENGAJUAN ANGGARAN DARI GUDANG/DAPUR ==================== //
    public function requestIndex()
{
    // Ambil semua request dengan data user pemintanya
    $requests = BudgetRequest::with('user')->orderBy('created_at', 'desc')->get();
    
    return view('admin.budget.request', compact('requests'));
}

public function approveRequest($id)
{
    try {
        DB::beginTransaction();

        $budgetRequest = BudgetRequest::findOrFail($id);
        $budget = Budget::lockForUpdate()->first();

        // 1. Validasi Status
        if ($budgetRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        // 2. Validasi Kecukupan Saldo Pusat (saldo_saat_ini)
        if ($budget->saldo_saat_ini < $budgetRequest->nominal) {
            return back()->with('error', 'Saldo pusat tidak mencukupi untuk menyetujui request ini!');
        }

        // 3. Eksekusi Perpindahan Saldo
        // Kurangi saldo pusat, pindahkan ke saldo belanja gudang
        $budget->decrement('saldo_saat_ini', $budgetRequest->nominal);
        $budget->increment('saldo_belanja_gudang', $budgetRequest->nominal);

        // 4. Catat ke Tabel Alokasi (biar masuk di riwayat alokasi index budget)
        BudgetAllocation::create([
            'nama_alokasi' => 'Persetujuan Dana: ' . $budgetRequest->perihal,
            'nominal' => $budgetRequest->nominal,
            'keterangan' => 'Disetujui dari request ' . $budgetRequest->user->name,
            'status_enable' => 1
        ]);

        // 5. Update Status Request
        $budgetRequest->update([
            'status' => 'approved',
            'catatan' => 'Disetujui oleh Admin pada ' . now()->format('d/m/Y H:i')
        ]);

        // 6. Tambahkan ke Riwayat Transaksi (Uang Keluar dari Pusat ke Gudang)
        \App\Models\Anggaran\BudgetTransaction::create([
            'budget_id'     => $budget->id,
            'tipe'          => 'keluar',
            'kategori'      => 'Persetujuan Dana',
            'sumber_dana'   => 'Request: ' . $budgetRequest->user->name,
            'nominal'       => $budgetRequest->nominal,
            'keterangan'    => $budgetRequest->perihal,
            'status_enable' => 1
        ]);

        DB::commit();
        return back()->with('success', 'Request berhasil disetujui. Saldo belanja gudang bertambah!');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}