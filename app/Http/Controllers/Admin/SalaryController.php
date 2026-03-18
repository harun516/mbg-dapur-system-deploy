<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggaran\Budget;
use App\Models\Anggaran\BudgetTransaction;
use App\Models\Anggaran\Salary\SalaryConfig;
use App\Models\Anggaran\Salary\SalaryPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    /**
     * Menampilkan halaman Payroll & Setting Gaji.
     */
    public function index(Request $request)
    {
        $roles = ['admin', 'dapur', 'gudang', 'kurir']; // atau ambil dari Role model kalau pakai Spatie
        $salaryConfigs = SalaryConfig::all(); // ambil semua, termasuk non-aktif kalau mau

        // Filter pembayaran gaji (sudah ada)
        $query = SalaryPayment::with('user');
        if ($request->has('periode') && '' != $request->periode) {
            [$bulan, $tahun] = explode(' ', $request->periode);
            $query->whereMonth('tanggal_bayar', Carbon::parse($bulan)->month)
                ->whereYear('tanggal_bayar', $tahun)
            ;
        }
        $payments = $query->latest('tanggal_bayar')->get();

        return view('admin.salary.index', compact('roles', 'salaryConfigs', 'payments'));
    }

    /**
     * Simpan / Update konfigurasi gaji (pakai updateOrCreate supaya bisa edit juga).
     */
    public function storeConfig(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:50',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
            'status_enable' => 'boolean',
        ]);

        SalaryConfig::updateOrCreate(
            ['role_name' => $request->role_name],
            [
                'gaji_pokok' => $request->gaji_pokok,
                'tunjangan' => $request->tunjangan,
                'total_diterima' => $request->gaji_pokok + $request->tunjangan,
                'status_enable' => $request->status_enable ?? true,
            ]
        );

        return redirect()->back()->with('success', 'Konfigurasi gaji berhasil disimpan / diperbarui.');
    }

    /**
     * Update konfigurasi gaji (khusus edit via modal).
     *
     * @param mixed $id
     */
    public function updateConfig(Request $request, $id)
    {
        $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan' => 'required|numeric|min:0',
            // status_enable tidak required karena checkbox bisa kosong
        ]);

        $config = SalaryConfig::findOrFail($id);

        $config->update([
            'gaji_pokok' => $request->gaji_pokok,
            'tunjangan' => $request->tunjangan,
            'total_diterima' => $request->gaji_pokok + $request->tunjangan,
            // Jika checkbox tidak dicentang, set ke false/0
            'status_enable' => $request->has('status_enable'),
        ]);

        return redirect()->back()->with('success', 'Konfigurasi gaji '.strtoupper($config->role_name).' berhasil diperbarui.');
    }

    // Hapus konfigurasi gaji
    public function destroyConfig($id)
    {
        $config = SalaryConfig::findOrFail($id);
        $roleName = $config->role_name;
        $config->delete();

        return redirect()->back()->with('success', 'Standar gaji untuk role '.strtoupper($roleName).' berhasil dihapus.');
    }

    // Proses Pembayaran Gaji (Potong Saldo Anggaran)
    public function processPayment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periode_bulan' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::find($request->user_id);
            $config = SalaryConfig::where('role_name', $user->role)->first();

            if (!$config) {
                return back()->with('error', 'Standar gaji untuk role '.$user->role.' belum diatur.');
            }

            $budget = Budget::first();
            $totalGaji = $config->total_diterima;

            // Cek Saldo
            if ($budget->saldo_saat_ini < $totalGaji) {
                return back()->with('error', 'Saldo anggaran tidak mencukupi untuk membayar gaji.');
            }

            // 1. Catat di Tabel Salary Payments
            SalaryPayment::create([
                'budget_id' => $budget->id,
                'user_id' => $user->id,
                'periode_bulan' => $request->periode_bulan,
                'tanggal_bayar' => now(),
                'total_diterima' => $totalGaji,
                'status_enable' => 1,
                'keterangan' => 'Pembayaran gaji periode '.$request->periode_bulan,
            ]);

            // 2. Potong Saldo Utama di Tabel Budgets
            $budget->decrement('saldo_saat_ini', $totalGaji);

            // 3. Catat di Histori Transaksi Anggaran (Budget Transactions)
            BudgetTransaction::create([
                'budget_id' => $budget->id,
                'tipe' => 'keluar',
                'kategori' => 'Gaji Pegawai',
                'sumber_dana' => 'Kas Internal',
                'nominal' => $totalGaji,
                'keterangan' => 'Gaji '.$user->name.' ('.$request->periode_bulan.')',
                'status_enable' => 1,
            ]);

            return back()->with('success', 'Gaji berhasil dibayarkan dan saldo anggaran terpotong.');
        });
    }
}
