<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesExport implements FromCollection, WithHeadings
{
    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function collection()
    {
        return $this->purchases->map(function ($purchase) {
            return [
                'Fecha'       => Carbon::parse($purchase->date)->format('d/m/Y'),
                'Referencia'  => $purchase->referencia,
                'Método'      => optional($purchase->payMethod)->name,
                'Proveedor'   => optional($purchase->contact)->name,
                'Total'       => $purchase->total,
                'Estado'      => strtoupper($purchase->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Referencia',
            'Método de Pago',
            'Proveedor',
            'Total',
            'Estado'
        ];
    }
}