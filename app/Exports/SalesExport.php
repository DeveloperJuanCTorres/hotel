<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class SalesExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithMapping,
    WithStrictNullComparison
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->sales as $sale) {
            foreach ($sale->details as $detail) {
                $rows->push([
                    'venta_id' => $sale->id,
                    'fecha' => $sale->created_at->format('d/m/Y H:i'),
                    'cliente' => $sale->contact->name ?? '-',
                    'documento' => $sale->contact->numero_doc ?? '',
                    'producto' => $detail->product->name ?? 'Producto eliminado',
                    'cantidad' => (float) $detail->cantidad,
                    'precio' => (float) $detail->precio_unitario,
                    'subtotal' => (float) $detail->subtotal,
                    'estado' => $sale->status,
                    'total_venta' => (float) $sale->total,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'N° Venta',
            'Fecha',
            'Cliente',
            'Documento',
            'Producto',
            'Cantidad',
            'Precio Unitario',
            'Subtotal',
            'Estado',
            'Total Venta'
        ];
    }

    public function map($row): array
    {
        return array_values($row);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 20,
            'C' => 35,
            'D' => 18,
            'E' => 40,
            'F' => 12,
            'G' => 18,
            'H' => 18,
            'I' => 15,
            'J' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '17365D'],
            ],
        ]);

        $sheet->getStyle('G:J')
            ->getNumberFormat()
            ->setFormatCode('#,##0.00');

        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

        $sheet->freezePane('A2');

        return [];
    }
}
