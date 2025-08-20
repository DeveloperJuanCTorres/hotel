@extends('layouts.app')

@section('content')
<div class="wrapper">
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

        if (status == "LIMPIEZA") {
            Swal.fire({
                title: `Habitación ${numero} (${type})`,
                text: `Estado actual: ${status}`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Cambiar a Disponible",
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
    });
});
</script>

@endsection
