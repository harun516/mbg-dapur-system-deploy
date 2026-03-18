<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_rencana',
        'menu_id',
        'total_porsi_target',
        'status',
        'catatan_admin',
        'status_enable',
    ];

    // Relasi balik ke Menu (Master Resep)
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Scope untuk ambil yang aktif saja
    public function scopeAktif($query)
    {
        return $query->where('status_enable', 1);
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'plan_id');
    }
}
