
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #263238;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
            color: #17365d;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        th {
            background: #17365d;
            color: white;
        }

        .venta-header {
            background: #eaf0f7;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-venta {
            background: #f3f5f8;
            font-weight: bold;
        }

        .total-general {
            margin-top: 20px;
            padding: 12px;
            background: #17365d;
            color: white;
            text-align: right;
            font-size: 14px;
        }
    </style>
</head>
<body>

<h2>REPORTE DETALLADO DE VENTAS</h2>

<div class="subtitle">
    Periodo:
    {{ $desde ?: 'Inicio' }}
    al
    {{ $hasta ?: 'Actualidad' }}
    | Generado: {{ now()->format('d/m/Y H:i') }}
</div>

@forelse($sales as $sale)

    <table>
        <thead>
            <tr>
                <th colspan="4" class="venta-header">
                    Venta #{{ $sale->id }}
                    | Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}
                    | Cliente: {{ $sale->contact->name ?? '-' }}
                    | Estado: {{ strtoupper($sale->status) }}
                </th>
            </tr>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($sale->details as $detail)
                <tr>
                    <td>{{ $detail->product->name ?? 'Producto eliminado' }}</td>
                    <td class="text-center">{{ $detail->cantidad }}</td>
                    <td class="text-right">
                        S/ {{ number_format($detail->precio_unitario, 2) }}
                    </td>
                    <td class="text-right">
                        S/ {{ number_format($detail->subtotal, 2) }}
                    </td>
                </tr>
            @endforeach

            <tr class="total-venta">
                <td colspan="3" class="text-right">TOTAL VENTA</td>
                <td class="text-right">
                    S/ {{ number_format($sale->total, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

@empty
    <p class="text-center">
        No se encontraron ventas con los filtros seleccionados.
    </p>
@endforelse

<div class="total-general">
    TOTAL GENERAL:
    S/ {{ number_format($totalGeneral, 2) }}
</div>

</body>
</html>
