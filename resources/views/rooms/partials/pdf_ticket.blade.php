<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: monospace;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .small {
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- EMPRESA -->
    <div class="center bold">
        {{ config('app.name') }} <br>
    </div>
    <div class="center small">
        SERVICIO DE HOSPEDAJE <br>
        --------------------------------
    </div>

    <div class="center bold">
        TICKET DE CONSUMO
    </div>

    <div class="center">
        {{ $serie }} - {{ str_pad($numero, 6, '0', STR_PAD_LEFT) }}
    </div>

    <div class="line"></div>

    <!-- DATOS -->
    <table>
        <tr>
            <td>Cliente:</td>
            <td class="right">{{ $cliente }}</td>
        </tr>
        <tr>
            <td>Habitación:</td>
            <td class="right">{{ $habitacion }}</td>
        </tr>
        <tr>
            <td>Fecha:</td>
            <td class="right">{{ $fecha }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- DETALLE -->
    <table>
        <tr class="bold">
            <td>CANT</td>
            <td>DESCRIPCIÓN</td>
            <td class="right">IMP.</td>
        </tr>

        <tr>
            <td>{{ $dias }}</td>
            <td>
                ALOJAMIENTO <br>
                Hab. {{ $habitacion }}
            </td>
            <td class="right">
                S/ {{ number_format($total, 2) }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- TOTALES -->
    <table>
        <tr class="bold">
            <td>TOTAL</td>
            <td class="right">S/ {{ number_format($total, 2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <!-- PIE -->
    <div class="center small">
        FORMA DE PAGO: CONTADO <br><br>
        ** Documento interno ** <br>
        Este Ticket es válido para canjear por un Comprobante Electrónico
    </div>

    <br>

    <div class="center">
        ¡Gracias por su preferencia!
    </div>

</body>
</html>
