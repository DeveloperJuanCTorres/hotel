<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #F2F2F2;
        }

        .text-right {
            text-align: right;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h2>Reporte de Gastos</h2>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Categoría</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th>Referencia</th>
                <th>Método</th>
            </tr>
        </thead>

        <tbody>

            @foreach($expenses as $expense)

            <tr>

                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>

                <td>{{ optional($expense->catexpense)->name }}</td>

                <td>{{ $expense->description }}</td>

                <td class="text-right">S/ {{ number_format($expense->amount,2) }}</td>

                <td>{{ $expense->referencia }}</td>

                <td>{{ optional($expense->paymethod)->name }}</td>

            </tr>

            @endforeach

        </tbody>
    </table>

    <br>

    <table style="width:35%;margin-left:auto;">
        <tr>
            <td><strong>Total Gastos</strong></td>
            <td class="text-right">
                S/ {{ number_format($expenses->sum('amount'),2) }}
            </td>
        </tr>
    </table>

</body>

</html>