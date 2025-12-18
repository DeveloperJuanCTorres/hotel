@extends('layouts.app')

@section('content')

<div class="wrapper sidebar_minimize">
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
                                    <select class="form-control" name="" id="tipo_pago" disabled>
                                        @foreach($pay_methods as $method)
                                        <option value="{{$method->id}}" {{ $transaction->pay_method_id == $method->id ? 'selected' : '' }}>{{$method->name}}</option>
                                        @endforeach
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
                            <button class="btn btn-info mx-2 btn-print" data-type="ticket">
                                <i class="fa fa-print px-2"></i>Imprimir Ticket
                            </button>
                            <button class="btn btn-info mx-2 text-white btn-print" data-type="boleta">
                                <i class="fa fa-print px-2"></i>Imprimir Boleta
                            </button>
                            <button class="btn btn-success mx-2 btn-print" data-type="factura">
                                <i class="fa fa-print px-2"></i>Imprimir Factura
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="datosComprobante" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-ml">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Datos del comprobante electrónico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAgregar">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="tipo_doc">Tipo Documento</label>
                                        <select class="form-select form-control" name="fact_tipo_doc" id="fact_tipo_doc" value="{{$transaction->contact->tipo_doc}}">
                                            <option value="DNI">DNI</option>
                                            <option value="RUC">RUC</option>
                                            <option value="PASAPORTE">PASAPORTE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="tipo_doc">Número Documento</label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="fact_numero_doc"
                                                name="fact_numero_doc"
                                                value="{{$transaction->contact->numero_doc}}"
                                            />
                                            <button class="btn btn-primary"type="button" id="btnBuscar">
                                                <span class="input-icon-addon">
                                                <i class="fa fa-search" id="iconBuscar"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="tipo_doc">Nombre</label>
                                        <input class="form-control" type="text" id="fact_name" name="fact_name" placeholder="Ingrese cliente" value="{{$transaction->contact->name}}">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label for="tipo_doc">Dirección</label>
                                        <input class="form-control" type="text" id="fact_address" name="fact_address" placeholder="Ingrese dirección" value="{{$transaction->contact->address}}">
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" type="hidden" id="fact_tipo_comprobante" name="fact_tipo_comprobante">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-generar">Generar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para vista previa del PDF -->
        <div class="modal fade" id="modalPdf" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- xl para pantalla grande -->
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vista previa del comprobante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <iframe id="iframePdf" src="" style="width:100%; height:80vh;" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="imprimirPdf()">Imprimir</button>
                </div>
                </div>
            </div>
        </div>

        @include('rooms.partials.create')

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<script>
    $(document).ready(function () {
        $(".btn-print").on("click", function () {
            let tipo = $(this).data("type");
            $('#fact_tipo_comprobante').val(tipo);
            $('#datosComprobante').modal('show');
        });

        $(".btn-generar").on("click", function () {
            const transaction_id = "{{ $transaction->id }}";
            let tipo = $('#fact_tipo_comprobante').val();
            let tipo_doc = $('#fact_tipo_doc').val();
            let numero_doc = $('#fact_numero_doc').val();
            let name = $('#fact_name').val();
            let address = $('#fact_address').val();
            let total_dias = cantNoches + parseInt(diasMasEl.innerText, 10);
            // Construimos fecha + hora de salida
            const fechaEntrada = "{{ $transaction->fecha_entrada }}";
            const habitacion = "{{ $transaction->room->numero }}";
            let fechaActual = hoy.toLocaleDateString("es-PE");
  

            $.ajax({
                url: "/comprobantes/generar",
                type: "POST",
                data: {
                    transaction_id: transaction_id,
                    tipo: tipo,
                    tipo_doc: tipo_doc,
                    numero_doc: numero_doc,
                    name: name,
                    address: address,
                    total: totalEl.value,
                    total_dias : total_dias,
                    fecha_entrada: fechaEntrada,
                    valor_unitario: precioUnitario,
                    habitacion: habitacion,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {

                    if(response.status){
                        $('#datosComprobante').modal('hide');
                        const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        });

                        Toast.fire({
                        icon: "success",
                        title: response.msg
                        });

                        
                        setTimeout(function(){                            
                            window.open(response.ruta_pdf, "_blank");
                            window.location.href = "/rooms"; 
                        }, 2000);

                        // $('#iframePdf').attr('src', response.ruta_pdf);
                        // $('#modalPdf').modal('show');
                        // alert(response.ruta_pdf);

                    } else {
                        alert(response.msg);
                    }
                },
                error: function () {
                    alert("Ocurrió un error al generar el comprobante");
                }
            });
        });
    });
</script>

@endsection