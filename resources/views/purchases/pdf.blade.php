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
            background: #f2f2f2;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .text-right {
            text-align: right;
        }
    </style>

</head>

<body>

    <h2>Reporte de Compras</h2>

    <table>

        <thead>

            <tr>

                <th>Fecha</th>

                <th>Referencia</th>

                <th>Proveedor</th>

                <th>Método</th>

                <th>Total</th>

                <th>Estado</th>

            </tr>

        </thead>

        <tbody>

            @foreach($purchases as $purchase)

            <tr>

                <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>

                <td>{{ $purchase->referencia }}</td>

                <td>{{ optional($purchase->contact)->name }}</td>

                <td>{{ optional($purchase->payMethod)->name }}</td>

                <td class="text-right">{{ number_format($purchase->total,2) }}</td>

                <td>{{ strtoupper($purchase->status) }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <p style="margin-top:15px;">
        <strong>Total Compras:</strong>
        S/ {{ number_format($purchases->sum('total'),2) }}
    </p>

</body>

</html>