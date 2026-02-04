<!-- Modal -->
<div class="modal fade" id="createActivo" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title">Nuevo Activo</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formAgregarActivo">
                    <div class="row">

                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Habitación</label>
                            <select id="room_id" name="room_id" class="form-control form-select" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Nombre del activo</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-control form-select" required>
                                <option value="operativo">Operativo</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">¿Activo?</label>
                            <select name="active" class="form-control form-select" required>
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formAgregarActivo" class="btn btn-primary registrar">
                    Registrar
                </button>
            </div>

        </div>
    </div>
</div>
