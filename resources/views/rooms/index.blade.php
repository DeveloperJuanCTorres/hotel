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
            <h3 class="fw-bold mb-3">Habitaciones</h3>
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
@endsection
