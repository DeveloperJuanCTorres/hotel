@foreach($rooms as $room)
<div class="col-md-4 col-lg-3 col-12">
    <a href="javascript:void(0);"
        class="room-link" 
        data-id="{{ $room->id }}" 
        data-status="{{ $room->status }}" 
        data-numero="{{ $room->numero }}"
        data-type="{{ $room->type->name }}"
        data-description="{{ $room->description }}"
        data-price="{{ $room->price }}">

        @if($room->status == 'DISPONIBLE')
        <div class="card card-stats card-primary card-round">
        @elseif($room->status == 'OCUPADO')
        <div class="card card-stats card-danger card-round">
        @elseif($room->status == 'RESERVADO')
        <div class="card card-stats card-success card-round">
        @elseif($room->status == 'LIMPIEZA')
        <div class="card card-stats card-black card-round">
        @endif
            <div class="card-body">
                <div class="row">
                    <!-- <div class="col-9 col-stats"> -->
                        <div class="numbers w-100">
                            <!-- <p class="card-category">{{$room->status}}</p> -->
                            <h5 class="card-title d-flex justify-content-between align-items-center w-100">
                                <span>{{$room->numero}}</span>
                                <span>S/. {{$room->price}}</span>
                            </h5>
                            <h5 class="w-100">{{$room->status}}</h5>
                        </div>
                        <hr>
                    <!-- </div>
                    <div class="col-3"> -->
                        <div class="text-center d-flex justify-content-between align-items-center w-100">
                            <span style="font-size: 12px;">{{$room->type->name}}</span>
                            <div>
                                @for($i=0;$i<$room->bed; $i++)
                                <i class="fas fa-bed" style="font-size: 20px;"></i>
                                @endfor
                            </div>
                        </div>
                    <!-- </div>                     -->
                </div>
            </div>
        </div>
    </a>
</div>
@endforeach