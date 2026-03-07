<?php

namespace App\Models\Anggaran;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    // Nama tabel (opsional jika nama file sudah sesuai, tapi bagus untuk dokumentasi)
    protected $table = 'budgets';

    protected $fillable = [
        'nama_proyek',
        'sumber_dana',
        'modal_awal',
        'saldo_saat_ini',
        'saldo_belanja_gudang', // Kolom baru untuk jatah belanja gudang
        'status_enable'         // Kolom status aktif/tidaknya budget ini
    ];

    /**
     * Casting tipe data agar angka desimal tetap akurat 
     * dan status_enable terbaca sebagai true/false
     */
    protected $casts = [
        'modal_awal' => 'decimal:2',
        'saldo_saat_ini' => 'decimal:2',
        'saldo_belanja_gudang' => 'decimal:2',
        'status_enable' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(BudgetTransaction::class);
    }
}