@props([
    'city' => 'Łódź',
    'name' => 'Na falistej',
    'logo' => asset('img/invest_1_logo.png'),
    'bg' => asset('img/invest_1_bg.png'),
    'price' => '999 999zł',
    'delivery' => 'III kwartał 2023',
    'link' => '#',
    'showPrice' => false,
])
<div class="invest-card position-relative downton">
    <a href="{{ $link }}" class="stretched-link z-2"></a>
    <div class="position-absolute invest-card-bg-overlay w-100 h-100 top-0 start-0">
        <img src="{{ $bg }}" alt="" class="w-100 h-100 object-fit-cover invest-card-bg">
    </div>
    <div class="d-flex isolation-isolate justify-content-between gap-3 text-white">
        <div class="fw-bold">
            <p class="small text-uppercase mb-3 lh-1">{{ $city }}</p>
            <p class="h3 lh-1">{{ $name }}</p>

        </div>
        <div class='invest-card-logo-wrapper'>
            <img src="{{ $logo }}" alt="" class="rounded-circle invest-card-logo img-fluid"
                loading="lazy" decoding="async" width="71" height="71">
        </div>
    </div>
    <div class="isolation-isolate text-white fw-semibold mb-auto fs-13">
        <p class="text-uppercase mb-0">
            Termin oddania:
        </p>
        <p class="mb-0">
            {{ $delivery }}
        </p>
        @if ($showPrice)
            <p class="price mt-3 fs-6 text-primary">
                Ceny już od:
                <span>
                    {{ $price }}
                </span>
            </p>
        @endif
    </div>

    <div class="position-relative z-2">
        <button class="btn btn-primary btn-with-icon ">
            Sprawdź
            <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062">
                <path id="chevron_right_FILL0_wght100_GRAD0_opsz24"
                    d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z"
                    transform="translate(-356 684)" fill="currentColor" />
            </svg>

        </button>
    </div>

</div>
