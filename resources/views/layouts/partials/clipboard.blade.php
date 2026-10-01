@props([
    'pageTitle' => $page,
    'sectionTitle' => 'Masz pytania?',
    'sectionSubTitle' => 'Napisz do nas!',
    'investmentName' => $investmentName ?? null,
    'investmentId' => $investmentName ?? null,
    'investmentText' => $investmentText ?? null,
    'propertyId' => 1,
    'back' => $back ?? false,
])

<section class="position-relative cta @if($propertyId) cta-white @endif">
    @if(!$propertyId)
        <img src="{{ asset('img/cta_bg.webp') }}" alt="Tło call to action" width="1920" height="1080" class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" loading="lazy" decoding="async">
    @endif
    <div class="container z-2">
        <div class="row row-gap-4 justify-content-center">
            <div class="@isset($investmentText) col-12 @else col-12 @endisset">
                <div class="contact-form-container text-secondary">
                    <div class="row">
                        <div class="col-8">
                            <p class="fs-5 text-uppercase fw-semibold text-secondary">WYŚLIJ ZAPYTANIE</p>
                            @include('components.contact-form-schowek', ['page' => $page, 'back' => $back])
                        </div>
                        <div class="col-4 d-flex align-items-end">
                            <img class="img-fluid position-relative person-img" src="{{ asset('img/cta_person_jpg.jpg') }}" alt="" width="475" height="710">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
