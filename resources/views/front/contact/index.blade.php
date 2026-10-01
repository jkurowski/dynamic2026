@extends('layouts.page')

@section('meta_title', $page->title)
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset
@section('content')
    <main class="with-bigger-section-spacing">

        @include('layouts.partials.page-header', ['h1' => $page->title, 'desc' => $page->title_text, 'header' => 'img/kontakt_bg.webp'])

        <section class="s1">
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-5">
                        <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200">Siedziba Firmy</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row row-gap-3 fs-14 justify-content-center">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="row text-secondary inline inline-tc" data-aos="fade">
                            <div class="col-12 contact-data" data-modaleditortext="9">{!! getInline($array, 9, 'modaleditortext') !!}</div>
                            {!! inlineEditButton(9, 'modaltytul,modaleditor,modallink,modallinkbutton,file,file_alt') !!}
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="row row-gap-2 mb-3 mb-md-30 text-secondary inline inline-tc">
                            <div class="col-12 contact-data" data-modaleditortext="10">{!! getInline($array, 10, 'modaleditortext') !!}</div>
                            {!! inlineEditButton(10, 'modaltytul,modaleditor,modallink,modallinkbutton,file,file_alt') !!}
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="w-100 h-100 inline inline-tc" data-aos="fade">
                            <img src="{{ getInline($array, 11, 'file') }}"
                                 alt="{{ getInline($array, 11, 'file_alt') }}"
                                 data-img="11"
                                 loading="eager"
                                 class="img-fluid rounded"
                            >
                            {!! inlineEditButton(11, 'modaltytul,modaleditor,modaleditortext,modallink,modallinkbutton') !!}
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row inline inline-tc">
                    <div class="col-12 col-md-6 text-secondary" data-modaleditortext="12">{!! getInline($array, 12, 'modaleditortext') !!}</div>
                    {!! inlineEditButton(12, 'modaltytul,modaleditor,modallink,modallinkbutton,file,file_alt') !!}
                </div>
                <div class="row justify-content-center align-items-stretch row-gap-4 fs-14">
                    @foreach($cities as $city)
                        <div class="col-12 col-lg-6">
                            <div class="contact-card-shadow">
                                <div class="row ">
                                    <div class="col-12 col-md-8">
                                        <div class="p-3 px-md-30 py-md-40 text-secondary">
                                            <div class="fs-24 fw-bold mb-3 mb-md-30">{!! $city->contact_title !!}</div>

                                            <div class="d-flex gap-3 align-items-center mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15.636" height="15.636" viewBox="0 0 15.636 15.636"><path id="Icon_awesome-phone" data-name="Icon awesome-phone" d="M15.067.751,11.891.018a.738.738,0,0,0-.84.424L9.586,3.863a.731.731,0,0,0,.211.855l1.851,1.515a11.318,11.318,0,0,1-5.411,5.411L4.721,9.793a.732.732,0,0,0-.855-.211L.445,11.049a.742.742,0,0,0-.428.843l.733,3.176a.733.733,0,0,0,.715.568,14.168,14.168,0,0,0,14.17-14.17A.732.732,0,0,0,15.067.751Z" transform="translate(15.635 0) rotate(90)" fill="currentcolor"></path></svg>

                                                <a href="tel:{{ $city->phone }}">{{ $city->phone }}</a>
                                            </div>
                                            <div class="d-flex gap-3 align-items-center mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="min-width:18px" width="17.827" height="12.341" viewBox="0 0 17.827 12.341"><g id="Icon_ionic-ios-mail" data-name="Icon ionic-ios-mail" transform="translate(-3.375 -7.875)"><path id="Path_93" data-name="Path 93" d="M21.056,10.34l-4.611,4.7a.083.083,0,0,0,0,.12l3.227,3.437a.556.556,0,0,1,0,.788.559.559,0,0,1-.788,0l-3.214-3.424a.088.088,0,0,0-.124,0l-.784.8a3.45,3.45,0,0,1-2.46,1.037A3.519,3.519,0,0,1,9.79,16.725l-.754-.767a.088.088,0,0,0-.124,0L5.7,19.382a.559.559,0,0,1-.788,0,.556.556,0,0,1,0-.788l3.227-3.437a.091.091,0,0,0,0-.12l-4.615-4.7a.084.084,0,0,0-.146.06v9.4a1.375,1.375,0,0,0,1.371,1.371H19.83A1.375,1.375,0,0,0,21.2,19.8V10.4A.086.086,0,0,0,21.056,10.34Z" transform="translate(0 -0.952)" fill="currentcolor"></path><path id="Path_94" data-name="Path 94" d="M12.621,15.721a2.33,2.33,0,0,0,1.676-.7L21.02,8.175a1.347,1.347,0,0,0-.848-.3H5.074a1.338,1.338,0,0,0-.848.3l6.724,6.844A2.33,2.33,0,0,0,12.621,15.721Z" transform="translate(-0.332)" fill="currentcolor"></path></g></svg>

                                                <a href="mailto:{{ $city->email }}">{{ $city->email }}</a>
                                            </div>
                                            <div class="d-flex gap-3 align-items-start mb-2">
                                                <svg class="text-secondary" xmlns="http://www.w3.org/2000/svg" width="12.204" height="17.435" viewBox="0 0 12.204 17.435"><path id="Icon_material-location-on" data-name="Icon material-location-on" d="M13.6,3A6.1,6.1,0,0,0,7.5,9.1c0,4.577,6.1,11.333,6.1,11.333S19.7,13.679,19.7,9.1A6.1,6.1,0,0,0,13.6,3Zm0,8.282A2.179,2.179,0,1,1,15.782,9.1,2.18,2.18,0,0,1,13.6,11.282Z" transform="translate(-7.5 -3)" fill="currentcolor"></path></svg>

                                                <p class="mb-0">{{ $city->address_line_1 }},<br>{{ $city->address_line_2 }}</p>
                                            </div>
                                            @if($city->working_hours)
                                                <div class="d-flex gap-3 align-items-start mb-2 mb-md-3">
                                                    <svg class="text-secondary" xmlns="http://www.w3.org/2000/svg" height="19" viewBox="0 0 24 24" width="19"><g><path d="M0,0h24v24H0V0z" fill="none"/></g><g><path d="M11.99,2C6.47,2,2,6.48,2,12s4.47,10,9.99,10C17.52,22,22,17.52,22,12S17.52,2,11.99,2z M15.29,16.71L11,12.41V7h2v4.59 l3.71,3.71L15.29,16.71z" fill="currentcolor"/></g></svg>
                                                    <p class="mb-0">{{ $city->working_hours }}</p>
                                                </div>
                                            @endif
                                            @if($city->short_message)
                                                <div class="d-flex gap-3 align-items-start mb-2 mb-md-3">
                                                    <p class="mb-0">{{ $city->short_message }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="ratio ratio-1x1 h-100">
                                            <div id="map{{$city->id}}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section>
            <div class="container inline inline-tc">
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-3">
                        <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200" data-modaltytul="13">{{ getInline($array, 13, 'modaltytul') }}</span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400" data-modaleditor="13">{{ getInline($array, 13, 'modaleditor') }}</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 mt-md-5 align-items-center gy-3">
                    <div class="col-12 col-md-6" data-modaleditortext="13">{!! getInline($array, 13, 'modaleditortext') !!}</div>
                    <div class="col-12 col-md-6">
                        <img src="{{ getInline($array, 13, 'file') }}"
                             alt="{{ getInline($array, 13, 'file_alt') }}"
                             data-img="13"
                             loading="lazy"
                             decoding="async"
                             class="img-fluid rounded"
                        >
                    </div>
                </div>
                {!! inlineEditButton(13, 'modallink,modallinkbutton') !!}
            </div>
        </section>

        <div id="kontakt">
            @include('layouts.partials.cta', ['pageTitle' => 'Kontakt'])
        </div>
    </main>

@endsection
@push('scripts')
    <script defer src="{{ asset('js/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}" onload="this.media='all'" media="print" />
    <script defer>
        document.addEventListener("DOMContentLoaded", function() {
            const phoneLinks = document.querySelectorAll(".phone-link");
            const emailLinks = document.querySelectorAll(".email-link");

            phoneLinks.forEach(link => {
                const iconWrapper = document.createElement("span");
                iconWrapper.classList.add("phone-icon"); // Optional: Can be styled
                iconWrapper.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="15.636" height="15.636" viewBox="0 0 15.636 15.636" style="margin-right: 15px"><path d="M15.067.751,11.891.018a.738.738,0,0,0-.84.424L9.586,3.863a.731.731,0,0,0,.211.855l1.851,1.515a11.318,11.318,0,0,1-5.411,5.411L4.721,9.793a.732.732,0,0,0-.855-.211L.445,11.049a.742.742,0,0,0-.428.843l.733,3.176a.733.733,0,0,0,.715.568,14.168,14.168,0,0,0,14.17-14.17A.732.732,0,0,0,15.067.751Z" transform="translate(15.635 0) rotate(90)" fill="currentcolor"></path></svg>`;
                link.parentNode.insertBefore(iconWrapper, link);
            });

            emailLinks.forEach(link => {
                const iconWrapper = document.createElement("span");
                iconWrapper.classList.add("email-link"); // Optional: Can be styled
                iconWrapper.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" style="min-width: 18px;margin-right: 15px" width="17.827" height="12.341" viewBox="0 0 17.827 12.341"><g id="Icon_ionic-ios-mail" data-name="Icon ionic-ios-mail" transform="translate(-3.375 -7.875)"><path id="Path_93" data-name="Path 93" d="M21.056,10.34l-4.611,4.7a.083.083,0,0,0,0,.12l3.227,3.437a.556.556,0,0,1,0,.788.559.559,0,0,1-.788,0l-3.214-3.424a.088.088,0,0,0-.124,0l-.784.8a3.45,3.45,0,0,1-2.46,1.037A3.519,3.519,0,0,1,9.79,16.725l-.754-.767a.088.088,0,0,0-.124,0L5.7,19.382a.559.559,0,0,1-.788,0,.556.556,0,0,1,0-.788l3.227-3.437a.091.091,0,0,0,0-.12l-4.615-4.7a.084.084,0,0,0-.146.06v9.4a1.375,1.375,0,0,0,1.371,1.371H19.83A1.375,1.375,0,0,0,21.2,19.8V10.4A.086.086,0,0,0,21.056,10.34Z" transform="translate(0 -0.952)" fill="currentcolor"></path><path id="Path_94" data-name="Path 94" d="M12.621,15.721a2.33,2.33,0,0,0,1.676-.7L21.02,8.175a1.347,1.347,0,0,0-.848-.3H5.074a1.338,1.338,0,0,0-.848.3l6.724,6.844A2.33,2.33,0,0,0,12.621,15.721Z" transform="translate(-0.332)" fill="currentcolor"></path></g></svg>`;
                link.parentNode.insertBefore(iconWrapper, link);
            });

            const mapData = [
                    @foreach($cities as $city)
                {
                    id: "map{{$city->id}}",
                    center: [{{$city->lat}}, {{$city->lng}}],
                    popupText: "{{$city->name}}"
                },
                @endforeach
            ];

            const createMap = ({ id, center, popupText }) => {
                const map = L.map(id, {
                    center,
                    zoom: 13,
                    zoomControl: false,
                    attributionControl: false
                });

                const customButton = L.Control.extend({
                    onAdd: function(map) {
                        const button = L.DomUtil.create('button', 'leaflet-button');
                        button.innerText = 'Otwórz w Google Maps';
                        button.onclick = function() {
                            let googleMapsUrl;
                            if(id === 'map3'){
                                googleMapsUrl = `https://maps.app.goo.gl/zjjmBEzu1ZrZxsqz9`;
                            } else {
                                const [lat, lng] = center;
                                googleMapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
                            }

                            window.open(googleMapsUrl, '_blank');
                        };
                        return button;
                    },
                    onRemove: function(map) {
                        // Cleanup if needed
                    }
                });

                L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                L.marker(center, {
                    icon: L.icon({
                        iconUrl: '{{ asset("img/marker.svg") }}',
                        iconSize: [55, 88],
                        iconAnchor: [28, 94],
                        popupAnchor: [0, -88]
                    })
                }).addTo(map).bindPopup(`<p class="fw-bold text-secondary fs-10">${popupText}</p>`);

                map.addControl(new customButton({ position: 'bottomright' }));
            };

            mapData.forEach(createMap);
        });

    </script>
@endpush
