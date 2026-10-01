@extends('layouts.page', ['body_class' => 'single-offer'])

@php

    /* TITLE */
    if(!empty($investment->meta_title)) {
        $seoTitle = $investment->meta_title;
    }
    elseif(!empty($page->meta_title)) {
        $seoTitle = $page->meta_title;
    }
    else {
        $seoTitle = settings()->get("page_title") . ' - ' . $page->title . ' - ' . $investment->name;
    }

    /* DESCRIPTION */
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

@section('content')
    <main>
        <section class="position-relative page-hero-section">
            <div class="position-absolute top-0 start-0 w-100 h-100">
                @if($investment->file_header)
                    <img src="{{ asset('investment/header/'.$investment->file_header) }}" alt="" width="1920" height="386" loading="eager" decoding="async" class="w-100 h-100 object-fit-cover">
                    <div style="position: absolute;opacity: 0.7;width: 100%;height: 100%;top: 0;left: 0;background-image: linear-gradient(#000, rgba(255, 255, 255, 0) {{ $investment->gradient_header ?: '100%' }});"></div>
                @else
                    <div style="position: absolute;width: 100%;height: 100%;top: 0;left: 0;background:#052748"></div>
                @endif
            </div>
            <div class="container isolation-isolate">
                <div class="row row-gap-30">
                    <div class="col-12">
                        <nav aria-label="breadcrumb small text-white" data-aos="fade">
                            <ol class="breadcrumb opacity-50">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('index') }}"
                                       style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Strona
                                        główna</a>
                                </li>
                                <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                                    <a href="#"
                                       style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Oferta
                                        mieszkania</a>
                                </li>
                                <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                                    <a href="#"
                                       style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">{{ $investment->name }}</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div
                        class="col-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3 col-xxl-4 offset-xxl-4 text-white text-center">
                        <h1 class="h2 mb-3" data-aos="fade-up">{{ $investment->name }}</h1>
                        <p class="text-pretty" data-aos="fade-up" data-aos-delay="200">
                            „Na Skraju”, to nasza najnowsza
                            inwestycja usytuowana w dynamicznie rozwijającej się
                            dzielnicy Ursus przy ul. Henryka Brodatego 51.</p>
                    </div>
                </div>
            </div>
        </section>

        @include('front.investments.single-investment-search', ['investment' => $investment, 'status' => $investment->status])
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

        <div data-bs-spy="scroll" data-bs-target="#navbar-secondary" class="position-relative with-bigger-section-spacing">

            <section id='opis-inwestycji' class="s1 pt-40">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-md-6">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 10, 'title') }}
                                    </span>
                                    <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">
                                        {{ investmentSection($investment->sections, 10, 'subtitle') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 10, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 offset-lg-1">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 10, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 10, 'file_alt') }}"
                                     loading="eager"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="s2">
                <div class="container">
                    <div class="row row-gap-4 align-items-center">
                        <div class="col-12 col-md-6 col-lg-5 order-last order-md-0">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 11, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 11, 'file_alt') }}"

                                     decoding="async"
                                >
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 offset-lg-1">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 11, 'title') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 11, 'content') !!}
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="s3 mb-130-lg">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-lg-4 ">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 12, 'title') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 12, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-lg-8 position-relative">
                            <div data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 12, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 12, 'file_alt') }}"

                                     decoding="async"
                                >
                            </div>
                            <div class="position-absolute-lg translate-middle-y-lg top-50 w-calc-lg">
                                <div class="row">
                                    <div class="col-12 col-xl-10 offset-xl-1" data-aos="fade-up">
                                        <div class="bg-white p-3 px-xl-40">
                                            <div class="col-12  col-xxl-6 offset-xxl-3">
                                                <p class="text-center text-pretty mb-20">Położyliśmy nacisk na optymalne wykorzystanie przestrzeni przy zachowaniu:</p>
                                            </div>

                                            <div class="row row-gap-3 justify-content-center">
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 87px; height: 87px;">
                                                        <img src="{{ asset("img/circle_plans.svg") }}" width="42" height="42" alt=""  decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Funkcjonalności<br> pomieszczeń</p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 87px; height: 87px;">
                                                        <img src="{{ asset("img/circle_living_room.svg") }}" width="42" height="42" alt=""  decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Różnorodności<br> aranżacyjnych<br> rozwiązań</p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 87px; height: 87px;">
                                                        <img src="{{ asset("img/circle_area.svg") }}" width="42" height="42" alt=""  decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Dużej wygody<br> użytkowania</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="s4">
                <div class="container">
                    <div class="row row-gap-4 align-items-center">
                        <div class="col-12 col-md-6 col-lg-5 order-last order-md-0">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 13, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 13, 'file_alt') }}"

                                     decoding="async"
                                >
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 offset-lg-1">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 13, 'title') }}
                                    </span>

                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 13, 'content') !!}
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="s5">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-md-6 col-lg-5 offset-lg-2">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 14, 'title') }}
                                    </span>

                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 14, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-5">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 14, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 14, 'file_alt') }}"

                                     decoding="async"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="s6">
                <div class="container">
                    <div class="row row-gap-4 align-items-center">
                        <div class="col-12 col-md-6 col-lg-5 order-last order-md-0">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 15, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 15, 'file_alt') }}"

                                     decoding="async"
                                >
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 offset-lg-1">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 15, 'title') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 15, 'content') !!}
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="s7">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-md-6 col-lg-5">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 16, 'title') }}okolica
                                    </span>

                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 16, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 offset-lg-1">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 16, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 16, 'file_alt') }}"

                                     decoding="async"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="s8">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-md-10 col-xl-8 offset-md-1 offset-xl-2">
                            <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 17, 'title') }}
                                    </span>

                                </h2>
                            </div>
                            <div class="row  mt-4 mt-md-40">
                                {!! investmentSection($investment->sections, 17, 'content') !!}
                            </div>

                        </div>
                    </div>
                    <div class="row row-gap-4 mt-4 mt-md-50">
                        <div class="col-6 col-md-3">
                            <div data-aos="fade">
                                <img class="w-100 h-100 object-fit-cover" src="{{ asset("/img/o_inwestycji_s8_1.webp") }}" alt="" width="672" height="448"  decoding="async">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="mt-md-3 mt-lg-30" data-aos="fade">
                                <img class="w-100 h-100 object-fit-cover" src="{{ asset("/img/o_inwestycji_s8_2.webp") }}" alt="" width="672" height="448"  decoding="async">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div data-aos="fade">
                                <img class="w-100 h-100 object-fit-cover" src="{{ asset("/img/o_inwestycji_s8_3.webp") }}" alt="" width="672" height="448"  decoding="async">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="mt-md-3 mt-lg-30" data-aos="fade">
                                <img class="w-100 h-100 object-fit-cover" src="{{ asset("/img/o_inwestycji_s8_4.webp") }}" alt="" width="672" height="448"  decoding="async">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="lokalizacja" class="s9">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-md-6 col-lg-5">
                            <div style="--translate-x: -25%;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168"  decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 18, 'title') }}
                                    </span>
                                    <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">
                                        {{ investmentSection($investment->sections, 18, 'subtitle') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 18, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 offset-lg-1">
                            <div class="ratio ratio-16x9" data-aos="fade">
                                <div id="map" class="leaflet-container leaflet-touch leaflet-retina leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom" tabindex="0"><div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(0px, 0px, 0px);"><div class="leaflet-pane leaflet-tile-pane"><div class="leaflet-layer " style="z-index: 1; opacity: 1;"><div class="leaflet-tile-container leaflet-zoom-animated" style="z-index: 19; transform: translate3d(0px, 0px, 0px) scale(1);"><img alt="" src="https://tile.openstreetmap.org/13/4538/2714.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(58px, -37px, 0px); opacity: 1;"><img alt="" src="https://tile.openstreetmap.org/13/4539/2714.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(314px, -37px, 0px); opacity: 1;"><img alt="" src="https://tile.openstreetmap.org/13/4538/2715.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(58px, 219px, 0px); opacity: 1;"><img alt="" src="https://tile.openstreetmap.org/13/4539/2715.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(314px, 219px, 0px); opacity: 1;"><img alt="" src="https://tile.openstreetmap.org/13/4537/2714.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-198px, -37px, 0px); opacity: 1;"><img alt="" src="https://tile.openstreetmap.org/13/4540/2714.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(570px, -37px, 0px); opacity: 1;"><img alt="" src="https://tile.openstreetmap.org/13/4537/2715.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-198px, 219px, 0px); opacity: 1;"><img alt="" src="https://tile.openstreetmap.org/13/4540/2715.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(570px, 219px, 0px); opacity: 1;"></div></div></div><div class="leaflet-pane leaflet-overlay-pane"></div><div class="leaflet-pane leaflet-shadow-pane"></div><div class="leaflet-pane leaflet-marker-pane"><img src="img/marker.svg" class="leaflet-marker-icon leaflet-zoom-animated leaflet-interactive" alt="Marker" tabindex="0" role="button" style="margin-left: -28px; margin-top: -94px; width: 55px; height: 88px; transform: translate3d(332px, 186px, 0px); z-index: 186;"></div><div class="leaflet-pane leaflet-tooltip-pane"></div><div class="leaflet-pane leaflet-popup-pane"></div><div class="leaflet-proxy leaflet-zoom-animated"></div></div><div class="leaflet-control-container"><div class="leaflet-top leaflet-left"><div class="leaflet-control-zoom leaflet-bar leaflet-control"><a class="leaflet-control-zoom-in" href="#" title="Zoom in" role="button" aria-label="Zoom in" aria-disabled="false"><span aria-hidden="true">+</span></a><a class="leaflet-control-zoom-out" href="#" title="Zoom out" role="button" aria-label="Zoom out" aria-disabled="false"><span aria-hidden="true">−</span></a></div></div><div class="leaflet-top leaflet-right"></div><div class="leaflet-bottom leaflet-left"></div><div class="leaflet-bottom leaflet-right"><div class="leaflet-control-attribution leaflet-control"><a href="https://leafletjs.com" title="A JavaScript library for interactive maps"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" class="leaflet-attribution-flag"><path fill="#4C7BE1" d="M0 0h12v4H0z"></path><path fill="#FFD500" d="M0 4h12v3H0z"></path><path fill="#E0BC00" d="M0 7h12v1H0z"></path></svg> Leaflet</a> <span aria-hidden="true">|</span> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a></div></div></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center pt-md-50 mt-30">
                        <div class="col-12">
                            <p class="h5 fw-semibold text-secondary mb-3 mb-md-30">Infrastruktura w otoczeniu inwestycji:</p>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                            <ul>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            przystanek autobusowy linii 187, 194, N35:
                                        </p>
                                        <p class="text-secondary">
                                            600m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            przystanek autobusowy linii 716, N85:
                                        </p>
                                        <p class="text-secondary">
                                            750m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            przystanek autobusowy linii 501, 517:
                                        </p>
                                        <p class="text-secondary">
                                            950m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            przystanek autobusowy linii 177:
                                        </p>
                                        <p class="text-secondary">
                                            800m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            stacja kolejowa:
                                        </p>
                                        <p class="text-secondary">
                                            1,5km
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            plac zabaw:
                                        </p>
                                        <p class="text-secondary">
                                            350m
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                            <ul>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            tereny sportowe i rekreacyjne:
                                        </p>
                                        <p class="text-secondary">
                                            900m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            park:
                                        </p>
                                        <p class="text-secondary">
                                            1,3km
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            centrum handlowe:
                                        </p>
                                        <p class="text-secondary">
                                            850m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            żłobek:
                                        </p>
                                        <p class="text-secondary">
                                            400m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            przedszkole publiczne
                                        </p>
                                        <p class="text-secondary">
                                            800m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            przychodnia:
                                        </p>
                                        <p class="text-secondary">
                                            1,2km
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                            <ul>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            Szkoła podstawowa:
                                        </p>
                                        <p class="text-secondary">
                                            650m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            market spożywczy:
                                        </p>
                                        <p class="text-secondary">
                                            950m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            sklep osiedlowy:
                                        </p>
                                        <p class="text-secondary">
                                            500m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            bazar:
                                        </p>
                                        <p class="text-secondary">
                                            1,3km
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            kościół:
                                        </p>
                                        <p class="text-secondary">
                                            900m
                                        </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-inline-flex justify-content-between gap-3 w-100">
                                        <p>
                                            apteka:
                                        </p>
                                        <p class="text-secondary">
                                            950m
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            @if($images && $images->isNotEmpty())
                @include('front.investments.gallery-grid', [
                    'images' => $images
                ])
            @endif

            <div id="kontakt">
                @include('layouts.partials.cta', ['pageTitle' => 'Strona inwestycji '.$investment->name, 'investmentName' => $investment->name, 'investmentId' => $investment->id, 'back' => true, 'investmentText' => $investment->contact_content])
            </div>
        </div>

    </main>
@endsection
@push('scripts')
    <script defer src="{{ asset('js/leaflet.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}" onload="this.media='all'" media="print" />
    <script defer>
        document.addEventListener("DOMContentLoaded", function() {
            const leafletMap = L.map("map", {
                center: [52.197750, 20.856528],
                zoom: 13,
            });

            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(leafletMap);

            L.marker([52.197750, 20.856528], {
                icon: L.icon({
                    iconUrl: '{{ asset("img/marker.svg") }}',
                    iconSize: [55, 88],
                    iconAnchor: [28, 94],
                    popupAnchor: [0, -88],
                })
            }).addTo(leafletMap).bindPopup(
                '<p class="fw-bold text-white">Inwestycja „{{ $investment->name }}”</p>'
            );

            const customButton = L.Control.extend({
                onAdd: function(map) {
                    const button = L.DomUtil.create('button', 'leaflet-button');
                    button.innerText = 'Otwórz w Google Maps';
                    button.style.padding = '5px 10px';
                    button.style.cursor = 'pointer';

                    button.onclick = function() {
                        const googleMapsUrl = `https://www.google.com/maps?q=52.197750,20.856528`;
                        window.open(googleMapsUrl, '_blank');
                    };

                    return button;
                },
                onRemove: function(map) {
                    // Cleanup if needed
                }
            });

            leafletMap.addControl(new customButton({ position: 'bottomleft' }));
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#navbar-secondary .scroll").click(function (e) {
                e.preventDefault();
                const section = $(this).attr('href');
                const $target = $(section);

                if ($target.length) {
                    $('html, body').stop().animate({
                        scrollTop: $target.offset().top - 120
                    }, 1500, 'easeInOutExpo');
                } else {
                    console.warn("Target section not found: " + section);
                }
            });
        });

        $(window).on('load', function() {
            if (window.location.hash) {
                const section = window.location.hash;
                const $target = $(section);

                if ($target.length) {
                    $('html, body').stop().animate({
                        scrollTop: $target.offset().top - 120
                    }, 1500, 'easeInOutExpo');
                }

                // Optional: clear the hash
                history.replaceState(null, null, ' ');
            }
        });
    </script>
@endpush
