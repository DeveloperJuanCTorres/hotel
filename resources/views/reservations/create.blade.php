<!-- Modal -->
<div class="modal fade" id="createReservation" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nueva Reservación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregar">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha inicio</label>
                                <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label for="hora_inicio">Hora inicio</label>
                                <input class="form-control" type="time" id="hora_inicio" name="hora_inicio">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label for="fecha_fin">Fecha fin</label>
                                <input class="form-control" type="date" id="fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label for="hora_fin">Hora fin</label>
                                <input class="form-control" type="time" id="hora_fin" name="hora_fin">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <a href="#" id="btnConsultar" class="btn btn-primary">Consultar habitación</a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="form-group">
                                <label for="tipo_doc">Habitaciones disponibles</label>
                                <select class="form-select form-control" name="habitacion" id="habitacion">
                                    <option value="0">--Seleccionar--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input class="form-control" type="text" id="total" name="total" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="form-group">
                                <label for="tipo_doc">Tipo Documento</label>
                                <select class="form-select form-control" name="tipo_doc" id="tipo_doc">
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="PASAPORTE">PASAPORTE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">                    
                            <div class="form-group">
                                <label for="tipo_doc">Número Documento</label>  
                                <div class="input-group">                                              
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="numero_doc"
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
                        <div class="col-lg-8 col-md-12 col-12">
                            <div class="form-group">
                                <label for="tipo_doc">Nombre</label>
                                <input class="form-control" type="text" id="name" name="name" placeholder="Ingrese cliente">
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label for="categoria_id" class="form-label">Teléfono</label>
                                <input type="number" class="form-control" id="phone" name="phone">
                            </div> 
                        </div>
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="form-group">
                                <label for="tipo_doc">Dirección</label>
                                <input class="form-control" type="text" id="address" name="address" placeholder="Ingrese dirección">
                            </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btnBuscar').on('click', function() {
            let tipo = $('#tipo_doc').val();
            let numero = $('#numero_doc').val();
            let $btn = $(this);
            let $icon = $('#iconBuscar');

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
                            $('#name').val(response.data.nombre_completo);
                            $('#address').val('');
                        }else if(tipo === 'RUC'){
                            $('#name').val(response.data.nombre_o_razon_social);
                            $('#address').val(response.data.direccion_completa);
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
        $('#numero_doc').on('input', function () {
            let tipo = $('#tipo_doc').val();

            if (tipo === 'DNI' || tipo === 'RUC') {
                // Solo números
                this.value = this.value.replace(/[^0-9]/g, '');
            } else if (tipo === 'PASAPORTE') {
                // Solo alfanumérico (letras y números)
                this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
            }
        });
        
        // Cambiar longitud máxima según tipo_doc
        $('#tipo_doc').on('change', function () {
            let tipo = $(this).val();

            if (tipo === 'DNI') {               
                $('#numero_doc').attr('maxlength', 8);
                $('#numero_doc').val($('#numero_doc').val().slice(0, 8)); // recortar si excede
                 $('#numero_doc').value.replace(/[^0-9]/g, ''); // elimina todo lo que no sea número
            } else if (tipo === 'RUC') {                              
                $('#numero_doc').attr('maxlength', 11);
                $('#numero_doc').val($('#numero_doc').val().slice(0, 11)); // recortar si excede
                $('#numero_doc').value.replace(/[^0-9]/g, ''); // elimina todo lo que no sea número
            }
            if (tipo === 'PASAPORTE') {
                $('#numero_doc').attr('maxlength', 20);
                $('#numero_doc').val($('#numero_doc').val().slice(0, 20)); // recortar si excede
            }
        });

        // Forzar la longitud inicial al abrir modal (por si cambia)
        $('#tipo_doc').trigger('change');

        // Forzar reglas al cargar
        $('#tipo_doc').trigger('change');
    });
</script>

<script>
    $(document).ready(function () {
        $("#btnConsultar").on("click", function (e) {
            e.preventDefault();

            let fecha_inicio = $("#fecha_inicio").val();
            let hora_inicio  = $("#hora_inicio").val();
            let fecha_fin    = $("#fecha_fin").val();
            let hora_fin     = $("#hora_fin").val();

            if (!fecha_inicio || !hora_inicio || !fecha_fin || !hora_fin) {
                alert("Debe ingresar fecha y hora de inicio y fin.");
                return;
            }

            // Guardamos el texto original del botón
            let $btn = $(this);
            let btnText = $btn.html();

             // Deshabilitar botón y mostrar cargando
            $btn.prop("disabled", true);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...');

            $.ajax({
                url: "/reservations/disponibles",
                type: "POST",
                data: {
                    fecha_inicio: fecha_inicio,
                    hora_inicio: hora_inicio,
                    fecha_fin: fecha_fin,
                    hora_fin: hora_fin,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    let $select = $("#habitacion");
                    $select.empty();
                    $select.append('<option value="0">--Seleccionar--</option>');

                    if (data.length > 0) {
                        $.each(data, function (i, habitacion) {
                            $select.append(
                                `<option value="${habitacion.id}" data-precio="${habitacion.price}">
                                    ${habitacion.numero} - ${habitacion.type.name} - (S/. ${habitacion.price})
                                </option>`
                            );
                        });
                    } else {
                        $select.append('<option value="">No hay habitaciones disponibles</option>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert("Error consultando habitaciones");
                },
                complete: function () {
                    // Restaurar botón al terminar (éxito o error)
                    $btn.prop("disabled", false);
                    $btn.html(btnText);
                }
            });
        });

        $("#habitacion").on("change", function () {
            let precio = $("option:selected", this).data("precio");
            let fecha_inicio = $("#fecha_inicio").val();
            let fecha_fin = $("#fecha_fin").val();

            if (!fecha_inicio || !fecha_fin || !precio) {
                $("#total").val("");
                return;
            }

            // Calcular diferencia en días
            let inicio = new Date(fecha_inicio);
            let fin = new Date(fecha_fin);

            let diffTime = fin - inicio;
            let diffDays = diffTime / (1000 * 60 * 60 * 24);

            if (diffDays < 1) diffDays = 1; // mínimo 1 día

            let total = precio * diffDays;
            $("#total").val(total.toFixed(2));
        });
    });
</script>








