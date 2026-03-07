<?php

namespace App\Models\Anggaran;

use Illuminate\Database\Eloquent\Model;

class BudgetTransaction extends Model
{
    protected $fillable = [
        'budget_id', 
        'tipe', 
        'kategori', 
        'sumber_dana', 
        'nominal', 
        'keterangan', 
        'status_enable' 
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}