<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket</title>

    <style>
        @page {
            size: 80mm auto;
            margin: 2mm;
        }

        body {
            width: 76mm;
            margin: auto;
            font-family: "Courier New", monospace;
            font-size: 12px;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        .logo {
            width: 55px;
            display: block;
            margin: auto;
        }
    </style>
</head>

<!-- <body onload="window.print(); setTimeout(()=>window.close(),500);"> -->
<body>

<script>
    window.onload = function () {

        window.print();

        window.onafterprint = function () {
            window.location.href = "{{ route('pos') }}";
        };

    };
</script>

    <div class="center">

        <img src="{{ asset('assets/img/camelot.jpeg') }}" class="logo w-full" style="width: 200px; height: auto;">

        <div class="bold">HOTEL CAMELOT</div>

        <div>www.hotel.grupotyg.pe</div>

    </div>

    <div class="line"></div>

    <table>

        <tr>
            <td>Ticket</td>
            <td class="right">#{{ str_pad($sale->id,6,'0',STR_PAD_LEFT) }}</td>
        </tr>

        <tr>
            <td>Fecha</td>
            <td class="right">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
        </tr>

        <tr>
            <td>Cajero</td>
            <td class="right">{{ auth()->user()->name }}</td>
        </tr>

    </table>

    <div class="line"></div>

    <div class="bold center">

        COMPROBANTE

    </div>

    <div class="line"></div>

    <div class="bold">
        Cliente:
    </div>

    <div>{{ $sale->contact->name }}</div>

    @if($sale->contact->numero_doc)
    <div>{{ $sale->contact->tipo_doc }}: {{ $sale->contact->numero_doc }}</div>
    @endif

    @if($sale->transaction_id)
    <div>Habitación: {{ optional($sale->transaction)->room->numero }}</div>
    @endif

    <div class="line"></div>

    <table>

        @foreach($sale->details as $item)

        <tr>
            <td colspan="2">{{ $item->product->name }}</td>
        </tr>

        <tr>
            <td>{{ $item->cantidad }} x {{ number_format($item->precio_unitario,2) }}</td>
            <td class="right">S/ {{ number_format($item->subtotal,2) }}</td>
        </tr>

        @endforeach

    </table>

    <div class="line"></div>

    <table>

        <tr>
            <td>Subtotal</td>
            <td class="right">S/ {{ number_format($sale->total,2) }}</td>
        </tr>

        <tr>
            <td>IGV</td>
            <td class="right">S/ 0.00</td>
        </tr>

        <tr class="bold">
            <td>TOTAL</td>
            <td class="right">S/ {{ number_format($sale->total,2) }}</td>
        </tr>

    </table>

    <div class="line"></div>

    <table>

        <tr>
            <td>Pago</td>
            <td class="right">{{ optional($sale->payMethod)->name }}</td>
        </tr>

    </table>

    <div class="line"></div>

    <div class="center">

        ¡Gracias por su preferencia!

    </div>

    <div class="center">

        Hotel Grupo TyG

    </div>

</body>

</html>