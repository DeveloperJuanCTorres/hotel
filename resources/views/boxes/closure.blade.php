<!-- Modal cierre de caja -->
<div class="modal fade" id="modalCierreCaja" tabindex="-1" aria-labelledby="modalCierreCajaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalCierreCajaLabel">Detalle de Cierre de Caja</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <h6>Ingresos por método de pago</h6>
        <table class="table table-sm table-bordered">
          <thead>
            <tr>
              <th>Método</th>
              <th>Ingresos</th>
            </tr>
          </thead>
          <tbody id="detalleIngresos"></tbody>
        </table>

        <h6>Egresos por método de pago</h6>
        <table class="table table-sm table-bordered">
          <thead>
            <tr>
              <th>Método</th>
              <th>Egresos</th>
            </tr>
          </thead>
          <tbody id="detalleEgresos"></tbody>
        </table>

        <h6 class="mt-3">Resumen</h6>
        <ul class="list-group">
          <li class="list-group-item">Monto inicial: <strong id="montoInicial"></strong></li>
          <li class="list-group-item">Total ingresos: <strong id="totalIngresos"></strong></li>
          <li class="list-group-item">Total egresos: <strong id="totalEgresos"></strong></li>
          <li class="list-group-item">Balance final: <strong id="balanceFinal"></strong></li>
        </ul>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnConfirmarCierre">Confirmar Cierre</button>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('btnCerrarCaja').addEventListener('click', function () {
        let btn = this;
        let aperturaId = this.getAttribute('data-apertura');

        // Guardar contenido original para restaurar
        let originalHtml = btn.innerHTML;

        // Mostrar ícono de cargando (spinner Bootstrap)
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
        btn.disabled = true;

        fetch(`/caja/${aperturaId}/preview-cierre`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    console.error("Error desde backend:", data.msg);
                    Swal.fire("Error", data.msg, "error");
                    return;
                }

                cierrePreview = data; // ✅ Guardamos el preview para usarlo luego

                // ✅ Validamos que detalle exista
                const detalles = Array.isArray(data.detalle) ? data.detalle : [];

                // Llenar tabla ingresos
                let ingresosHtml = '';
                detalles.forEach(d => {
                    ingresosHtml += `<tr><td>${d.metodo}</td><td>${d.ingresos}</td></tr>`;
                });
                document.getElementById('detalleIngresos').innerHTML = ingresosHtml;

                // Llenar tabla egresos
                let egresosHtml = '';
                detalles.forEach(d => {
                    egresosHtml += `<tr><td>${d.metodo}</td><td>${d.egresos}</td></tr>`;
                });
                document.getElementById('detalleEgresos').innerHTML = egresosHtml;

                // Totales (si existen)
                if (data.totales) {
                    document.getElementById('montoInicial').innerText = data.totales.monto_inicial;
                    document.getElementById('totalIngresos').innerText = data.totales.total_ingresos;
                    document.getElementById('totalEgresos').innerText = data.totales.total_egresos;
                    document.getElementById('balanceFinal').innerText = data.totales.balance_final;
                }

                // Mostrar modal
                let modal = new bootstrap.Modal(document.getElementById('modalCierreCaja'));
                modal.show();
            })
            .catch(err => {
                console.error("Error en fetch:", err);
                Swal.fire("Error", "No se pudo obtener el cierre de caja", "error");
            })
            .finally(() => {
                // Restaurar botón siempre al finalizar
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
    });

    // Confirmar cierre
    document.getElementById('btnConfirmarCierre').addEventListener('click', function () {
        let aperturaId = document.getElementById('btnCerrarCaja').getAttribute('data-apertura');

        if (!cierrePreview) {
            Swal.fire("Error", "Primero debes previsualizar el cierre.", "error");
            return;
        }

        fetch(`/caja/${aperturaId}/cerrar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                detalle: cierrePreview.detalle, // ✅ usamos la variable global
                totales: cierrePreview.totales
            })
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.status) {
                window.open(resp.printUrl, '_blank'); // abre impresión en nueva pestaña
            } else {
                Swal.fire("Error", resp.msg, "error");
            }
        })
        .catch(err => {
            console.error("Error en fetch:", err);
            Swal.fire("Error", "No se pudo cerrar la caja", "error");
        });
    });
</script>