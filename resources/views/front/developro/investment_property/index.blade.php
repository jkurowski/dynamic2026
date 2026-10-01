@extends('layouts.page', ['body_class' => 'investments'])

@php
    if(!empty($investment->meta_title)) {
        $seoTitle = $investment->meta_title;
    }
    elseif(!empty($page->meta_title)) {
        $seoTitle = $page->meta_title;
    }
    else {
        $seoTitle = settings()->get("page_title") . ' - ' . $page->title . ' - ' . $investment->name .' - '. $property->name;
    }

    if(!empty($investment->meta_description)) {
        $seoDescription = $investment->meta_description;
    }
    elseif(!empty($page->meta_description)) {
        $seoDescription = $page->meta_description;
    }
    else {
        $seoDescription = settings()->get("page_description");
    }
@endphp

@section('meta_title', $page->title)
@section('seo_title', $seoTitle)
@section('seo_description', $seoDescription)
@if($investment->status == 3)
    @section('seo_robots', 'noindex, nofollow')
@endif

@section('content')
    <main>
        <section class="position-relative page-hero-section page-hero-section-small">
            <div class="position-absolute top-0 start-0 w-100 h-100">
                @if($investment->file_header)
                    <img src="{{ asset('investment/header/'.$investment->file_header) }}" alt="" width="1920" height="386" loading="eager" decoding="async" class="w-100 h-100 object-fit-cover">
                    <div style="position: absolute;opacity: 0.7;width: 100%;height: 100%;top: 0;left: 0;background-image: linear-gradient(#000, rgba(255, 255, 255, 0) {{ $investment->gradient_header ?: '100%' }});"></div>
                @else
                    <div style="position: absolute;width: 100%;height: 100%;top: 0;left: 0;background:#052748"></div>
                @endif
            </div>
            <div class="container isolation-isolate">
                <div class="row row-gap-10">
                    <div class="col-12">
                        <nav aria-label="breadcrumb small text-white" data-aos="fade" class="aos-init aos-animate">
                            <ol class="breadcrumb opacity-50">
                                <li class="breadcrumb-item">
                                    <a href="/" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Strona główna</a>
                                </li>
                                <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                                    <a href="{{ route('developro.show', $investment->slug) }}" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">{{ $investment->name }}</a>
                                </li>
                                @isset($building->name)
                                    <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                                        <a href="{{route('developro.building', [$investment->slug, $building, 'buildingSlug' => Str::slug($building->name)])}}" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">{{ $building->name }}</a>
                                    </li>
                                @endisset
                                <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                                    @if($investment->type == 1)
                                        <a href="{{route('developro.building.floor', [$investment->slug, $building, 'buildingSlug' => Str::slug($building->name), $floor, 'floorSlug' => Str::slug($floor->name)])}}" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">
                                            {{ $floor->name }}
                                        </a>
                                    @endif
                                    @if($investment->type == 2)
                                        <a href="{{route('developro.floor', [$investment->slug, $floor, 'floorSlug' => Str::slug($floor->name)])}}" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">
                                            {{ $floor->name }}
                                        </a>
                                    @endif
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3 text-white text-center">
                        @isset($investment->name)
                            <h1 class="h2 mb-3 text-uppercase" data-aos="fade-up">{{ $investment->name }}</h1>
                        @endisset
                        <p class="text-pretty" data-aos="fade-up" data-aos-delay="200">
                            @isset($building->name)
                                {{ $building->name }} -
                            @endisset
                            {{ $floor->name }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <div id="mainContent"></div>
        <section class="sticky-top py-0 bg-white sticky-top-menu">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @include('front.investments.submenu', ['menuIds' => $investment->menu])
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-40 pb-0">
            <div class="container">
                <div class="row row-gap-3 justify-content-between small">
                    <div class="col-6 col-sm-4">
                        @if($investment->type == 1 && $prev)
                        <a href="{{ route('developro.building.floor.property', [
                                                            $investment->slug,
                                                            $building,
                                                            Str::slug($building->name),
                                                            $prev->floor,
                                                            Str::slug($floor->name),
                                                            $prev,
                                                            Str::slug($prev->name),
                                                            number2RoomsName($prev->rooms, true),
                                                            round(floatval($prev->area), 2).'-m2'
                                                        ]) }}" class="btn btn-primary  px-3 min-w-max-content flex-fill d-inline-flex align-items-center gap-1">
                            <svg class="me-2 me-sm-3 me-md-4" xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062">
                                <path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(362.073 -672.938) rotate(180)" fill="currentColor" />
                            </svg>
                            Poprzednie
                        </a>
                        @endif

                        @if($investment->type == 2 && $prev)
                        <a href="{{ route('developro.property', [$investment->slug, $floor, Str::slug($floor->name), $prev, Str::slug($prev->name), number2RoomsName($prev->rooms, true), round(floatval($prev->area), 2).'-m2']) }}" class="btn btn-primary  px-3 min-w-max-content flex-fill d-inline-flex align-items-center gap-1">
                            <svg class="me-2 me-sm-3 me-md-4" xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062">
                                <path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(362.073 -672.938) rotate(180)" fill="currentColor" />
                            </svg>
                            Poprzednie
                        </a>
                        @endif
                    </div>
                    <div class="col-12 col-sm-4 text-center order-first order-sm-0">
                        @if($investment->type == 1)
                            <a href="{{route('developro.building.floor', [$investment->slug, $building, 'buildingSlug' => Str::slug($building->name), $floor, 'floorSlug' => Str::slug($floor->name)])}}" class="btn btn-outline-primary" style="--bs-btn-hover-color: var(--bs-white);">
                                Plan piętra
                            </a>
                        @endif
                        @if($investment->type == 2)
                            <a href="{{route('developro.floor', [$investment->slug, $floor, 'floorSlug' => Str::slug($floor->name)])}}" class="btn btn-outline-primary" style="--bs-btn-hover-color: var(--bs-white);">
                                Plan piętra
                            </a>
                        @endif
                    </div>
                    <div class="col-6 col-sm-4 text-end">
                        @if($investment->type == 1 && $next)
                        <a href="{{ route('developro.building.floor.property', [
                                                            $investment->slug,
                                                            $building,
                                                            Str::slug($building->name),
                                                            $next->floor,
                                                            Str::slug($next->name),
                                                            $next,
                                                            Str::slug($next->name),
                                                            number2RoomsName($next->rooms, true),
                                                            round(floatval($next->area), 2).'-m2'
                                                        ]) }}" class="btn btn-primary px-3 min-w-max-content flex-fill d-inline-flex align-items-center  gap-1">
                            Następne
                            <svg class="ms-2 ms-sm-3 ms-md-4" xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062">
                                <path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" />
                            </svg>
                        </a>
                        @endif
                        @if($investment->type == 2 && $next)
                        <a href="{{ route('developro.property', [$investment->slug, $floor, Str::slug($floor->name), $next, Str::slug($next->name), number2RoomsName($next->rooms, true), round(floatval($next->area), 2).'-m2']) }}" class="btn btn-primary  px-3 min-w-max-content flex-fill d-inline-flex align-items-center  gap-1">
                            Następne
                            <svg class="ms-2 ms-sm-3 ms-md-4" xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062">
                                <path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" />
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row row-gap-30">
                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="text-secondary">
                            @if($investment->status != 3)
                                @if($property->highlighted && $property->promotion_price && $property->price_brutto && $property->status == 1)
                                    <div class="promo-info">PROMOCJA</div>
                                @endif
                            @endif

                            <h2 class="mb-0">{{$property->name}}</h2>
                            <p class="text-uppercase fw-900 fs-15 ff-secondary mb-0 lh-1">{{$investment->name}}</p>

                            @if($investment->status != 3)
                                <p class="mb-20"><small>{{ investmentAdvanced($investment->progress) }}</small></p>

                                <?php if ($property->status == 3) : ?>
                                <p class="text-danger text-uppercase fw-bold fs-5">Sprzedany</p>
                                <?php elseif ($property->status == 1) : ?>
                                <p class="text-success text-uppercase fw-bold fs-5">Dostępny</p>
                                <?php else : ?>
                                <p class="text-warning text-uppercase fw-bold fs-5">Rezerwacja</p>
                                <?php endif; ?>

    {{--                            @if($investment->show_prices)--}}
                                    <p class="h4 mb-1 ff-secondary row">
                                        <span class="col-12">
                                            @if($property->price_brutto && $property->status == 1 && !$property->highlighted)
                                                <span class="fs-2 d-block">@money($property->price_brutto)</span>
                                                <span class="fs-14 d-block">@money(($property->price_brutto / $property->area)) / m<sup>2</sup></span>
                                            @endif
                                        </span>
                                    </p>
                                    @if($property->highlighted && $property->promotion_price && $property->price_brutto && $property->status == 1)
                                        <p class="h4 mb-1 ff-secondary row">
                                            <span class="col-6">
                                                <span class="fs-2 d-block">@money($property->promotion_price)</span>
                                                <span class="fs-14 d-block">@money(($property->promotion_price / $property->area)) / m<sup>2</sup></span>
                                            </span>
                                            <span class="col-6 text-end pt-2">
                                                @if($property->price_brutto)
                                                    <span class="text-body-emphasis opacity-50 fs-24 align-middle text-decoration-line-through d-block">@money($property->price_brutto)</span>
                                                    <span class="text-body-emphasis d-block opacity-50 fs-14 align-middle text-decoration-line-through">@money($property->price_brutto / $property->area) / m<sup>2</sup></span>
                                                @endif
                                            </span>

                                            @php
                                                $rabat = $property->price_brutto - $property->promotion_price;
                                            @endphp
                                            @if($property->price_brutto <> $property->promotion_price)
                                                <span class="rabat h4 d-block w-100">Rabat: @money($rabat)</span>
                                            @endif
                                        </p>
                                        @if($property->promotion_end_date && $property->status == 1)
                                        <span class="fs-15 text-black mb-0">Promocja ważna do: {{ $property->promotion_end_date }}</span>
                                        @endif
                                    @endif
                                @auth
                                    @if($property->highlighted)
                                        @if($property->has_price_history)
                                            @php
                                                $lowest_price = $property->lowestPriceLast30Days();
                                            @endphp
                                            @if($lowest_price > 0)
                                                <p class="fs-10 text-black mb-0">Najniższa cena z 30 dni przed obniżką: @money($lowest_price)</p>
                                            @endif
                                        @else
                                            <p class="fs-10 text-black mb-0">Najniższa cena z 30 dni przed obniżką @money($property->price_30)</p>
                                        @endif
                                    @endif
                                @endauth
    {{--                            @endif--}}


                                    @if($property->has_price_history)
                                        <a href="#" class="btn btn-primary btn-with-icon px-3 min-w-max-content flex-fill d-inline-flex align-items-center justify-content-center gap-1 btn-history mt-3" data-id="{{ $property->id }}">Pokaż historię ceny</a>
                                        <div id="modalHistory"></div>
                                    @else
                                        @if($property->status == 1)
                                        <p>Cena nie zmieniła się od: 11.09.2025</p>
                                        @endif
                                    @endif
                            @else
                                @if($investment->show_prices)
                                    <p class="h4 mb-1 ff-secondary row">
                                        <span class="col-12">
                                            @if($property->price_brutto && $property->area)
                                                <span class="fs-4 d-block mt-4">Szacowane ceny od: @money(($property->price_brutto / $property->area)) / m<sup>2</sup></span>
                                            @endif
                                        </span>
                                    </p>
                               @endif
                            @endif

                            <div class="mb-50 mt-4">
                                <table class="text-sm-down-small w-100">
                                    <tbody>
                                    <tr>
                                        <td class="td-with-icon pb-2"><img src="{{ asset('img/tile.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                        <td class="pb-2">Piętro</td>
                                        <td class="text-end pb-2">{{$floor->name}}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-with-icon pb-2"><img src="{{ asset('img/blueprint.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                        <td class="pb-2">Metraż</td>
                                        <td class="text-end pb-2">{{$property->area}} m<sup>2</sup></td>
                                    </tr>
                                    @if($property->type == 1)
                                        <tr>
                                            <td class="td-with-icon pb-2"><img src="{{ asset('img/rooms.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                            <td class="pb-2">Liczba Pokoi</td>
                                            <td class="text-end pb-2">{{$property->rooms}}</td>
                                        </tr>
                                        <tr>
                                            <td class="td-with-icon pb-2"><img src="{{ asset('img/kitchen.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                            <td class="pb-2">Aneks/Kuchnia</td>
                                            <td class="text-end pb-2">{{ kitchenType($property->kitchen) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="td-with-icon pb-2"><img src="{{ asset('img/window.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                            <td class="pb-2">Wystawa okienna</td>
                                            <td class="text-end pb-2">{{ getWindowDirections($property->window) }}</td>
                                        </tr>
                                        @if($property->terrace_area)
                                            <tr>
                                                <td class="td-with-icon pb-2"><img src="{{ asset('img/terrace.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                                <td class="pb-2">Taras</td>
                                                <td class="text-end pb-2">{{$property->terrace_area}} m<sup>2</sup></td>
                                            </tr>
                                        @endif
                                        @if($property->garden_area)
                                            <tr>
                                                <td class="td-with-icon pb-2"><img src="{{ asset('img/shovels.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                                <td class="pb-2">Ogródek</td>
                                                <td class="text-end pb-2">{{$property->garden_area}} m<sup>2</sup></td>
                                            </tr>
                                        @endif
                                        @if($property->balcony_area)
                                            <tr>
                                                <td class="td-with-icon pb-2"><img src="{{ asset('img/shovels.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                                <td class="pb-2">Balkon</td>
                                                <td class="text-end pb-2">{{$property->balcony_area}} m<sup>2</sup></td>
                                            </tr>
                                        @endif
                                        @if($property->loggia_area)
                                            <tr>
                                                <td class="td-with-icon pb-2"><img src="{{ asset('img/shovels.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                                                <td class="pb-2">Loggia</td>
                                                <td class="text-end pb-2">{{$property->loggia_area}} m<sup>2</sup></td>
                                            </tr>
                                        @endif
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                            @if($investment->status != 3)
                                @if ($property->status == 1 && $property->type == 1)
                                    <div class="property-related">

                                        @if($property->history_info)
                                            <p>{!! $property->history_info !!}</p>
                                        @endif

                                        @if($property->relatedProperties->isNotEmpty())
                                            <h5>Przynależne powierzchnie</h5>
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>Nazwa</th>
                                                    <th class="text-center">Powierzchnia</th>
                                                    <th class="text-center">Cena</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($property->relatedProperties as $related)
                                                    <tr>
                                                        <td valign="middle">{{ $related->name }}</td>
                                                        <td class="text-center" valign="middle">{{ $related->area }} m<sup>2</sup></td>
                                                        <td class="text-center" valign="middle">
                                                            @money($related->price_brutto)
                                                        </td>
                                                        <td valign="middle" align="right">
                                                            @if($related->has_price_history)
                                                                <a href="#" class="btn-history" data-id="{{ $related->id }}"><svg class="d-block" width="16px" height="16px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M10.6972,0.468433 C12.354,1.06178 13.7689,2.18485 14.7228,3.66372 C15.6766,5.14258 16.1163,6.89471 15.9736,8.64872 C15.8309,10.4027 15.1138,12.0607 13.9334,13.366 C12.753,14.6712 11.1752,15.5508 9.4443,15.8685 C7.71342,16.1863 5.92606,15.9244 4.35906,15.1235 C2.79206,14.3226 1.53287,13.0274 0.776508,11.4384 C0.539137,10.9397 0.750962,10.343 1.24963,10.1057 C1.74831,9.86829 2.34499,10.0801 2.58236,10.5788 C3.14963,11.7705 4.09402,12.742 5.26927,13.3426 C6.44452,13.9433 7.78504,14.1397 9.08321,13.9014 C10.3814,13.6631 11.5647,13.0034 12.45,12.0245 C13.3353,11.0456 13.8731,9.80205 13.9801,8.48654 C14.0872,7.17103 13.7574,5.85694 13.042,4.74779 C12.3266,3.63864 11.2655,2.79633 10.0229,2.35133 C8.78032,1.90632 7.42568,1.88344 6.1688,2.28624 C5.34644,2.54978 4.59596,2.98593 3.96459,3.5597 L4.69779,4.29291 C5.32776,4.92287 4.88159,6.00002 3.99069,6.00002 L1.77635684e-15,6.00002 L1.77635684e-15,2.00933 C1.77635684e-15,1.11842 1.07714,0.672258 1.70711,1.30222 L2.54916,2.14428 C3.40537,1.3473 4.43126,0.742882 5.55842,0.381656 C7.23428,-0.155411 9.04046,-0.124911 10.6972,0.468433 Z M8,4 C8.55229,4 9,4.44772 9,5 L9,7.58579 L10.7071,9.29289 C11.0976,9.68342 11.0976,10.3166 10.7071,10.7071 C10.3166,11.0976 9.68342,11.0976 9.29289,10.7071 L7,8.41421 L7,5 C7,4.44772 7.44772,4 8,4 Z"/>
                                                                    </svg></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif

                                        @if($property->visitor_related_type != 1)
                                            <div class="property-offer-check">
                                                <p>Dodanie powierzchni dodatkowych służy jedynie orientacyjnej wycenie. Ostateczna oferta oraz warunki zakupu zostaną przedstawione przez przedstawiciela sprzedaży.</p>
                                                <a href="#" class="btn btn-primary btn-with-icon px-3 min-w-max-content flex-fill d-inline-flex align-items-center justify-content-center gap-1 btn-offer" data-id="{{ $property->id }}">Dodaj do oferty</a>
                                                <div id="offerModal"></div>
                                                <table class="table d-none mt-3">
                                                    <thead>
                                                    <tr>
                                                        <th>Nazwa</th>
                                                        <th class="text-center">Powierzchnia</th>
                                                        <th class="text-center">Cena</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="offerList"></tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>

                                    @if($property->highlighted)
                                        <div class="property-summary fs-5 d-flex" data-totalprice="{{ ($property->promotion_price + $property->relatedProperties->sum('price_brutto')) }}">
                                            Cena za całość: <span class="ms-auto"><b class="fw-bold" id="totalDisplay">@money(($property->promotion_price + $property->relatedProperties->sum('price_brutto')))</b></span>
                                        </div>
                                    @else
                                        @if($property->price_brutto)
                                            <div class="property-summary fs-5 d-flex" data-totalprice="{{ ($property->price_brutto + $property->relatedProperties->sum('price_brutto')) }}">
                                                Cena za całość: <span class="ms-auto"><b class="fw-bold" id="totalDisplay">@money(($property->price_brutto + $property->relatedProperties->sum('price_brutto')))</b></span>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                <div class="mb-3"></div>

                                @if($property->priceComponents && $property->status == 1)
                                <div class="row">
                                    <div class="col-12">
                                        <p>Dodatkowe koszty i opcje:</p>
                                        <ul class="price-component mb-0 list-unstyled">
                                            @foreach($property->priceComponents as $priceComponent)
                                                <li>
                                                    {{ $priceComponent->name }}
                                                    <span class="ms-auto text-end">
                                                @if($priceComponent->pivot->value)
                                                            <span><b>@money($priceComponent->pivot->value)</b></span>
                                                        @endif
                                                            <?php if ($priceComponent->pivot->category == 1) : ?>
                                                    <span class="small">Obowiązkowy</span>
                                                <?php elseif ($priceComponent->pivot->category == 2) : ?>
                                                    <span class="small">Opcjonalny</span>
                                                <?php else : ?>
                                                    <span class="small">Zmienny</span>
                                                <?php endif; ?>
                                                </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @endif

                            <div class="mb-3"></div>
                            <div class="d-flex flex-wrap gap-2">
                                @if($investment->status != 3)
                                    <a href="#kontakt" class="btn btn-primary btn-with-icon px-3 min-w-max-content flex-fill d-inline-flex align-items-center justify-content-center gap-1">Zapytaj o ofertę <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" /></svg>
                                    </a>
                                @else
                                    <a href="#kontakt" class="btn btn-primary btn-with-icon px-3 min-w-max-content flex-fill d-inline-flex align-items-center justify-content-center gap-1">Zapytaj o przewidywaną cenę <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" /></svg>
                                    </a>
                                @endif

                                @if($property->file_pdf)
                                    <a href="{{ asset('/investment/property/pdf/'.$property->file_pdf) }}" class="btn btn-primary btn-with-icon d-inline-flex align-items-center gap-1 justify-content-center px-3 min-w-max-content flex-fill" target="_blank">
                                        <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="11.109" height="12.96" viewBox="0 0 11.109 12.96">
                                            <path id="Icon_metro-file-pdf" data-name="Icon metro-file-pdf" d="M13.188,4.952a1.683,1.683,0,0,1,.347.55,1.669,1.669,0,0,1,.145.636V14.47a.691.691,0,0,1-.694.694H3.265a.691.691,0,0,1-.694-.694V2.9A.691.691,0,0,1,3.265,2.2h6.48a1.67,1.67,0,0,1,.636.145,1.683,1.683,0,0,1,.55.347ZM9.977,3.187V5.906H12.7a.79.79,0,0,0-.159-.3L10.273,3.346a.79.79,0,0,0-.3-.159Zm2.777,11.051V6.832H9.745a.691.691,0,0,1-.694-.694V3.129H3.5V14.238h9.257ZM9.036,9.949a5.5,5.5,0,0,0,.608.405,7.177,7.177,0,0,1,.846-.051q1.063,0,1.28.354a.35.35,0,0,1,.014.376.021.021,0,0,1-.007.014l-.014.014v.007q-.043.275-.513.275a2.983,2.983,0,0,1-.832-.145,5.274,5.274,0,0,1-.94-.383,13.023,13.023,0,0,0-2.835.6q-1.107,1.895-1.75,1.895a.421.421,0,0,1-.2-.051l-.174-.087-.043-.036a.3.3,0,0,1-.043-.26,1.571,1.571,0,0,1,.405-.662,3.5,3.5,0,0,1,.955-.7.106.106,0,0,1,.166.043.042.042,0,0,1,.014.029q.376-.615.774-1.425A11.038,11.038,0,0,0,7.5,8.271a5.846,5.846,0,0,1-.221-1.154A2.812,2.812,0,0,1,7.322,6.2q.08-.289.3-.289h.159a.3.3,0,0,1,.253.108.578.578,0,0,1,.065.492.157.157,0,0,1-.029.058.188.188,0,0,1,.007.058v.217a9.471,9.471,0,0,1-.1,1.389A3.659,3.659,0,0,0,9.036,9.949ZM4.871,12.922a3.193,3.193,0,0,0,.991-1.143,4.123,4.123,0,0,0-.633.608A2.4,2.4,0,0,0,4.871,12.922ZM7.749,6.268a2.151,2.151,0,0,0-.014.955q.007-.051.051-.318,0-.022.051-.311a.163.163,0,0,1,.029-.058.021.021,0,0,1-.007-.014.015.015,0,0,0,0-.011.015.015,0,0,1,0-.011.416.416,0,0,0-.094-.26.021.021,0,0,1-.007.014v.014Zm-.9,4.781a10.608,10.608,0,0,1,2.054-.586,1.091,1.091,0,0,1-.094-.069,1.3,1.3,0,0,1-.116-.1,3.831,3.831,0,0,1-.918-1.273,9.665,9.665,0,0,1-.6,1.425q-.217.405-.325.6Zm4.672-.116a1.731,1.731,0,0,0-1.013-.174,2.736,2.736,0,0,0,.9.2.7.7,0,0,0,.13-.007s0-.012-.014-.022Z" transform="translate(-2.571 -2.203)" fill="#fff" />
                                        </svg>

                                        Pobierz kartę
                                        <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062">
                                            <path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" />
                                        </svg>
                                    </a>
                                @endif
                                @if($property->walk_3d)
                                    {!! $property->walk_3d !!}
                                @endif

                                @if($investment->status != 3)
                                <button id="addToFav" class="btn btn-primary btn-with-icon px-3 min-w-max-content flex-fill d-inline-flex align-items-center justify-content-center gap-1" data-id="{{$property->id}}">Dodaj do schowka <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" /></svg></button>
                                @endif
                                <!--
                                <a href="/kontakt.php" class="btn btn-primary btn-with-icon d-inline-flex align-items-center gap-1 justify-content-center px-3 min-w-max-content flex-fill">
                                -->
                            </div>
                            @if($investment->file_brochure)
                                <div class="mt-2">
                                    <a href="{{ asset('/investment/brochure/'.$investment->file_brochure) }}" target="_blank" class="link-body-primary small">Prospekt informacyjny</a>
                                </div>                            @endif
                            <div id="clipboardmessage"></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 offset-lg-1 order-first order-md-0">
                        @if($property->file && $property->model_3d)
                        <ul class="nav justify-content-center justify-content-md-end mb-4 mb-sm-30 gap-30" role="tablist">
                            @if($property->model_3d)
                                <li class="nav-item tab-switcher" role="presentation">
                                    <button class="nav-link active tab-switcher-button-shadow" id="btn-3d" data-bs-toggle="tab" data-bs-target="#btn-3d-pane" type="button" role="tab" aria-controls="list-tab-pane" aria-selected="true">Rzut 3D</button>
                                </li>
                            @endif
                            @if($property->file)
                                <li class="nav-item tab-switcher" role="presentation">
                                    <button class="nav-link tab-switcher-button-shadow" id="btn-2d" data-bs-toggle="tab" data-bs-target="#btn-2d-pane" type="button" role="tab" aria-controls="grid-tab-pane" aria-selected="false">Plan</button>
                                </li>
                            @endif
                        </ul>
                        @endif
                        <div class="tab-content">
                            @if($property->model_3d)
                                <div class="tab-pane fade show active" id="btn-3d-pane" role="tabpanel" aria-labelledby="btn-3d" tabindex="0">
                                    <div class="w-100 h-100">
                                        <div class="ratio ratio-4x3">
                                            {!! createIframeFromButton($property->model_3d) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($property->file)
                                <div class="tab-pane fade @if(!$property->model_3d) show active @endif" id="btn-2d-pane" role="tabpanel" aria-labelledby="btn-2d" tabindex="0">
                                    <div class="w-100 h-100">
                                        <a href="{{ asset('/investment/property/'.$property->file) }}" class="glightbox">
                                            <picture>
                                                @if($property->file_webp)
                                                    <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$property->file_webp) }}">
                                                @endif
                                                <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$property->file) }}">
                                                <img src="{{ asset('/investment/property/thumbs/'.$property->file) }}" alt="{{$property->name}}" class="w-100">
                                            </picture>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div id="kontakt">
            @if($investment->status != 3)
                @include('layouts.partials.cta', ['pageTitle' => $investment->name.' - '.$property->name, 'propertyId' => $property->id, 'investmentName' => $investment->name, 'investmentId' => $investment->id, 'back' => true, 'sectionTitle' => $property->name, 'sectionSubTitle' => 'Wyślij zapytanie', 'investmentText' => $investment->contact_content])
            @else
                @include('layouts.partials.cta', ['pageTitle' => $investment->name.' - '.$property->name, 'propertyId' => $property->id, 'investmentName' => $investment->name, 'investmentId' => $investment->id, 'back' => true, 'sectionTitle' => $property->name, 'sectionSubTitle' => 'Zapisz się na listę zainteresowanych', 'investmentText' => $investment->contact_content])
            @endif
        </div>

        @if($property->text)
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 offset-md-3 pb-5">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">Opis mieszkania</span>
                                    <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">{{ $investment->name }}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="col-12">
                            {!! parse_text($property->text, true) !!}
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Podobne mieszkania -->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-3">
                        <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                            <span data-aos="fade-up" data-aos-delay="200">
                                Sprawdź
                            </span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">
                                Podobne mieszkania
                            </span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            // Zmienne do slidera
            $arrow_prev = '<button  class="slick-prev slick-arrow" aria-label="Previous" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="9.05" height="16.484" viewBox="0 0 9.05 16.484"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M363.434-675.758,356-683.192l.808-.808,8.242,8.242-8.242,8.242-.808-.808Z" transform="translate(365.05 -667.516) rotate(180)" fill="#fff"/></svg></button>';

            $arrow_next = '<button  class="slick-next slick-arrow" aria-label="Next" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="9.05" height="16.484" viewBox="0 0 9.05 16.484"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M363.434-675.758,356-683.192l.808-.808,8.242,8.242-8.242,8.242-.808-.808Z" transform="translate(-356 684)" fill="#fff"/></svg></button>'; ?>
            <?php
            $slider_options = [
                'mobileFirst' => true,
                'prevArrow' => $arrow_prev,
                'nextArrow' => $arrow_next,
                'centerMode' => true,
                'slidesToShow' => 1,
                'centerPadding' => '15px',
                'responsive' => [
                    [
                        'breakpoint' => 576,
                        'settings' => [
                            'centerPadding' => '10%',
                        ]
                    ],
                    [
                        'breakpoint' => 768,
                        'settings' => [
                            'centerPadding' => '15%',
                        ]
                    ],
                    [
                        'breakpoint' => 1199,
                        'settings' => [
                            'centerPadding' => 'calc(505 / 1920 * 100vw)',
                        ]
                    ]
                ]
            ];
            ?>
            <div class="invests-vertical-slider mt-4" data-slick='<?= json_encode($slider_options) ?>'>
                @foreach($similarProperties as $p)
                    <div>
                        <div class="invest-card-horizontal position-relative d-flex flex-column-reverse flex-sm-row justify-content-between  bg-white">
                            @if($investment->type == 1)
                                <a href="{{ route('developro.building.floor.property', [
                                                            $investment->slug,
                                                            $building,
                                                            Str::slug($building->name),
                                                            $p->floor,
                                                            Str::slug($p->name),
                                                            $p,
                                                            Str::slug($p->name),
                                                            number2RoomsName($p->rooms, true),
                                                            round(floatval($p->area), 2).'-m2'
                                                        ]) }}" class="stretched-link"></a>
                            @endif
                            @if($investment->type == 2)
                            <a href="{{ route('developro.property', [$investment->slug, $p->floor, Str::slug($p->floor->name), $p, Str::slug($p->name), number2RoomsName($p->rooms, true), round(floatval($p->area), 2).'-m2']) }}" class="stretched-link"></a>
                            @endif
                            <div class="text-secondary invest-card-horizontal-left flex-fill">
                                <p class="h3 mb-0">{{ $p->name }}</p>
                                <p class="fs-10 text-uppercase fw-900 mb-2">{{ $investment->name }}</p>
                                @auth
                                @if($p->investment->show_prices)
                                    <p class="h3 mb-1">
                                        @if($p->price_brutto && !$p->highlighted)
                                            <span class="me-2">@money($p->price_brutto)</span>
                                        @else
                                            @if($p->promotion_price)
                                                <span class="me-2">@money($p->promotion_price)</span>
                                            @endif
                                            @if($p->price_brutto)
                                                <span class="text-body-emphasis opacity-50 fs-6 align-middle text-decoration-line-through">@money($p->price_brutto)</span>
                                            @endif
                                        @endif
                                    </p>
                                    @if($p->price_30)
                                        <p class="fs-8 text-black">
                                            Najniższa cena z ostatnich 30 dni: @money($p->price_30)
                                        </p>
                                    @endif
                                @endif
                                @endauth
                                <div class="small mb-40">
                                    <table class="w-100">
                                        <tbody>
                                        <tr>
                                            <td class="td-with-icon">
                                                <img src="{{ asset('img/tile.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12">
                                            </td>
                                            <td>Piętro</td>
                                            <td class="text-end">{{ $p->floor->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="td-with-icon">
                                                <img src="{{ asset('img/blueprint.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12">
                                            </td>
                                            <td>Metraż</td>
                                            <td class="text-end">{{ $p->area }} m<sup>2</sup></td>
                                        </tr>
                                        @if($p->type == 1)
                                        <tr>
                                            <td class="td-with-icon">
                                                <img src="{{ asset('img/rooms.svg') }}" alt="" loading="lazy" decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12">
                                            </td>
                                            <td>Liczba Pokoi</td>
                                            <td class="text-end">{{ $p->rooms }}</td>
                                        </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    @if($investment->status == 3)
                                        <h5 class="mt-2" style="color:#c6a76c">Inwestycja planowana</h5>
                                    @endif
                                </div>
                                <div class="position-relatieve z-2">
                                    @if($investment->type == 1)
                                        <a class="btn btn-primary btn-with-icon " href="{{ route('developro.building.floor.property', [
                                                            $investment->slug,
                                                            $building,
                                                            Str::slug($building->name),
                                                            $p->floor,
                                                            Str::slug($p->name),
                                                            $p,
                                                            Str::slug($p->name),
                                                            number2RoomsName($p->rooms, true),
                                                            round(floatval($p->area), 2).'-m2'
                                                        ]) }}">
                                            Sprawdź
                                            <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" /></svg>
                                        </a>
                                    @endif
                                    @if($investment->type == 2)
                                    <a class="btn btn-primary btn-with-icon " href="{{ route('developro.property', [$investment->slug, $p->floor, Str::slug($p->floor->name), $p, Str::slug($p->name), number2RoomsName($p->rooms, true), round(floatval($p->area), 2).'-m2']) }}">
                                        Sprawdź
                                        <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" /></svg>
                                    </a>
                                    @endif
                                </div>
                            </div>

                            <div class="invest-card-horizontal-right">
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
                @endforeach
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <link href="{{ asset('/css/history.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>

    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/tip.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/floor.js') }}" charset="utf-8"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const anchor = document.querySelector('.widget3dEstate');
            if (anchor) {
                anchor.classList.add(
                    'btn',
                    'btn-primary',
                    'btn-with-icon',
                    'd-inline-flex',
                    'align-items-center',
                    'gap-1',
                    'justify-content-center',
                    'px-3',
                    'min-w-max-content',
                    'flex-fill'
                );
                anchor.innerHTML = 'Wirtualny Spacer <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" /></svg>';
            }
        });
    </script>
    <div id="root3dEstate"></div>
    <script>
        (function(w, d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0], a = {renderDom: function(){}};
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.async=!!0;
            js.src = 'https://implementations.3destate.pl/sdk.js';
            fjs.parentNode.insertBefore(js, fjs);
            w.API_IMPLEMENTATION_3D_ESTATE = a;
            w.websiteCode = "kalter--agencja-regal-estate-";
        }(window, document, 'script', 'jssdk-implementation-3destate'));

        window.API_IMPLEMENTATION_3D_ESTATE.renderDom();
    </script>
    <script src="https://implementations.3destate.pl/model360.js"></script>
    <script>
        const button = document.querySelector('#addToFav');
        const baseUrl = "https://www.kalternieruchomosci.pl/";
        //const baseUrl = "https://newkalter.test/";

        button.addEventListener('click', function() {
            const xhr = new XMLHttpRequest();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const property_id = button.getAttribute('data-id');

            xhr.open('POST', baseUrl + 'pl/clipboard');
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            const data = { id: property_id };
            const jsonData = JSON.stringify(data);
            xhr.send(jsonData);

            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const message = response.message;
                    const count = response.count;
                    document.querySelector('#clipboardmessage').innerHTML = message;

                    // Check if count is truthy and element doesn't already exist
                    if (count && !document.querySelector('#clipboardwidget')) {
                        const li = document.createElement('li');
                        li.id = 'clipboardwidget';
                        li.innerHTML = '<a href="' + baseUrl + 'pl/schowek" class="Clipboard"><span>Schowek</span></a>';

                        const asideList = document.querySelector('aside ul');
                        if (asideList) {
                            asideList.appendChild(li);
                        }
                    }
                }
            });
        });

        document.querySelectorAll('.btn-offer').forEach(button => {
            button.addEventListener('click', async (event) => {
                event.preventDefault();

                const btn = event.currentTarget;
                if (!btn) return;
                if (btn.disabled) return;
                btn.disabled = true;

                const modalHolder = document.getElementById('offerModal');
                const dataId = btn.dataset.id;
                modalHolder.innerHTML = '';

                try {
                    const url = baseUrl + `pl/przynalezne/${dataId}/`;

                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });

                    btn.disabled = false;

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Błąd z backendu:', response.status, errorText);
                        throw new Error(`Błąd sieci: ${response.status}`);
                    }

                    const html = await response.text();
                    modalHolder.innerHTML = html;

                    const modalElement = document.getElementById('portletModal');
                    if (!modalElement) { btn.disabled = false; throw new Error('Brak elementu modala'); }
                    const bootstrapModal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    bootstrapModal.show();

                    modalElement.addEventListener('shown.bs.modal', () => {
                        console.log("Modal shown");

                        const filterButtons = modalElement.querySelectorAll('.col-12.p-0 .btn');
                        const listItems = modalElement.querySelectorAll('#otherListGroup .list-group-item');

                        filterButtons.forEach(btn => {
                            btn.addEventListener('click', (e) => {
                                e.preventDefault();
                                const type = btn.dataset.type;

                                // Update buttons style
                                filterButtons.forEach(b => {
                                    b.classList.remove('btn-primary');
                                    b.classList.add('btn-secondary');
                                });
                                btn.classList.remove('btn-secondary');
                                btn.classList.add('btn-primary');

                                // Filter list
                                listItems.forEach(item => {
                                    if (type === "0" || item.dataset.type === type) {
                                        item.style.display = '';
                                    } else {
                                        item.style.display = 'none';
                                    }
                                });
                            });
                        });

                        const listButtons = modalElement.querySelectorAll('#otherListGroup button');
                        const otherHolder = document.getElementById('relatedInfo');
                        let selectedSurfaceId = null;

                        listButtons.forEach(btn => {
                            btn.addEventListener('click', (e) => {
                                e.preventDefault();
                                const li = btn.closest('li');
                                selectedSurfaceId = li.dataset.id;

                                // Update active state
                                listButtons.forEach(b => b.classList.remove('active'));
                                btn.classList.add('active');

                                const url = baseUrl + `pl/przynalezne/${selectedSurfaceId}/show/`;
                                otherHolder.innerHTML = '<div class="text-center p-3"><div class="spinner-border spinner-border-sm" role="status"></div></div>';

                                fetch(url, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                    }
                                })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(`Server responded with status ${response.status}`);
                                        }
                                        return response.text();
                                    })
                                    .then(html => {
                                        otherHolder.innerHTML = html;
                                        $('#invesmentplan').mapster({
                                            fillOpacity: 1,
                                            fillColor: 'fbc000',
                                            stroke: true,
                                            strokeColor: '024282',
                                            strokeOpacity: 1,
                                            strokeWidth: 2,
                                            clickNavigate: false,
                                            isSelectable: false,
                                            mapKey: 'data-item',
                                            onConfigured: function() {
                                                $('#invesmentplan').mapster('set', true, {
                                                    fillColor: 'fbc000',
                                                    fillOpacity: 1
                                                });
                                            },
                                            areas: [{
                                                key: selectedSurfaceId,
                                                selected: true
                                            }]
                                        });
                                    })
                                    .catch(error => {
                                        console.error('Fetch error:', error);
                                        otherHolder.innerHTML = '<div class="alert alert-danger p-2 small">Błąd ładowania danych</div>';
                                    });
                            });
                        });



                        const addBtn = modalElement.querySelector('.modal-footer .btn-primary');
                        const addedSurfaceIds = new Set();

                        if (!addBtn.dataset.bound) {
                            addBtn.addEventListener('click', () => {
                                if (!selectedSurfaceId) {
                                    alert('Proszę wybrać element z listy');
                                    return;
                                }

                                if (addedSurfaceIds.has(selectedSurfaceId)) return;

                                const url = baseUrl + `pl/przynalezne/${selectedSurfaceId}/table/`;
                                const otherHolderList = document.getElementById('offerList');

                                fetch(url, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                    }
                                })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(`Server responded with status ${response.status}`);
                                        }
                                        return response.text();
                                    })
                                    .then(html => {
                                        otherHolderList.insertAdjacentHTML('beforeend', html);

                                        // Pokaż tabelę
                                        document.querySelector('.property-offer-check .table').classList.remove('d-none');

                                        // Dodaj do listy zapamiętanych
                                        addedSurfaceIds.add(selectedSurfaceId);

                                        // Aktualizacja ceny
                                        const newRow = otherHolderList.querySelector(`tr[data-id="${selectedSurfaceId}"]`);
                                        const price = parseFloat(newRow.dataset.price);

                                        updateTotalPrice(price, 'add');

                                        // Zamknij modal
                                        bootstrap.Modal.getInstance(modalElement).hide();
                                    })
                                    .catch(error => {
                                        console.error('Fetch error:', error);
                                    });
                            });

                            addBtn.dataset.bound = "true";
                        }
                    });

                    // Wyczyść zawartość po zamknięciu modala
                    modalElement.addEventListener('hidden.bs.modal', () => {
                        modalHolder.innerHTML = '';
                    }, { once: true });

                } catch (error) {
                    try { btn && (btn.disabled = false); } catch (e) {}
                    alert('Wystąpił błąd podczas ładowania.');
                    console.error(error);
                }
            });
        });

        document.addEventListener('click', async function (e) {
            // Delete Button
            const btnDelete = e.target.closest('.btn-delete');
            if (btnDelete) {
                e.preventDefault();
                const tr = btnDelete.closest('tr');
                if (tr) {
                    const priceToRemove = parseFloat(tr.dataset.price) || 0;
                    tr.remove();
                    updateTotalPrice(priceToRemove, 'remove');
                    checkOfferList();
                }
                return;
            }

            // History Button
            const btnHistory = e.target.closest('.btn-history');
            if (btnHistory) {
                e.preventDefault();

                // Disable button to prevent double click
                btnHistory.disabled = true;

                const modalHolder = document.getElementById('modalHistory');
                const dataId = btnHistory.dataset.id;
                modalHolder.innerHTML = '';

                try {
                    const url = baseUrl + `pl/historia/${dataId}/`;

                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Błąd z backendu:', response.status, errorText);
                        throw new Error(`Błąd sieci: ${response.status}`);
                    }

                    const html = await response.text();
                    modalHolder.innerHTML = html;

                    const modalElement = document.getElementById('portletModal');
                    const bootstrapModal = new bootstrap.Modal(modalElement);
                    bootstrapModal.show();

                    modalElement.addEventListener('hidden.bs.modal', () => {
                        modalHolder.innerHTML = '';
                    }, { once: true });

                } catch (error) {
                    alert('Wystąpił błąd podczas ładowania historii.');
                    console.error(error);
                } finally {
                    // Re-enable the button after request completes
                    btnHistory.disabled = false;
                }
            }
        });

        function checkOfferList() {
            const tbody = document.getElementById('offerList');
            const table = document.querySelector('.property-offer-check .table');

            if (!tbody || !table) return;

            if (tbody.children.length === 0) {
                table.classList.add('d-none');
            } else {
                table.classList.remove('d-none');
            }
        }
        function updateTotalPrice(price, action = 'add') {
            const summary = document.querySelector('.property-summary');
            if (!summary) return;

            let priceValue = parseFloat(price);
            if (isNaN(priceValue)) priceValue = 0;

            let currentPrice = parseFloat(summary.dataset.totalprice || 0);

            if (action === 'add') {
                currentPrice += priceValue;
            } else if (action === 'remove') {
                currentPrice -= priceValue;
            }

            if (currentPrice < 0) currentPrice = 0;

            summary.dataset.totalprice = currentPrice.toFixed(2);

            const display = document.getElementById('totalDisplay');
            if (display) {
                display.textContent = formatPrice(currentPrice);
            }
        }
        function formatPrice(price) {
            let parts = price.toFixed(2).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            return parts.join('.');
        }
    </script>
@endpush

@section('schema')
    <script type="application/ld+json">
        {!! json_encode([
            "@context" => "https://schema.org",
            "@type" => "RealEstateListing",

            "name" => "{$property->name} – {$investment->name}",
            "url" => url()->current(),
            "datePosted" => date('Y-m-d', strtotime('+3 months')),
            "offers" => [
                "@type" => "Offer",
                "price" => (float) (
                    $property->highlighted && $property->promotion_price
                        ? $property->promotion_price
                        : $property->price_brutto
                ),
                "priceCurrency" => "PLN",
                "availability" => match($property->status) {
                    1 => "https://schema.org/InStock",
                    2 => "https://schema.org/PreOrder",
                    3 => "https://schema.org/SoldOut",
                    default => "https://schema.org/InStock"
                },
                "url" => url()->current(),
                "priceValidUntil" => date('Y-m-d', strtotime('+3 months')),
            ],

            "itemOffered" => [
                "@type" => "Apartment",
                "name" => $property->name,
                "numberOfRooms" => (int) $property->rooms,
                "floorSize" => [
                    "@type" => "QuantitativeValue",
                    "value" => (float) $property->area,
                    "unitCode" => "MTK"
                ],
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => $investment->city->name,
                    "addressCountry" => "PL"
                ]
            ],

            "image" => $property->file
                ? asset('/investment/property/'.$property->file)
                : null

        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
        <script type="application/ld+json">
            {!! json_encode([
                "@context" => "https://schema.org",
                "@type" => "Product",

                "name" => "{$property->name} – {$investment->name}",
                "image" => $property->file ? asset('/investment/property/'.$property->file) : null,
                "description" => "{$property->rooms} pokoje, {$property->area} m2",

                "offers" => [
                    "@type" => "Offer",
                    "price" => (float) (
                        $property->highlighted && $property->promotion_price
                            ? $property->promotion_price
                            : $property->price_brutto
                    ),
                    "priceCurrency" => "PLN",
                    "availability" => match($property->status) {
                        1 => "https://schema.org/InStock",
                        2 => "https://schema.org/PreOrder",
                        3 => "https://schema.org/SoldOut",
                        default => "https://schema.org/InStock"
                    },
                    "url" => url()->current(),
                    "priceValidUntil" => date('Y-m-d', strtotime('+3 months')),
                ],

                "additionalProperty" => [
                    [
                        "@type" => "PropertyValue",
                        "name" => "Liczba pokoi",
                        "value" => (int) $property->rooms
                    ],
                    [
                        "@type" => "PropertyValue",
                        "name" => "Metraż",
                        "value" => (float) $property->area . " m2"
                    ],
                    [
                        "@type" => "PropertyValue",
                        "name" => "Miasto",
                        "value" => $investment->city->name
                    ]
                ]

            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endsection
