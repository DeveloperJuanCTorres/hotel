@foreach($activos as $activo)
<tr>
    <td>{{ $activo->room->numero ?? '-' }}</td>

    <td>{{ $activo->name }}</td>

    <td>{{ $activo->description ?? '-' }}</td>

    <td>
        @if($activo->status === 'operativo')
            <span class="badge bg-success">Operativo</span>
        @elseif($activo->status === 'mantenimiento')
            <span class="badge bg-warning text-dark">Mantenimiento</span>
        @else
            <span class="badge bg-danger">Baja</span>
        @endif
    </td>

    <td class="text-center">
        @if($activo->active)
            <span class="badge bg-primary">Sí</span>
        @else
            <span class="badge bg-secondary">No</span>
        @endif
    </td>

    <td class="text-center">
        <div class="dropdown">
            <button class="btn btn-primary btn-sm" type="button"
                data-bs-toggle="dropdown">
                Opciones <i class="fas fa-angle-down ps-1"></i>
            </button>

            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item activo-edit"
                        href="javascript:void(0);"
                        data-id="{{ $activo->id }}">
                        <i class="bi bi-pencil-square me-2"></i> Editar
                    </a>
                </li>

                <li>
                    <a class="dropdown-item activo-eliminar"
                        href="javascript:void(0);"
                        data-id="{{ $activo->id }}"
                        data-name="{{ $activo->name }}">
                        <i class="bi bi-trash me-2"></i> Eliminar
                    </a>
                </li>
            </ul>
        </div>
    </td>
</tr>
@endforeach
