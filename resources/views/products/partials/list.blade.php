@foreach($products as $product)
    <tr>
        <td>
            <img src="{{asset("storage/$product->image")}}" width="50" alt="">
        </td>
        <td>{{$product->name}}</td>
        <td>{{$product->taxonomy->name}}</td>
        <td>{{$product->unit->name}}</td>
        <td>S/. {{$product->price}}</td>
        <td>{{$product->stock}}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" type="button" id="dropdownMenuButton{{ $product->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    Opciones <i class="fas fa-angle-down" style="padding-left: 5px;"></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $product->id }}">
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bi bi-eye me-2"></i> Ver
                        </a>
                    </li> -->
                    <li>
                        <a class="dropdown-item product-edit" href="javascript:void(0);"
                            data-id="{{ $product->id }}">
                            <i class="bi bi-pencil-square me-2"></i> Editar
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item product-eliminar" href="javascript:void(0);"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}">
                            <i class="bi bi-pencil-square me-2"></i> Eliminar
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach