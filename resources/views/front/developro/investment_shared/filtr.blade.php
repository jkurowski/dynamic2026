<section class="single-investment-search search section-search">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 col-xl-8 offset-lg-1 offset-xl-2">
                <form action="" class="bg-secondary text-white rounded d-flex row-gap-0 flex-wrap flex-sm-nowrap search-form" autocomplete="off">
                    <div class="row row-gap-3 align-items-end px-30 py-3 w-md-100 pb-md-40 pb-20">
                        <p class="col-12 w-100 text-uppercase mb-0">Wyszukiwarka</p>
                        <div class="col-12 w-100 d-none">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="showAll">
                                <label class="form-check-label" for="showAll">
                                    Pokaż wszystkie
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <select name="rooms" id="filtr-rooms" class="form-select">
                                <option value="">Pokoje</option>
                                {!! room2Select($investment->room_range) !!}
                            </select>
                        </div>
                        @if($area_range)
                            <div class="col">
                                <select name="area" id="filtr-area" class="form-select">
                                    <option value="">Powierzchnia</option>
                                    {!! area2Select($area_range) !!}
                                </select>
                            </div>
                        @endif

                        @php
                            $floors = $investment->selectFloors();
                        @endphp

                        @if($floors->isNotEmpty())
                            <div class="col">
                                <select name="floor" id="filtr-floor" class="form-select">
                                    <option value="">Piętro</option>
                                    @foreach ($floors as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col">
                            <select name="kitchen" id="filtr-kitchen" class="form-select">
                                <option value="">Aneks / Kuchnia</option>
                                <option value="1">Aneks</option>
                                <option value="2">Kuchnia</option>
                            </select>
                        </div>
                        <div class="col">
                            <select name="additional" id="filtr-additional" class="form-select">
                                <option value="">Taras / Ogródek</option>
                                <option value="1">Taras</option>
                                <option value="2">Ogródek</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-fill">
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
