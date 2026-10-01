<div id="roomsList">
    <div class="container-fluid">
        @if($properties->count() > 0)
            @foreach($properties as $room)
                <div class="row">
                    @if($room->price_brutto)
                        <span class="ribbon1"><span>Oferta specjalna</span></span>
                    @endif
                    <div class="col col-top">

                        @if($investment->type == 2)
                            <a href="{{route('developro.property', ['slug' => $investment->slug, 'floor' => $room->floor_id, 'property' => $room->id])}}">
                                <h2>{{$room->name_list}}<br><span>{{$room->number}}</span></h2>
                            </a>
                        @endif

                        @if($investment->type == 3)
                            <a href="{{route('developro.house', ['slug' => $investment->slug, 'property' => $room->id])}}">
                                <h2>{{$room->name_list}}<br><span>{{$room->number}}</span></h2>
                            </a>
                        @endif
                    </div>
                    <div class="col">
                        @if($room->file)
                            <picture>
                                <source type="image/webp" srcset="/investment/property/list/webp/{{$room->file_webp}}">
                                <source type="image/jpeg" srcset="/investment/property/list/{{$room->file}}">
                                <img src="/investment/property/list/{{$room->file}}" alt="{{$room->name}}">
                            </picture>
                        @endif
                    </div>
                    <div class="col">
                        <ul class="mb-0 list-unstyled">
                            @if($room->price_brutto && $room->status == 1)
                                <li>cena: <b>@money($room->price_brutto)</b></li>
                            @endif
                            @if($room->type == 1)
                            <li>pokoje: <b>{{$room->rooms}}</b></li>
                            @endif
                            <li>pow.: <b>{{$room->area}} m<sup>2</sup></b></li>
                        </ul>
                    </div>
                    <div class="col justify-content-center">
                        <span class="badge room-list-status-{{ $room->status }}">{{ roomStatus($room->status) }}</span>
                    </div>
                    <div class="col justify-content-end col-list-btn">
                        @if($investment->type == 2)
                            <a href="{{route('developro.property', ['slug' => $investment->slug, 'floor' => $room->floor_id, 'property' => $room->id])}}" class="bttn">ZOBACZ</a>
                        @endif

                        @if($investment->type == 3)
                            <a href="{{route('developro.house', ['slug' => $investment->slug, 'property' => $room->id])}}" class="bttn">ZOBACZ</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-12 text-center">
                    <b>Brak wyników</b>
                </div>
            </div>
        @endif
    </div>
</div>
