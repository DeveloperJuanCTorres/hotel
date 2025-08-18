@foreach($rooms as $room)
<div class="col-md-4 col-lg-3 col-12">
    <a href="">
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
                    <div class="col-3">
                        <div class="icon-big text-center">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="col-9 col-stats">
                        <div class="numbers">
                            <p class="card-category">{{$room->status}}</p>
                            <h3 class="card-title">-{{$room->numero}}-</h3>
                            <h5>{{$room->type->name}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
@endforeach