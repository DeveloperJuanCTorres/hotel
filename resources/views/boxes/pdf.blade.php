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
            padding: 5px;
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

    <h2>Reporte de Aperturas de Caja</h2>

    <table>

        <thead>

            <tr>

                <th>Caja</th>

                <th>Usuario</th>

                <th>Monto Inicial</th>

                <th>Fecha Apertura</th>

                <th>Monto Final</th>

                <th>Fecha Cierre</th>

                <th>Estado</th>

            </tr>

        </thead>

        <tbody>

            @foreach($boxes as $box)

            <tr>

                <td>{{ optional($box->box)->name }}</td>

                <td>{{ optional($box->user)->name }}</td>

                <td class="text-right">S/ {{ number_format($box->monto_inicial,2) }}</td>

                <td>{{ $box->fecha_apertura ? \Carbon\Carbon::parse($box->fecha_apertura)->format('d/m/Y H:i') : '-' }}</td>

                <td class="text-right">
                    {{ $box->monto_final ? 'S/ '.number_format($box->monto_final,2) : '-' }}
                </td>

                <td>{{ $box->fecha_cierre ? \Carbon\Carbon::parse($box->fecha_cierre)->format('d/m/Y H:i') : '-' }}</td>

                <td>{{ strtoupper($box->status) }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <p style="margin-top:15px;">
        <strong>Total Monto Inicial:</strong>
        S/ {{ number_format($boxes->sum('monto_inicial'),2) }}

        <br>

        <strong>Total Monto Final:</strong>
        S/ {{ number_format($boxes->sum('monto_final'),2) }}
    </p>

</body>

</html>