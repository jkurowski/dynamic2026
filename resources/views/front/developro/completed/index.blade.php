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
            'header' => 'img/inwestycje_zrealizowane_bg.webp',
            'mb' => 0,
        ])

        <section class="py-40">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-10 offset-lg-1">
                        <nav>
                            <ul class="navbar-nav nav-snap-md-down flex-row justify-content-center gap-40 with-underline-active" style="--bs-nav-link-color: var(--bs-secondary);--bs-nav-link-hover-color: var(--bs-primary); --bs-navbar-active-color: var(--bs-secondary);">
                                <?php foreach ($cities as $city) : ?>
                                @if($city->completed)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('developro.completed.city', $city->slug) }}">{{ $city->name }}</a>
                                </li>
                                @endif
                                <?php endforeach; ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('developro.completed') }}">Wszystkie</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-0">
            <div class="container">
                <div class="row row-gap-4">
                    <?php foreach ($investments as $p) : ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="invest-card invest-card-big invest-card-min-h position-relative">
                            <a href="{{ route('developro.show', $p->slug) }}" class="stretched-link z-2"></a>
                            <div class="position-absolute w-100 h-100 top-0 start-0">
                                <img src="{{ asset('investment/thumbs/' . $p->file_thumb) }}" alt="" class="w-100 h-100 object-fit-cover invest-card-bg">
                                <div style="position: absolute;opacity: 0.8;width: 100%;height: 100%;top: 0;left: 0;background-image: linear-gradient(#000, rgba(255, 255, 255, 0) {{ $p->gradient_thumb ?: '100%' }});"></div>
                            </div>
                            <div class="d-flex isolation-isolate justify-content-between gap-3 text-white">
                                <div class="fw-bold">
                                    <p class="small text-uppercase mb-3 lh-1">{{ $p->city->name }}</p>
                                    <p class="fs-40 ff-secondary fw-bold lh-1">{{ $p->name }}</p>
                                </div>
                                @if ($p->file_logo)
                                    <div>
                                        <img src="{{ asset('investment/logo/' . $p->file_logo) }}" alt="" class="rounded-circle invest-card-logo img-fluid" loading="lazy" decoding="async" width="71" height="71">
                                    </div>
                                @endif
                            </div>
                            <div class="isolation-isolate text-white fw-semibold mb-auto fs-13">
                                <p class="text-uppercase mb-0">Termin zakończenia:</p>
                                <p class="mb-0">{{ $p->date_end }}</p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
@endpush
