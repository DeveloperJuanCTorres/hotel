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
              <h3 class="fw-bold mb-3">Productos</h3>
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
                  <a href="#">Productos</a>
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
                    <div class="table-responsive" id="products-container">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Unidad Medida</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                            @include('products.partials.list')
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            
          </div>
        </div>

        @include('products.create')
        @include('products.edit')

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

    function loadProducts() {
        showLoader();
        $.ajax({
            url: "{{ route('products.list') }}",
            type: "GET",
            success: function(data) {
                $("#products-container tbody").html(data);
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
    //setInterval(loadProducts, 10000);
</script>

<script>
    $(document).ready(function () {
        $("#btnCreate").on("click", function () {
        // AJAX para traer categorías y unidades
            $.ajax({
                url: "{{ route('products.form.data') }}", // <-- crea esta ruta en Laravel
                type: "GET",
                dataType: "json",
                success: function (response) {
                // Limpiar selects
                $("#taxonomy_id").empty();
                $("#unit_id").empty();
                $("#igv_type_id").empty();

                // Llenar categorías
                if (response.categorias && response.categorias.length > 0) {
                    $.each(response.categorias, function (i, cat) {
                    $("#taxonomy_id").append('<option value="' + cat.id + '">' + cat.name + '</option>');
                    });
                } else {
                    $("#taxonomy_id").append('<option value="">Sin categorías</option>');
                }

                // Llenar unidades
                if (response.unidades && response.unidades.length > 0) {
                    $.each(response.unidades, function (i, uni) {
                    $("#unit_id").append('<option value="' + uni.id + '">' + uni.name + '</option>');
                    });
                } else {
                    $("#unit_id").append('<option value="">Sin unidades</option>');
                }

                // Llenar tipos de igv
                if (response.igv && response.igv.length > 0) {
                    $("#igv_type_id").append('<option value="">-Seleccionar-</option>');
                    $.each(response.igv, function (i, igv) {
                    $("#igv_type_id").append('<option value="' + igv.id + '">' + igv.name + '</option>');
                    });
                } else {
                    $("#igv_type_id").append('<option value="">Sin Tipo de IGV</option>');
                }

                // Mostrar modal SOLO cuando ya tenemos los datos
                $('#createProduct').modal('show');
                },
                error: function () {
                alert("Error al cargar datos del formulario.");
                }
            });
        });
  });
</script>

<script>
    $(document).ready(function () {
        $(".registrar").on("click", function (e) {
            e.preventDefault();

            let formData = new FormData(document.getElementById("formAgregar"));

            $.ajax({
                url: "{{ route('products.store') }}",
                type: "POST",
                data: formData,
                contentType: false, // necesario para enviar archivos
                processData: false, // necesario para enviar archivos
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status) {
                        $("#createProduct").modal("hide");
                        $("#formAgregar")[0].reset();
                        loadProducts(); 
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

<script>
    $(document).ready(function(){
        $(document).on("click", ".product-edit", function(e){
            e.preventDefault();

            let id     = $(this).data("id");

            $.ajax({
                url: "{{ route('products.edit') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    if(response.status){
                        console.log(response.product.image);
                        $('#edit_id').val(response.product.id);
                        $('#edit_name').val(response.product.name);
                        $('#edit_description').val(response.product.description);
                        $('#edit_price').val(response.product.price);
                        $('#edit_stock').val(response.product.stock);
                        $('#edit_price_compra').val(response.product.price_compra);
                        // Mostrar imagen si existe
                        if (response.product.image) {
                            $('#view_image')
                                .attr('src', '/storage/' + response.product.image)
                                .show(); // 👈 mostrar
                        } else {
                            $('#view_image').hide(); // 👈 ocultar
                        }
                        // Limpiar selects
                        $("#edit_taxonomy_id").empty();
                        $("#edit_unit_id").empty();
                        $("#edit_igv_type_id").empty();

                        // Llenar categorías
                        if (response.categorias && response.categorias.length > 0) {
                            $.each(response.categorias, function (i, cat) {
                                $("#edit_taxonomy_id").append('<option value="' + cat.id + '">' + cat.name + '</option>');
                            });
                            $("#edit_taxonomy_id").val(response.product.taxonomy_id);
                        } else {
                            $("#edit_taxonomy_id").append('<option value="">Sin categorías</option>');
                        }

                        // Llenar unidades
                        if (response.unidades && response.unidades.length > 0) {
                            $.each(response.unidades, function (i, uni) {
                                $("#edit_unit_id").append('<option value="' + uni.id + '">' + uni.name + '</option>');
                            });
                            $("#edit_unit_id").val(response.product.unit_id);
                        } else {
                            $("#edit_unit_id").append('<option value="">Sin unidades</option>');
                        }

                        // Llenar tipos de igv
                        if (response.igv && response.igv.length > 0) {
                            $("#edit_igv_type_id").append('<option value="">-Seleccionar-</option>');
                            $.each(response.igv, function (i, igv) {
                                $("#edit_igv_type_id").append('<option value="' + igv.id + '">' + igv.name + '</option>');
                            });
                            $("#edit_igv_type_id").val(response.product.igv_type_id);
                        } else {
                            $("#edit_igv_type_id").append('<option value="">Sin Tipo de IGV</option>');
                        }

                        $('#editProduct').modal('show');
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
    $(document).on("submit", "#formEditProduct", function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('products.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.status){
                    $('#editProduct').modal('hide');
                    loadProducts(); 
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
        $(document).on("click", ".product-eliminar", function(e){
            e.preventDefault();

            let id     = $(this).data("id");
            let name     = $(this).data("name");

            Swal.fire({
                title: "Eliminacion de producto?",
                text: "Estás seguro de eliminar el producto: " + name + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "Cancelar"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('products.delet') }}",
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
                                loadProducts(); 
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
