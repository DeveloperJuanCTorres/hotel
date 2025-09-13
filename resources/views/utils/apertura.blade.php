<!-- Modal -->
<div class="modal fade" id="createApertura" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-ml">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="staticBackdropLabel">Aperturar caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAperturar">
                    <div class="row">
                        <div class="col-lg-12 col-12 mb-3">
                            <label for="boxe_id" class="form-label">Caja</label>
                            <select id="boxe_id" name="boxe_id" class="form-control form-select">
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="col-lg-6 col-12 mb-3">
                            <label for="monto_inicial" class="form-label">Monto inicial</label>
                            <input type="number" class="form-control" id="monto_inicial" name="monto_inicial">
                        </div>

                        <div class="col-lg-6 col-12 mb-3">
                            <label for="categoria_id" class="form-label">Fecha</label>
                            <input type="datetime-local" class="form-control" id="fecha_apertura" name="fecha_apertura" value="{{ now()->format('Y-m-d\TH:i') }}" readonly>
                        </div>                       
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formAperturar" class="btn btn-primary aperturar">Aperturar caja</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $(".aperturar").on("click", function (e) {
            e.preventDefault();

            let formData = new FormData(document.getElementById("formAperturar"));

            $.ajax({
                url: "{{ route('openings.store') }}",
                type: "POST",
                data: formData,
                contentType: false, // necesario para enviar archivos
                processData: false, // necesario para enviar archivos
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status) {
                        $("#createApertura").modal("hide");
                        $("#formAperturar")[0].reset();
                        Swal.fire({
                            title: "Registro exitoso!",
                            text: response.msg,
                            icon: "success",
                            confirmButtonText: "OK",
                            allowOutsideClick: false
                            }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // refresca la página al dar click en OK
                            }
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








