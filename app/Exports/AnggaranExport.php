<?php

namespace App\Exports;

use App\Models\Anggaran\Budget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AnggaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Hanya ambil budget yang aktif
        return Budget::where('status_enable', true)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Proyek',
            'Sumber Dana',
            'Modal Awal',
            'Saldo Saat Ini',
            'Jatah Belanja Gudang',
            'Tanggal Dibuat',
        ];
    }

    public function map($budget): array
    {
        return [
            $budget->id,
            $budget->nama_proyek,
            $budget->sumber_dana,
            $budget->modal_awal,
            $budget->saldo_saat_ini,
            $budget->saldo_belanja_gudang,
            $budget->created_at->format('d/m/Y'),
        ];
    }
}