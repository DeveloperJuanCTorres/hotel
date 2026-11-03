
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Consumo</title>

    <style>
        @page {
            margin: 3mm 4mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        .logo {
            text-align: center;
            margin-bottom: 7px;
        }

        .logo img {
            max-width: 135px;
            max-height: 85px;
            width: auto;
            height: auto;
        }

        .empresa {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .subtitulo {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            line-height: 1.5;
            margin-top: 8px;
            margin-bottom: 8px;
            padding: 0 5px;
            letter-spacing: 0;
        }

        .separador {
            border-top: 1px dashed #000;
            margin: 9px 0;
        }

        .separador-doble {
            border-top: 2px solid #000;
            margin: 9px 0;
        }

        .ticket-titulo {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3px;
        }

        .ticket-numero {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td, th {
            padding: 3px 0;
            vertical-align: top;
            overflow-wrap: break-word;
        }

        .datos td:first-child {
            width: 35%;
            font-weight: bold;
        }

        .datos td:last-child {
            width: 65%;
            text-align: right;
        }

        .detalle th {
            border-bottom: 1px solid #000;
            font-size: 9px;
            padding-bottom: 5px;
        }

        .detalle td {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .descripcion-secundaria {
            font-size: 9px;
            font-weight: normal;
        }

        .total-box {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 0;
            margin: 8px 0;
        }

        .total-box table td {
            font-size: 15px;
            font-weight: bold;
        }

        .pago {
            font-size: 10px;
            text-align: center;
            font-weight: bold;
            margin: 8px 0;
        }

        .aviso {
            font-size: 9px;
            text-align: center;
            line-height: 1.5;
            margin-top: 10px;
        }

        .agradecimiento {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-top: 13px;
        }

        .pie {
            text-align: center;
            font-size: 8px;
            margin-top: 8px;
        }
    </style>
</head>

<body>

    <!-- LOGO -->
    <div class="logo">
        <img src="{{ public_path('assets/img/camelot.png') }}" alt="Camelot">
    </div>

    <!-- EMPRESA -->

    <div class="subtitulo">

        <div style="font-size: 11px; font-weight: bold; margin-top: 3px;">
            PARDO MIGUEL N° 910 - JAÉN
        </div>
    </div>

    <div class="separador-doble"></div>

    <!-- TÍTULO -->
    <div class="ticket-titulo">
        TICKET DE CONSUMO
    </div>

    <div class="ticket-numero">
        N° {{ $serie }}-{{ str_pad($numero, 6, '0', STR_PAD_LEFT) }}
    </div>

    <div class="separador"></div>

    <!-- INFORMACIÓN DEL CLIENTE -->
    <table class="datos">
        <tr>
            <td>CLIENTE</td>
            <td>{{ $cliente }}</td>
        </tr>

        <tr>
            <td>HABITACIÓN</td>
            <td>{{ $habitacion }}</td>
        </tr>

        <tr>
            <td>FECHA</td>
            <td>{{ $fecha }}</td>
        </tr>

        <tr>
            <td>ESTADÍA</td>
            <td>
                {{ $dias }}
                {{ $dias == 1 ? 'día' : 'días' }}
            </td>
        </tr>
    </table>

    <div class="separador"></div>

    <!-- DETALLE DEL SERVICIO -->
    <table class="detalle">
        <thead>
            <tr>
                <th style="width: 15%; text-align:left;">
                    CANT.
                </th>

                <th style="width: 53%; text-align:left;">
                    DESCRIPCIÓN
                </th>

                <th style="width: 32%; text-align:right;">
                    IMPORTE
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $dias }}</td>

                <td>
                    <strong>ALOJAMIENTO</strong>

                    <div class="descripcion-secundaria">
                        Habitación {{ $habitacion }}
                    </div>
                </td>

                <td class="right">
                    S/ {{ number_format($total, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="total-box">
        <table>
            <tr>
                <td>TOTAL</td>
                <td class="right">
                    S/ {{ number_format($total, 2) }}
                </td>
            </tr>
        </table>
    </div>

    <!-- FORMA DE PAGO -->
    <div class="pago">
        FORMA DE PAGO: CONTADO
    </div>

    <div class="separador"></div>

    <!-- AVISO -->
    <div class="aviso">
        <strong>DOCUMENTO INTERNO</strong>
        <br><br>

        Este ticket es válido para solicitar
        su comprobante de pago electrónico.
        <br>

        Consérvelo para cualquier consulta.
    </div>

    <!-- AGRADECIMIENTO -->
    <div class="agradecimiento">
        ¡GRACIAS POR SU PREFERENCIA!
    </div>

    <div class="pie">
        Esperamos verlo nuevamente.
    </div>

</body>
</html>
