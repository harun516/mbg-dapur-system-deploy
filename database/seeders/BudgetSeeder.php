<?php

namespace Database\Seeders;

use App\Models\Anggaran\Budget;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run()
    {
        Budget::create([
            'nama_proyek' => 'Program Makan Bergizi Gratis 2026',
            'modal_awal' => 1000000000, // 1 Miliar
            'saldo_saat_ini' => 1000000000,
            'sumber_dana' => 'APBN',
        ]);
    }
}
