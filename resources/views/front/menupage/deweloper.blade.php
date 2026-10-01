@extends('layouts.page', ['body_class' => 'homepage'])

@section('meta_title', $page->title)
@section('seo_title', $page->meta_title)
@section('seo_description', $page->meta_description)
@section('seo_robots', $page->meta_robots)

@section('content')
    <main class="with-bigger-section-spacing">

        @include('layouts.partials.page-header', [
            'h1' => $page->title,
            'desc' => $page->title_text,
            'header' => 'img/deweloper_bg.webp',
            'mb' => 100,
        ])

        <section class="s1">
            <div class="container">
                <div class="row row-gap-4 inline inline-tc">
                    <div class="col-12 col-md-6 col-lg-5">
                        <div style="--translate-x: 0;"
                             class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                     height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200" data-modaltytul="1">{{ getInline($array, 1, 'modaltytul') }}</span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400" data-modaleditor="1">{{ getInline($array, 1, 'modaleditor') }}</span>
                            </h2>
                        </div>
                        <div class="text-pretty mt-4 mt-md-40" data-aos="fade" data-modaleditortext="1">{!! getInline($array, 1, 'modaleditortext') !!}</div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5 offset-lg-2">
                        <div class="w-100 h-100" data-aos="fade">
                            <img src="{{ getInline($array, 1, 'file') }}" alt="{{ getInline($array, 1, 'file_alt') }}" data-img="1" loading="eager" class="img-fluid rounded">
                        </div>
                    </div>
                    {!! inlineEditButton(1, 'modallink,modallinkbutton') !!}
                </div>
            </div>
        </section>
        <section class="s2">
            <div class="container inline inline-tc">
                <div class="row row-gap-4 align-items-center pb-5">
                    <div class="col-12">
                        <div style="--translate-x: 0;"
                             class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                     height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200" data-modaltytul="2">{{ getInline($array, 2, 'modaltytul') }}</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="text-pretty " data-aos="fade" data-modaleditortext="2">{!! getInline($array, 2, 'modaleditortext') !!}</div>
                    </div>
                </div>
                {!! inlineEditButton(2, 'modaleditor,modallink,modallinkbutton,file,file_alt') !!}
            </div>
        </section>

        <section class="s3">
            <div class="container inline inline-tc">
                <div class="row row-gap-4 align-items-center">
                    <div class="col-12 col-md-6 col-lg-4 offset-xl-3 text-center text-md-start order-last order-md-0">
                        <div class="w-100 h-100" data-aos="fade">
                            <img src="{{ getInline($array, 3, 'file') }}"
                                 alt="{{ getInline($array, 3, 'file_alt') }}"
                                 data-img="3"
                                 loading="lazy"
                                 decoding="async"
                                 class="img-fluid rounded"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-7 offset-lg-1 offset-xl-0 col-xl-5">
                        <div class="text-pretty" data-aos="fade" data-modaleditortext="3">{!! getInline($array, 3, 'modaleditortext') !!}</div>
                    </div>
                </div>
                {!! inlineEditButton(3, 'modaltytul,modaleditor,modallink,modallinkbutton') !!}
            </div>
        </section>

        <section class="s4">
            <div class="container">
                <div class="row row-gap-4 align-items-center mb-50">
                    <div class="col-12">
                        <div style="--translate-x: 0;"
                             class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200">Misja</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row row-gap-4">

                    <div class="col-12 col-sm-6 col-lg-3 inline inline-tc">
                        <div data-aos="fade">
                            <div style="width:87px; height:87px;" class="mb-30 bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ getInline($array, 4, 'file') }}"
                                     alt="{{ getInline($array, 4, 'file_alt') }}"
                                     data-img="4"
                                     loading="lazy"
                                     decoding="async"
                                     width="42"
                                     height="42"
                                >
                            </div>
                            <p class="fs-5 fw-semibold text-secondary text-center mb-4" data-modaltytul="4">{{ getInline($array, 4, 'modaltytul') }}</p>
                            <div data-modaleditortext="4">{!! getInline($array, 4, 'modaleditortext') !!}</div>
                        </div>
                        {!! inlineEditButton(4, 'modaleditor,modallink,modallinkbutton') !!}
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 inline inline-tc">
                        <div data-aos="fade">
                            <div style="width:87px; height:87px;" class="mb-30 bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ getInline($array, 5, 'file') }}"
                                     alt="{{ getInline($array, 5, 'file_alt') }}"
                                     data-img="5"
                                     loading="lazy"
                                     decoding="async"
                                     width="42"
                                     height="42"
                                >
                            </div>
                            <p class="fs-5 fw-semibold text-secondary  text-center mb-4" data-modaltytul="5">{{ getInline($array, 5, 'modaltytul') }}</p>
                            <div data-modaleditortext="5">{!! getInline($array, 5, 'modaleditortext') !!}</div>
                        </div>
                        {!! inlineEditButton(5, 'modaleditor,modallink,modallinkbutton') !!}
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 inline inline-tc">
                        <div data-aos="fade">
                            <div style="width:87px; height:87px;" class="mb-30 bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ getInline($array, 6, 'file') }}"
                                     alt="{{ getInline($array, 6, 'file_alt') }}"
                                     data-img="6"
                                     loading="lazy"
                                     decoding="async"
                                     width="42"
                                     height="42"
                                >
                            </div>
                            <p class="fs-5 fw-semibold text-secondary  text-center mb-4" data-modaltytul="6">{{ getInline($array, 6, 'modaltytul') }}</p>
                            <div data-modaleditortext="6">{!! getInline($array, 6, 'modaleditortext') !!}</div>
                        </div>
                        {!! inlineEditButton(6, 'modaleditor,modallink,modallinkbutton') !!}
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 inline inline-tc">
                        <div data-aos="fade">
                            <div style="width:87px; height:87px;" class="mb-30 bg-white icon-shadow mx-auto rounded-circle d-flex align-items-center justify-content-center">
                                <img src="{{ getInline($array, 7, 'file') }}"
                                     alt="{{ getInline($array, 7, 'file_alt') }}"
                                     data-img="7"
                                     loading="lazy"
                                     decoding="async"
                                     width="42"
                                     height="42"
                                >
                            </div>
                            <p class="fs-5 fw-semibold text-secondary  text-center mb-4" data-modaltytul="7">{{ getInline($array, 7, 'modaltytul') }}</p>
                            <div data-modaleditortext="7">{!! getInline($array, 7, 'modaleditortext') !!}</div>
                        </div>
                        {!! inlineEditButton(7, 'modaleditor,modallink,modallinkbutton') !!}
                    </div>
                </div>
            </div>
        </section>

        <section class="s5">
            <div class="container inline inline-tc">
                <div class="row row-gap-4 align-items-center pb-5">
                    <div class="col-12">
                        <div style="--translate-x: 0;"
                             class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200" data-modaltytul="8">{{ getInline($array, 8, 'modaltytul') }}</span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400" data-modaleditor="8">{{ getInline($array, 8, 'modaleditor') }}</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row row-gap-4 align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="w-100 h-100" data-aos="fade">
                            <img src="{{ getInline($array, 8, 'file') }}"
                                 alt="{{ getInline($array, 8, 'file_alt') }}"
                                 data-img="8"
                                 loading="lazy"
                                 decoding="async"
                                 class="img-fluid rounded"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-5 offset-lg-1">
                        <div class="text-pretty" data-aos="fade" data-modaleditortext="8">{!! getInline($array, 8, 'modaleditortext') !!}</div>
                    </div>
                </div>
                {!! inlineEditButton(8, 'modallink,modallinkbutton') !!}
            </div>
        </section>

        <div id="kontakt">
            @include('layouts.partials.cta', ['pageTitle' => $page->title, 'back' => true])
        </div>
    </main>
@endsection
