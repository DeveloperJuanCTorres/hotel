@extends('layouts.app')

@section('content')

<div class="wrapper">
    @include('utils.menu')

    <div class="main-panel">
        <div class="main-header">
            @include('utils.topbar')
            
            @include('utils.navbar')
        </div>

        <div class="container">
            <div class="page-inner">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Datos de la habitación</h4>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between pb-4">
                                    <span class="fw-bold">Habitación</span>
                                    <span class="text-primary">{{ $transaction->room->numero }}</span>
                                </div> 
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Tipo</span>
                                    <span class="text-primary">{{ $transaction->room->type->name }}</span>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Datos del huesped</h4>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between pb-4">
                                    <span class="fw-bold">Nombre</span>
                                    <span class="text-primary">{{ $transaction->contact->name }}</span>
                                </div> 
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Documento</span>
                                    <span class="text-primary">{{ $transaction->contact->numero_doc }}</span>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Fechas</h4>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between pb-4">
                                    <span class="fw-bold">Entrada</span>
                                    <span class="text-primary">{{ $transaction->fecha_entrada }}</span>
                                </div> 
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Salida</span>
                                    <span class="text-primary">{{ $transaction->fecha_salida }} - {{ $transaction->hora_salida }}</span>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row px-3">
                    <div class="card px-0">
                        <div class="card-header">
                            <h4 class="card-title">Costo del alojamiento</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                            <th class="bg-primary text-white" scope="col" >#</th>
                                            <th class="bg-primary text-white" scope="col" >Costo por tarifa</th>
                                            <th class="bg-primary text-white" scope="col" >Cant. noches</th>
                                            <th class="bg-primary text-white" scope="col" >Días de más</th>
                                            <th class="bg-primary text-white" scope="col" >Cargo por salir tarde</th>
                                            <th class="bg-primary text-white" scope="col" style="width: 250px;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>S/. {{$transaction->precio_unitario}}</td>
                                                <td>{{$transaction->cant_noches}}</td>
                                                <td>
                                                    <span id="dias_mas"></span>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number" id="cargo_tarde">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" readonly id="subtotal">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>                                
                        </div>
                        <!-- <div class="card-header">
                            <h4 class="card-title">Servicio al cuarto</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead >
                                            <tr>
                                            <th class="bg-light" scope="col" >#</th>
                                            <th class="bg-light" scope="col" >Descripción</th>
                                            <th class="bg-light" scope="col" >Precio unitario</th>
                                            <th class="bg-light" scope="col" >Cantidad</th>
                                            <th class="bg-light" scope="col" ></th>
                                            <th class="bg-light" scope="col" style="width: 250px;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>S/. {{$transaction->precio_unitario}}</td>
                                                <td>{{$transaction->cant_noches}}</td>
                                                <td>
                                                    <span id="dias_mas"></span>
                                                </td>
                                                <td>
                                                    
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" readonly id="subtotal">
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-end">TOTAL:</th>
                                                <th><span id="total_general"> S/. 350.00</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>                                
                        </div> -->
                        
                        <div class="row">
                            <div class="col-lg-9 col-md-8 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <span class="h5 mb-0 px-4 fw-bold">Estado:</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <span class="badge bg-primary w-100" style="font-size: 20px; display:inline-block; min-width:auto;">
                                        {{ $transaction->estado_pago }}
                                    </span>
                                </div>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9 col-md-8 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <span class="h5 mb-0 px-4 fw-bold">Total a pagar S/.</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <input class="form-control" readonly type="text" id="total">
                                </div>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9 col-md-8 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <span class="h5 mb-0 px-4 fw-bold">Tipo de pago</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <select class="form-control" name="" id="tipo_pago">
                                        <option value="0">--Seleccionar--</option>
                                        <option value="EFECTIVO">Efectivo</option>
                                        <option value="TARJETA">Tarjeta</option>
                                        <option value="DEPOSITO">Deposito</option>
                                    </select>
                                </div>                                
                            </div>
                        </div>
                        <div class="row" id="row_operacion" style="display: none;">
                            <div class="col-lg-9 col-md-8 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <span class="h5 mb-0 px-4 fw-bold">Nº Operación</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-12">
                                <div class="d-flex justify-content-end align-items-center px-4 py-2">
                                    <input class="form-control" type="text" placeholder="Ingrese Nº operación">
                                </div>                                
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center px-4 py-4">
                            <button class="btn btn-info mx-2"><i class="fa fa-print px-2"></i>Imprimir Ticket</button>
                            <button class="btn btn-warning mx-2 text-white"><i class="fa fa-print px-2"></i>Imprimir Boleta</button>
                            <button class="btn btn-success mx-2"><i class="fa fa-print px-2"></i>Imprimir Factura</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('rooms.partials.create')

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script>
    document.getElementById('tipo_pago').addEventListener('change', function() {
        let valor = this.value;
        let rowOperacion = document.getElementById('row_operacion');

        if (valor === 'TARJETA' || valor === 'DEPOSITO') {
            rowOperacion.style.display = 'flex'; // mostrar
        } else {
            rowOperacion.style.display = 'none'; // ocultar
        }
    });
</script>

<script>
    const precioUnitario = parseFloat("{{ $transaction->precio_unitario }}");
    const cantNoches = parseInt("{{ $transaction->cant_noches }}");

    const diasMasEl = document.getElementById("dias_mas");
    const cargoTardeEl = document.getElementById("cargo_tarde");
    const subtotalEl = document.getElementById("subtotal");
    const totalEl = document.getElementById("total");

    // Construimos fecha + hora de salida
    const fechaSalidaStr = "{{ $transaction->fecha_salida }}"; // "2025-08-26"
    const horaSalidaStr = "{{ $transaction->hora_salida }}";   // "14:30:00"
    const fechaSalida = new Date(fechaSalidaStr + "T" + horaSalidaStr);

    const hoy = new Date(); // fecha/hora actual

    let diasMas = 0;
    if (hoy > fechaSalida) {
        const diffTime = hoy - fechaSalida; // milisegundos
        diasMas = Math.floor(diffTime / (1000 * 60 * 60 * 24)); // días completos
    }
    diasMasEl.innerText = diasMas;

    // Función para actualizar subtotal
    function actualizarSubtotal() {
        let cargoTarde = parseFloat(cargoTardeEl.value) || 0;
        if (cargoTarde < 0) cargoTarde = 0; // No permitir negativos
        const subtotal = (cantNoches * precioUnitario) + cargoTarde;
        subtotalEl.value = subtotal.toFixed(2);
        totalEl.value = subtotal.toFixed(2);
    }

    // Calcular cargo tarde inicial (si hay días de más)
    cargoTardeEl.value = (diasMas * precioUnitario).toFixed(2);
    actualizarSubtotal();

    // Escuchar cambios en cargo_tarde
    cargoTardeEl.addEventListener("input", () => {
        let val = parseFloat(cargoTardeEl.value) || 0;
        if (val < 0) {
            cargoTardeEl.value = 0; // corrige solo si es negativo
        }
        actualizarSubtotal();
    });
</script>

@endsection