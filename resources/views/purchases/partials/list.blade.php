@foreach($purchases as $purchase)
    <tr>
        <td>{{$purchase->date}}</td>
        <td>{{$purchase->referencia}}</td>
        <td>{{$purchase->paymethod->name}}</td>
        <td>{{ number_format($purchase->total, 2) }}</td>
        <td>{{$purchase->status}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" type="button" id="dropdownMenuButton{{ $purchase->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $purchase->id }}">
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-eye me-2"></i> Ver
                        </a>
                    </li> -->
                    <li>
                        <a class="dropdown-item" href="{{ route('purchases.edit', $purchase->id) }}">
                            <i class="bi bi-pencil-square me-2"></i> Editar
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item purchase-eliminar" href="javascript:void(0);"
                            data-id="{{ $purchase->id }}"
                            data-name="{{ $purchase->referencia }}">
                            <i class="bi bi-pencil-square me-2"></i> Eliminar
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach