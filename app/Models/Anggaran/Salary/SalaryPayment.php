<?php

namespace App\Models\Anggaran\Salary;


use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Anggaran\Budget;

class SalaryPayment extends Model
{
    protected $fillable = [
        'budget_id', 'user_id', 'periode_bulan', 
        'tanggal_bayar', 'total_diterima', 'status_enable', 'keterangan'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function budget() {
        return $this->belongsTo(Budget::class);
 }
}   