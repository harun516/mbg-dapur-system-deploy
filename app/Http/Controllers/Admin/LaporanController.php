<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AnggaranExport;
// Namespace sesuai struktur folder kamu
use App\Http\Controllers\Controller;
use App\Models\Anggaran\Budget;
use App\Models\Anggaran\Salary\SalaryPayment;
use App\Models\PenerimaanDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        // 1. Total Saldo Aktif
        $totalAnggaran = Budget::where('status_enable', true)->sum('saldo_saat_ini');

        // 2. Total Salary Terbayar
        $totalSalary = SalaryPayment::whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('total_diterima')
        ;

        // 3. FIX: Total Belanja Gudang (Menghitung qty * harga_satuan dari detail)
        $totalBelanja = PenerimaanDetail::whereHas('penerimaan', function ($query) {
            $query->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
            ;
        })->sum(DB::raw('qty * harga_satuan'));

        return view('admin.laporan.index', compact('totalAnggaran', 'totalSalary', 'totalBelanja'));
    }

    public function exportAnggaran()
    {
        return Excel::download(new AnggaranExport(), 'rekap-anggaran-'.date('d-m-Y').'.xlsx');
    }
}
