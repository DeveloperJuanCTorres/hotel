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
            
            <div class="page-header">
              <h3 class="fw-bold mb-3">Compras</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Compras</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="/">Dashboard</a>
                </li>
              </ul>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Listado</h4>
                    <a href="/purchases/create" class="btn btn-primary"><i class="fas fa-plus px-2"></i>Agregar</a>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive" id="purchases-container">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Método</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                            @include('purchases.partials.list')
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            
          </div>
        </div>

        <!-- include('purchases.edit') -->

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $("#basic-datatables").DataTable({
            
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            }
        });
    
    });
</script>

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

    function loadPuchases() {
        showLoader();
        $.ajax({
            url: "{{ route('purchases.list') }}",
            type: "GET",
            success: function(data) {
                $("#purchases-container tbody").html(data);
            },
            error: function() {
                console.error("Error al cargar las compras");
            },
            complete: function() {
                hideLoader();
            }
        });
    }

    // refrescar cada 20 segundos
    //setInterval(loadClients, 10000);
</script>

<script>
    $(document).ready(function(){
        $(document).on("click", ".purchase-eliminar", function(e){
            e.preventDefault();

            let id     = $(this).data("id");
            let name     = $(this).data("name");

            Swal.fire({
                title: "Eliminacion de compra",
                text: "Estás seguro de eliminar la compra: " + name + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "Cancelar"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('purchases.delet') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response){
                            if(response.status){
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
                                loadPuchases(); 
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
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000
    });
</script>
@endif

@endsection