<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRequirement extends Model
{
    protected $fillable = ['menu_id', 'item_id', 'qty_per_porsi'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
