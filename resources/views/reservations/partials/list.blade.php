@foreach($reservations as $reservation)
    <tr>
        <td>{{$reservation->contact->name}}</td>
        <td>{{$reservation->room->numero}}</td>
        <td>{{$reservation->fecha_inicio . ' - ' . $reservation->hora_inicio}}</td>
        <td>{{$reservation->fecha_fin . ' - ' . $reservation->hora_fin}}</td>
        <td>{{$reservation->total}}</td>
        <td>{{$reservation->saldo}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" type="button" id="dropdownMenuButton{{ $reservation->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $reservation->id }}">
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-eye me-2"></i> Ver
                        </a>
                    </li> -->
                    <li>
                        <a class="dropdown-item reservation-edit" href="javascript:void(0);"
                            data-id="{{ $reservation->id }}">
                            <i class="bi bi-pencil-square me-2"></i> Editar
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item reservation-eliminar" href="javascript:void(0);"
                            data-id="{{ $reservation->id }}"
                            data-name="{{ $reservation->contact->name }}">
                            <i class="bi bi-pencil-square me-2"></i> Eliminar
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach