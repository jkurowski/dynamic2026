<div class="container invest-card-list text-secondary property-list-item @if($p->status == 3) property-list-item-sold @endif">
    <div class="row">
        <div class="col-6 half-1">
            <div class="row">
                <div class="col-2 d-flex align-items-center property-list-item-col-1">
                    <div class="property-list-item-name">
                        <h2 class="h4 lh-1 mb-2">{{ $p->name }}</h2>
                        <span class="mb-0 lh-1 d-block">{{ $p->investment->name }}</span>
                    </div>
                </div>
                <div class="@if($p->investment->status != 3) col-4 @else col-8 @endif d-flex align-items-center property-list-item-col-2">
                    <div class="row w-100">
                        <div class="col-12">
                            <div class="row">
                                <div class="d-flex d-flex align-items-center gap-2 property-list-item-info property-list-item-city">
                                    <img src="{{ asset('img/location.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="13" height="13"> {{ $p->investment->city->name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center gap-2 property-list-item-info">
                                    <img src="{{ asset('img/tile.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="13" height="13"> {{ $p->floor->name }}
                                </div>
                                <div class="col-4 d-flex align-items-center gap-2 property-list-item-info p-0">
                                    <img src="{{ asset('img/blueprint.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="13" height="13"> {{ $p->area }} m<sup>2</sup>
                                </div>
                                <div class="col-4 d-flex align-items-center gap-2 property-list-item-info p-0">
                                    <img src="{{ asset('img/rooms.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="13" height="13"> {{ $p->rooms }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($p->investment->status != 3)
                <div class="col-2 property-list-item-col-3 d-flex align-items-center">

                    <div>
                        @if($p->price_brutto && $p->status == 1 && !$p->highlighted)
                            <p class="h3 lh-1 mb-0">@money($p->price_brutto)</p>
                            <span class="d-block small">@money(($p->price_brutto / $p->area)) / m<sup>2</sup></span>
                        @endif
                        @if($p->price_brutto && $p->status == 1 && $p->highlighted)
                            <p class="h3 lh-1 mb-0 promo-price">@money($p->promotion_price)</p>
                            <span class="d-block small promo-price">@money(($p->promotion_price / $p->area)) / m<sup>2</sup></span>
                        @endif
                        @if($p->status <> 1)
                            <p class="h3 lh-1 mb-0 promo-price">&nbsp;</p>
                            <span class="d-block small promo-price">&nbsp;</span>
                        @endif
                    </div>

                </div>
                @endif
                @if($p->investment->status != 3)
                <div class="col-2 d-flex align-items-center property-list-item-status property-list-item-col-4">

                    <div class="w-100 text-center">
                        @if($p->status == 1 && $p->highlighted)
                            <p class="text-highlighted text-uppercase fw-bold fs-5 mb-0 lh-1">Promocja</p>
                        @endif
                        @if ($p->status == 1 && !$p->highlighted)
                            <p class="text-success text-uppercase fw-bold fs-5 mb-0 lh-1">Dostępny</p>
                        @endif
                        @if ($p->status == 2)
                            <p class="text-warning text-uppercase fw-bold fs-5 mb-0 lh-1">Rezerwacja</p>
                        @endif
                        @if ($p->status == 3)
                            <p class="text-danger text-uppercase fw-bold fs-5 mb-0 lh-1">Sprzedany</p>
                        @endif
                            @if(isset($sort) && str_contains($sort, 'views_desc') && $index < 10)
                                <p><small><i>Wizyt: {{ $p->views }}</i></small></p>
                            @endif
                    </div>
                </div>
                @endif
                <div class="col-2 d-flex align-items-center property-list-item-col-5">
                    @if(Route::currentRouteName() === 'clipboard.index')
                        <button id="addToFav" class="btn btn-primary btn-with-icon text-nowrap pe-3 ps-3" data-id="{{$p->id}}">USUŃ</button>
                    @else
                        @if ($p->status == 1)
                        <button class="btn btn-primary text-nowrap list-fav d-flex align-items-center justify-content-center" data-id="{{$p->id}}">
                            <svg width="26px" height="26px" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 5.00005C7.01165 5.00082 6.49359 5.01338 6.09202 5.21799C5.71569 5.40973 5.40973 5.71569 5.21799 6.09202C5 6.51984 5 7.07989 5 8.2V17.8C5 18.9201 5 19.4802 5.21799 19.908C5.40973 20.2843 5.71569 20.5903 6.09202 20.782C6.51984 21 7.07989 21 8.2 21H15.8C16.9201 21 17.4802 21 17.908 20.782C18.2843 20.5903 18.5903 20.2843 18.782 19.908C19 19.4802 19 18.9201 19 17.8V8.2C19 7.07989 19 6.51984 18.782 6.09202C18.5903 5.71569 18.2843 5.40973 17.908 5.21799C17.5064 5.01338 16.9884 5.00082 16 5.00005M8 5.00005V7H16V5.00005M8 5.00005V4.70711C8 4.25435 8.17986 3.82014 8.5 3.5C8.82014 3.17986 9.25435 3 9.70711 3H14.2929C14.7456 3 15.1799 3.17986 15.5 3.5C15.8201 3.82014 16 4.25435 16 4.70711V5.00005M12 11V17M9 14H15" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        @endif
                    @endif

                    @if($p->investment->type == 1)
                        <a class="btn btn-primary btn-with-icon text-nowrap w-100 @if(Route::currentRouteName() === 'clipboard.index') pe-2 ps-2 ms-2 @endif" href="{{ route('developro.building.floor.property', [
                            $p->investment->slug,
                            $p->building,
                            Str::slug($p->building->name),
                            $p->floor,
                            Str::slug($p->floor->name),
                            $p,
                            Str::slug($p->name),
                            number2RoomsName($p->rooms, true),
                            round(floatval($p->area), 2).'-m2'
                        ]) }}">
                    @elseif($p->investment->type == 2)
                        <a class="btn btn-primary btn-with-icon text-nowrap w-100 @if(Route::currentRouteName() === 'clipboard.index') pe-2 ps-2 ms-2 @endif" href="{{ route('developro.property', [
                            $p->investment->slug,
                            $p->floor,
                            Str::slug($p->floor->name),
                            $p,
                            Str::slug($p->name),
                            number2RoomsName($p->rooms, true),
                            round(floatval($p->area), 2).'-m2'
                        ]) }}">
                    @else
                        <a class="btn btn-primary btn-with-icon text-nowrap w-100 @if(Route::currentRouteName() === 'clipboard.index') pe-2 ps-2 ms-2 @endif" href="#">
                    @endif
                            SPRAWDŹ
                        </a>

                </div>
            </div>
        </div>
        <div class="col-6 half-2">
            @if($p->file)
                <picture>
                    @if($p->file_webp)
                        <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$p->file_webp) }}">
                    @endif
                    <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$p->file) }}">
                    <img src="{{ asset('/investment/property/thumbs/'.$p->file) }}" alt="{{$p->name}}" loading="lazy" decoding="async" class="w-100 h-100 object-fit-contain">
                </picture>
            @endif
        </div>
    </div>
</div>
