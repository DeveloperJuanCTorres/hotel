<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpensesExport implements FromCollection, WithHeadings
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    public function collection()
    {
        return $this->expenses->map(function ($expense) {
            return [
                'Fecha'       => Carbon::parse($expense->date)->format('d/m/Y'),
                'Categoría'   => optional($expense->catexpense)->name,
                'Descripción' => $expense->description,
                'Monto'       => $expense->amount,
                'Referencia'  => $expense->referencia,
                'Método'      => optional($expense->paymethod)->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Categoría',
            'Descripción',
            'Monto',
            'Referencia',
            'Método de Pago',
        ];
    }
}