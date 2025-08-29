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
            
            <div class="page-header">
              <h3 class="fw-bold mb-3">Clientes</h3>
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
                  <a href="#">Clientes</a>
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
                    <button class="btn btn-primary" id="btnCreate"><i class="fas fa-plus px-2"></i>Agregar</button>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive" id="clients-container">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Tipo documento</th>
                            <th>Numero documento</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                            @include('clients.partials.list')
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            
          </div>
        </div>

        @include('clients.create')
        @include('clients.edit')

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

    function loadClients() {
        showLoader();
        $.ajax({
            url: "{{ route('clients.list') }}",
            type: "GET",
            success: function(data) {
                $("#clients-container tbody").html(data);
            },
            error: function() {
                console.error("Error al cargar los clientes");
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
    $(document).ready(function () {
        $("#btnCreate").on("click", function () {
            $('#createClient').modal('show');
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".registrar").on("click", function (e) {
            e.preventDefault();

            let formData = new FormData(document.getElementById("formAgregar"));

            $.ajax({
                url: "{{ route('clients.store') }}",
                type: "POST",
                data: formData,
                contentType: false, // necesario para enviar archivos
                processData: false, // necesario para enviar archivos
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status) {
                        $("#createClient").modal("hide");
                        $("#formAgregar")[0].reset();
                        loadClients(); 
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
                    }
                    else{
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
    $(document).ready(function(){
        $(document).on("click", ".client-edit", function(e){
            e.preventDefault();

            let id     = $(this).data("id");

            $.ajax({
                url: "{{ route('clients.edit') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    if(response.status){
                        $('#edit_id').val(response.contact.id);
                        $('#edit_tipo_doc').val(response.contact.tipo_doc);
                        $('#edit_numero_doc').val(response.contact.numero_doc);
                        $('#edit_name').val(response.contact.name);
                        $('#edit_address').val(response.contact.address);
                        $('#edit_email').val(response.contact.email);
                        $('#edit_phone').val(response.contact.phone);
                        
                        $('#editClient').modal('show');
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
    $(document).on("submit", "#formEditClient", function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('clients.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.status){
                    $('#editClient').modal('hide');
                    loadClients(); 
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
        $(document).on("click", ".client-eliminar", function(e){
            e.preventDefault();

            let id     = $(this).data("id");
            let name     = $(this).data("name");

            Swal.fire({
                title: "Eliminacion de cliente",
                text: "Estás seguro de eliminar el cliente: " + name + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "Cancelar"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('clients.delet') }}",
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
                                loadClients(); 
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