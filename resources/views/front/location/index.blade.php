@extends('layouts.page')

@section('meta_title', $page->title)
@section('seo_title', $page->meta_title)
@section('seo_description', $page->meta_description)

@section('pageheader')
    @include('layouts.partials.page-header', ['page' => $page, 'header_file' => 'location.jpg'])
@stop

@section('content')
    <div id="location">
        <div class="container container-bg">
            <div class="row m-0">
                <div class="order-2 order-lg-1 col-12 col-lg-4 p-0">
                    <div class="map-nav">
                        <ul class="mb-0 list-unstyled">
                            <li data-id="1"><span><i class="las la-chalkboard-teacher"></i></span> Przedszkola i szkoły podstawowe</li>
                            <li data-id="2"><span><i class="las la-bus"></i></span> Transport publiczny</li>
                            <li data-id="3"><span><i class="las la-road"></i></span> Wylot na Autostradę A1</li>
                            <li data-id="4"><span><i class="las la-tree"></i></span> Tereny rekreacyjne, place zabaw, parki rowerowe</li>
                            <li data-id="5"><span><i class="las la-utensils"></i></span> Gastronomia</li>
                            <li data-id="6"><span><i class="las la-stethoscope"></i></span> Przychodnie publicznych i prywatne</li>
                            <li data-id="7"><span><i class="las la-star-of-life"></i></span> Apteki</li>
                            <li data-id="8"><span><i class="las la-church"></i></span> Kościoły</li>
                            <li data-id="9"><span><i class="las la-shopping-cart"></i></span> Markety, sklepy, drogerie</li>
                        </ul>
                    </div>
                </div>

                <div class="order-1 order-lg-2 col-12 col-lg-8 p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>
        <div class="container p-rwd-767-0">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-lg-10 pt-3 pt-lg-5 mt-4">
                    <p class="m-0">Bliski Olechów jest naprawdę bliski! Wszechobecna zieleń, wygodny dojazd do centrum oraz bliskość wszelkich przydatnych punktów handlowo-usługowych, edukacyjnych i ochrony zdrowia to zalety Widzewa. Nasza inwestycja Bliski Olechów jest świetnie zlokalizowana przy ulicy Odnowiciela, w bliskości drogi wylotowej na A1 oraz kilku przystanków komunikacji zarówno tramwajowej jak i autobusowej.</p>
                </div>

                <div class="col-12 mt-3">
                    <div class="location-img mt-3 mt-lg-5">
                        <div class="location-img-apla location-img-apla-right">
                            <h2>ZIELEŃ I WYPOCZYNEK</h2>
                            <p>Bliski Olechów znajduje się  przy Parku Źródła Olechówki oraz innych terenach zielonych wyposażonych w m.in. tężnię solankową, nowoczesne place zabaw dla dzieci oraz infrastrukturę sportowo-rekreacyjną.</p>
                        </div>
                        <img src="{{ asset('/gfx/park.jpg') }}" alt="">
                    </div>
                </div>

                <div class="col-12 pb-0 pb-lg-5 mb-0 mb-lg-5">
                    <div class="location-img">
                        <div class="location-img-apla location-img-apla-left">
                            <h2>NA WYCIĄGNIĘCIE RĘKI</h2>
                            <p>Bliskość przedszkoli i dobrych szkół, przychodni publicznych i prywatnych, szeroki wybór sklepów i aptek oraz świetne miejsca do sportu,  rekreacji i odpoczynku w otoczeniu zieleni to powód, dla którego naszą najnowszą inwestycję zdecydowaliśmy się zrealizować właśnie tutaj.</p>
                        </div>
                        <img src="{{ asset('/gfx/kids.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <link href="{{ asset('/css/leaflet.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/leaflet.min.js') }}" charset="utf-8"></script>
    <script>
        const tileLayer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            'attribution': 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
        });

        const education = L.featureGroup(),
            transport = L.featureGroup(),
            highway = L.featureGroup(),
            park = L.featureGroup(),
            restaurant = L.featureGroup(),
            clinic = L.featureGroup(),
            pharmacy = L.featureGroup(),
            church = L.featureGroup(),
            store = L.featureGroup(),
            investment = L.featureGroup();

        @foreach($markers as $i)
        const marker_{{ $i->id }} = L.marker([{{ $i->lat }}, {{ $i->lng }}]).bindPopup('<h4>{{ $i->name }}</h4><p>{{ $i->address }}</p>').addTo({{ leafletLayerGroup($i->group_id) }});
        @endforeach

        const investmentIcon = L.icon({
            iconUrl: '{{ asset('/gfx/investmentIcon.png') }}',
            iconSize: [65, 101],
            iconAnchor: [32, 100],
        });
        const marker_investment = L.marker([51.739406,19.562571], {icon: investmentIcon}).bindPopup('<h4>Bliski Olechów</h4><p>Kazimierza Odnowiciela 13</p>').addTo(investment);

        let map = new L.Map('map', {
            'center': [0, 0],
            'zoom': 0,
            'layers': [tileLayer]
        });
        const arrayGroups = [education, transport, highway, park, restaurant, clinic, pharmacy, church, store, investment];
        const fGroup = new L.featureGroup(arrayGroups).addTo(map);

        education.id = 1;
        transport.id = 2;
        highway.id = 3;
        park.id = 4;
        restaurant.id = 5;
        clinic.id = 6;
        pharmacy.id = 7;
        church.id = 8;
        store.id = 9;
        investment.id = 10;

        map.fitBounds(fGroup.getBounds());

        $(".map-nav li").click(function(){
            $(".map-nav li").removeClass('active');
            $(this).addClass('active');

            const groupId = $(this).data("id");
            new L.featureGroup(arrayGroups).addTo(map);

            fGroup.eachLayer(function(layer){
                if(layer.id !== groupId && layer.id !== 10) {
                    map.removeLayer(layer);
                }
                if(layer.id === groupId){
                    map.fitBounds([layer.getBounds(), investment.getBounds()]);
                }
            });
        })
    </script>
@endpush
