@extends('layouts.page', ['body_class' => 'homepage'])

@section('meta_title', $page->title)
@section('seo_title', $page->meta_title)
@section('seo_description', $page->meta_description)
@section('seo_robots', $page->meta_robots)

@section('pagheader')
    <div id="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>Mapa</h1>
                    @include('layouts.breadcrumbs', ['items' => $page->ancestors, 'title' => ($page->content_header) ? $page->content_header : $page->title])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div id="map"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="{{ URL::asset('css/leaflet.css') }}">
    <script type="text/javascript" src="{{ URL::asset('js/leaflet.js') }}"></script>

    <script type="text/javascript">
        let map = L.map('map').setView([52.227388, 21.011063], 13),
            theMarker = {},
            zoom = map.getZoom(),
            latLng = map.getCenter();

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let markers = [
                @foreach ($markers as $p)
                [{{$p->lat}}, {{$p->lng}}, '{{$p->name}}'],
                @endforeach
            ],
            route = L.featureGroup().addTo(map),
            n = markers.length;

        for (let i = 0; i < n-1; i++) {
            let marker = new L.Marker(markers[i]).bindPopup(markers[i][2]);
            route.addLayer(marker);
        }
        route.addLayer(new L.Marker(markers[n-1]).bindPopup(markers[n-1][2]));
        map.fitBounds(route.getBounds());
    </script>
@endpush
