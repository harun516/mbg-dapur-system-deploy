<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ItemBatch extends Model
{
    protected $fillable = [
        'item_id', 'no_batch', 'qty_masuk', 'qty_sisa',
        'tanggal_masuk', 'expired_date', 'status_enable',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Indikator Expired
    public function getStatusExpiredAttribute()
    {
        $days = Carbon::parse($this->expired_date)->diffInDays(now(), false);
        if ($days > 0) {
            return 'expired';
        }
        if ($days >= -30) {
            return 'warning';
        } // Kurang dari 30 hari

        return 'safe';
    }
}
