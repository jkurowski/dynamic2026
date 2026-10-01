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

        <section class="s2">
            <div class="container">
                <div class="row row-gap-4 align-items-center mb-50">
                    <div class="col-12">
                        <div style="--translate-x: 0;"
                            class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                    height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200">
                                    Aktualnie
                                </span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">
                                    poszukujemy
                                </span>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="row row-gap-4">
                    <div class="col-12 col-lg-10 offset-lg-1">
                        <div class="accordion" id="accordionPanelsStayOpenExample" data-aos="fade">
                            @foreach($data['jobofferts'] as $offer)
                            <div class="accordion-item shadow-post-article mb-30">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fs-24" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapse{{ $offer->id }}" aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapse{{ $offer->id }}">
                                        {{ $offer->name }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapse{{ $offer->id }}" class="accordion-collapse collapse @if($loop->first)show @endif">
                                    <div class="accordion-body">
                                        {!! $offer->text !!}
                                        <div class="pt-4 pt-md-40">
                                            <a target="_blank" href="mailto:{{ $offer->email }}?subject=Dot. oferty {{ urlencode($offer->name) }}" title="Aplikuj na stanowisko: {{ $offer->name }}"
                                               class="btn btn-primary btn-with-icon min-w-max-content flex-fill d-inline-flex align-items-center justify-content-center gap-1">
                                                Aplikuj
                                                <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z"  transform="translate(-356 684)" fill="currentColor"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
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
