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

                <form id="formCompra"
                      action="{{ route('purchases.update', $purchase->id) }}"
                      method="POST">
                    @csrf

                    <input type="hidden" name="boxe_opening_id"
                           value="{{ $purchase->boxe_opening_id }}">

                    <div class="row">

                        {{-- ================= DETALLE ================= --}}
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="card-title mb-0">Detalle de compra</h4>
                                    <button class="btn btn-warning"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalProductos">
                                        <i class="fas fa-plus px-2"></i>Agregar productos
                                    </button>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-head-bg-warning" id="tablaDetalle">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cant.</th>
                                                    <th>Precio</th>
                                                    <th>Importe</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($purchase->details as $detail)
                                                <tr id="producto-{{ $detail->product_id }}">
                                                    <td>{{ $detail->product->name }}</td>
                                                    <td>
                                                        <input type="number"
                                                               class="form-control form-control-sm cantidad"
                                                               value="{{ $detail->cantidad }}"
                                                               min="1">
                                                    </td>
                                                    <td>{{ $detail->precio_unitario }}</td>
                                                    <td class="importe">
                                                        {{ number_format($detail->subtotal, 2) }}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm btnEliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
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

                        {{-- ================= DATOS GENERALES ================= --}}
                        <div class="col-lg-5">
                            <div class="card">
                                <div class="card-header bg-dark text-white text-center">
                                    <h4 class="mb-0">IMPORTANTE</h4>
                                </div>

                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12">
                                            <label>Proveedor</label>
                                            <select name="proveedor_id" class="form-control" required>
                                                @foreach($proveedores as $p)
                                                    <option value="{{ $p->id }}"
                                                        {{ $purchase->contact_id == $p->id ? 'selected' : '' }}>
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-6 mt-2">
                                            <label>Referencia</label>
                                            <input type="text"
                                                   name="referencia"
                                                   class="form-control"
                                                   value="{{ $purchase->referencia }}">
                                        </div>

                                        <div class="col-6 mt-2">
                                            <label>Fecha</label>
                                            <input type="date"
                                                   name="date"
                                                   class="form-control"
                                                   value="{{ $purchase->date }}"
                                                   required>
                                        </div>

                                        <div class="col-6 mt-2">
                                            <label>Forma de pago</label>
                                            <select name="pay_method_id" class="form-control" required>
                                                @foreach($pay_methods as $m)
                                                    <option value="{{ $m->id }}"
                                                        {{ $purchase->pay_method_id == $m->id ? 'selected' : '' }}>
                                                        {{ $m->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-6 mt-2">
                                            <label>Estado</label>
                                            <select name="status" class="form-control" required>
                                                <option value="RECIBIDO" {{ $purchase->status=='RECIBIDO'?'selected':'' }}>Recibido</option>
                                                <option value="PENDIENTE" {{ $purchase->status=='PENDIENTE'?'selected':'' }}>Pendiente</option>
                                                <option value="SOLICITADO" {{ $purchase->status=='SOLICITADO'?'selected':'' }}>Solicitado</option>
                                            </select>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <button class="btn btn-dark w-100">
                                                <i class="fas fa-save px-2"></i>Actualizar compra
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="productosInputs"></div>
                </form>
            </div>
        </div>

        @include('purchases.partials.productlist')
        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

{{-- ================= SCRIPTS ================= --}}

<script>
    function recalcularTotales() {
        let total = 0;

        $("#tablaDetalle tbody tr").each(function() {
            let imp = parseFloat($(this).find(".importe").text());
            total += isNaN(imp) ? 0 : imp;
        });

        let subtotal = total / 1.18;
        let igv = total - subtotal;

        $("#subtotal").text(subtotal.toFixed(2));
        $("#igv").text(igv.toFixed(2));
        $("#total").text(total.toFixed(2));
    }

    recalcularTotales();

    $(document).on('click', '.btnAgregar', function () {
        const filaProducto = $(this).closest('tr');
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        const precio = parseFloat(filaProducto.find('input[type="number"]').val());
        let cantidad = 1;
        let importe = (cantidad * precio).toFixed(2);

        // Producto repetido → sumar cantidad
        if ($("#producto-" + id).length) {
            let row = $("#producto-" + id);
            let qty = parseInt(row.find(".cantidad").val()) + 1;
            row.find(".cantidad").val(qty);
            row.find(".importe").text((qty * precio).toFixed(2));
            recalcularTotales();
            return;
        }

        let fila = `
            <tr id="producto-${id}">
                <td>${nombre}</td>
                <td>
                    <input type="number"
                        class="form-control form-control-sm cantidad"
                        value="${cantidad}" min="1">
                </td>
                <td>${precio}</td>
                <td class="importe">${importe}</td>
                <td>
                    <button class="btn btn-danger btn-sm btnEliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $("#tablaDetalle tbody").append(fila);
        recalcularTotales();
    });

    $(document).on('input', '.cantidad', function () {
        let row = $(this).closest("tr");
        let precio = parseFloat(row.find("td:nth-child(3)").text());
        let qty = parseInt($(this).val());
        row.find(".importe").text((precio * qty).toFixed(2));
        recalcularTotales();
    });

    $(document).on('click', '.btnEliminar', function () {
        $(this).closest("tr").remove();
        recalcularTotales();
    });

    $("#formCompra").on("submit", function() {
        $("#productosInputs").empty();

        $("#tablaDetalle tbody tr").each(function(i, row) {
            let id = $(row).attr("id").replace("producto-", "");
            let cantidad = $(row).find(".cantidad").val();
            let precio = $(row).find("td:nth-child(3)").text();
            let importe = $(row).find(".importe").text();

            $("#productosInputs").append(`
                <input type="hidden" name="productos[${i}][id]" value="${id}">
                <input type="hidden" name="productos[${i}][cantidad]" value="${cantidad}">
                <input type="hidden" name="productos[${i}][precio]" value="${precio}">
                <input type="hidden" name="productos[${i}][importe]" value="${importe}">
            `);
        });
    });
</script>

@endsection
