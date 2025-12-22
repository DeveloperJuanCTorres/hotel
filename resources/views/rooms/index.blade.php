@extends('layouts.app')

@section('content')
<div class="wrapper sidebar_minimize">
    @include('utils.menu')

    <div class="main-panel">
        <div class="main-header">
            @include('utils.topbar')
            
            @include('utils.navbar')
        </div>

        <div class="container">
          <div class="page-inner">
            <!-- Loader (inicialmente oculto) -->
            <div id="loader-bar" style="display:none; height:4px; background:#007bff; position:fixed; top:0; left:0; width:0%; z-index:9999;"></div>
            <!-- Card -->
            <!-- <h3 class="fw-bold mb-3">Habitaciones</h3> -->
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Habitaciones</h4>
                  </div>
                  <div class="card-body">
                    <span class="badge badge-primary">Disponible</span>
                    <span class="badge badge-danger">Ocupado</span>
                    <span class="badge badge-warning">Reservado</span>
                    <span class="badge badge-black">Limpieza</span>
                  </div>
                </div>
              </div>
            <div class="row" id="rooms-container">
                @include('rooms.partials.list')
            </div>
          </div>
        </div>

        @include('rooms.partials.create')

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    function showLoader() {
        $("#loader-bar").stop().css({width: "0%", display: "block"}).animate(
            { width: "80%" }, 1000 // animación inicial hasta 80%
        );
    }

    function hideLoader() {
        $("#loader-bar").stop().animate(
            { width: "100%" }, 500, // termina la animación
            function() {
                $(this).fadeOut(200, function() {
                    $(this).css("width", "0%"); // reiniciar para el próximo uso
                });
            }
        );
    }

    function loadRooms() {
        showLoader();
        $.ajax({
            url: "{{ route('rooms.list') }}",
            type: "GET",
            success: function(data) {
                $("#rooms-container").html(data);
            },
            error: function() {
                console.error("Error al cargar habitaciones");
            },
            complete: function() {
                hideLoader();
            }
        });
    }

    // refrescar cada 20 segundos
    setInterval(loadRooms, 20000);
</script>

<script>
    $(document).ready(function(){
        $(document).on("click", ".room-link", function(e){
            e.preventDefault();

            let id     = $(this).data("id");
            let status = $(this).data("status");
            let numero = $(this).data("numero");
            let type   = $(this).data("type");
            let description   = $(this).data("description");
            let price   = $(this).data("price");

            let boxe_opening_id = $('#boxe_opening_id').val();

            if (boxe_opening_id != 0) {               
            
                if (status == "DISPONIBLE") {
                    // pasar info al modal
                    $('#roomNumero').text(numero);
                    $('#roomType').text(type);
                    $('#roomStatus').text(status);
                    $('#roomDescription').text(description);
                    $('#roomPrice').val(price);
                    $('#roomId').val(id);
                    
                    // abrir modal
                    $('#fromReservation').val(0);
                    $('#reservation_id').val('');
                    $('#roomModal').modal('show');
                    // alert("test.");
                }

                if (status == "LIMPIEZA") {
                    Swal.fire({
                        title: `Habitación ${numero} (${type})`,
                        text: `Estado actual: ${status}`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Finalizar Limpieza",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('rooms.updateStatus') }}",
                                type: "POST",
                                data: {
                                    id: id,
                                    status: status,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response){
                                    if(response.status){
                                        loadRooms()
                                        const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                        });
                                        Toast.fire({
                                        icon: "success",
                                        title: response.msg
                                        });
                                    } else {
                                        const Toast = Swal.mixin({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                        });
                                        Toast.fire({
                                        icon: "error",
                                        title: response.msg
                                        });
                                    }
                                },
                                error: function(){
                                    Swal.fire("Error", "Hubo un problema en el servidor", "error");
                                }
                            });
                        }
                    });
                }
                
                if (status == "OCUPADO") {
                    Swal.fire({
                        title: `Habitación ${numero} (${type})`,
                        text: `Estado actual: ${status}`,
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Ver detalle",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/habitacion/' + id + '/buscar-transaccion',
                                type: "GET",
                                data: {
                                    id: id,
                                    status: status,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response){
                                    // Redirige a la vista detalle de transacción
                                    window.location.href = '/transaccion/' + response.transaccion_id + '/detalle';
                                },
                                error: function(){
                                    Swal.fire("Error", "Hubo un problema en el servidor", "error");
                                }
                            });
                        }
                    });
                }  

                if (status == "RESERVADO") {
                    Swal.fire({
                        title: `Habitación ${numero} (${type})`,
                        text: "Esta habitación tiene una reserva",
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonText: "Realizar Check-in",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {

                        if (!result.isConfirmed) return;

                        $.ajax({
                            url: '/habitacion/' + id + '/reserva',
                            type: 'GET',
                            success: function(res) {

                                if (!res.status) {
                                    Swal.fire("Error", res.msg, "error");
                                    return;
                                }

                                let r = res.data;
                                let c = r.contact;

                                $('#fromReservation').val(1);
                                $('#reservation_id').val(r.reservation_id);
                               
                                // Datos habitación
                                $('#roomId').val(id);
                                $('#roomNumero').text(numero);
                                $('#roomType').text(type);
                                $('#roomStatus').text(status);
                                $('#roomDescription').text(description);
                                $('#roomPrice').val(price);

                                // Datos cliente
                                $('#cliente').val(c.name);
                                $('#tipo_doc').val(c.tipo_doc);
                                $('#numero_doc').val(c.numero_doc);
                                $('#direccion').val(c.address);

                                // Datos reserva
                                $('#roomCant').val(r.noches);
                                // $('#roomPrice').val(r.total);
                                $('#fechaSalida').val(r.fecha_fin);
                                $('#horaSalida').val(r.hora_fin);

                                // Guardar ID reserva
                                $('#reservation_id').val(r.reservation_id);
                                console.log(c);

                                $('#roomModal').modal('show');
                            },
                            error: function() {
                                Swal.fire("Error", "Error en el servidor", "error");
                            }
                        });
                    });
                }
            }
            else
            {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                    });
                    Toast.fire({
                    icon: "warning",
                    title: "Debe aperturar caja"
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function(){
        $(document).on("click", ".registrar", function(e){
            e.preventDefault();

            $reservation_id = $('#reservation_id').val();

            $id = $('#roomId').val();
            $tipo_doc = $('#tipo_doc').val();
            $numero_doc = $('#numero_doc').val();
            $cliente = $('#cliente').val();
            $direccion = $('#direccion').val();
            $cant_per = $('#roomCantPer').val();
            $precio = $('#roomPrice').val();
            $cant_noches = $('#roomCant').val();
            $estado_pago = $('#estado_pago').val();
            $pay_method_id = $('#pay_method_id').val();
            $fecha_salida = $('#fechaSalida').val();
            $hora_salida = $('#horaSalida').val();
            $boxe_opening_id = $('#boxe_opening_id').val();
      

            $.ajax({
                url: "{{ route('rooms.register') }}",
                type: "POST",
                data: {
                    id: $id,
                    reservation_id: $reservation_id,
                    tipo_doc: $tipo_doc,
                    numero_doc: $numero_doc,
                    cliente: $cliente,
                    direccion: $direccion,
                    cant_per: $cant_per,
                    precio: $precio,
                    cant_noches: $cant_noches,
                    estado_pago: $estado_pago,
                    pay_method_id: $pay_method_id,
                    fecha_salida: $fecha_salida,
                    hora_salida: $hora_salida,
                    boxe_opening_id: $boxe_opening_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    if(response.status){
                        $('#roomModal').modal('hide');
                        loadRooms()
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: response.msg
                        });
                    } else {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "error",
                            title: response.msg
                        });
                    }
                },
                error: function(){
                    Swal.fire("Error", "Hubo un problema en el servidor", "error");
                }
            });
        });
    });
</script>

@endsection
