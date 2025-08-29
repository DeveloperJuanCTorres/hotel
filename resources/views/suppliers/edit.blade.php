<!-- Modal -->
<div class="modal fade" id="editSupplier" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-ml">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditSupplier" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id"> <!-- id oculto -->
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="form-group">
                                <label for="tipo_doc">Tipo Documento</label>
                                <select class="form-select form-control" name="tipo_doc" id="edit_tipo_doc">
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="PASAPORTE">PASAPORTE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12">                    
                            <div class="form-group">
                                <label for="tipo_doc">Número Documento</label>  
                                <div class="input-group">                                              
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="edit_numero_doc"
                                        name="numero_doc"
                                    />
                                    <button class="btn btn-primary"type="button" id="btnBuscar">
                                        <span class="input-icon-addon">
                                        <i class="fa fa-search" id="iconBuscar"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="form-group">
                                <label for="tipo_doc">Nombre</label>
                                <input class="form-control" type="text" id="edit_name" name="name" placeholder="Ingrese cliente">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="form-group">
                                <label for="tipo_doc">Dirección</label>
                                <input class="form-control" type="text" id="edit_address" name="address" placeholder="Ingrese dirección">
                            </div>
                        </div>
                        <div class="col-lg-12 col-12 mb-3">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                        </div>

                        <div class="col-lg-12 col-12 mb-3">
                            <div class="form-group">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone">
                            </div> 
                        </div>                          
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditSupplier" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </div>
</div>








