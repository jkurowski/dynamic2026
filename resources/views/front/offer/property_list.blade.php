<div id="roomsList" class="border-0 mt-5">
    <div class="container">
        @foreach($properties as $room)
            <div class="row">
                @if($room->price)
                    <span class="ribbon1"><span>Oferta specjalna</span></span>
                @endif
                <div class="col col-top">
                    <a href="{{ $room->url }}">
                        <h2>{{$room->name_list}}<br><span>{{$room->number}}</span></h2>
                    </a>
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
                        @if($room->price)
                            <li>cena: <b>@money($room->price)</b></li>
                        @endif
                        <li>pokoje: <b>{{$room->rooms}}</b></li>
                        <li>pow.: <b>{{$room->area}} m<sup>2</sup></b></li>
                    </ul>
                </div>
                <div class="col justify-content-center">
                    <span class="badge room-list-status-{{ $room->status }}">{{ roomStatus($room->status) }}</span>
                </div>
                <div class="col justify-content-end col-list-btn">
                    <a href="{{ $room->url }}" class="bttn">ZOBACZ</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
