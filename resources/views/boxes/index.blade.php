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
              <h3 class="fw-bold mb-3">Aperturas de caja</h3>
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
                  <a href="#">Aperturas</a>
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
                  </div>
                  <div class="card-body">
                    <div class="table-responsive" id="boxes-container">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Caja</th>
                            <th>Usuario</th>
                            <th>Monto inicial</th>
                            <th>Fecha apertura</th>
                            <th>Monto final</th>
                            <th>Fecha cierre</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                            @include('boxes.partials.list')
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            
          </div>
        </div>

        <!-- include('boxes.view') -->

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

    function loadBoxes() {
        showLoader();
        $.ajax({
            url: "{{ route('openings.list') }}",
            type: "GET",
            success: function(data) {
                $("#boxes-container tbody").html(data);
            },
            error: function() {
                console.error("Error al cargar productos");
            },
            complete: function() {
                hideLoader();
            }
        });
    }

    // refrescar cada 20 segundos
    //setInterval(loadCategories, 10000);
</script>

<script>
    $(document).ready(function(){
        $(document).on("click", ".boxe-view", function(e){
            e.preventDefault();

            let id     = $(this).data("id");

            $.ajax({
                url: "{{ route('openings.view') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    if(response.status){
                        $('#edit_id').val(response.category.id);
                        $('#edit_name').val(response.category.name);
                        $('#edit_description').val(response.category.description);
                        
                        $('#editCategory').modal('show');
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

<script>
    $(document).on("submit", "#formEditCategory", function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('categories.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.status){
                    $('#editCategory').modal('hide');
                    loadCategories(); 
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
                    Swal.fire("Error", response.msg, "error");
                }
            },
            error: function(response){
                Swal.fire("Error", "Hubo un problema en el servidor", "error");
            }
        });
    });
</script>

<script>
    $(document).ready(function(){
        $(document).on("click", ".category-eliminar", function(e){
            e.preventDefault();

            let id     = $(this).data("id");
            let name     = $(this).data("name");

            Swal.fire({
                title: "Eliminacion de categoría",
                text: "Estás seguro de eliminar la categoría: " + name + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "Cancelar"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('categories.delet') }}",
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
                                loadCategories(); 
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

@endsection