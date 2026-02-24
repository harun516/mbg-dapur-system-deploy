<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanDetail extends Model
{
            protected $fillable = [
            'penerimaan_id',
            'item_id',
            'harga_satuan',
            'no_batch',
            'qty',
            'expired_date'
        ];

     public static function booted()
        {
            static::creating(function ($detail) {
                // 1. Cari record terakhir untuk mengambil nomor urut
                $last = self::latest('id')->first();

                if (!$last) {
                    $number = 1;
                } else {
                    // Mengambil 3 angka terakhir dari BATCH-A001 (yaitu 001)
                    $lastNumber = (int) substr($last->no_batch, -3);
                    $number = $lastNumber + 1;
                }

                // 2. Format: BATCH-A + angka dengan padding 3 digit (001, 002, dst)
                $detail->no_batch = 'BATCH-A' . str_pad($number, 3, '0', STR_PAD_LEFT);
            });

            static::created(function ($detail) {
                \App\Models\ItemBatch::create([
                    'item_id' => $detail->item_id,
                    'no_batch' => $detail->no_batch, // Sudah terisi otomatis dari proses creating di atas
                    'qty_masuk' => $detail->qty,
                    'qty_sisa' => $detail->qty,
                    'tanggal_masuk' => $detail->penerimaan->tanggal,
                    'expired_date' => $detail->expired_date,
                    'status_enable' => true
                ]);
            });
        }
        

        public function penerimaan()
        {
            return $this->belongsTo(Penerimaan::class);
        }

        public function item()
        {
            return $this->belongsTo(Item::class);
        }
}
