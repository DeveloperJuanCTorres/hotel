<!-- Modal Lista de Productos -->
<div class="modal fade" id="modalProductos" tabindex="-1" aria-labelledby="modalProductosLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable"> <!-- scrollable -->
    <div class="modal-content" style="max-height: 90vh;"> <!-- altura fija -->
      <div class="modal-header">
        <h5 class="modal-title" id="modalProductosLabel">Lista de Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body d-flex flex-column">

        <!-- Barra de filtros -->
        <div class="row mb-3">
          <div class="col-md-4">
            <select id="filtroCategoria" class="form-control form-select">
              <option value="">-- Todas las categorías --</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-8">
            <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar producto...">
          </div>
        </div>

        <!-- Lista de productos -->
        <div class="table-responsive flex-grow-1">
          <table class="table table-bordered table-hover" id="tablaProductos">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($products as $producto)
              <tr data-categoria="{{ $producto->taxonomy_id }}">
                <td class="nombre">{{ $producto->name }}</td>
                <td>{{ $producto->taxonomy->name ?? '' }}</td>
                <td>{{ number_format($producto->price, 2) }}</td>
                <td>{{ $producto->stock }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-primary btnAgregar" 
                          data-id="{{ $producto->id }}" 
                          data-nombre="{{ $producto->name }}" 
                          data-precio="{{ $producto->price }}">
                    <i class="fas fa-plus"></i> Agregar
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