<!-- Custom template | don't include it in your project! -->
<div class="custom-template">
    <div class="title">Settings</div>
    <div class="custom-content">
        <div class="switcher">
        <div class="switch-block">
            <h4>Logo Header</h4>
            <div class="btnSwitch">
            <button
                type="button"
                class="selected changeLogoHeaderColor"
                data-color="dark"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="blue"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="purple"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="light-blue"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="green"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="orange"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="red"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="white"
            ></button>
            <br />
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="dark2"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="blue2"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="purple2"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="light-blue2"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="green2"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="orange2"
            ></button>
            <button
                type="button"
                class="changeLogoHeaderColor"
                data-color="red2"
            ></button>
            </div>
        </div>
        <div class="switch-block">
            <h4>Navbar Header</h4>
            <div class="btnSwitch">
            <button
                type="button"
                class="changeTopBarColor"
                data-color="dark"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="blue"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="purple"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="light-blue"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="green"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="orange"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="red"
            ></button>
            <button
                type="button"
                class="selected changeTopBarColor"
                data-color="white"
            ></button>
            <br />
            <button
                type="button"
                class="changeTopBarColor"
                data-color="dark2"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="blue2"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="purple2"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="light-blue2"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="green2"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="orange2"
            ></button>
            <button
                type="button"
                class="changeTopBarColor"
                data-color="red2"
            ></button>
            </div>
        </div>
        <div class="switch-block">
            <h4>Sidebar</h4>
            <div class="btnSwitch">
            <button
                type="button"
                class="changeSideBarColor"
                data-color="white"
            ></button>
            <button
                type="button"
                class="selected changeSideBarColor"
                data-color="dark"
            ></button>
            <button
                type="button"
                class="changeSideBarColor"
                data-color="dark2"
            ></button>
            </div>
        </div>
        </div>
    </div>
    <div class="custom-toggle">
        <i class="icon-settings"></i>
    </div>
</div>
<!-- End Custom template -->

@include('utils.apertura')
@include('boxes.closure')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function () {
        $("#btnApertura").on("click", function () {
        // AJAX para traer cajas
            $.ajax({
                url: "{{ route('opening.form.data') }}", // <-- crea esta ruta en Laravel
                type: "GET",
                dataType: "json",
                success: function (response) {
                // Limpiar selects
                $("#boxe_id").empty();
                console.log(response);

                // Llenar cajas
                if (response.cajas && response.cajas.length > 0) {
                    $.each(response.cajas, function (i, caja) {
                    $("#boxe_id").append('<option value="' + caja.id + '">' + caja.name + '</option>');
                    });
                } else {
                    $("#boxe_id").append('<option value="">Sin cajas</option>');
                }                

                // Mostrar modal SOLO cuando ya tenemos los datos
                $('#createApertura').modal('show');
                },
                error: function () {
                alert("Error al cargar datos del formulario.");
                }
            });
        });
  });
</script>