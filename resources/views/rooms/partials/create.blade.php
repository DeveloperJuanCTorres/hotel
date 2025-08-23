<!-- Modal -->
<div class="modal fade" id="roomModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Chek-ing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4>Datos de la Habitación</h4>
        <div class="row">
            <div class="col-md-6 col-12">
                <input class="form-control d-none" type="text" id="roomId" readonly>
                <p><strong>Número:</strong> <span id="roomNumero"></span></p>
                <p><strong>Detalles:</strong> <span id="roomDescription"></span></p>
            </div>
            <div class="col-md-6 col-12">
                <p><strong>Tipo:</strong> <span id="roomType"></span></p>
                <p><strong>Estado:</strong> <span class="badge badge-primary" id="roomStatus"></span></p>
            </div>
        </div>  
        <hr>
        <div class="row">
            <div class="col-md-6 col-12">
                <h4 class="text-center">Datos del Cliente</h4>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Tipo Documento</label>
                            <select class="form-select form-control" name="tipo_doc" id="tipo_doc">
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
                                    id="numero_doc"
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
                            <label for="tipo_doc">Cliente</label>
                            <input class="form-control" type="text" id="cliente" name="cliente" placeholder="Ingrese cliente">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Dirección</label>
                            <input class="form-control" type="text" id="direccion" name="direccion" placeholder="Ingrese dirección">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-12">
                <h4 class="text-center">Datos de Alojamiento</h4>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Cant. de personas</label>
                            <select class="form-select form-control" name="cant_personas" id="roomCantPer">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>                        
                    </div>
                    <div class="col-lg-4 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Precio</label>
                            <input class="form-control" type="number" id="roomPrice">
                        </div>                       
                    </div>
                    <div class="col-lg-4 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Cant. Noches</label>
                            <input class="form-control" type="number" id="roomCant" value="1" min="1" max="29">
                        </div>                       
                    </div>
                    <div class=" row mx-2">
                        <h4 class="left">Total a pagar:  S/. <span id="roomTotal"></span></h4>
                    </div>
                    <div class="col-lg-8 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Estado de Pago</label>
                            <select class="form-select form-control" name="cant_personas" id="estadoPago">
                                <option value="0">--Selecciona--</option>
                                <option value="pagado">Pagado</option>
                                <option value="credito">Falta pagar</option>
                            </select>
                        </div>                       
                    </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Fecha de salida</label>
                            <input class="form-control d-none" type="date" id="fechaEntrada" readonly>
                            <input class="form-control" type="date" id="fechaSalida" readonly>
                        </div>                       
                    </div>
                    <div class="col-lg-6 col-md-12 col-12">
                        <div class="form-group">
                            <label for="tipo_doc">Hora de salida</label>
                            <input class="form-control" type="time" id="horaSalida">
                        </div>                       
                    </div>
                </div>
            </div>
        </div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary registrar">Registrar ingreso</button>
      </div>
    </div>
  </div>
</div>

<script>
    function calcularTotal() {
        let cantidad = parseFloat(document.getElementById("roomCant").value) || 0;
        let precio   = parseFloat(document.getElementById("roomPrice").value) || 0;
        let total    = cantidad * precio;

        document.getElementById("roomTotal").innerText = total.toFixed(2); // 2 decimales
    }

    // Detectar cambios en los inputs
    document.getElementById("roomCant").addEventListener("input", calcularTotal);
    document.getElementById("roomPrice").addEventListener("input", calcularTotal);

    

</script>

<script>
    document.getElementById("roomCant").addEventListener("input", function () {
        let valor = parseInt(this.value);

        if (isNaN(valor) || valor < 1) {
            this.value = 1;
            calcularTotal();
        } else if (valor > 29) {
            this.value = 29;
            calcularTotal();
        }
    });
</script>

<script>
    document.getElementById("roomPrice").addEventListener("input", function () {
        let valor = parseInt(this.value);

        if (isNaN(valor) || valor < 1) {
            this.value = 1;
            calcularTotal();
        } else if (valor > 1000) {
            this.value = 1000;
            calcularTotal();
        }
    });
</script>

<script>
    function formatearFecha(fecha) {
        let anio = fecha.getFullYear();
        let mes = String(fecha.getMonth() + 1).padStart(2, '0');
        let dia = String(fecha.getDate()).padStart(2, '0');
        return `${anio}-${mes}-${dia}`;
    }

    function calcularFechaSalida() {
        let entradaStr = document.getElementById("fechaEntrada").value;
        let noches  = parseInt(document.getElementById("roomCant").value) || 0;

        if (entradaStr && noches > 0) {
            // 🚨 Construir fecha en local correctamente
            let partes = entradaStr.split("-"); // [YYYY, MM, DD]
            let fecha = new Date(
                parseInt(partes[0]), 
                parseInt(partes[1]) - 1, 
                parseInt(partes[2])
            );

            fecha.setDate(fecha.getDate() + noches);

            document.getElementById("fechaSalida").value = formatearFecha(fecha);
        } else {
            document.getElementById("fechaSalida").value = "";
        }
    }

    // Al abrir el modal -> recalcular
    document.getElementById('roomModal').addEventListener('shown.bs.modal', function () {
        $('#tipo_doc').val('DNI');
        $('#numero_doc').val('');
        $('#cliente').val('');
        $('#direccion').val('');
        $('#roomCantPer').val('1');
        $('#roomCant').val('1');
        $('#estadoPago').val('0');
        $('#horaSalida').val('12:00');

        calcularTotal();
        calcularFechaSalida();
    });

    // Al cargar la página -> poner fecha actual como entrada y calcular salida
    window.addEventListener("load", function () {
        let hoy = new Date();
        document.getElementById("fechaEntrada").value = formatearFecha(hoy);
        calcularFechaSalida();
    });

    // Recalcular al cambiar noches
    document.getElementById("roomCant").addEventListener("input", calcularFechaSalida);
</script>

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
                            $('#cliente').val(response.data.nombre_completo);
                            $('#direccion').val('');
                        }else if(tipo === 'RUC'){
                            $('#cliente').val(response.data.nombre_o_razon_social);
                            $('#direccion').val(response.data.direccion_completa);
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