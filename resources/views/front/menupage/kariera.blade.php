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
            'header' => 'img/kariera_bg.webp',
            'mb' => 100,
        ])

        <section class="s1">
            <div class="container">
                <div class="row row-gap-4 inline inline-tc">
                    <div class="col-12 col-md-6 col-lg-5">
                        <div style="--translate-x: 0;"
                            class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200" data-modaltytul="14">{{ getInline($array, 14, 'modaltytul') }}</span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400" data-modaleditor="14">{{ getInline($array, 14, 'modaleditor') }}</span>
                            </h2>
                        </div>
                        <div class="text-pretty mt-4 mt-md-40" data-aos="fade" data-modaleditortext="14">{!! getInline($array, 14, 'modaleditortext') !!}</div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5 offset-lg-2">
                        <div class="w-100 h-100" data-aos="fade">
                            <img src="{{ getInline($array, 14, 'file') }}" alt="{{ getInline($array, 14, 'file_alt') }}" data-img="14" loading="eager" class="img-fluid rounded">
                        </div>
                    </div>
                    {!! inlineEditButton(14, 'modallink,modallinkbutton') !!}
                </div>
            </div>
        </section>


        <section class="s3 d-none" id="aplikuj">
            <div class="container">
                <div class="row row-gap-4">
                    <div class="col-12 col-md-6 col-lg-5 col-xl-4">
                        <div style="--translate-x: 0;"
                            class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                    height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200">Dołącz</span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">do zespołu</span>
                            </h2>
                        </div>
                        <div class="text-pretty mt-4 mt-md-40 text-secondary" data-aos="fade">
                            <p>Będąc dynamicznie rozwijającą się polską firmą, w swojej pracy kierujemy się wartościami. Stawiamy przede wszystkim na ludzi, szanujemy środowisko naturalne, odpowiedzialnie traktujemy podjęte zobowiązania umowne i zawsze dotrzymujemy słowa. Takie podejście przekazujemy również naszym pracownikom.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5 offset-lg-2 offset-xl-3">
                        <div class="apply-form-container text-secondary" data-aos="fade">
                            <p class="fs-5 text-uppercase fw-semibold text-secondary">FORMULARZ</p>
                            @include('components.contact-form-kariera')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
@endpush
