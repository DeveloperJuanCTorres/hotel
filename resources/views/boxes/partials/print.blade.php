<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h2, h3 { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body onload="window.print()">
    <h2>Cierre de Caja</h2>
    <p><strong>Fecha cierre:</strong> {{ $closure->fecha_cierre }}</p>

    <h3>Ingresos</h3>
    <table>
        <thead>
            <tr><th>Método</th><th>Monto</th></tr>
        </thead>
        <tbody>
        @foreach($detalle as $d)
            @if($d['ingresos'] > 0)
                <tr>
                    <td>{{ $d['metodo'] }}</td>
                    <td>{{ number_format($d['ingresos'], 2) }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <h3>Egresos</h3>
    <table>
        <thead>
            <tr><th>Método</th><th>Monto</th></tr>
        </thead>
        <tbody>
        @foreach($detalle as $d)
            @if($d['egresos'] > 0)
                <tr>
                    <td>{{ $d['metodo'] }}</td>
                    <td>{{ number_format($d['egresos'], 2) }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <h3>Totales</h3>
    <table>
        <tr><th>Monto Inicial</th><td>{{ number_format($closure->monto_inicial, 2) }}</td></tr>
        <tr><th>Total Ingresos</th><td>{{ number_format($closure->total_ingresos, 2) }}</td></tr>
        <tr><th>Total Egresos</th><td>{{ number_format($closure->total_egresos, 2) }}</td></tr>
        <tr><th>Balance Final</th><td>{{ number_format($closure->balance_final, 2) }}</td></tr>
    </table>
</body>
</html>