<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['nama_menu', 'porsi_standar', 'status_enable'];

    public function requirements() {
        return $this->hasMany(MenuRequirement::class);
    }
}