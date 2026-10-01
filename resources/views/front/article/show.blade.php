@extends('layouts.page', ['body_class' => 'homepage'])

@section('meta_title', $page->title .' - '.$article->title)
@section('seo_title',$page->meta_title)
@section('seo_description', $page->meta_description)
@section('seo_robots', $page->meta_robots)

@section('content')
    <main class="with-bigger-section-spacing">

        <section class="position-relative page-hero-section pb-0 min-h-auto">
            <div class="container isolation-isolate">
                <div class="row row-gap-30">
                    <div class="col-12">
                        <nav aria-label="breadcrumb small text-white" data-aos="fade">
                            <ol class="breadcrumb breadcrumb-black ">
                                <li class="breadcrumb-item">
                                    <a href="/">Strona główna</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{route('aktualnosci.index')}}">Aktualności</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">{{$article->title}}</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10">
                        <div class="text-secondary text-center post-hero-padding">
                            <h1 class="h2">{{$article->title}}</h1>
                            <p class="fs-14 fw-900 ff-secondary">{{$article->posted_at}}</p>
                            <picture>
                                @if($article->file_webp)
                                    <source type="image/webp" srcset="{{asset('uploads/articles/webp/'.$article->file_webp) }}">
                                @endif
                                <source type="image/jpeg" srcset="{{asset('uploads/articles/'.$article->file) }}">
                                <img src="{{asset('uploads/articles/'.$article->file) }}" alt="@if($article->file_alt){{$article->file_alt}}@else{{$article->title}}@endif" class="img-fluid rounded mt-4 mt-md-30">
                            </picture>
                        </div>

                        <div class="post-content">
                            <p>{{ $article->content_entry }}</p>
                            <p>{!! parse_text($article->content, true) !!}</p>
                        </div>

                        <a href="{{route('aktualnosci.index')}}" class="bttn mt-4">WRÓĆ DO LISTY</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="slider">
            <div class="container">
                <div class="row pb-md-3">
                    <div class="col-12 col-md-6 offset-md-3">
                        <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center section-header text-secondary">
                            <div class="position-absolute top-50 start-50 translate-middle z-2">
                                <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade">
                            </div>
                            <h2 class="fw-bold text-center text-uppercase">
                                <span data-aos="fade-up" data-aos-delay="200">Sprawdź</span>
                                <span class="fw-900 fs-4 d-block text-center " data-aos="fade-up" data-aos-delay="400">Podobne aktualności</span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $arrow_prev = '<button  class="slick-prev slick-arrow" aria-label="Previous" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="9.05" height="16.484" viewBox="0 0 9.05 16.484"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M363.434-675.758,356-683.192l.808-.808,8.242,8.242-8.242,8.242-.808-.808Z" transform="translate(365.05 -667.516) rotate(180)" fill="#fff"/></svg></button>';

            $arrow_next = '<button  class="slick-next slick-arrow" aria-label="Next" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="9.05" height="16.484" viewBox="0 0 9.05 16.484"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M363.434-675.758,356-683.192l.808-.808,8.242,8.242-8.242,8.242-.808-.808Z" transform="translate(-356 684)" fill="#fff"/></svg></button>';
            $slider_options = [

                'prevArrow' => $arrow_prev,
                'nextArrow' => $arrow_next,
                'mobileFirst' => true,
                'centerMode' => false,
                'slidesToShow' => 1,
                'centerPadding' => '15px',
                'responsive' => [
                    [
                        'breakpoint' => 576,
                        'settings' => [
                            'centerPadding' => '10%',
                        ]
                    ],
                    [
                        'breakpoint' => 768,
                        'settings' => [
                            'centerPadding' => '15%',
                        ]
                    ],
                    [
                        'breakpoint' => 1199,
                        'settings' => [
                            'slidesToShow' => 1,
                            'centerPadding' => 'calc(273 / 1920 * 100vw)',
                        ]
                    ]
                ]


            ];
            ?>
            <div class="invests-vertical-slider with-arrows mt-50" data-slick='<?= json_encode($slider_options) ?>'>
                @foreach($previousArticles as $prev)
                    <div>
                        <article class="shadow-post-article rounded">
                            <div class="row ">
                                <div class="col-12 col-md-7 col-lg-8">
                                    <div class="h-100">
                                        <a href="{{route('aktualnosci.show', $prev->slug)}}" title="{{ $prev->title }}" itemprop="url">
                                            <picture>
                                                @if($prev->file_webp)
                                                    <source type="image/webp" srcset="{{asset('uploads/articles/thumbs/webp/'.$prev->file_webp) }}">
                                                @endif
                                                <source type="image/jpeg" srcset="{{asset('uploads/articles/thumbs/'.$prev->file) }}">

                                                <img src="{{asset('uploads/articles/thumbs/'.$prev->file) }}" alt="@if($prev->file_alt){{$prev->file_alt}}@else{{$prev->title}}@endif" loading="lazy" decoding="async" fetchpriority="low" class="w-100 h-auto object-fit-cover">
                                            </picture>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 col-lg-4">
                                    <div class="d-flex flex-column justify-content-between h-100 p-3 py-md-4 ps-md-0 ">
                                        <div>
                                            <p class="fs-24 ff-secondary text-secondary text-balance mb-1">{{$prev->title}}</p>
                                            <span class="d-block fs-10 text-secondary ff-secondary fw-900 mb-30">{{$prev->posted_at}}</span>
                                            <p>{{$prev->content_entry}}</p>
                                        </div>
                                        <div>
                                            <a href="{{route('aktualnosci.show', $prev->slug)}}" class="btn btn-primary btn-with-icon d-inline-flex justify-content-center align-items-center">
                                                Czytaj więcej
                                                <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062"><path id="chevron_right_FILL0_wght100_GRAD0_opsz24" d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z" transform="translate(-356 684)" fill="currentColor" /></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection