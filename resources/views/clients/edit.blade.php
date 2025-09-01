<!-- Modal -->
<div class="modal fade" id="editClient" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-ml">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditClient" method="POST" enctype="multipart/form-data">
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
                                    <button class="btn btn-primary"type="button" id="edit_btnBuscar">
                                        <span class="input-icon-addon">
                                        <i class="fa fa-search" id="edit_iconBuscar"></i>
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
                <button type="submit" form="formEditClient" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#edit_btnBuscar').on('click', function() {
            let tipo = $('#edit_tipo_doc').val();
            let numero = $('#edit_numero_doc').val();
            let $btn = $(this);
            let $icon = $('#edit_iconBuscar');

            // Mostrar loader (spinner)
            $icon.removeClass('fa-search').addClass('fa-spinner fa-spin');

            if(numero === ''){
                alert("Ingrese número de documento");
                return;
            }

            $.ajax({
                url: "{{ route('buscar.documento') }}", 
                type: "POST",
                data: {
                    tipo_doc: tipo,
                    numero_doc: numero,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    if(response.success){
                        if(tipo === 'DNI'){
                            $('#edit_name').val(response.data.nombre_completo);
                            $('#edit_address').val('');
                        }else if(tipo === 'RUC'){
                            $('#edit_name').val(response.data.nombre_o_razon_social);
                            $('#edit_address').val(response.data.direccion_completa);
                        }
                    }else{
                        alert("No se encontró información");
                    }
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                    alert("Error en la consulta");
                },
                complete: function(){
                    // Restaurar ícono original
                    $icon.removeClass('fa-spinner fa-spin').addClass('fa-search');
                }
            });
        });

        // Validar mientras escribe
        $('#edit_numero_doc').on('input', function () {
            let tipo = $('#edit_tipo_doc').val();

            if (tipo === 'DNI' || tipo === 'RUC') {
                // Solo números
                this.value = this.value.replace(/[^0-9]/g, '');
            } else if (tipo === 'PASAPORTE') {
                // Solo alfanumérico (letras y números)
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
            }
        });
        
        // Cambiar longitud máxima según tipo_doc
        $('#edit_tipo_doc').on('change', function () {
            let tipo = $(this).val();

            if (tipo === 'DNI') {               
                $('#edit_numero_doc').attr('maxlength', 8);
                $('#edit_numero_doc').val($('#edit_numero_doc').val().slice(0, 8)); // recortar si excede
                 $('#edit_numero_doc').value.replace(/[^0-9]/g, ''); // elimina todo lo que no sea número
            } else if (tipo === 'RUC') {                              
                $('#edit_numero_doc').attr('maxlength', 11);
                $('#edit_numero_doc').val($('#edit_numero_doc').val().slice(0, 11)); // recortar si excede
                $('#edit_numero_doc').value.replace(/[^0-9]/g, ''); // elimina todo lo que no sea número
            }
            if (tipo === 'PASAPORTE') {
                $('#edit_numero_doc').attr('maxlength', 20);
                $('#edit_numero_doc').val($('#edit_numero_doc').val().slice(0, 20)); // recortar si excede
            }
        });

        // Forzar la longitud inicial al abrir modal (por si cambia)
        $('#edit_tipo_doc').trigger('change');

        // Forzar reglas al cargar
        $('#edit_tipo_doc').trigger('change');
    });
</script>






