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

        $allocations = BudgetAllocation::where('status_enable', 1)->get();
        $totalAlokasi = $allocations->sum('nominal');
        $modalAwal = $budget->saldo_saat_ini ?? 0;
        $persenTerpakai = ($modalAwal > 0) ? ($totalAlokasi / $modalAwal) * 100 : 0;
        $sisaBebas = $budget->saldo_saat_ini;
        $saldoGudang = $budget->saldo_belanja_gudang;

        return view('admin.budget.index', compact(
            'budget',
            'transactions',
            'allocations',
            'sisaBebas',
            'persenTerpakai',
            'saldoGudang',
            'totalAlokasi'
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
                'kategori' => 'Modal Utama', // Disesuaikan untuk label Hijau "Anggaran Masuk"
                'sumber_dana' => $request->sumber_dana,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status_enable' => 1
            ]);

            $budget->increment('saldo_saat_ini', $request->nominal);

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

            // Validasi Budget ada
            if (!$budget) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Data budget tidak ditemukan!');
            }

            // Validasi Saldo Cukup
            if ($request->nominal > $budget->saldo_saat_ini) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Saldo utama tidak mencukupi untuk alokasi ini!');
            }

            BudgetAllocation::create([
                'nama_alokasi' => $request->nama_alokasi,
                'nominal' => $request->nominal,
                'keterangan' => $request->keterangan,
                'status_enable' => 1
            ]);

            // Penyesuaian Kategori agar sesuai Label Biru di Blade
            if (str_contains(strtolower($request->nama_alokasi), 'belanja')) {
                $budget->decrement('saldo_saat_ini', $request->nominal);
                $budget->increment('saldo_belanja_gudang', $request->nominal);
                $kategori = 'Kirim Saldo ke gudang';
                $pesan = 'Dana berhasil dialokasikan dan dipindahkan ke Saldo Belanja Gudang!';
            } else {
                $budget->decrement('saldo_saat_ini', $request->nominal);
                $kategori = 'Alokasi Umum';
                $pesan = 'Alokasi dana berhasil disimpan!';
            }

            BudgetTransaction::create([
                'budget_id'     => $budget->id,
                'tipe'          => 'keluar',
                'kategori'      => $kategori,
                'sumber_dana'   => $request->nama_alokasi,
                'nominal'       => $request->nominal,
                'keterangan'    => $request->keterangan ?? "Alokasi dana untuk " . $request->nama_alokasi,
                'status_enable' => 1
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

            if ($budgetRequest->status !== 'pending') {
                return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
            }

            if ($budget->saldo_saat_ini < $budgetRequest->nominal) {
                return back()->with('error', 'Saldo pusat tidak mencukupi!');
            }

            $budget->decrement('saldo_saat_ini', $budgetRequest->nominal);
            $budget->increment('saldo_belanja_gudang', $budgetRequest->nominal);

            BudgetAllocation::create([
                'nama_alokasi' => 'Persetujuan Dana: ' . $budgetRequest->perihal,
                'nominal' => $budgetRequest->nominal,
                'keterangan' => 'Disetujui dari request ' . $budgetRequest->user->name,
                'status_enable' => 1
            ]);

            $budgetRequest->update([
                'status' => 'approved',
                'catatan' => 'Disetujui oleh Admin pada ' . now()->format('d/m/Y H:i')
            ]);

            // Penyesuaian Kategori agar label seragam dengan alokasi manual
            BudgetTransaction::create([
                'budget_id'     => $budget->id,
                'tipe'          => 'keluar',
                'kategori'      => 'Kirim Saldo ke gudang',
                'sumber_dana'   => 'Request: ' . $budgetRequest->user->name,
                'nominal'       => $budgetRequest->nominal,
                'keterangan'    => 'Persetujuan request: ' . $budgetRequest->perihal,
                'status_enable' => 1
            ]);

            DB::commit();
            return back()->with('success', 'Request disetujui. Saldo belanja gudang bertambah!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
