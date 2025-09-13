<!-- Modal -->
<div class="modal fade" id="editCategory" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-ml">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Editar Categoría de gastos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditCategory" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id"> <!-- id oculto -->
                    <div class="row">
                        <div class="col-lg-12 col-12 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>

                        <div class="col-lg-12 col-12 mb-3">
                            <label for="categoria_id" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="edit_description" name="description">
                        </div>                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditCategory" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </div>
</div>








