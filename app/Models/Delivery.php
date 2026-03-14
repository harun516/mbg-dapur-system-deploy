<?php

namespace App\Models;

use App\Models\Master_Penerima\Recipient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_plan_id',
        'recipient_id',
        'courier_id',
        'status',
        'foto_bukti',
        'status_enable',
        'waktu_antar',
        'waktu_sampai',
    ];

    // Relasi ke Rencana Produksi (untuk ambil data Menu & Porsi)
    public function productionPlan()
    {
        return $this->belongsTo(ProductionPlan::class, 'production_plan_id');
    }

    // Relasi ke Penerima (Sekolah)
    public function recipient()
    {
        return $this->belongsTo(Recipient::class, 'recipient_id');
    }

    // Relasi ke User (Kurir)
    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }
}
