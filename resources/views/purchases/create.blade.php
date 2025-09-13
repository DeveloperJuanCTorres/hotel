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
                <form id="formCompra" action="{{ route('purchases.store') }}" method="POST">
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
                                <h4 class="card-title mb-0">Detalle de compra</h4>
                                <button class="btn btn-warning" type="button" id="btnCreate" data-bs-toggle="modal" data-bs-target="#modalProductos">
                                    <i class="fas fa-plus px-2"></i>Agregar productos
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-head-bg-warning" id="tablaDetalle">
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
                            <div class="card-header bg-dark">
                                <h4 class="card-title mb-0 text-center text-white">IMPORTANTE</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="proveedor_id" class="form-label">Proveedor</label>
                                            <select class="form-select form-control" name="proveedor_id" id="proveedor_id" required>
                                                <option value="">--Selecciona--</option>
                                                @foreach($proveedores as $proveedor)
                                                <option value="{{$proveedor->id}}">{{$proveedor->name}}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                    </div>    
                                    
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="referencia" class="form-label">Referencia</label>
                                            <input class="form-control" type="text" id="referencia" name="referencia">
                                        </div> 
                                    </div>
                                    
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="date" class="form-label">Fecha</label>
                                            <input class="form-control" type="date" id="date" name="date" required>
                                        </div> 
                                    </div>                                                        
                                    <div class="col-lg-6 col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="pay_method_id" class="form-label">Forma pago</label>
                                            <select class="form-select form-control" name="pay_method_id" id="pay_method_id" required>
                                                <option value="">--Seleccionar--</option>
                                                @foreach($pay_methods as $method)
                                                <option value="{{$method->id}}">{{$method->name}}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                    </div> 
                                    <div class="col-lg-6 col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="status" class="form-label">Estado</label>
                                            <select class="form-select form-control" name="status" id="status" required>
                                                <option value="">--Seleccionar--</option>
                                                <option value="RECIBIDO">Recibido</option>
                                                <option value="PENDIENTE">Pendiente</option>
                                                <option value="SOLICITADO">Solicitado</option>
                                            </select>
                                        </div> 
                                    </div> 
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-dark w-100"><i class="fas fa-money-bill-alt px-2"></i>Guardar compra</button>
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

        @include('purchases.partials.productlist')

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

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
        const filaProducto = $(this).closest('tr');
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        const precio = parseFloat(filaProducto.find('input[type="number"]').val());
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
    $("#formCompra").on("submit", function(e) {
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

        return true;
    });
</script>

@endsection