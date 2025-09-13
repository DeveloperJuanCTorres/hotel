<!-- Modal -->
<div class="modal fade" id="createProduct" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo Producto</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregar">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12 col-12 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>

                                <div class="col-lg-6 col-12 mb-3">
                                    <label for="categoria_id" class="form-label">Categoría</label>
                                    <select id="taxonomy_id" name="taxonomy_id" class="form-control form-select">
                                    <option value="">Cargando...</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 col-12 mb-3">
                                    <label for="unidad_id" class="form-label">Unidad de Medida</label>
                                    <select id="unit_id" name="unit_id" class="form-control form-select">
                                    <option value="">Cargando...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 mb-3">
                            <label for="price" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg">
                            <small class="text-muted">Formatos permitidos: PNG, JPG. Máx: 2MB</small>
                        </div>  

                        <div class="col-lg-12 col-12 mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-lg-4 col-12 mb-3">
                            <label for="price" class="form-label">Precio venta (inc.IGV)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price" name="price">
                        </div>

                        <div class="col-lg-8 col-12 mb-3">
                            <label for="igv_type_id" class="form-label">Tipo de IGV</label>
                            <select id="igv_type_id" name="igv_type_id" class="form-control form-select">
                            <option value="">Cargando...</option>
                            </select>
                        </div>      
                        
                        <div class="col-lg-4 col-12 mb-3">
                            <label for="price_compra" class="form-label">Precio compra (inc.IGV)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="price_compra" name="price_compra">
                        </div>

                        <div class="col-lg-4 col-12 mb-3">
                            <label for="price" class="form-label">stock</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formAgregar" class="btn btn-primary registrar">Registrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) { // 2MB en bytes
                alert("El archivo debe ser menor o igual a 2MB.");
                this.value = ""; // reset input
            }
        }
    });
</script>







