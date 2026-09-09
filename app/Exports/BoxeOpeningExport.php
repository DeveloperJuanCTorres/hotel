<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BoxeOpeningExport implements FromCollection, WithHeadings
{
    protected $boxes;

    public function __construct($boxes)
    {
        $this->boxes = $boxes;
    }

    public function collection()
    {
        return $this->boxes->map(function ($box) {
            return [
                'Caja'           => optional($box->box)->name,
                'Usuario'        => optional($box->user)->name,
                'Monto Inicial'  => $box->monto_inicial,
                'Fecha Apertura' => $box->fecha_apertura
                    ? Carbon::parse($box->fecha_apertura)->format('d/m/Y H:i')
                    : '-',
                'Monto Final'    => $box->monto_final,
                'Fecha Cierre'   => $box->fecha_cierre
                    ? Carbon::parse($box->fecha_cierre)->format('d/m/Y H:i')
                    : '-',
                'Estado'         => strtoupper($box->status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Caja',
            'Usuario',
            'Monto Inicial',
            'Fecha Apertura',
            'Monto Final',
            'Fecha Cierre',
            'Estado'
        ];
    }
}