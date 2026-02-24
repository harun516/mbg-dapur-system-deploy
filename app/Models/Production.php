<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = ['menu_id', 'jumlah_porsi', 'tanggal_produksi', 'status'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}