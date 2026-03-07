<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Anggaran\Budget;


class BudgetSeeder extends Seeder
{
    public function run()
{
    \App\Models\Anggaran\Budget::create([
        'nama_proyek' => 'Program Makan Bergizi Gratis 2026',
        'modal_awal' => 1000000000, // 1 Miliar
        'saldo_saat_ini' => 1000000000,
        'sumber_dana' => 'APBN',
    ]);
}
}
