<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{

    protected $fillable = ['plan_id', 'menu_id', 'jumlah_porsi', 'tanggal_produksi', 'status', 'user_id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Tambahkan relasi balik ke Plan agar sinkronisasi lancar
    public function productionPlan()
    {
        return $this->belongsTo(ProductionPlan::class, 'plan_id');
    }
}