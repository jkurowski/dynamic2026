@extends('admin.layout')
@section('content')
    @if(Route::is('admin.map.edit'))
        <form method="POST" action="{{route('admin.map.update', $entry->id)}}">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.map.store')}}">
                    @endif
                    @csrf
                    <div class="container">
                        <div id="map"></div>

                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-map-pin"></i><a href="{{route('admin.map.index')}}">Mapa</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-select', ['label' => 'Kategoria', 'name' => 'group_id', 'selected' => $entry->group_id, 'select' => [
                                        '1' => 'Przedszkola i szkoły podstawowe',
                                        '2' => 'Transport publiczny',
                                        '3' => 'Wylot na Autostradę A1',
                                        '4' => 'Tereny rekreacyjne, place zabaw, parki rowerowe',
                                        '5' => 'Gastronomia',
                                        '6' => 'Przychodnie publicznych i prywatne',
                                        '7' => 'Apteki',
                                        '8' => 'Kościoły',
                                        '9' => 'Markety, sklepy, drogerie'
                                    ]])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Nazwa', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Szerokość geograficzna', 'name' => 'lat', 'value' => $entry->lat, 'required' => 1])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Długość geograficzna', 'name' => 'lng', 'value' => $entry->lng, 'required' => 1])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Zoom', 'name' => 'zoom', 'value' => $entry->zoom, 'required' => 1])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Adres', 'name' => 'address', 'value' => $entry->address, 'required' => 1])
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Route::is('admin.map.edit'))
                        <input type="hidden" name="article_id" value="{{$entry->id}}">
                    @endif
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
                @push('scripts')
                    <link href="/css/leaflet.css" rel="stylesheet">
                    <script src="/js/leaflet.js" charset="utf-8"></script>
                    <script>
                        function setOnLoad($lat, $lng, $zoom){
                            $('input[name="zoom"]').val($zoom);
                            $('input[name="lat"]').val($lat);
                            $('input[name="lng"]').val($lng);
                            map.setView([$lat, $lng], $zoom);
                        }

                        function loadInputs($lat, $lng){
                            $('input[name="lat"]').val($lat);
                            $('input[name="lng"]').val($lng);
                        }

                        function setZoom($zoom){
                            $('input[name="zoom"]').val($zoom);
                        }

                        let map = L.map('map').setView([52.227388, 21.011063], 13),
                            theMarker = {},
                            zoom = map.getZoom(),
                            latLng = map.getCenter();

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        @if($entry->lat && $entry->lng && $entry->zoom)
                        setOnLoad('{{ $entry->lat }}', '{{ $entry->lng }}', '{{ $entry->zoom }}');
                        theMarker = L.marker([
                            '{{ $entry->lat }}',
                            '{{ $entry->lng }}'
                        ], {
                            draggable:'true'
                        }).addTo(map);
                        @else
                        setOnLoad(latLng.lat, latLng.lng, zoom);
                        theMarker = L.marker([
                            '52.227388',
                            '21.011063'
                        ], {
                            draggable:'true'
                        }).addTo(map);
                        @endif

                        map.on('zoomend', function() {
                            setZoom(map.getZoom());
                        });

                        map.on('click', function(e) {
                            let lat = e.latlng.lat,
                                lng = e.latlng.lng;
                            loadInputs(lat, lng);

                            if (theMarker !== undefined) {
                                map.removeLayer(theMarker);
                            }
                            theMarker = L.marker([lat, lng], {
                                draggable:'true'
                            }).addTo(map);
                        });

                        theMarker.on('dragend', function(event) {
                            const latlng = event.target.getLatLng();
                            loadInputs(latlng.lat, latlng.lng);
                        });
                    </script>
        @endpush
@endsection
