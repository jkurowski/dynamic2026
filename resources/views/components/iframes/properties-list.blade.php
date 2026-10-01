<div id="roomsList">
    <div class="container-fluid">
        @if ($properties->count() > 0)
            @foreach ($properties as $room)
                <div class="row">
                    @if ($room->price)
                        <span class="ribbon1"><span>Oferta specjalna</span></span>
                    @endif
                    <div class="col col-top">
                      
                        <a href="{{ $getRouteForProperty($investment, $room) }}">
                        <h2>{{ $room->name_list }}<br><span>{{ $room->number }}</span></h2>
                        </a>
                    </div>
                    <div class="col">
                        @if ($room->file)
                            <picture>
                                <source type="image/webp" srcset="/investment/property/list/webp/{{ $room->file_webp }}">
                                <source type="image/jpeg" srcset="/investment/property/list/{{ $room->file }}">
                                <img src="/investment/property/list/{{ $room->file }}" alt="{{ $room->name }}">
                            </picture>
                        @endif
                    </div>
                    <div class="col">
                        <ul class="mb-0 list-unstyled">
                            @if ($room->price && $room->status == 1)
                                <li>cena: <b>@money($room->price)</b></li>
                            @endif
                            <li>pokoje: <b>{{ $room->rooms }}</b></li>
                            <li>pow.: <b>{{ $room->area }} m<sup>2</sup></b></li>
                        </ul>
                    </div>
                    <div class="col justify-content-center">
                        <span class="badge room-list-status-{{ $room->status }}">{{ roomStatus($room->status) }}</span>
                    </div>
                    <div class="col justify-content-end col-list-btn">
                        <a href="{{ $getRouteForProperty($investment, $room) }}" class="bttn">ZOBACZ</a>
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
