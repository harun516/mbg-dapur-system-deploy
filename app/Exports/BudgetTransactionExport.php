<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
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
            'Status',
        ];
    }

    public function map($trx): array
    {
        // Logika kategori yang sama dengan di Blade
        $kategori = $trx->kategori;
        if (str_contains(strtolower($trx->kategori), 'alokasi') || 'Kirim Saldo ke gudang' == $trx->kategori) {
            $kategori = 'Kirim ke Gudang';
        } elseif ('Belanja Bahan Baku' == $trx->kategori || 'penerimaan gudang' == $trx->kategori) {
            $kategori = 'Penerimaan Barang';
        } elseif ('Modal Utama' == $trx->kategori || 'masuk' == $trx->tipe) {
            $kategori = 'Anggaran Masuk';
        }

        return [
            $trx->created_at->format('d M Y'),
            $trx->created_at->format('H:i').' WIB',
            $kategori,
            $trx->sumber_dana,
            ucfirst($trx->tipe),
            ('keluar' == $trx->tipe ? '-' : '+').' '.$trx->nominal,
            'Belanja Bahan Baku' == $trx->kategori ? 'Belanja stok dari supplier' : ($trx->keterangan ?? '-'),
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
