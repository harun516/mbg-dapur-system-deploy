<?php

namespace App\Http\Controllers;

use App\Models\Penerimaan;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Anggaran\Budget; // Import model Budget
use App\Models\Anggaran\BudgetTransaction; // Import model Transaction
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    public function create()
    {
        $items = Item::aktif()->orderBy('nama_barang', 'asc')->get();
        
        // Ambil saldo gudang untuk ditampilkan di form (Saldo Terkini)
        $budget = Budget::first(); 
        $saldoGudang = $budget ? $budget->saldo_belanja_gudang : 0;

        return view('gudang.penerimaan.input', compact('items', 'saldoGudang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier' => 'required|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.no_batch' => 'required|string',
            'items.*.expired_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // --- LOGIKA ANGGARAN ---
            // 1. Hitung Total Nominal Belanja dari request
            $totalBelanja = 0;
            foreach ($request->items as $item) {
                $totalBelanja += ($item['qty'] * $item['harga_satuan']);
            }

            // 2. Ambil data Budget & Cek Saldo
            $budget = Budget::lockForUpdate()->first();
            if (!$budget || $budget->saldo_belanja_gudang < $totalBelanja) {
                return back()->with('error', 'Saldo Gudang tidak mencukupi! Sisa: Rp ' . number_format($budget->saldo_belanja_gudang ?? 0, 0, ',', '.'));
            }

            // 3. Simpan Header Penerimaan
            $penerimaan = Penerimaan::create([
                'tanggal' => $request->tanggal,
                'supplier' => $request->supplier,
                'user_id' => Auth::id(),
                // Opsional: jika tabel penerimaan punya kolom total_belanja
                // 'total_belanja' => $totalBelanja, 
            ]);

            // 4. Simpan Detail, Update Stock, & Master Item
            foreach ($request->items as $item) {
                $penerimaan->details()->create([
                    'item_id'      => $item['item_id'],
                    'no_batch'     => $item['no_batch'],
                    'qty'          => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                    'expired_date' => $item['expired_date'],
                ]);

                Stock::create([
                    'item_id'      => $item['item_id'],
                    'no_batch'     => $item['no_batch'],
                    'qty_masuk'    => $item['qty'],
                    'qty_sisa'     => $item['qty'],
                    'expired_date' => $item['expired_date'],
                ]);

                $masterItem = Item::find($item['item_id']);
                $masterItem->increment('stok', $item['qty']);
            }

            // --- EKSEKUSI PEMOTONGAN SALDO ---
            // 5. Kurangi Saldo Belanja Gudang
            $budget->decrement('saldo_belanja_gudang', $totalBelanja);

            // 6. Catat ke Riwayat Transaksi Anggaran (agar muncul di dashboard admin)
            BudgetTransaction::create([
                'budget_id'     => $budget->id,
                'tipe'          => 'keluar', // Pastikan ini 'keluar' agar berwarna merah
                'kategori'      => 'penerimaan gudang', // Sesuaikan dengan label di View tadi
                'sumber_dana'   => 'Saldo Gudang',
                'nominal'       => -$totalBelanja, // Simpan angka positif, View akan memberi tanda minus (-) otomatis
                'keterangan'    => 'Belanja stok dari supplier', // Keterangan otomatis sesuai permintaan
                'status_enable' => 1
            ]);

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Penerimaan berhasil & Saldo Gudang terpotong!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
    // Menggunakan eager loading agar query efisien (mencegah N+1)
    $query = Penerimaan::with(['details.item', 'user'])->latest();

    // Filter Tanggal
    if ($request->filled('start_date')) {
        $query->whereDate('tanggal', '>=', $request->start_date);
    }
    if ($request->filled('end_date')) {
        $query->whereDate('tanggal', '<=', $request->end_date);
    }

    // Filter Nama Barang (melalui detail)
    if ($request->filled('item_id')) {
        $query->whereHas('details', function($q) use ($request) {
            $q->where('item_id', $request->item_id);
        });
    }

    // Menggunakan paginate agar loading halaman tidak berat jika data sudah ribuan
    $penerimaans = $query->paginate(10)->withQueryString(); 

    // Menggunakan scopeAktif yang kita buat tadi agar dropdown rapi
    $items = Item::aktif()->orderBy('nama_barang')->get();

    return view('gudang.penerimaan.index', compact('penerimaans', 'items'));
    }
}