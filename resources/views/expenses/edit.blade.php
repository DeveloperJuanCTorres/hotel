<!-- Modal -->
<div class="modal fade" id="editExpense" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="staticBackdropLabel">Editar Gasto</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditExpense" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id"> <!-- id oculto -->
                    <div class="row">
                        <div class="col-lg-6 col-12 mb-3">
                            <label for="nombre" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="edit_date" name="date">
                        </div>

                        <div class="col-lg-6 col-12 mb-3">
                            <label for="categoria_id" class="form-label">Categoría de gasto</label>
                            <select id="edit_categorie_expense_id" name="categorie_expense_id" class="form-control form-select">
                            <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="col-lg-12 col-12 mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-lg-4 col-12 mb-3">
                            <label for="amount" class="form-label">Monto</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="edit_amount" name="amount">
                        </div>

                        <div class="col-lg-4 col-12 mb-3">
                            <label for="igv_type_id" class="form-label">Referencia</label>
                            <input type="text" class="form-control" id="edit_referencia" name="referencia">
                        </div>      
                        
                        <div class="col-lg-4 col-12 mb-3">
                            <label for="method" class="form-label">Metodo</label>
                            <select id="edit_method" name="method" class="form-control form-select">
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="DEPOSITO">Deposito</option>
                                <option value="TRANSFERENCIA">Transferencia</option>
                            </select>
                        </div>                        
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditExpense" class="btn btn-success">Actualizar</button>
            </div>
        </div>
    </div>
</div>








