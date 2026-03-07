<?php

namespace App\Models\Anggaran\Salary;

use Illuminate\Database\Eloquent\Model;

class SalaryConfig extends Model
{
    protected $table = 'salary_configs';

    protected $fillable = [
        'role_name', 
        'gaji_pokok', 
        'tunjangan', 
        'status_enable',
        'keterangan',
        'total_diterima'
    ];

}