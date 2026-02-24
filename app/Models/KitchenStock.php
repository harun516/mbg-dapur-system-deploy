<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenStock extends Model
{
    protected $fillable = ['item_id', 'no_batch', 'qty_sisa', 'status_enable'];
    public function item()
    { 
    return $this->belongsTo(Item::class); 
    }
}
