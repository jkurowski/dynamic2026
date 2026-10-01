@extends('layouts.page', ['body_class' => 'offer'])

@section('content')
    <main>
        <section class="position-relative page-hero-section">
            <div class="position-absolute top-0 start-0 w-100 h-100 with-image-overlay-gradient ">
                <img src="{{ asset('img/oferta_bg.webp') }}" alt="" width="1920" height="386" loading="eager"
                    decoding="async" class="w-100 h-100 object-fit-cover">
            </div>
            <div class="container isolation-isolate">
                <div class="row row-gap-30">
                    <div class="col-12">
                        <nav aria-label="breadcrumb small text-white" data-aos="fade">
                            <ol class="breadcrumb opacity-50">
                                <li class="breadcrumb-item">
                                    <a href=""
                                        style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Strona
                                        główna</a>
                                </li>
                                <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                                    <a href="#"
                                        style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Oferta</a>
                                </li>

                            </ol>
                        </nav>
                    </div>
                    <div
                        class="col-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3 col-xxl-4 offset-xxl-4 text-white text-center">
                        <h1 class="h2 mb-3" data-aos="fade-up">Oferta</h1>
                        <p class="text-pretty" data-aos="fade-up" data-aos-delay="200">Lorem ipsum dolor sit amet,
                            consetetur
                            sadipscing elitr, sed diam nonumy eirmod tempor.</p>
                    </div>
                </div>
            </div>
        </section>
        @include('front.investments.search')

        <section>
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-gap-4">
                    @foreach ($investments as $investment)
                        <div class="col">
                            <?php
                            $link = '';
                            switch ($investment->id) {
                                case 16: // Na skraju
                                    $link = route('offer.na-skraju');
                                    break;
                                case 17: // Słonimska Residence II
                                    $link = route('offer.slonimska-residence-ii');
                                    break;
                                case 18: // Ogrody Andersena
                                    $link = route('offer.ogrody-andersena');
                                    break;
                                case 19: // DownTown
                                    $link = route('offer.downtown');
                                    break;
                                default:
                                    $link = '#';
                            }
                        ?>
                            @include('front.investments.slider-invest-card-vertical', [
                                'logo' => asset('img/offer_logo_id_' . $investment->id . '.jpg'),
                                'city' => $investment->city()->first()->name,
                                'name' => $investment->name,
                                'bg' => asset('img/offer_thumb_id_' . $investment->id . '.jpg'),
                                'link' => $link,
                                'delivery' => $investment->end_date,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-3">
                        <div style="--translate-x: 0;"
                            class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168"
                                    height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200">
                                    Aktualne
                                </span>
                                <span class="fw-900 fs-4 d-block text-center text-primary text-shadow" data-aos="fade-up"
                                    data-aos-delay="400">
                                    Promocje
                                </span>
                            </h2>
                        </div>
                        <div class="pt-4 mt-3 text-center" data-aos="fade">
                            <p>
                                Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $slider_options = [
                'arrows' => false,
                'mobileFirst' => true,
                'centerMode' => true,
                'slidesToShow' => 1,
                'centerPadding' => '15px',
                'responsive' => [
                    [
                        'breakpoint' => 576,
                        'settings' => [
                            'centerPadding' => '10%',
                        ],
                    ],
                    [
                        'breakpoint' => 768,
                        'settings' => [
                            'centerPadding' => '15%',
                        ],
                    ],
                    [
                        'breakpoint' => 1199,
                        'settings' => [
                            'centerPadding' => 'calc(505 / 1920 * 100vw)',
                        ],
                    ],
                ],
            ];
            ?>
            <div class="invests-horizontal-slider mt-4" data-slick='<?= json_encode($slider_options) ?>'>
                <div class='h-auto'>
                    @include('front.investments.slider-invest-card-horizontal')
                </div>
                <div class='h-auto'>
                    @include('front.investments.slider-invest-card-horizontal')
                </div>
                <div class='h-auto'>
                    @include('front.investments.slider-invest-card-horizontal')

                </div>
                <div class='h-auto'>
                    @include('front.investments.slider-invest-card-horizontal')

                </div>
                <div class='h-auto'>
                    @include('front.investments.slider-invest-card-horizontal')
                </div>
            </div>
        </section>
    </main>
@endsection

