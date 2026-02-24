<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'nama_barang',
        'satuan',
        'status_enable',
    ];

    // Otomatis hanya mengambil yang aktif (status_enable = 1)
    public function scopeAktif($query)
    {
        return $query->where('status_enable', 1);
    }
}