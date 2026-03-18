<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\KitchenStock; // Alias agar tidak bentrok dengan class Request Laravel
use App\Models\Request as StockRequest;
use App\Models\RequestDetail;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    // 1. Tampilan daftar permintaan (Dilihat oleh Gudang untuk Approve)
    public function index()
    {
        $requests = StockRequest::where('status_enable', 1)->latest()->paginate(10);

        // Sesuaikan dengan folder resources/views/gudang/request/index.blade.php
        return view('gudang.request.index', compact('requests'));
    }

    // 2. Form buat permintaan baru (Sisi Dapur)
    public function create()
    {
        $items = Item::where('status_enable', 1)->get();

        // SESUAIKAN: Folder kamu adalah dapur/stok/create
        return view('dapur.stok.create', compact('items'));
    }

    // 3. Simpan permintaan (Sisi Dapur)
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:0.0001',
        ]);

        DB::beginTransaction();

        try {
            $stockRequest = StockRequest::create([
                'no_request' => 'REQ-'.date('Ymd-His'),
                'tanggal_request' => now(),
                'status' => 'Pending',
                'status_enable' => 1,
            ]);

            foreach ($request->items as $item) {
                RequestDetail::create([
                    'request_id' => $stockRequest->id,
                    'item_id' => $item['item_id'],
                    'qty_diminta' => $item['qty'],
                ]);
            }

            DB::commit();

            // Redirect ke halaman stok dapur setelah berhasil kirim request
            return redirect()->route('dapur.stok.index')->with('success', 'Permintaan barang berhasil dikirim ke Gudang.');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Gagal membuat permintaan: '.$e->getMessage());
        }
    }

    // 4. Proses Approval (Sisi Gudang)
    public function approve($id)
    {
        $stockRequest = StockRequest::with('details.item')->findOrFail($id);

        if ('Pending' !== $stockRequest->status) {
            DB::rollback();

            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        DB::beginTransaction();

        try {
            foreach ($stockRequest->details as $detail) {
                $qtyNeeded = $detail->qty_diminta;

                $batches = Stock::where('item_id', $detail->item_id)
                    ->where('qty_sisa', '>', 0)
                    ->orderBy('expired_date', 'asc')
                    ->get()
                ;

                $available = $batches->sum('qty_sisa');
                if ($available < $qtyNeeded) {
                    throw new \Exception("Stok '".$detail->item->nama_barang."' tidak cukup. Tersedia: {$available}, Dibutuhkan: {$qtyNeeded}");
                }

                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) {
                        break;
                    }

                    $take = min($batch->qty_sisa, $qtyNeeded);

                    // Kurangi di gudang
                    $batch->decrement('qty_sisa', $take);

                    // Tambah ke dapur (cek dulu ada atau tidak)
                    $kitchenStock = KitchenStock::where('item_id', $detail->item_id)
                        ->where('no_batch', $batch->no_batch)
                        ->first()
                    ;

                    if ($kitchenStock) {
                        // Jika ada, tambah qty_sisa
                        $kitchenStock->increment('qty_sisa', $take);
                    } else {
                        // Jika belum ada, buat baru
                        KitchenStock::create([
                            'item_id' => $detail->item_id,
                            'no_batch' => $batch->no_batch,
                            'qty_sisa' => $take,
                            'status_enable' => 1,
                        ]);
                    }

                    $qtyNeeded -= $take;
                }
            }

            $stockRequest->update(['status' => 'Approved']);

            DB::commit();

            return back()->with('success', 'Permintaan berhasil disetujui. Stok telah dipindahkan ke dapur.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal approve request #'.$id.': '.$e->getMessage());

            return back()->with('error', 'Gagal approve: '.$e->getMessage());
        }
    }

    // 5. Dashboard Stok Dapur (Sisi Dapur)
    public function kitchenStock()
    {
        $kitchenStocks = KitchenStock::with('item')
            ->where('status_enable', 1)
            ->where('qty_sisa', '>', 0)
            ->get()
            ->groupBy('item_id')
        ;

        // SESUAIKAN: Folder kamu adalah dapur/stok/index
        return view('dapur.stok.index', compact('kitchenStocks'));
    }

    public function getDetail($id)
    {
        // Mengambil detail beserta nama barang
        $details = RequestDetail::with('item')->where('request_id', $id)->get();

        return response()->json($details);
    }
}
