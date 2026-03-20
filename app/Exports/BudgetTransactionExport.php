<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BudgetTransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Waktu',
            'Kategori',
            'Sumber Dana',
            'Tipe',
            'Nominal',
            'Keterangan',
            'Status'
        ];
    }

    public function map($trx): array
    {
        // Logika kategori yang sama dengan di Blade
        $kategori = $trx->kategori;
        if (str_contains(strtolower($trx->kategori), 'alokasi') || $trx->kategori == 'Kirim Saldo ke gudang') {
            $kategori = 'Kirim ke Gudang';
        } elseif ($trx->kategori == 'Belanja Bahan Baku' || $trx->kategori == 'penerimaan gudang') {
            $kategori = 'Penerimaan Barang';
        } elseif ($trx->kategori == 'Modal Utama' || $trx->tipe == 'masuk') {
            $kategori = 'Anggaran Masuk';
        }

        return [
            $trx->created_at->format('d M Y'),
            $trx->created_at->format('H:i') . ' WIB',
            $kategori,
            $trx->sumber_dana,
            ucfirst($trx->tipe),
            ($trx->tipe == 'keluar' ? '-' : '+') . ' ' . $trx->nominal,
            $trx->kategori == 'Belanja Bahan Baku' ? 'Belanja stok dari supplier' : ($trx->keterangan ?? '-'),
            $trx->status_enable ? 'Aktif' : 'Hidden',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Bold header
        ];
    }
}