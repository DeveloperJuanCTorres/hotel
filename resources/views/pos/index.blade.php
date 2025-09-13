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
                <form id="formVenta" action="{{ route('sales.store') }}" method="POST">
                    @csrf
                   
                    @if($caja_abierta)
                        <input type="hidden" id="boxe_opening_id" name="boxe_opening_id" value="{{$caja_abierta->id}}">
                    @else
                        <input type="hidden" id="boxe_opening_id" name="boxe_opening_id" value="0">
                    @endif
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Detalle de venta</h4>
                                <button class="btn btn-primary" type="button" id="btnCreate" data-bs-toggle="modal" data-bs-target="#modalProductos">
                                    <i class="fas fa-plus px-2"></i>Agregar productos
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-head-bg-primary" id="tablaDetalle">
                                        <thead>
                                            <tr>
                                                <th scope="col">Producto</th>
                                                <th scope="col">Cant.</th>
                                                <th scope="col">Precio</th>
                                                <th scope="col">Importe</th>
                                                <th scope="col">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                    
                                        </tbody>    
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                                <td colspan="2" id="subtotal">0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>IGV (18%)</strong></td>
                                                <td colspan="2" id="igv">0.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                <td colspan="2" id="total">0.00</td>
                                            </tr>
                                        </tfoot>                            
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card">
                            <div class="card-header bg-success">
                                <h4 class="card-title mb-0 text-center text-white">IMPORTANTE</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="categoria_id" class="form-label">Tipo de cliente</label>
                                            <select class="form-select form-control" name="tipo_cliente" id="tipo_cliente" required>
                                                <option value="0">--Selecciona--</option>
                                                <option value="1">Huesped de habitación</option>
                                                <option value="2">Cliente final</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-12" id="div_tipo_doc" style="display:none;">
                                        <div class="form-group">
                                            <label for="tipo_doc">Tipo Documento</label>
                                            <select class="form-select form-control" name="tipo_doc" id="tipo_doc">
                                                <option value="DNI">DNI</option>
                                                <option value="RUC">RUC</option>
                                                <option value="PASAPORTE">PASAPORTE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-12" id="div_numero_doc" style="display:none;">                    
                                        <div class="form-group">
                                            <label for="tipo_doc">Número Documento</label>  
                                            <div class="input-group">                                              
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="numero_doc"
                                                    name="numero_doc"
                                                />
                                                <button class="btn btn-primary"type="button" id="btnBuscar">
                                                    <span class="input-icon-addon">
                                                    <i class="fa fa-search" id="iconBuscar"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-12" id="div_cliente" style="display:none;">
                                        <div class="form-group">
                                            <label for="client">Cliente</label>
                                            <input class="form-control" type="text" id="client" name="client" placeholder="Ingrese cliente">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-12" id="div_direccion" style="display:none;">
                                        <div class="form-group">
                                            <label for="address">Dirección</label>
                                            <input class="form-control" type="text" id="address" name="address" placeholder="Ingrese dirección">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-12" id="div_transaction" style="display:none;">
                                        <div class="form-group">
                                            <label for="categoria_id" class="form-label">Cargar a una habitación</label>
                                            <select class="form-select form-control" name="transaction_id" id="transaction_id">
                                                <option value="">--Selecciona habitación--</option>
                                                @foreach($transactions as $transaction)
                                                <option value="{{$transaction->id}}">{{$transaction->room->numero . ' - ' . $transaction->contact->name}}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                    </div> 
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="categoria_id" class="form-label">Comprobante</label>
                                            <select class="form-select form-control" name="tipo_comprobante" id="tipo_comprobante">
                                                @foreach($invoices as $invoice)
                                                <option value="{{$invoice->id}}">{{$invoice->name}}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                    </div> 
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="categoria_id" class="form-label">Fecha</label>
                                            <input class="form-control" type="date" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" readonly>
                                        </div> 
                                    </div>                                                        
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="categoria_id" class="form-label">Forma pago</label>
                                            <select class="form-select form-control" name="tipo_comprobante" id="tipo_comprobante">
                                                @foreach($pay_methods as $method)
                                                <option value="{{$method->id}}">{{$method->name}}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                    </div> 
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-money-bill-alt px-2"></i>Guardar venta</button>
                                        </div> 
                                    </div>                             
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>            
                <!-- Aquí se insertarán los inputs ocultos de productos -->
                    <div id="productosInputs"></div>
                </form>
            </div>
        </div>

        @include('pos.partials.productlist')

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script>
    document.getElementById("tipo_cliente").addEventListener("change", function() {
        let valor = this.value;

        // Ocultar ambos primero
        document.getElementById("div_tipo_doc").style.display = "none";
        document.getElementById("div_numero_doc").style.display = "none";
        document.getElementById("div_cliente").style.display = "none";
        document.getElementById("div_direccion").style.display = "none";
        document.getElementById("div_transaction").style.display = "none";

        if (valor == "1") { 
            // Huesped
            document.getElementById("div_transaction").style.display = "block";
        } else if (valor == "2") { 
            // Cliente final
            document.getElementById("div_tipo_doc").style.display = "block";
            document.getElementById("div_numero_doc").style.display = "block";
            document.getElementById("div_cliente").style.display = "block";
            document.getElementById("div_direccion").style.display = "block";
        }
    });
</script>

<script>
    function recalcularTotales() {
        let total = 0;

        $("#tablaDetalle tbody tr").each(function() {
            let importe = parseFloat($(this).find(".importe").text());
            total += isNaN(importe) ? 0 : importe;
        });

        // Si el precio ya incluye IGV
        let subtotal = total / 1.18;
        let igv = total - subtotal;

        $("#subtotal").text(subtotal.toFixed(2));
        $("#igv").text(igv.toFixed(2));
        $("#total").text(total.toFixed(2));
    }

    // === Eventos ===

    // Agregar producto
    $(document).on('click', '.btnAgregar', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let precio = parseFloat($(this).data('precio')).toFixed(2);
        let cantidad = 1;
        let importe = (cantidad * precio).toFixed(2);

        // Validar repetidos
        if($("#producto-"+id).length){
            let row = $("#producto-"+id);
            let qty = parseInt(row.find(".cantidad").val()) + 1;
            row.find(".cantidad").val(qty);
            row.find(".importe").text((qty * precio).toFixed(2));
            recalcularTotales();
            return;
        }

        let fila = `
            <tr id="producto-${id}">
                <td>${nombre}</td>
                <td><input type="number" class="form-control form-control-sm cantidad" value="${cantidad}" min="1"></td>
                <td>${precio}</td>
                <td class="importe">${importe}</td>
                <td>
                    <button class="btn btn-danger btn-sm btnEliminar"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;

        $("#tablaDetalle tbody").append(fila);
        recalcularTotales();
    });

    // Cambiar cantidad
    $(document).on('input', '.cantidad', function () {
        let row = $(this).closest("tr");
        let precio = parseFloat(row.find("td:nth-child(3)").text());
        let qty = parseInt($(this).val());
        row.find(".importe").text((precio * qty).toFixed(2));
        recalcularTotales();
    });

    // Eliminar producto
    $(document).on('click', '.btnEliminar', function () {
        $(this).closest("tr").remove();
        recalcularTotales();
    });
</script>

<script>
    $(document).ready(function() {
        // Filtro por categoría
        $("#filtroCategoria").on("change", function() {
            let categoria = $(this).val();
            $("#tablaProductos tbody tr").each(function() {
                let rowCat = $(this).data("categoria").toString();
                if (categoria === "" || categoria === rowCat) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Filtro por nombre
        $("#filtroNombre").on("keyup", function() {
            let texto = $(this).val().toLowerCase();
            $("#tablaProductos tbody tr").each(function() {
                let nombre = $(this).find(".nombre").text().toLowerCase();
                if (nombre.includes(texto)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>

<script>
    $("#formVenta").on("submit", function(e) {
        // limpiar inputs anteriores
        $("#productosInputs").empty();

        $("#tablaDetalle tbody tr").each(function(index, row) {
            let id = $(row).attr("id").replace("producto-", "");
            let cantidad = $(row).find(".cantidad").val();
            let precio = $(row).find("td:nth-child(3)").text();
            let importe = $(row).find(".importe").text();

            $("#productosInputs").append(`
                <input type="hidden" name="productos[${index}][id]" value="${id}">
                <input type="hidden" name="productos[${index}][cantidad]" value="${cantidad}">
                <input type="hidden" name="productos[${index}][precio]" value="${precio}">
                <input type="hidden" name="productos[${index}][importe]" value="${importe}">
            `);
        });

        return true; // continuar con el submit
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btnBuscar').on('click', function() {
            let tipo = $('#tipo_doc').val();
            let numero = $('#numero_doc').val();
            let $btn = $(this);
            let $icon = $('#iconBuscar');

            // Mostrar loader (spinner)
            $icon.removeClass('fa-search').addClass('fa-spinner fa-spin');

            if(numero === ''){
                alert("Ingrese número de documento");
                return;
            }

            $.ajax({
                url: "{{ route('buscar.documento') }}", 
                type: "POST",
                data: {
                    tipo_doc: tipo,
                    numero_doc: numero,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    if(response.success){
                        if(tipo === 'DNI'){
                            $('#client').val(response.data.nombre_completo);
                            $('#address').val('');
                        }else if(tipo === 'RUC'){
                            $('#client').val(response.data.nombre_o_razon_social);
                            $('#address').val(response.data.direccion_completa);
                        }
                    }else{
                        alert("No se encontró información");
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                    alert("Error en la consulta");
                },
                complete: function(){
                    // Restaurar ícono original
                    $icon.removeClass('fa-spinner fa-spin').addClass('fa-search');
                }
            });
        });

        // Validar mientras escribe
        $('#numero_doc').on('input', function () {
            let tipo = $('#tipo_doc').val();

            if (tipo === 'DNI' || tipo === 'RUC') {
                // Solo números
                this.value = this.value.replace(/[^0-9]/g, '');
            } else if (tipo === 'PASAPORTE') {
                // Solo alfanumérico (letras y números)
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
            }
        });
        
        // Cambiar longitud máxima según tipo_doc
        $('#tipo_doc').on('change', function () {
            let tipo = $(this).val();

            if (tipo === 'DNI') {               
                $('#numero_doc').attr('maxlength', 8);
                $('#numero_doc').val($('#numero_doc').val().slice(0, 8)); // recortar si excede
                 $('#numero_doc').value.replace(/[^0-9]/g, ''); // elimina todo lo que no sea número
            } else if (tipo === 'RUC') {                              
                $('#numero_doc').attr('maxlength', 11);
                $('#numero_doc').val($('#numero_doc').val().slice(0, 11)); // recortar si excede
                $('#numero_doc').value.replace(/[^0-9]/g, ''); // elimina todo lo que no sea número
            }
            if (tipo === 'PASAPORTE') {
                $('#numero_doc').attr('maxlength', 20);
                $('#numero_doc').val($('#numero_doc').val().slice(0, 20)); // recortar si excede
            }
        });

        // Forzar la longitud inicial al abrir modal (por si cambia)
        $('#tipo_doc').trigger('change');

        // Forzar reglas al cargar
        $('#tipo_doc').trigger('change');
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: "{{ $errors->first() }}",
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

@endsection