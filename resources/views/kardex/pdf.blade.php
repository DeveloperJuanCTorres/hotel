<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background: #eee;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>

</head>

<body>

    <h2>Reporte Kardex</h2>

    <table>

        <thead>

            <tr>

                <th>Fecha</th>

                <th>Tipo</th>

                <th>Referencia</th>

                <th>Cantidad</th>

                <th>P.Unit</th>

                <th>Stock</th>

                <th>C.Prom.</th>

                <th>C.Total</th>

            </tr>

        </thead>

        <tbody>

            @foreach($movimientos as $m)

            <tr>

                <td>{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i') }}</td>

                <td>{{ $m->tipo }}</td>

                <td>{{ $m->referencia }}</td>

                <td align="right">{{ number_format($m->cantidad,2) }}</td>

                <td align="right">{{ number_format($m->precio_unitario,2) }}</td>

                <td align="right">{{ number_format($m->stock,2) }}</td>

                <td align="right">{{ number_format($m->costo_promedio,2) }}</td>

                <td align="right">{{ number_format($m->costo_total,2) }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <p style="margin-top:15px;">
        <strong>Stock Actual:</strong> {{ $stock_final }}
    </p>

</body>

</html>