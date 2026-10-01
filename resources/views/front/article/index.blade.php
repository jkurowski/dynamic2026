@extends('layouts.page', ['body_class' => 'homepage'])

@section('meta_title', $page->title)
@section('seo_title',$page->meta_title)
@section('seo_description', $page->meta_description)
@section('seo_robots', $page->meta_robots)

@section('content')
    <main class="with-bigger-section-spacing">

        @include('layouts.partials.page-header', ['h1' => $page->title, 'desc' => $page->title_text, 'header' => 'img/kredyty_bg.webp', 'mb' => 100])


        <section class="posts-section">
            <div class="container">
                <div class="row row-gap-30">
                    @foreach ($articles as $n)
                        <div class="col-12">
                            <article class="shadow-post-article rounded">
                                <div class="row ">
                                    <div class="col-12 col-lg-8 col-xl-5">
                                        <div class="h-100">
                                            <a href="{{route('aktualnosci.show', $n->slug)}}" title="{{ $n->title }}" itemprop="url">
                                            <picture>
                                                @if($n->file_webp)
                                                <source type="image/webp" srcset="{{asset('uploads/articles/thumbs/webp/'.$n->file_webp) }}">
                                                @endif
                                                <source type="image/jpeg" srcset="{{asset('uploads/articles/thumbs/'.$n->file) }}">

                                                <img src="{{asset('uploads/articles/thumbs/'.$n->file) }}" alt="@if($n->file_alt){{$n->file_alt}}@else{{$n->title}}@endif" loading="lazy" decoding="async" fetchpriority="low" class="w-100 h-auto object-fit-cover">
                                            </picture>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 col-xl-7">
                                        <div class="d-flex flex-column justify-content-between h-100 p-3 py-lg-3">
                                            <div>
                                                <p class="fs-24 ff-secondary text-secondary text-balance mb-1">
                                                    <a href="{{route('aktualnosci.show', $n->slug)}}" itemprop="url"><span itemprop="name headline">{{ $n->title }}</span></a>
                                                </p>
                                                <span class="d-block fs-10 text-secondary ff-secondary fw-900 mb-30">{{ $n->posted_at }}</span>
                                                <div class="mb-30">
                                                    {{ $n->content_entry }}
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{route('aktualnosci.show', $n->slug)}}" class="btn btn-primary btn-with-icon d-inline-flex justify-content-center align-items-center">
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
@push('scripts')

@endpush
