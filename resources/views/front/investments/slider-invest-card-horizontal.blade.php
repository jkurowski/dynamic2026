@props([
    'name' => 'Mieszkanie A/3',
    'price' => '611 000 Zł',
    'oldPrice' => '640 000 Zł',
    'lowestPrice' => '611 000 Zł',
    'floor' => 'Piętro',
    'floorNumber' => 'Parter',
    'area' => '83,78',
    'rooms' => '4',
    'link' => '#',
    'city' => 'Łódź',
])
<div
    class="invest-card-horizontal position-relative d-flex flex-column-reverse flex-sm-row justify-content-between  bg-white">
    <a href="{{ $link }}" class="stretched-link"></a>
    <div class="text-secondary invest-card-horizontal-left flex-fill">
        <p class="h3 mb-0">
            {{ $name }}
        </p>
        <p class="fs-10 text-uppercase fw-900 mb-2">
            {{ $city }}
        </p>
        <p class="h3 mb-1">
            <span class="me-2">
                {{ $price }}
            </span>
            <span class="text-body-emphasis opacity-50 fs-6 align-middle text-decoration-line-through">
                {{ $oldPrice }}
            </span>
        </p>
        <p class="fs-8 text-black">
            Najniższa cena z ostatnich 30 dni: {{ $lowestPrice }}
        </p>
        <div class="small mb-40">
            <table class="w-100">
                <tbody>
                    <tr>
                        <td class="td-with-icon"><img src="{{asset('img/tile.svg')}}" alt="" loading="lazy" decoding="async"
                                class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                        <td>Piętro</td>
                        <td class="text-end">{{ $floorNumber }}</td>
                    </tr>
                    <tr>
                        <td class="td-with-icon"><img src="{{asset('img/blueprint.svg')}}" alt="" loading="lazy"
                                decoding="async" class="w-10 h-10 object-fit-contain" width="12" height="12">
                        </td>
                        <td>Metraż</td>
                        <td class="text-end">{{ $area }}m<sup>2</sup></td>
                    </tr>
                    <tr>
                        <td class="td-with-icon"><img src="{{asset('img/rooms.svg')}}" alt="" loading="lazy" decoding="async"
                                class="w-10 h-10 object-fit-contain" width="12" height="12"></td>
                        <td>Liczba Pokoi</td>
                        <td class="text-end">{{ $rooms }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="position-relatieve z-2">
            <a class="btn btn-primary btn-with-icon " href="{{ $link }}">
                Sprawdź
                <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062" viewBox="0 0 6.073 11.062">
                    <path id="chevron_right_FILL0_wght100_GRAD0_opsz24"
                        d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z"
                        transform="translate(-356 684)" fill="currentColor" />
                </svg>

            </a>
        </div>
    </div>

    <div class="invest-card-horizontal-right">
        <img src="img/investment_plan.png" alt="" loading="lazy" decoding="async"
            class="w-100 h-100 object-fit-contain" width="440" height="310">
    </div>

</div>
