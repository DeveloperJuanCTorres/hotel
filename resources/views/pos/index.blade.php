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
            <!-- <div class="page-header">
              <h3 class="fw-bold mb-3">Modulo de ventas</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Ventas</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="/">Dashboard</a>
                </li>
              </ul>
            </div> -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detalle de venta</h4>
                        <button class="btn btn-primary" id="btnCreate"><i class="fas fa-plus px-2"></i>Agregar productos</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-head-bg-primary">
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
                                    <tr>
                                        <td>Gaseosa</td>
                                        <td>1</td>
                                        <td>2.50</td>
                                        <td>2.50</td>
                                        <td>x</td>
                                    </tr>
                                    
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2">SUBTOTAL <span class="px-2">S/. 2.50</span></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2">IGV <span class="px-2">S/. 2.50</span></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2">TOTAL <span class="px-2">S/. 2.50</span></td>
                                    </tr>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="card-title mb-0 text-center text-white">IMPORTANTE</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="form-group">
                                    <label for="categoria_id" class="form-label">Tipo de cliente</label>
                                    <select class="form-select form-control" name="tipo_cliente" id="tipo_cliente">
                                        <option value="0">--Selecciona--</option>
                                        <option value="1">Huesped de habitación</option>
                                        <option value="2">Cliente final</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label for="categoria_id" class="form-label">Comprobante</label>
                                    <select class="form-select form-control" name="tipo_comprobante" id="tipo_comprobante">
                                        <option value="TICKET">TICKET</option>
                                        <option value="BOLETA">BOLETA</option>
                                        <option value="FACTURA">FACTURA</option>
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
                                        <option value="EFECTIVO">EFECTIVO</option>
                                        <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                        <option value="DEPOSITO">DEPOSITO</option>
                                    </select>
                                </div> 
                            </div> 
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="form-group">
                                    <button class="btn btn-success w-100"><i class="fas fa-money-bill-alt px-2"></i>Guardar venta</button>
                                </div> 
                            </div>                             
                        </div>
                    </div>
                    </div>
                </div>
            </div>            
            
          </div>
        </div>

        @include('reservations.create')
        @include('reservations.edit')

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

@endsection