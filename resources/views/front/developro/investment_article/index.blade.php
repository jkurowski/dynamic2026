@extends('layouts.page', ['body_class' => 'investments'])

@php

    /* TITLE */
    if(!empty($investment->meta_title)) {
        $seoTitle = $investment->meta_title;
    }
    elseif(!empty($page->meta_title)) {
        $seoTitle = $page->meta_title;
    }
    else {
        $seoTitle = settings()->get("page_title") . ' - ' . $page->title . ' - ' . $investment->name . ' - Dziennik inwestycji';
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
                        <nav aria-label="breadcrumb small text-white" data-aos="fade" class="aos-init aos-animate">
                            <ol class="breadcrumb opacity-50">
                                <li class="breadcrumb-item">
                                    <a href="/"
                                       style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Strona
                                        główna</a>
                                </li>
                                <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                                    <a href="#" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">{{ $investment->name }}</a>
                                </li>

                            </ol>
                        </nav>
                    </div>
                    <div class="col-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3 text-white text-center">
                        @isset($investment->name)
                            <h1 class="h2 mb-3 text-uppercase" data-aos="fade-up">{{ $investment->name }}</h1>
                        @endisset
                        @isset($page->title_text)
                            <p class="text-pretty" data-aos="fade-up" data-aos-delay="200">{{ $page->title_text }}</p>
                        @endisset
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
                        @include('front.investments.submenu', ['menuIds' => $investment->menu, 'activeMenuId' => 6])
                    </div>
                </div>
            </div>
        </section>

        <section class="posts-section">
            <div class="container">
                <div class="row row-gap-30">
                    @foreach ($investment_news as $n)
                        <div class="col-12">
                            <article class="shadow-post-article rounded">
                                <div class="row ">
                                    <div class="col-12 col-lg-8 col-xl-5">
                                        <div class="h-100 position-relative">
                                            <a href="{{route('developro.investment.news.show', [$investment->slug, $n->slug])}}" title="{{ $n->title }}" itemprop="url">
                                                <picture>
                                                    @if($n->file_webp)
                                                        <source type="image/webp" srcset="{{asset('investment/articles/thumbs/webp/'.$n->file_webp) }}">
                                                    @endif
                                                    <source type="image/jpeg" srcset="{{asset('investment/articles/thumbs/'.$n->file) }}">

                                                    <img src="{{asset('investment/articles/thumbs/'.$n->file) }}" alt="@if($n->file_alt){{$n->file_alt}}@else{{$n->title}}@endif" loading="lazy" decoding="async" fetchpriority="low" class="w-100 h-auto object-fit-cover">
                                                </picture>
                                            </a>
                                            <span class="post-date">{{$n->date}}</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl-7">
                                        <div class="d-flex flex-column justify-content-between h-100 p-3 py-lg-3">
                                            <div>
                                                <p class="fs-24 ff-secondary text-secondary text-balance mb-1">
                                                    <a href="{{route('developro.investment.news.show', [$investment->slug, $n->slug])}}" itemprop="url"><span itemprop="name headline">{{ $n->title }}</span></a>
                                                </p>
                                                <span class="d-block fs-10 text-secondary ff-secondary fw-900 mb-30">{{ $n->posted_at }}</span>
                                                <div class="mb-30">
                                                    {{ $n->content_entry }}
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{route('developro.investment.news.show', [$investment->slug, $n->slug])}}" class="btn btn-primary btn-with-icon d-inline-flex justify-content-center align-items-center">
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
            </div>
        </section>
    </main>
@endsection
