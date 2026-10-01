<div class="p-4">
    <div id="relatedProperty">
        <div class="row">
            <div class="col-5">
                <h2 class="mb-3">{{ $property->name }}</h2>
                <ul class="mb-0 list-unstyled">
                    @if($property->price_brutto)
                        <li class="d-flex">Cena: <span class="ms-auto"><strong>@money($property->price_brutto)</strong></span></li>
                        <li class="d-flex">Cena za m2 <span class="ms-auto"><strong>@money(($property->price_brutto / $property->area))</strong></span></li>
                    @endif
                    @if($property->building)
                        <li class="d-flex">Budynek: <span class="ms-auto"><strong>{{ $property->building->name }}</strong></span></li>
                    @endif
                    @if($property->floor)
                        <li class="d-flex">Piętro: <span class="ms-auto"><strong>{{ $property->floor->name }}</strong></span></li>
                    @endif
                    <li class="d-flex">Powierzchnia: <span class="ms-auto"><strong>{{ $property->area }} m<sup>2</sup></strong></span></li>
                </ul>
            </div>
            <div class="col-7">
                @if($property->floor && $property->floor->file)
                    <div id="relatedFloor" class="mb-4">
                        <div class="position-relative">
                            @if($property->floor->file_webp)
                                <img src="{{ asset('/investment/floor/webp/'.$property->floor->file_webp) }}" alt="{{$property->floor->name}}" id="invesmentplan" usemap="#invesmentplan" class="img-fluid w-100">
                            @else
                                <img src="{{ asset('/investment/floor/'.$property->floor->file) }}" alt="{{$property->floor->name}}" id="invesmentplan" usemap="#invesmentplan" class="img-fluid w-100">
                            @endif
                            <map name="invesmentplan">
                                @if($property->html)
                                    @php
                                        $cords = cords($property->html);
                                    @endphp
                                    <area
                                        shape="poly"
                                        href=""
                                        data-item="{{$property->id}}"
                                        title="{{$property->name}}"
                                        alt="{{$property->slug}}"
                                        coords="{{ $cords }}"
                                    >
                                @elseif($property->cords)
                                    @php
                                        $jsonCords = json_decode($property->cords);
                                        $points = [];
                                        if($jsonCords) {
                                            if(isset($jsonCords->points)) {
                                                foreach($jsonCords->points as $p) {
                                                    $points[] = $p->x . ',' . $p->y;
                                                }
                                            } elseif(is_array($jsonCords)) {
                                                foreach($jsonCords as $p) {
                                                    if(isset($p->x) && isset($p->y)) {
                                                        $points[] = $p->x . ',' . $p->y;
                                                    }
                                                }
                                            }
                                        }
                                        $cords = implode(',', $points);
                                    @endphp
                                    @if($cords)
                                        <area
                                            shape="poly"
                                            href=""
                                            data-item="{{$property->id}}"
                                            title="{{$property->name}}"
                                            alt="{{$property->slug}}"
                                            coords="{{ $cords }}"
                                        >
                                    @endif
                                @endif
                            </map>
                        </div>
                    </div>
                @endif

{{--                @if($property->file)--}}
{{--                    <div id="main-image" class="d-block">--}}
{{--                        <picture>--}}
{{--                            @if($property->file_webp)--}}
{{--                                <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$property->file_webp) }}">--}}
{{--                            @endif--}}
{{--                            <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$property->file) }}">--}}
{{--                            <img src="{{ asset('/investment/property/thumbs/'.$property->file) }}" alt="{{$property->name}}" loading="eager" class="img-fluid">--}}
{{--                        </picture>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>
        </div>
    </div>
</div>
