<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    protected $fillable = ['request_id', 'item_id', 'qty_diminta'];
    public function item() { return $this->belongsTo(Item::class); }
}
