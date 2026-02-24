<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // Nama tabel yang kita buat di migration tadi
    protected $table = 'stocks';

    // Kolom yang boleh diisi secara massal via Stock::create()
    protected $fillable = [
        'item_id',
        'no_batch',
        'qty_masuk',
        'qty_sisa',
        'expired_date',
    ];

    /**
     * Relasi ke Master Item (Bahan Baku)
     * Ini supaya di stok.blade.php kamu bisa panggil $batch->item->nama_barang
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}