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

            {{-- Loader --}}
            <div id="loader-bar" style="display:none; height:4px; background:#007bff; position:fixed; top:0; left:0; width:0%; z-index:9999;"></div>

            <div class="page-header">
              <h3 class="fw-bold mb-3">Activos</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Activos</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="/">Dashboard</a></li>
              </ul>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Listado</h4>
                    <button class="btn btn-primary" id="btnCreate">
                        <i class="fas fa-plus px-2"></i>Agregar
                    </button>
                  </div>

                  <div class="card-body">
                    <div class="table-responsive" id="activos-container">
                      <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Habitación</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                            @include('activos.partials.list')
                        </tbody>
                      </table>
                    </div>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>

        {{-- Modales --}}
        @include('activos.create')
        @include('activos.edit')

        @include('utils.footer')
    </div>

    @include('utils.setting')
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- DataTable --}}
<script>
$(document).ready(function () {
    $("#basic-datatables").DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        }
    });
});
</script>

{{-- Loader --}}
<script>
function showLoader() {
    $("#loader-bar").stop().css({width:"0%",display:"block"})
        .animate({ width:"80%" },1000);
}

function hideLoader() {
    $("#loader-bar").stop().animate({ width:"100%" },500,function(){
        $(this).fadeOut(200).css("width","0%");
    });
}

function loadActivos() {
    showLoader();
    $.ajax({
        url: "{{ route('activos.list') }}",
        type: "GET",
        success: function(data) {
            $("#activos-container tbody").html(data);
        },
        complete: function() {
            hideLoader();
        }
    });
}
</script>

{{-- Crear --}}
<script>
    $("#btnCreate").on("click", function () {
        $.ajax({
            url: "{{ route('activos.form.data') }}",
            type: "GET",
            success: function (response) {
                $("#room_id").empty();

                if (response.rooms.length > 0) {
                    $.each(response.rooms, function (i, room) {
                        $("#room_id").append(`<option value="${room.id}">${room.numero}</option>`);
                    });
                }

                $('#createActivo').modal('show');
            }
        });
    });
</script>

{{-- Guardar --}}
<script>
    $(document).ready(function () {
        $(".registrar").on("click", function (e) {
            e.preventDefault();

            let formData = new FormData(document.getElementById("formAgregarActivo"));

            $.ajax({
                url: "{{ route('activos.store') }}",
                type: "POST",
                data: formData,
                contentType: false, // necesario para enviar archivos
                processData: false, // necesario para enviar archivos
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status) {
                        $("#createActivo").modal("hide");
                        $("#formAgregarActivo")[0].reset();
                        loadActivos(); 
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
                        console.log(response.msg);
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

{{-- Editar --}}
<script>
    $(document).ready(function(){
        $(document).on("click",".activo-edit",function(e){
            e.preventDefault();
            let id     = $(this).data("id");

            $.ajax({
                url: "{{ route('activos.edit') }}",
                type: "POST",
                data:{
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success:function(response){
                    if(response.status){
                        $('#edit_id').val(response.activo.id);
                        $('#edit_name').val(response.activo.name);
                        $('#edit_description').val(response.activo.description);
                        $('#edit_status').val(response.activo.status);
                        $('#edit_active').val(response.activo.active);
                        // 👉 1. Limpiar select
                        $('#edit_room_id').empty();

                        // 👉 2. Cargar opciones
                        $.each(response.rooms, function (i, room) {
                            $('#edit_room_id').append(
                                `<option value="${room.id}">${room.numero}</option>`
                            );
                        });

                        // 👉 3. ASIGNAR EL VALOR (AQUÍ recién funciona)
                        $('#edit_room_id').val(response.activo.room_id);

                        $('#editActivo').modal('show');
                    }
                }
            });
        });
    });
</script>

{{-- Actualizar --}}
<script>
    $(document).on("submit","#formEditActivo",function(e){
        e.preventDefault();

        $.ajax({
            url:"{{ route('activos.update') }}",
            type:"POST",
            data: new FormData(this),
            processData:false,
            contentType:false,
            success:function(response){
                if(response.status){
                    $('#editActivo').modal('hide');
                    loadActivos();
                    Swal.fire("Correcto", response.msg, "success");
                }
            }
        });
    });
</script>

{{-- Eliminar --}}
<script>
    $(document).on("click",".activo-eliminar",function(){
        let id = $(this).data("id");
        let name = $(this).data("name");

        Swal.fire({
            title: "Eliminar activo?",
            text: "¿Eliminar: " + name + "?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar"
        }).then((result)=>{
            if(result.isConfirmed){
                $.post("{{ route('activos.delet') }}",{
                    id:id,
                    _token:"{{ csrf_token() }}"
                },function(response){
                    if(response.status){
                        loadActivos();
                        Swal.fire("Eliminado", response.msg, "success");
                    }
                });
            }
        });
    });
</script>

@endsection
