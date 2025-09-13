<!-- Modal -->
<div class="modal fade" id="createExpense" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo Gasto</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregar">
                    @if($caja_abierta)
                        <input type="hidden" class="form-control" id="box_opening_id" name="box_opening_id" value="{{$caja_abierta->id}}">
                    @else
                        <input type="hidden" class="form-control" id="box_opening_id" name="box_opening_id" value="0">
                    @endif
                    <div class="row">
                        <div class="col-lg-6 col-12 mb-3">
                            <label for="nombre" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>

                        <div class="col-lg-6 col-12 mb-3">
                            <label for="categoria_id" class="form-label">Categoría de gasto</label>
                            <select id="categorie_expense_id" name="categorie_expense_id" class="form-control form-select">
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="col-lg-12 col-12 mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-lg-4 col-12 mb-3">
                            <label for="amount" class="form-label">Monto</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount">
                        </div>

                        <div class="col-lg-4 col-12 mb-3">
                            <label for="igv_type_id" class="form-label">Referencia</label>
                            <input type="text" class="form-control" id="referencia" name="referencia">
                        </div>      
                        
                        <div class="col-lg-4 col-12 mb-3">
                            <label for="method" class="form-label">Metodo pago</label>
                            <select id="pay_method_id" name="pay_method_id" class="form-control form-select">
                                <option value="">Cargando...</option>
                            </select>
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







