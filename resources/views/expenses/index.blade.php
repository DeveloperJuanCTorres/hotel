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
              <h3 class="fw-bold mb-3">Gastos</h3>
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
                  <a href="#">Gastos</a>
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
                    <div class="table-responsive" id="expenses-container">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
                        <thead>
                          <tr>
                            <th>Fecha</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Monto</th>
                            <th>Referencia</th>
                            <th>Método</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                            @include('expenses.partials.list')
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            
          </div>
        </div>

        @include('expenses.create')
        @include('expenses.edit')

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

    function loadExpenses() {
        showLoader();
        $.ajax({
            url: "{{ route('expenses.list') }}",
            type: "GET",
            success: function(data) {
                $("#expenses-container tbody").html(data);
            },
            error: function() {
                console.error("Error al cargar gastos");
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
            $box_opening = $('#box_opening_id').val();
            if ($box_opening == 0) {
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
                    title: "Debes aperturar caja"
                });
            }
            else
            {
                $.ajax({
                    url: "{{ route('expenses.form.data') }}", // <-- crea esta ruta en Laravel
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                    // Limpiar selects
                    $("#categorie_expense_id").empty();
                    $("#pay_method_id").empty();

                    // Llenar categorías
                    if (response.categorias && response.categorias.length > 0) {
                        $.each(response.categorias, function (i, cat) {
                        $("#categorie_expense_id").append('<option value="' + cat.id + '">' + cat.name + '</option>');
                        });
                    } else {
                        $("#categorie_expense_id").append('<option value="">Sin categoría de gastos</option>');
                    }

                    // Llenar metodos de pago
                    if (response.metodos_pago && response.metodos_pago.length > 0) {
                        $.each(response.metodos_pago, function (i, cat) {
                        $("#pay_method_id").append('<option value="' + cat.id + '">' + cat.name + '</option>');
                        });
                    } else {
                        $("#pay_method_id").append('<option value="">Sin categoría de gastos</option>');
                    }
                    
                    // Mostrar modal SOLO cuando ya tenemos los datos
                    $('#createExpense').modal('show');
                    },
                    error: function () {
                    alert("Error al cargar datos del formulario.");
                    }
                });
            }
        });
  });
</script>

<script>
    $(document).ready(function () {
        $(".registrar").on("click", function (e) {
            e.preventDefault();

            let formData = new FormData(document.getElementById("formAgregar"));

            $.ajax({
                url: "{{ route('expenses.store') }}",
                type: "POST",
                data: formData,
                contentType: false, // necesario para enviar archivos
                processData: false, // necesario para enviar archivos
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status) {
                        $("#createExpense").modal("hide");
                        $("#formAgregar")[0].reset();
                        loadExpenses(); 
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
        $(document).on("click", ".expense-edit", function(e){
            e.preventDefault();

            let id     = $(this).data("id");

            $.ajax({
                url: "{{ route('expenses.edit') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response){
                    if(response.status){
                        $('#edit_id').val(response.expense.id);
                        $('#edit_categorie_expense').val(response.expense.date);
                        $('#edit_date').val(response.expense.date);
                        $('#edit_description').val(response.expense.description);
                        $('#edit_amount').val(response.expense.amount);
                        $('#edit_referencia').val(response.expense.referencia);
                        $('#edit_method').val(response.expense.method);
                        
                        // Limpiar selects
                        $("#edit_categorie_expense_id").empty();

                        // Llenar categorías
                        if (response.categorias && response.categorias.length > 0) {
                            $.each(response.categorias, function (i, cat) {
                                $("#edit_categorie_expense_id").append('<option value="' + cat.id + '">' + cat.name + '</option>');
                            });
                            $("#edit_categorie_expense_id").val(response.expense.categorie_expense_id);
                        } else {
                            $("#edit_categorie_expense_id").append('<option value="">Sin categoría de gastos</option>');
                        }
                        
                        $('#editExpense').modal('show');
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
    $(document).on("submit", "#formEditExpense", function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('expenses.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.status){
                    $('#editExpense').modal('hide');
                    loadExpenses(); 
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
        $(document).on("click", ".expense-eliminar", function(e){
            e.preventDefault();

            let id     = $(this).data("id");
            let description     = $(this).data("description");

            Swal.fire({
                title: "Eliminacion de gasto?",
                text: "Estás seguro de eliminar el gasto: " + description + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "Cancelar"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('expenses.delet') }}",
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
                                loadExpenses(); 
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
