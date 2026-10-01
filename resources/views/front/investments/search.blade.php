@push('style')
    <style>
        .slider-container {
            position: relative;
            width: 100%;
            height: 35px;
            padding-top: 15px;
        }
        .slider-track {
            position: absolute;
            width: 100%;
            height: 5px;
            background: #ddd;
            border-radius: 5px;
            top: 20px;
        }
        .slider-range {
            position: absolute;
            height: 5px;
            background: #007bff;
            border-radius: 5px;
            top: 20px;
        }
        .slider-input {
            position: absolute;
            width: 100%;
            background: none;
            pointer-events: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            top: 15px;
            margin: 0;
            height: 15px;
        }
        .slider-input::-webkit-slider-thumb {
            height: 18px;
            width: 18px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #007bff;
            cursor: pointer;
            pointer-events: auto;
            -webkit-appearance: none;
        }
        .slider-input::-moz-range-thumb {
            height: 18px;
            width: 18px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #007bff;
            cursor: pointer;
            pointer-events: auto;
            -moz-appearance: none;
        }
        .slider-label { font-size: 1rem; margin-bottom: 5px; display: block; width: 170px;line-height: normal}
        .slider-label.slider-label-lg {width:290px}
        .slider-label small {display: block}
        .slider-col {
            padding-top: 1.225rem;
        }
        @media (max-width: 575.98px) {
            .slider-label {
                font-size: 1rem;
                margin-bottom: 0;
                display: block;
                width: 100%;
            }
        }
    </style>
@endpush

<section class="oferta-search search section-search">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <form action="{{ route('developro.search.index') }}"
                    class="bg-secondary text-white rounded d-flex row-gap-0 flex-wrap flex-sm-nowrap search-form"
                    autocomplete="off">
                    <div class="container-fluid d-block d-sm-none">
                        <div class="row row-gap-3 py-3 row-button">
                            <div class="col-12">
                                <button type="button" id="toggleSearchform" onclick="toggleSearch()">Pokaż / ukryj wyszukiwarkę</button>
                            </div>
                        </div>
                    </div>
                    <div class="row row-gap-3 align-items-end px-30 py-3 pb-30 toggle-searchform row-form">
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <div class="col-12">
                            <div class="row align-items-end">
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <select name="city" id="city-select" class="form-select">
                                        <option value="">Miasto</option>
                                        @foreach($cities as $c)
                                            <option value="{{ $c->id }}" {{ request('city') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-xl-3">
                                    <select name="invest" id="invest-select" class="form-select">
                                        <option value="">Inwestycja</option>
                                        @foreach($current_investment as $p)
                                            <option value="{{ $p->id }}" {{ request('invest') == $p->id ? 'selected' : '' }} data-city="{{ $p->city ? $p->city->id : '' }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                    <select name="floor" id=floorrooms" class="form-select">
                                        <option value="">Piętro</option>
                                        <option value="1" {{ request('floor') == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ request('floor') == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ request('floor') == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ request('floor') == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ request('floor') == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                    <select name="advanced" id="advanced" class="form-select">
                                        <option value="">Zaawansowanie</option>
                                        <option value="1" {{ request('advanced') == 1 ? 'selected' : '' }}>Przedsprzedaż</option>
                                        <option value="2" {{ request('advanced') == 2 ? 'selected' : '' }}>Realizacja</option>
                                        @if(1 == 2)
                                        <option value="3" {{ request('advanced') == 3 ? 'selected' : '' }}>Realizacja 40%</option>
                                        <option value="4" {{ request('advanced') == 4 ? 'selected' : '' }}>Realizacja 60%</option>
                                        <option value="5" {{ request('advanced') == 5 ? 'selected' : '' }}>Realizacja 80%</option>
                                        <option value="6" {{ request('advanced') == 6 ? 'selected' : '' }}>Realizacja 100%</option>
                                        @endif
                                        <option value="7" {{ request('advanced') == 7 ? 'selected' : '' }}>Gotowe do odbioru</option>
                                        <option value="8" {{ request('advanced') == 8 ? 'selected' : '' }}>Planowana</option>
                                    </select>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                    <select name="rooms" id="rooms" class="form-select">
                                        <option value="">Pokoje</option>
                                        <option value="1" {{ request('rooms') == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ request('rooms') == 2 ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ request('rooms') == 3 ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ request('rooms') == 4 ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ request('rooms') == 5 ? 'selected' : '' }}>5</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="" selected>Status</option>
                                        <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Dostępny</option>
                                        <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Rezerwacja</option>
                                        <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Sprzedane</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                    <select name="garden" id="garden" class="form-select">
                                        <option value="" selected>Ogródek</option>
                                        <option value="1" {{ request('garden') == 1 ? 'selected' : '' }}>Tak</option>
                                        <option value="2" {{ request('garden') == 2 ? 'selected' : '' }}>Nie</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                                    <select name="type" id="apartment" class="form-select">
                                        <option value="1" {{ request('type') == 1 ? 'selected' : '' }}>Lokal mieszkalny</option>
                                        <option value="5" {{ request('type') == 5 ? 'selected' : '' }}>Lokal usługowy</option>
                                        <option value="2" {{ request('type') == 2 ? 'selected' : '' }}>Komórka lokatorska</option>
                                        <option value="3" {{ request('type') == 3 ? 'selected' : '' }}>Miejsce parkingowe</option>
                                        <option value="6" {{ request('type') == 6 ? 'selected' : '' }}>Miejsce park. + KL</option>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-6 d-block d-sm-flex slider-col">
                                    <label class="slider-label">Powierzchnia<small><span id="area-val"></span> m²</small></label>
                                    <div class="slider-container" id="area-slider-container">
                                        <div class="slider-track"></div>
                                        <div class="slider-range" id="area-slider-range"></div>
                                        <input type="range" class="slider-input" id="area-min-input" min="0" max="200" value="{{ request('area_min', 0) }}">
                                        <input type="range" class="slider-input" id="area-max-input" min="0" max="200" value="{{ request('area_max', 200) }}">
                                    </div>
                                    <input type="hidden" name="area_min" id="area_min" value="{{ request('area_min', 0) }}">
                                    <input type="hidden" name="area_max" id="area_max" value="{{ request('area_max', 200) }}">
                                </div>
                                <div class="col-12 col-lg-6 d-block d-sm-flex slider-col">
                                    <label class="slider-label slider-label-lg">Cena<small><span id="price-val"></span> PLN</small></label>
                                    <div class="slider-container" id="price-slider-container">
                                        <div class="slider-track"></div>
                                        <div class="slider-range" id="price-slider-range"></div>
                                        <input type="range" class="slider-input" id="price-min-input" min="0" max="2000000" step="10000" value="{{ request('price_min', 0) }}">
                                        <input type="range" class="slider-input" id="price-max-input" min="0" max="2000000" step="10000" value="{{ request('price_max', 2000000) }}">
                                    </div>
                                    <input type="hidden" name="price_min" id="price_min" value="{{ request('price_min', 0) }}">
                                    <input type="hidden" name="price_max" id="price_max" value="{{ request('price_max', 2000000) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fill toggle-searchform">
                        <button type="submit"
                            class="btn btn-primary w-100 h-100 fs-14 text-uppercase px-sm-4 d-flex align-items-center justify-content-center flex-sm-column gap-2 gap-sm-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21.631" height="21.636"
                                viewBox="0 0 21.631 21.636">
                                <path id="Icon_ionic-ios-search" data-name="Icon ionic-ios-search"
                                    d="M25.877,24.563l-6.016-6.072a8.573,8.573,0,1,0-1.3,1.318l5.977,6.033a.926.926,0,0,0,1.307.034A.932.932,0,0,0,25.877,24.563ZM13.124,19.882A6.77,6.77,0,1,1,17.912,17.9,6.728,6.728,0,0,1,13.124,19.882Z"
                                    transform="translate(-4.5 -4.493)" fill="#fff" />
                            </svg>
                            <span>
                                Szukaj
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    function toggleSearch() {
        document.querySelector('.search-form').classList.toggle('open');
    }
</script>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const citySelect = document.getElementById('city-select');
        const investSelect = document.getElementById('invest-select');

        citySelect.addEventListener('change', function() {
            const selectedCity = citySelect.value;
            Array.from(investSelect.options).forEach(option => {
                if (selectedCity === '' || option.getAttribute('data-city') === selectedCity) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
            if (selectedCity === '') {
                investSelect.value = '';
            }
        });

        function initDualSlider(containerId, minInputId, maxInputId, minHiddenId, maxHiddenId, rangeId, valId, isPrice = false) {
            const minInput = document.getElementById(minInputId);
            const maxInput = document.getElementById(maxInputId);
            const minHidden = document.getElementById(minHiddenId);
            const maxHidden = document.getElementById(maxHiddenId);
            const range = document.getElementById(rangeId);
            const valSpan = document.getElementById(valId);
            const minVal = parseInt(minInput.min);
            const maxVal = parseInt(maxInput.max);

            function updateSlider() {
                let v1 = parseInt(minInput.value);
                let v2 = parseInt(maxInput.value);

                if (v1 > v2) {
                    let tmp = v1;
                    v1 = v2;
                    v2 = tmp;
                }

                minHidden.value = v1;
                maxHidden.value = v2;

                const percent1 = ((v1 - minVal) / (maxVal - minVal)) * 100;
                const percent2 = ((v2 - minVal) / (maxVal - minVal)) * 100;

                range.style.left = percent1 + "%";
                range.style.width = (percent2 - percent1) + "%";

                if (isPrice) {
                    valSpan.innerText = v1.toLocaleString() + " - " + v2.toLocaleString();
                } else {
                    valSpan.innerText = v1 + " - " + v2;
                }
            }

            minInput.addEventListener("input", updateSlider);
            maxInput.addEventListener("input", updateSlider);
            updateSlider();
        }

        initDualSlider("area-slider-container", "area-min-input", "area-max-input", "area_min", "area_max", "area-slider-range", "area-val");
        initDualSlider("price-slider-container", "price-min-input", "price-max-input", "price_min", "price_max", "price-slider-range", "price-val", true);
    });
</script>
@endpush
