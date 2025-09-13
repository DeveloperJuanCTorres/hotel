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
            <!-- Loader (inicialmente oculto) -->
            <div id="loader-bar" style="display:none; height:4px; background:#007bff; position:fixed; top:0; left:0; width:0%; z-index:9999;"></div>
            
            <div class="page-header">
              <h3 class="fw-bold mb-3">Kardex</h3>
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
                  <a href="#">Kardex</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="/">Dashboard</a>
                </li>
              </ul>
            </div>
            <div class="row">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="product_id" class="form-label">Producto</label>
                        <select name="product_id" id="product_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="from_date" class="form-label">Desde</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="to_date" class="form-label">Hasta</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>

                @if($movimientos->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Referencia</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Stock</th>
                                    <th>Costo Promedio</th>
                                    <th>Costo Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movimientos as $m)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $m->tipo }}</td>
                                        <td>{{ $m->referencia }}</td>
                                        <td class="text-end">{{ number_format($m->cantidad, 2) }}</td>
                                        <td class="text-end">{{ number_format($m->precio_unitario, 2) }}</td>
                                        <td class="text-end">{{ number_format($m->stock, 2) }}</td>
                                        <td class="text-end">{{ number_format($m->costo_promedio, 2) }}</td>
                                        <td class="text-end">{{ number_format($m->costo_total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <strong>Stock actual en productos:</strong> {{ $stock_final }}
                    </div>
                @else
                    <div class="alert alert-info">
                        Seleccione un producto para ver su Kardex.
                    </div>
                @endif
            </div>            
            
          </div>
        </div>

    

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

@endsection