@foreach($boxes as $box)
    <tr>
        <td>{{$box->box->name}}</td>
        <td>{{$box->user->name}}</td>
        <td>{{$box->monto_inicial}}</td>
        <td>{{$box->fecha_apertura}}</td>
        <td>{{$box->monto_final}}</td>
        <td>{{$box->fecha_cierre}}</td>
        <td>{{$box->status}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" type="button" id="dropdownMenuButton{{ $box->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    Opciones<i class="fas fa-angle-down" style="padding-left: 5px;"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $box->id }}">
                    <li>
                        <a class="dropdown-item box_view" href="javascript:void(0);"
                            data-id="{{ $box->id }}">
                            <i class="bi bi-eye me-2"></i> Ver
                        </a>
                    </li>                    
                </ul>
            </div>
        </td>
    </tr>
@endforeach