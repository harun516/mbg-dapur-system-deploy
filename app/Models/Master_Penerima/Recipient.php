<?php

namespace App\Models\Master_Penerima;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $fillable = [
        'status_enable',
        'nama_lembaga',
        'pimpinan',
        'alamat',
        'no_hp_pic',
        'nama_pic',
        'jumlah_porsi'
    ];
}