<section class="position-relative page-hero-section pb-{{ $pb ?? 0 }} mb-{{ $mb ?? 0 }}">
    <div class="position-absolute top-0 start-0 w-100 h-100 with-image-overlay-gradient">
        @isset($header)
        <img src="{{ asset($header) }}" alt="" width="1920" height="386" loading="eager" decoding="async" class="w-100 h-100 object-fit-cover">
        @endisset
    </div>
    <div class="container isolation-isolate">
        <div class="row row-gap-30">
            <div class="col-12">
                <nav aria-label="breadcrumb small text-white" data-aos="fade">
                    <ol class="breadcrumb opacity-50">
                        <li class="breadcrumb-item">
                            <a href="/" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">Strona główna</a>
                        </li>
                        <li class="breadcrumb-item" style="--bs-breadcrumb-divider-color: var(--bs-white);">
                            <a href="#" style="--bs-secondary: var(--bs-white);--bs-breadcrumb-item-active-color: var(--bs-white);">{{ $h1 }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3 text-white text-center">
                @isset($h1)
                <h1 class="h2 mb-3 text-uppercase" data-aos="fade-up">{{ $h1 }}</h1>
                @endisset
                @isset($desc)
                <p class="text-pretty" data-aos="fade-up" data-aos-delay="200">{{ $desc }}</p>
                @endisset
            </div>
        </div>
    </div>
</section>