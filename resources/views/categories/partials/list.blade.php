@foreach($categories as $category)
    <tr>
        <td>{{$category->id}}</td>
        <td>{{$category->name}}</td>
        <td>{{$category->description}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" style="border-radius: 50px;width: 30px; height: 30px;" type="button" id="dropdownMenuButton{{ $category->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $category->id }}">
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-eye me-2"></i> Ver
                        </a>
                    </li> -->
                    <li>
                        <a class="dropdown-item category-edit" href="javascript:void(0);"
                            data-id="{{ $category->id }}">
                            <i class="bi bi-pencil-square me-2"></i> Editar
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item category-eliminar" href="javascript:void(0);"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}">
                            <i class="bi bi-pencil-square me-2"></i> Eliminar
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach