@foreach($suppliers as $supplier)
    <tr>
        <td>{{$supplier->tipo_doc}}</td>
        <td>{{$supplier->numero_doc}}</td>
        <td>{{$supplier->name}}</td>
        <td>{{$supplier->address}}</td>
        <td>{{$supplier->email}}</td>
        <td>{{$supplier->phone}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" style="border-radius: 50px;width: 30px; height: 30px;" type="button" id="dropdownMenuButton{{ $supplier->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $supplier->id }}">
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-eye me-2"></i> Ver
                        </a>
                    </li> -->
                    <li>
                        <a class="dropdown-item supplier-edit" href="javascript:void(0);"
                            data-id="{{ $supplier->id }}">
                            <i class="bi bi-pencil-square me-2"></i> Editar
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item supplier-eliminar" href="javascript:void(0);"
                            data-id="{{ $supplier->id }}"
                            data-name="{{ $supplier->name }}">
                            <i class="bi bi-pencil-square me-2"></i> Eliminar
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach