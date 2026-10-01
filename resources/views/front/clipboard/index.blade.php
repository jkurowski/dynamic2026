@extends('layouts.page')

@section('meta_title', $page->title)
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset
@section('content')
    <style>
        .table .row-diff td {
            background-color: rgba(255, 193, 7, 0.1);
        }
        .table .row-diff th {
            border-left: 4px solid #ffc107;
        }
        .last-left {
            border-bottom:0 !important;
        }
        .table-responsive .table {
            display: table;
            width: auto !important;
        }
        .table-responsive .table tr {
            display: table-row;
        }
        .table-responsive .table th, .table-responsive .table td {
            display: table-cell;
            white-space: nowrap;
            vertical-align: middle;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            float: none !important;
        }
        .table-responsive .table th:first-child, .table-responsive .table td:first-child {
            position: sticky;
            left: 0;
            background-color: #fff;
            z-index: 10;
            border-right: 2px solid #dee2e6;
        }
        .table-responsive .table thead th {
            vertical-align: bottom;
        }
        .table-responsive .table th.col-2, .table-responsive .table td.col-2 {
            width: 200px;
            min-width: 200px;
        }
        .table-responsive .table th.col-3, .table-responsive .table td.col-3,
        .table-responsive .table th.col, .table-responsive .table td.col {
            width: 300px;
            min-width: 300px;
        }
        #clipboard .table img {
            max-width: 250px;
        }
    </style>
    <main class="with-bigger-section-spacing">

        @include('layouts.partials.page-header', ['h1' => $page->title, 'desc' => $page->title_text, 'header' => 'img/kontakt_bg.webp'])
        <section id="clipboard" class="p-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div id="clipboardmessage"></div>
                    </div>
                </div>

                <div class="row mt-30">
                    <div class="col-12">
                        @if($properties->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                            <thead>
                                <tr>
                                    <th class="col-2"></th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                    <th class="col-3 text-center" data-room="{{$p->id}}">
                                        <a href="{{ asset('/investment/property/'.$p->file) }}" class="glightbox">
                                            <picture>
                                                @if($p->file_webp)
                                                    <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$p->file_webp) }}">
                                                @endif
                                                <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$p->file) }}">
                                                <img src="{{ asset('/investment/property/thumbs/'.$p->file) }}" alt="{{$p->name}}" class="w-100">
                                            </picture>
                                        </a>
                                        <h2 class="h4 lh-1 mb-2 fs-3 mt-2">{{ $p->name }}</h2>
                                        <span class="mb-0 lh-1 d-block fs-15 opacity-50">{{ $p->investment->name }}</span>
                                    </th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th class="col-2">
                                    Status
                                </th>
                                @if($properties->count() > 0)
                                    @foreach($properties as $p)
                                        <td class="col-3 text-center" data-room="{{$p->id}}">
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
                                        </td>
                                    @endforeach
                                @endif
                            </tr>
                                <tr class="">
                                    <th class="col-2">
                                        Cena
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                    <td class="col text-center" data-room="{{$p->id}}">
                                        @if($p->price_brutto && $p->status == 1 && !$p->highlighted)
                                            <strong class="d-block fs-3 mb-0 lh-1">@money($p->price_brutto)</strong>
                                            <small>@money(($p->price_brutto / $p->area)) / m<sup>2</sup></small>
                                        @endif

                                        @if($p->highlighted && $p->promotion_price && $p->price_brutto && $p->status == 1)
                                                <strong class="d-block fs-3 mb-0 lh-1">@money($p->promotion_price)</strong>
                                                <small>@money(($p->promotion_price / $p->area)) / m<sup>2</sup></small>

                                                @if($p->price_brutto)
                                                    <strong class="opacity-50 fs-4 text-decoration-line-through d-block lh-1 mt-2">@money($p->price_brutto)</strong>
                                                    <small class="d-block opacity-50 text-decoration-line-through">@money($p->price_brutto / $p->area) / m<sup>2</sup></small>
                                                @endif

                                            @php
                                                $rabat = $p->price_brutto - $p->promotion_price;
                                            @endphp
                                            @if($p->price_brutto <> $p->promotion_price)
                                                <span class="rabat h4 d-block w-100 mt-3">Rabat: @money($rabat)</span>
                                            @endif

                                            @if($p->promotion_end_date && $p->status == 1)
                                                <span class="fs-15 text-black mb-0">Promocja ważna do: {{ $p->promotion_end_date }}</span>
                                            @endif
                                        @endif
                                    </td>
                                        @endforeach
                                    @endif
                                </tr>

                            <tr class="@if($properties->count() > 1 && $properties->pluck('investment.city.name')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                        Lokalizacja
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                    <td class="col text-center" data-room="{{$p->id}}">
                                        {{ $p->investment->city->name ?: '-' }}
                                    </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('floor.name')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                        Piętro
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                    <td class="col text-center" data-room="{{$p->id}}">
                                        {{ $p->floor->name ?: '-' }}
                                    </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('area')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                        Powierzchnia
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                    <td class="col text-center" data-room="{{$p->id}}">
                                        {{ $p->area ? $p->area . ' m' : '-' }}<sup>2</sup>
                                    </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('rooms')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                        Pokoje
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                            <td class="col text-center" data-room="{{$p->id}}">
                                                {{ $p->rooms ?: '-' }}
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('kitchen')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                        Aneks / kuchnia
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                            <td class="col text-center" data-room="{{$p->id}}">
                                                {{ kitchenType($p->kitchen) ?: '-' }}
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('window')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                        Wystawa okienna
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                            <td class="col text-center" data-room="{{$p->id}}">
                                                {{ getWindowDirections($p->window) ?: '-' }}
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('terrace_area')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                       Taras
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                            <td class="col text-center" data-room="{{$p->id}}">
                                                {!! $p->terrace_area ? $p->terrace_area . ' m<sup>2</sup>' : '-' !!}
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('garden_area')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                       Ogródek
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                            <td class="col text-center" data-room="{{$p->id}}">
                                                {!! $p->garden_area ? $p->garden_area . ' m<sup>2</sup>' : '-' !!}
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr class="@if($properties->count() > 1 && $properties->pluck('balcony_area')->unique()->count() > 1) row-diff @endif">
                                    <th class="col-2">
                                       Balkon
                                    </th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                            <td class="col text-center" data-room="{{$p->id}}">
                                                {!! $p->balcony_area ? $p->balcony_area . ' m<sup>2</sup>' : '-' !!}
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                                <tr>
                                    <th class="col-2 last-left">&nbsp;</th>
                                    @if($properties->count() > 0)
                                        @foreach($properties as $p)
                                            <td class="col text-center" data-room="{{$p->id}}">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button class="btn btn-primary btn-with-icon text-nowrap w-100 remove-property" data-id="{{$p->id}}">USUŃ</button>
                                                    </div>
                                                    <div class="col-6">
                                                        @if($p->investment->type == 1)
                                                            <a class="btn btn-primary btn-with-icon text-nowrap w-100" href="{{ route('developro.building.floor.property', [
                            $p->investment->slug,
                            $p->building,
                            Str::slug($p->building->name),
                            $p->floor,
                            Str::slug($p->floor->name),
                            $p,
                            Str::slug($p->name),
                            number2RoomsName($p->rooms, true),
                            round(floatval($p->area), 2).'-m2'
                        ]) }}">SPRAWDŹ</a>
                                                        @endif
                                                        @if($p->investment->type == 2)
                                                            <a class="btn btn-primary btn-with-icon text-nowrap w-100" href="{{ route('developro.property', [
                            $p->investment->slug,
                            $p->floor,
                            Str::slug($p->floor->name),
                            $p,
                            Str::slug($p->name),
                            number2RoomsName($p->rooms, true),
                            round(floatval($p->area), 2).'-m2'
                        ]) }}">SPRAWDŹ</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                            </div>
                        @else
                            <p class="text-center pt-5 pb-5"><b>Twoja lista jest pusta</b></p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @if($properties->count() > 0)
        <div id="kontakt" class="mt-30">
            @include('layouts.partials.clipboard', ['pageTitle' => $page->title, 'back' => true])
        </div>
        @endif
    </main>
@endsection
@push('scripts')
    <script type="text/javascript">
        const buttons = document.querySelectorAll('.remove-property');
        buttons.forEach(function(button) {
            button.addEventListener('click', function() {
                removeProperty(button.getAttribute('data-id'))
            });
        });

        const baseUrl = "https://www.kalternieruchomosci.pl/";

        function removeProperty(property_id) {
            const xhr = new XMLHttpRequest();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            xhr.open('DELETE', baseUrl+'pl/clipboard');
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            const data = { id: property_id };
            const jsonData = JSON.stringify(data);
            xhr.send(jsonData);

            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const message = response.message;
                    document.querySelector('#clipboardmessage').innerHTML = message;

                    const items = document.querySelectorAll(`[data-room="${property_id}"]`);
                    if (items.length > 0) {
                        items.forEach(item => {
                            item.animate(
                                [{ opacity: 1 }, { opacity: 0 }],
                                { duration: 500 }
                            );
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endpush
