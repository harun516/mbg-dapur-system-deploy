<?php

namespace App\Models\Anggaran;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetAllocation extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'budget_allocations';

    // Kolom yang boleh diisi manual (Mass Assignment)
    protected $fillable = [
        'nama_alokasi',
        'nominal',
        'keterangan',
    ];
}
