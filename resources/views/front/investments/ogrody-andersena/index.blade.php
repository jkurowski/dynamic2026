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
                                       style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Oferta</a>
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
                    </div>
                </div>
            </div>
        </section>

        @include('front.investments.single-investment-search', ['status' => $investment->status, 'price' => $investment])
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

            <section class="s1 mt-5" id="opis-inwestycji">
                <div class="container">
                    <div class="row row-gap-4 align-items-center">
                        <div class="col-12 col-md-6 col-lg-5 order-last order-md-0 ">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 19, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 19, 'file_alt') }}"
                                     loading="eager"
                                >
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 offset-lg-1">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 19, 'title') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 19, 'content') !!}
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="s2">
                <div class="container">
                    <div class="row">
                        <div class="col-12 py-5">
                            <div class="row row-gap-3 justify-content-center">
                                <div class="col-6 col-sm-4">
                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 87px; height: 87px;">
                                        <img src="{{ asset('img/ogrody-andersena/ico_mieszkania.png') }}" width="42"
                                             height="42" alt="" loading="lazy" decoding="async" class="img-fluid">
                                    </div>
                                    <p class="text-secondary text-center mt-3 mt-lg-30">Funkcjonalne mieszkania</p>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 87px; height: 87px;">
                                        <img src="{{ asset('img/ogrody-andersena/ico_okna.png') }}" width="42"
                                             height="42" alt="" loading="lazy" decoding="async" class="img-fluid">
                                    </div>
                                    <p class="text-secondary text-center mt-3 mt-lg-30">Duże okna</p>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 87px; height: 87px;">
                                        <img src="{{ asset('img/ogrody-andersena/ico_windy.png') }}" width="42"
                                             height="42" alt="" loading="lazy" decoding="async"
                                             class="img-fluid">
                                    </div>
                                    <p class="text-secondary text-center mt-3 mt-lg-30">Cichobieżne windy</p>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 87px; height: 87px;">
                                        <img src="{{ asset('img/ogrody-andersena/ico_garaz.png') }}" width="42"
                                             height="42" alt="" loading="lazy" decoding="async"
                                             class="img-fluid">
                                    </div>
                                    <p class="text-secondary text-center mt-3 mt-lg-30">Miejsca garażowe do kazdego
                                        mieszkania, również rodzinne</p>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 87px; height: 87px;">
                                        <img src="{{ asset('img/ogrody-andersena/ico_wysokosc.png') }}" width="42"
                                             height="42" alt="" loading="lazy" decoding="async"
                                             class="img-fluid">
                                    </div>
                                    <p class="text-secondary text-center mt-3 mt-lg-30">280cm wysokość pomieszczeń</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-gap-4 align-items-center">

                        <div class="col-12 col-md-6 col-lg-5">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 20, 'title') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 20, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 offset-lg-1">
                            <div class="w-100 h-100" data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 20, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 20, 'file_alt') }}"
                                     loading="lazy"
                                     decoding="async"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="s3">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-lg-8 position-relative">
                            <div data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 21, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 21, 'file_alt') }}"
                                     loading="lazy"
                                     decoding="async"
                                >
                            </div>
                            <div class="position-absolute-lg translate-middle-y-lg top-50 w-calc-lg">
                                <div class="row">
                                    <div class="col-12 col-xl-10 offset-xl-1" data-aos="fade-up">
                                        <div class="bg-white p-3 px-xl-40">
                                            <div class="row row-gap-3 justify-content-center">
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_strefy.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Strefy rekreacyjne
                                                        dla mieszkańców</p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_plac_zabaw.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Plac zabaw dla
                                                        dzieci</p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_rowerownia.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Rowerownia</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 order-first order-lg-0">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 21, 'title') }}
                                    </span>

                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 21, 'content') !!}
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="s4" id='atuty'>
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-lg-5 ">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 22, 'title') }}
                                    </span>

                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 22, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 position-relative">
                            <div data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 22, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 22, 'file_alt') }}"
                                     loading="lazy"
                                     decoding="async"
                                >
                            </div>
                            <div class="position-absolute-lg translate-middle-y-lg top-50 w-calc-lg">
                                <div class="row">
                                    <div class="col-12 col-xl-10 offset-xl-1" data-aos="fade-up">
                                        <div class="bg-white p-3 px-xl-40">
                                            <div class="row row-gap-3 justify-content-center">
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_ogrodki.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Prywatne ogródki
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_balkony.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Tarasy i balkony
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_disabled.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Udogodnienia dla
                                                        niepełnosprawnych</p>
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

            <section class="s5">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-lg-7 position-relative">
                            <div data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 23, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 23, 'file_alt') }}"
                                     loading="lazy"
                                     decoding="async"
                                >
                            </div>
                            <div class="position-absolute-lg translate-middle-y-lg top-50 w-calc-lg">
                                <div class="row">
                                    <div class="col-12 col-xl-10 offset-xl-1" data-aos="fade-up">
                                        <div class="bg-white p-3 px-xl-40">
                                            <div class="row row-gap-3 justify-content-center">
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_centrum.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">20 min. samochodem
                                                        do centrum Warszawy
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_przystanki.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Przystanki
                                                        autobusowe
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_skm.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">SKM - tylko 7 min.
                                                        do stacji Warszawa Wileńska</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5 order-first order-md-0">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 23, 'title') }}
                                    </span>

                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 23, 'content') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="s6 mt-lg-5">
                <div class="container">
                    <div class="row row-gap-4">
                        <div class="col-12 col-lg-5 ">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 24, 'title') }}
                                    </span>

                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 24, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 position-relative">
                            <div data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 24, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 24, 'file_alt') }}"
                                     loading="lazy"
                                     decoding="async"
                                >
                            </div>
                            <div class="position-absolute-lg translate-middle-y-20-lg top-50 w-calc-lg">
                                <div class="row">
                                    <div class="col-12 col-xl-10 offset-xl-1" data-aos="fade-up">
                                        <div class="bg-white p-3 px-xl-40">
                                            <div class="row row-gap-3 justify-content-center">
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_monitoring.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Monitoring
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_zabudowa.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Jednorodzinna
                                                        zabudowa w sąsiedztwie
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_las.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Blisko lasu
                                                        rembertowskiego - ok. 10 min. spacerem</p>
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

            <section class="s7 mt-lg-5">
                <div class="container mt-lg-5">
                    <div class="row row-gap-4">

                        <div class="col-12 col-lg-7 position-relative">
                            <div data-aos="fade">
                                <img class="img-fluid rounded w-100"
                                     src="{{ investmentSection($investment->sections, 25, 'file') }}"
                                     alt="{{ investmentSection($investment->sections, 25, 'file_alt') }}"
                                     loading="lazy"
                                     decoding="async"
                                >
                            </div>
                            <div class="position-absolute-lg translate-middle-y-lg top-50 w-calc-lg">
                                <div class="row">
                                    <div class="col-12 col-xl-10 offset-xl-1" data-aos="fade-up">
                                        <div class="bg-white p-3 px-xl-40">
                                            <div class="row row-gap-3 justify-content-center">
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_sklepy.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Sklepy
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_restauracje.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Restauracje
                                                    </p>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <div class="bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 87px; height: 87px;">
                                                        <img src="{{ asset('img/ogrody-andersena/ico_szkoly.png') }}"
                                                             width="42" height="42" alt="" loading="lazy"
                                                             decoding="async" class="img-fluid">
                                                    </div>
                                                    <p class="text-secondary text-center mt-3 mt-lg-30">Szkoły i
                                                        przedzszkola</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5 order-first order-md-0">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 25, 'title') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 25, 'content') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section-localization mt-50" id="lokalizacja">
                <div class="container">
                    <div class="row row-gap-4  align-items-center">
                        <div class="col-12 col-md-6 col-lg-5">
                            <div style="--translate-x: 0;"
                                 class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                                <div class="position-absolute top-50 start-50 translate-middle z-2">
                                    <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                         height="168" loading="lazy" decoding="async" data-aos="fade">
                                </div>
                                <h2 class="fw-bold text-center text-uppercase">
                                    <span data-aos="fade-up" data-aos-delay="200">
                                        {{ investmentSection($investment->sections, 26, 'title') }}
                                    </span>
                                </h2>
                            </div>
                            <div class="text-pretty mt-4 mt-md-40" data-aos="fade">
                                {!! investmentSection($investment->sections, 26, 'content') !!}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 offset-lg-1">
                            <div class="ratio ratio-16x9" data-aos="fade">
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>


                    <div class="row justify-content-center pt-md-50 mt-30">
                        <div class="col-12">
                            <p class="h5 fw-semibold text-secondary mb-3 mb-md-30">Infrastruktura w otoczeniu inwestycji
                            </p>
                        </div>
                        <?php
                        $list1 = [
                            [
                                'name' => 'Basen, kręgielnia, squash',
                                'distance' => '1,3 km - 18 min',
                            ],
                            [
                                'name' => 'Boisko sportowe',
                                'distance' => '1,6 km - 23 min',
                            ],
                            [
                                'name' => 'Korty tenisowe',
                                'distance' => '1,6 km - 22 min',
                            ],
                            [
                                'name' => 'Park miejski',
                                'distance' => '2,3 km - 32 min',
                            ],
                            [
                                'name' => 'Plac zabaw',
                                'distance' => '600 m - 8 min',
                            ],
                            [
                                'name' => 'Centrum handlowe',
                                'distance' => '1,2 km - 18 min',
                            ],
                            [
                                'name' => 'Market spożywczy',
                                'distance' => '60 m - 1 min',
                            ],
                        ];

                        $list2 = [
                            [
                                'name' => 'Biedronka',
                                'distance' => '1,1 km – 16 min',
                            ],
                            [
                                'name' => 'Lidl',
                                'distance' => '1,6 km - 22 min',
                            ],
                            [
                                'name' => 'Poczta',
                                'distance' => '600 m - 8 min',
                            ],
                            [
                                'name' => 'Bazar',
                                'distance' => '400 m - 5 min',
                            ],
                            [
                                'name' => 'Apteka',
                                'distance' => '450 m - 6 min',
                            ],
                            [
                                'name' => 'Przystanek autobusowy linia 245',
                                'distance' => '300 m - 4 min',
                            ],

                            [
                                'name' => 'Stacja kolejowa',
                                'distance' => '2,2 km - 31 min',
                            ],
                        ];

                        $list3 = [
                            [
                                'name' => 'Żłobek',
                                'distance' => '50 m - 1 min',
                            ],
                            [
                                'name' => 'Przedszkole publiczne',
                                'distance' => '400 m - 5 min',
                            ],
                            [
                                'name' => 'Szkoła podstawowa',
                                'distance' => '700 m - 10 min',
                            ],
                            [
                                'name' => 'Kościół',
                                'distance' => '550 m - 8 min',
                            ],
                            [
                                'name' => 'Centrum medyczne',
                                'distance' => '50 m - 1 min',
                            ],
                            [
                                'name' => 'Miejska przychodnia',
                                'distance' => '2,1 km - 29 min',
                            ],
                        ];

                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <ul class="mb-0">
                                @foreach ($list1 as $item)
                                    <li>
                                        <div class="d-inline-flex justify-content-between gap-3 w-100">
                                            <p>
                                                {{ $item['name'] }}
                                            </p>
                                            <p class="text-secondary">
                                                {{ $item['distance'] }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <ul class="mb-0">
                                @foreach ($list2 as $item)
                                    <li>
                                        <div class="d-inline-flex justify-content-between gap-3 w-100">
                                            <p>
                                                {{ $item['name'] }}
                                            </p>
                                            <p class="text-secondary">
                                                {{ $item['distance'] }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <ul class="">
                                @foreach ($list3 as $item)
                                    <li>
                                        <div class="d-inline-flex justify-content-between gap-3 w-100">
                                            <p>
                                                {{ $item['name'] }}
                                            </p>
                                            <p class="text-secondary">
                                                {{ $item['distance'] }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
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
                center: [52.282791795930834, 21.129569915341722],
                zoom: 13,
            });

            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            }).addTo(leafletMap);

            L.marker([52.282791795930834, 21.129569915341722], {
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
                        const googleMapsUrl = `https://www.google.com/maps?q=52.282791795930834, 21.129569915341722`;
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
@endpush
