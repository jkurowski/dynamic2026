@props([
    'title' => 'Galeria',
    'subtitle' => 'zdjęć',
    'only_slider' => false,
    'images' => [
        0 => [
            'url' => 'img/na-skraju/img_slider_bot_1_1.jpg',
            'alt' => 'Zdjęcie 1',
        ],
        1 => [
            'url' => 'img/na-skraju/img_slider_bot_1_2.jpg',
            'alt' => 'Zdjęcie 2',
        ],
        2 => [
            'url' => 'img/na-skraju/img_slider_bot_1_3.jpg',
            'alt' => 'Zdjęcie 3',
        ],
    ],
])
<section class="slider">
    @if (!$only_slider)
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                        <div class="position-absolute top-50 start-50 translate-middle z-2">
                            <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168">
                        </div>
                        <h2 class="fw-bold text-center text-uppercase">
                            @if ($title)
                                <span data-aos="fade-up" data-aos-delay="200">
                                    {{ $title }}
                                </span>
                            @endif
                            @if ($subtitle)
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">
                                    {{ $subtitle }}
                                </span>
                            @endif
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <?php
    $arrow_prev = '<button  class="slick-prev slick-arrow" aria-label="Previous" type="button">

                    <svg xmlns="http://www.w3.org/2000/svg" width="9.05" height="16.484" viewBox="0 0 9.05 16.484">
                    <path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M363.434-675.758,356-683.192l.808-.808,8.242,8.242-8.242,8.242-.808-.808Z" transform="translate(365.05 -667.516) rotate(180)" fill="#fff"/>
                  </svg>

                </button>';

    $arrow_next = '<button  class="slick-next slick-arrow" aria-label="Next" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="9.05" height="16.484" viewBox="0 0 9.05 16.484">
        <path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M363.434-675.758,356-683.192l.808-.808,8.242,8.242-8.242,8.242-.808-.808Z" transform="translate(-356 684)" fill="#fff"/>
        </svg>

            </button>';

    $slider_options = [
        'prevArrow' => $arrow_prev,
        'nextArrow' => $arrow_next,
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
    <div class="gallery-slider invests-horizontal-slider with-arrows mt-4" data-slick='<?= json_encode($slider_options) ?>'>
        @foreach ($images as $image)
            <div>
                <img loading="eager" src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" width="789" height="209" class="w-100 h-100 object-fit-cover">
            </div>
        @endforeach
    </div>
</section>
@push('scripts')
    <style>
        .gallery-slider .slick-track {
            display: flex !important;
        }

        .gallery-slider .slick-slide {
            height: inherit !important;
        }
    </style>
@endpush
