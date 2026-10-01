@props([
    'pageTitle' => $page,
    'sectionTitle' => 'Masz pytania?',
    'sectionSubTitle' => 'Napisz do nas!',
    'investmentName' => $investmentName ?? null,
    'investmentId' => $investmentName ?? null,
    'investmentText' => $investmentText ?? null,
    'propertyId' => $propertyId ?? null,
    'back' => $back ?? false,
])

<section class="position-relative cta @if($propertyId) cta-white @endif">
    @if(!$propertyId)
    <img src="{{ asset('img/cta_bg.webp') }}" alt="Tło call to action" width="1920" height="1080" class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" loading="lazy" decoding="async">
    @endif
    <div class="container z-2">
        <div class="row">
            <div class="col-12 text-white mb-5 pb-4">
                <div style="--translate-x: 0;" class="position-relative text-center d-flex flex-column justify-content-center align-items-center ">
                    <div class="position-absolute top-50 start-50 translate-middle z-2">
                        @if(!$propertyId)
                        <img src="{{ asset('img/sygnet.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade" class="aos-init aos-animate">
                        @else
                        <img src="{{ asset('img/sygnet_secondary.svg') }}" alt="" width="168" height="168" loading="lazy" decoding="async" data-aos="fade" class="aos-init aos-animate">
                        @endif
                    </div>
                    <h2 class="fw-bold text-center text-uppercase">
                        <span data-aos="fade-up" data-aos-delay="200" class="aos-init aos-animate">{{ $sectionTitle }}</span>
                        <span class="fw-900 fs-4 d-block text-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="400">{{ $sectionSubTitle }}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row row-gap-4 justify-content-center">
            <div class="@isset($investmentText) col-12 @else col-12 @endisset">
                <div class="contact-form-container text-secondary">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <p class="fs-5 text-uppercase fw-semibold text-secondary">FORMULARZ KONTAKTOWY</p>
                            @include('components.contact-form', ['page' => $page, 'back' => $back])
                        </div>
                        <div class="d-none d-lg-flex col-4 align-items-end">
                            <img class="img-fluid position-relative person-img" src="{{ asset('img/cta_person_jpg.jpg') }}" alt="" width="475" height="710">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
