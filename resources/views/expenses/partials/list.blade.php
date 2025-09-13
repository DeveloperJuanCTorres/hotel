@foreach($expenses as $expense)
    <tr>
        <td>{{$expense->date}}</td>
        <td>{{$expense->catexpense->name}}</td>
        <td>{{$expense->description}}</td>        
        <td>S/. {{$expense->amount}}</td>
        <td>{{$expense->referencia}}</td>
        <td>{{$expense->paymethod->name}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" type="button" id="dropdownMenuButton{{ $expense->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    Opciones <i class="fas fa-angle-down" style="padding-left: 5px;"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $expense->id }}">
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-eye me-2"></i> Ver
                        </a>
                    </li> -->
                    <li>
                        <a class="dropdown-item expense-edit" href="javascript:void(0);"
                            data-id="{{ $expense->id }}">
                            <i class="bi bi-pencil-square me-2"></i> Editar
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item expense-eliminar" href="javascript:void(0);"
                            data-id="{{ $expense->id }}"
                            data-description="{{ $expense->description }}">
                            <i class="bi bi-pencil-square me-2"></i> Eliminar
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach