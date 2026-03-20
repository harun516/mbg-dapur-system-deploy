<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PenerimaanExport implements FromView, ShouldAutoSize
{
    protected $penerimaans;
    protected $grandTotal;

    public function __construct($penerimaans, $grandTotal)
    {
        $this->penerimaans = $penerimaans;
        $this->grandTotal = $grandTotal;
    }

    public function view(): View
    {
        return view('gudang.exports.penerimaan_excel', [
            'penerimaans' => $this->penerimaans,
            'grandTotal' => $this->grandTotal
        ]);
    }
}