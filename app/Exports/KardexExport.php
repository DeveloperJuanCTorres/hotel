<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KardexExport implements FromCollection, WithHeadings
{
    protected $movimientos;

    public function __construct($movimientos)
    {
        $this->movimientos = $movimientos;
    }

    public function collection()
    {
        return $this->movimientos->map(function ($m) {
            return [
                'Fecha' => \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i'),
                'Tipo' => $m->tipo,
                'Referencia' => $m->referencia,
                'Cantidad' => $m->cantidad,
                'Precio Unitario' => $m->precio_unitario,
                'Stock' => $m->stock,
                'Costo Promedio' => $m->costo_promedio,
                'Costo Total' => $m->costo_total,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Tipo',
            'Referencia',
            'Cantidad',
            'Precio Unitario',
            'Stock',
            'Costo Promedio',
            'Costo Total',
        ];
    }
}