<section class="single-investment-search search section-search">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <form
                    action="{{ Route::is(['developro.plan', 'developro.page', 'developro.mockup', 'developro.investment.news', 'developro.investment.news.show', 'developro.show']) ? route('developro.plan', $investment->slug) . '#properties' : '#properties' }}"
                    class="bg-secondary text-white rounded d-block d-sm-flex row-gap-0 flex-wrap flex-sm-nowrap search-form"
                    autocomplete="off"
                    method="get"
                >
                    <div class="container-fluid d-block d-sm-none">
                        <div class="row row-gap-3 py-3 row-button">
                            <div class="col-12">
                                <button type="button" id="toggleSearchform" onclick="toggleSearch()">Pokaż / ukryj wyszukiwarkę</button>
                            </div>
                        </div>
                    </div>
                    <div class="row row-gap-3 align-items-end px-30 py-3 w-md-100 pb-md-40 pb-20 toggle-searchform">
                        <p class="col-12 w-100 text-uppercase mb-0 d-none">Wyszukiwarka</p>
                        @if($investment->room_range)
                            @php $rooms = explode(',', $investment->room_range) @endphp
                            <div class="@if($status != 3) col @else @if(!isset($is_floor)) col-12 col-sm-6 col-lg-3 @else col-12 col-lg-6 @endif @endif">
                                <select name="rooms" id="rooms" class="form-select">
                                    <option value="">Pokoje</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room }}" @if(request()->input('rooms') == $room) selected @endif>{{ $room }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if(isset($is_building))
                            <div class="@if($status != 3) col @else col-12 col-sm-6 col-lg-3 @endif">
                                <select name="floor" id="floor" class="form-select">
                                    <option value="">Piętro</option>
                                    {!! floorToSelect($investment->floors) !!}
                                </select>
                            </div>
                        @endif

                        @if($status != 3)
                            <div class="col">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="1" @if(request()->input('status') == 1) selected @endif>Na sprzedaż</option>
                                    <option value="2" @if(request()->input('status') == 2) selected @endif>Rezerwacja</option>
                                    <option value="3" @if(request()->input('status') == 3) selected @endif>Sprzedane</option>
                                </select>
                            </div>
                        @endif

                        @if($investment->area_range)
                            @php
                                preg_match_all('/\d+/', $investment->area_range, $numbers);
                                $min = (int)($numbers[0][0] ?? 0);
                                $max = (int)(collect($numbers[0])->last() ?? 0);
                            @endphp
                            @if($status != 3)
                                <div class="w-100"></div>
                            @endif
                            <div class="col-12 col-lg-6 d-block d-sm-flex slider-col">
                                <label class="slider-label">Powierzchnia<small><span id="area-val"></span> m²</small></label>
                                <div class="slider-container" id="area-slider-container">
                                    <div class="slider-track"></div>
                                    <div class="slider-range" id="area-slider-range"></div>
                                    <input type="range" class="slider-input" id="area-min-input" min="{{ $min }}" max="{{ $max }}" value="{{ request('area_min', $min) }}">
                                    <input type="range" class="slider-input" id="area-max-input" min="{{ $min }}" max="{{ $max }}" value="{{ request('area_max', $max) }}">
                                </div>
                                <input type="hidden" name="area_min" id="area_min" value="{{ request('area_min', $min) }}">
                                <input type="hidden" name="area_max" id="area_max" value="{{ request('area_max', $max) }}">
                            </div>
                        @endif

                        @if(isset($price))
                            @if($price->min_price && $price->max_price && $status != 3)
                                <div class="col-12 col-lg-6 d-block d-sm-flex slider-col">
                                    <label class="slider-label slider-label-lg">Cena<small><span id="price-val"></span> PLN</small></label>
                                    <div class="slider-container" id="price-slider-container">
                                        <div class="slider-track"></div>
                                        <div class="slider-range" id="price-slider-range"></div>
                                        <input type="range" class="slider-input" id="price-min-input" min="{{ $price->min_price }}" max="{{ $price->max_price }}" step="10000" value="{{ request('price_min', $price->min_price) }}">
                                        <input type="range" class="slider-input" id="price-max-input" min="{{ $price->min_price }}" max="{{ $price->max_price }}" step="10000" value="{{ request('price_max', $price->max_price) }}">
                                    </div>
                                    <input type="hidden" name="price_min" id="price_min" value="{{ request('price_min', $price->min_price) }}">
                                    <input type="hidden" name="price_max" id="price_max" value="{{ request('price_max', $price->max_price) }}">
                                </div>
                            @endif
                        @endif

                    </div>
                    <div class="flex-fill toggle-searchform">
                        <button type="submit" class="btn btn-primary w-100 h-100 fs-14 text-uppercase px-sm-4 d-flex align-items-center justify-content-center flex-sm-column gap-2 gap-sm-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21.631" height="21.636" viewBox="0 0 21.631 21.636">
                                <path id="Icon_ionic-ios-search" data-name="Icon ionic-ios-search" d="M25.877,24.563l-6.016-6.072a8.573,8.573,0,1,0-1.3,1.318l5.977,6.033a.926.926,0,0,0,1.307.034A.932.932,0,0,0,25.877,24.563ZM13.124,19.882A6.77,6.77,0,1,1,17.912,17.9,6.728,6.728,0,0,1,13.124,19.882Z" transform="translate(-4.5 -4.493)" fill="#fff" />
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
    <style>.slider-label.slider-label-lg {width:290px}</style>
@endpush
