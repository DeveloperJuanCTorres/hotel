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

            {{-- Loader --}}
            <div id="loader-bar" style="display:none; height:4px; background:#007bff; position:fixed; top:0; left:0; width:0%; z-index:9999;"></div>

            <div class="page-header">
              <h3 class="fw-bold mb-3">Ventas</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Ventas</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="/">Dashboard</a></li>
              </ul>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-3">
                                <label class="form-label">Desde</label>
                                <input type="date" id="from_date" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Hasta</label>
                                <input type="date" id="to_date" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Cliente</label>
                                <select id="contact_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach($contacts as $contact)
                                        <option value="{{ $contact->id }}">
                                            {{ $contact->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Estado de Pago</label>
                                <select id="payment_status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="paid">Pagado</option>
                                    <option value="due">Debido</option>
                                </select>
                            </div>

                        </div>

                        <div class="mt-3 text-end">
                            <button id="btnBuscar" class="btn btn-primary">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="sales_table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Pagado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="sales_tbody">
                            @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $sale->contact->name ?? '-' }}</td>
                                    <td class="text-end">S/. {{ number_format($sale->total, 2) }}</td>
                                    <td class="text-center">
                                        @if($sale->status === 'pagado')
                                            <span class="badge bg-success">Pagado</span>
                                        @else
                                            <span class="badge bg-warning">Debido</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-view"
                                            data-id="{{ $sale->id }}">
                                            <i class="bi bi-eye"></i>Ver
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

              </div>
            </div>

          </div>
        </div>


        <div class="modal fade" id="viewSale" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Detalle de Venta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Cliente:</strong> <span id="v_cliente"></span></p>
                        <p><strong>Comprobante:</strong> <span id="v_transaccion"></span></p>
                        <p><strong>Estado:</strong> <span id="v_estado"></span></p>

                        <table class="table table-bordered mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">P. Unit</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detail_tbody"></tbody>
                        </table>

                        <div class="text-end fw-bold">
                            TOTAL: S/. <span id="v_total"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {

        $('.select2').select2();

        loadSales();

        $('#btnBuscar').click(function () {
            loadSales();
        });

        function loadSales() {
            $.ajax({
                url: "{{ route('sales.search') }}",
                type: "GET",
                data: {
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val(),
                    contact_id: $('#contact_id').val(),
                    payment_status: $('#payment_status').val()
                },
                success: function (response) {
                    let tbody = '';

                    if (response.length === 0) {
                        tbody = `<tr>
                            <td colspan="7" class="text-center">No hay resultados</td>
                        </tr>`;
                    } else {
                        $.each(response, function (i, sale) {

                            let badge = sale.payment_status === 'paid'
                                ? `<span class="badge bg-success">Pagado</span>`
                                : `<span class="badge bg-warning">Debido</span>`;

                            tbody += `
                                <tr>
                                    <td>${sale.transaction_date}</td>
                                    <td>${sale.invoice_no}</td>
                                    <td>${sale.contact.name}</td>
                                    <td class="text-end">${sale.final_total}</td>
                                    <td class="text-end">${sale.total_paid}</td>
                                    <td class="text-end">${sale.final_total - sale.total_paid}</td>
                                    <td class="text-center">${badge}</td>
                                </tr>
                            `;
                        });
                    }

                    $('#sales_table tbody').html(tbody);
                }
            });
        }
    });
</script>

<script>
    $(document).on('click', '.btn-view', function () {

        let sale_id = $(this).data('id');

        $.get("{{ url('sales') }}/" + sale_id, function (sale) {

            $('#v_cliente').text(sale.contact?.name ?? '-');
            $('#v_transaccion').text(sale.transaction_id);
            $('#v_estado').html(
                sale.status === 'pagado'
                    ? '<span class="badge bg-success">Pagado</span>'
                    : '<span class="badge bg-warning">Debido</span>'
            );
            $('#v_total').text(parseFloat(sale.total).toFixed(2));

            let html = '';
            sale.details.forEach(d => {
                html += `
                    <tr>
                        <td>${d.product?.name ?? '-'}</td>
                        <td class="text-center">${d.cantidad}</td>
                        <td class="text-end">S/. ${parseFloat(d.precio_unitario).toFixed(2)}</td>
                        <td class="text-end">S/. ${parseFloat(d.subtotal).toFixed(2)}</td>
                    </tr>
                `;
            });

            $('#detail_tbody').html(html);
            $('#viewSale').modal('show');
        });
    });

</script>

@endsection
