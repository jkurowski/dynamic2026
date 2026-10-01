@extends('layouts.page')
@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-10 offset-lg-1">
                    <div class="w-100 h-100">
                        <img src="{{ asset('img/module_placeholder.jpg') }}" width="1140" height="492" loading="lazy"
                            decoding="async" class="w-100 h-100 object-fit-cover rounded" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="nav justify-content-end" role="tablist">
                        <li class="nav-item tab-switcher" role="presentation">
                            <button class="nav-link active" id="list-tab" data-bs-toggle="tab"
                                data-bs-target="#list-tab-pane" type="button" role="tab" aria-controls="list-tab-pane"
                                aria-selected="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="29" viewBox="0 0 34 29">
                                    <g id="list" opacity="1">
                                        <g id="Rectangle_441" data-name="Rectangle 441" transform="translate(0 24)"
                                            fill="#fff" stroke="currentColor" stroke-width="1">
                                            <rect width="34" height="5" rx="1" stroke="none" />
                                            <rect x="0.5" y="0.5" width="33" height="4" rx="0.5"
                                                fill="none" />
                                        </g>
                                        <g id="Rectangle_442" data-name="Rectangle 442" transform="translate(0 16)"
                                            fill="#fff" stroke="currentColor" stroke-width="1">
                                            <rect width="34" height="5" rx="1" stroke="none" />
                                            <rect x="0.5" y="0.5" width="33" height="4" rx="0.5"
                                                fill="none" />
                                        </g>
                                        <g id="Rectangle_443" data-name="Rectangle 443" fill="#fff" stroke="currentColor"
                                            stroke-width="1">
                                            <rect width="34" height="5" rx="1" stroke="none" />
                                            <rect x="0.5" y="0.5" width="33" height="4" rx="0.5"
                                                fill="none" />
                                        </g>
                                        <g id="Rectangle_444" data-name="Rectangle 444" transform="translate(0 8)"
                                            fill="#fff" stroke="currentColor" stroke-width="1">
                                            <rect width="34" height="5" rx="1" stroke="none" />
                                            <rect x="0.5" y="0.5" width="33" height="4" rx="0.5"
                                                fill="none" />
                                        </g>
                                    </g>
                                </svg>

                            </button>
                        </li>
                        <li class="nav-item tab-switcher" role="presentation">
                            <button class="nav-link" id="grid-tab" data-bs-toggle="tab" data-bs-target="#grid-tab-pane"
                                type="button" role="tab" aria-controls="grid-tab-pane" aria-selected="false">
                                <svg id="grid" xmlns="http://www.w3.org/2000/svg" width="34" height="29"
                                    viewBox="0 0 34 29">
                                    <g id="Rectangle_430" data-name="Rectangle 430" fill="#fff" stroke="currentColor"
                                        stroke-width="1">
                                        <rect width="15" height="7" rx="1" stroke="none" />
                                        <rect x="0.5" y="0.5" width="14" height="6" rx="0.5"
                                            fill="none" />
                                    </g>
                                    <g id="Rectangle_433" data-name="Rectangle 433" transform="translate(0 11)"
                                        fill="#fff" stroke="currentColor" stroke-width="1">
                                        <rect width="15" height="7" rx="1" stroke="none" />
                                        <rect x="0.5" y="0.5" width="14" height="6" rx="0.5"
                                            fill="none" />
                                    </g>
                                    <g id="Rectangle_435" data-name="Rectangle 435" transform="translate(0 22)"
                                        fill="#fff" stroke="currentColor" stroke-width="1">
                                        <rect width="15" height="7" rx="1" stroke="none" />
                                        <rect x="0.5" y="0.5" width="14" height="6" rx="0.5"
                                            fill="none" />
                                    </g>
                                    <g id="Rectangle_431" data-name="Rectangle 431" transform="translate(19)"
                                        fill="#fff" stroke="currentColor" stroke-width="1">
                                        <rect width="15" height="7" rx="1" stroke="none" />
                                        <rect x="0.5" y="0.5" width="14" height="6" rx="0.5"
                                            fill="none" />
                                    </g>
                                    <g id="Rectangle_432" data-name="Rectangle 432" transform="translate(19 11)"
                                        fill="#fff" stroke="currentColor" stroke-width="1">
                                        <rect width="15" height="7" rx="1" stroke="none" />
                                        <rect x="0.5" y="0.5" width="14" height="6" rx="0.5"
                                            fill="none" />
                                    </g>
                                    <g id="Rectangle_434" data-name="Rectangle 434" transform="translate(19 22)"
                                        fill="#fff" stroke="currentColor" stroke-width="1">
                                        <rect width="15" height="7" rx="1" stroke="none" />
                                        <rect x="0.5" y="0.5" width="14" height="6" rx="0.5"
                                            fill="none" />
                                    </g>
                                </svg>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <!--  -->
            <div class="row mt-30">
                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="list-tab-pane" role="tabpanel"
                            aria-labelledby="list-tab" tabindex="0">

                            <div class="row row-cols-1 row-gap-4">
                                <?php foreach ([[], [], [], []] as $i => $row) : ?>
                                <div class="col">
                                    <div
                                        class="invest-card-list text-secondary d-flex column-gap-4 row-gap-3 position-relative align-items-center flex-wrap flex-xl-nowrap justify-content-between">
                                        <div class="col-xl-auto">
                                            <p class="h4 lh-1 mb-1">
                                                Mieszkanie A/3
                                            </p>
                                            <p class="fw-bold fs-15 mb-0">
                                                Na skraju
                                            </p>
                                            <p>
                                                <small>
                                                    Etap: I
                                                </small>
                                            </p>
                                        </div>
                                        <div
                                            class="col-xl-auto d-flex column-gap-30 row-gap-3 align-items-center flex-wrap flex-lg-nowrap flex-fill justify-content-md-center">
                                            <div class="d-flex gap-2">
                                                <div><img src="img/location.svg" alt="" loading="lazy"
                                                        decoding="async" class="w-10 h-10 object-fit-contain"
                                                        width="13" height="13"></div>
                                                <div>Białystok</div>
                                            </div>
                                            <div class="vr" style="color:#DFDEDE;"></div>
                                            <div class="fs-13 d-flex gap-2">
                                                <div><img src="img/tile.svg" alt="" loading="lazy"
                                                        decoding="async" class="w-10 h-10 object-fit-contain"
                                                        width="13" height="13"></div>
                                                <div>Parter</div>
                                            </div>
                                            <div class="vr" style="color:#DFDEDE;"></div>
                                            <div class="fs-13 d-flex gap-2">
                                                <div><img src="img/blueprint.svg" alt="" loading="lazy"
                                                        decoding="async" class="w-10 h-10 object-fit-contain"
                                                        width="13" height="13"></div>
                                                <div>Metraż</div>
                                            </div>
                                            <div class="vr" style="color:#DFDEDE;"></div>
                                            <div class="fs-13 d-flex gap-2">
                                                <div><img src="img/rooms.svg" alt="" loading="lazy"
                                                        decoding="async" class="w-10 h-10 object-fit-contain"
                                                        width="13" height="13"></div>
                                                <div>4</div>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 text-lg-center">
                                            <?php if ($i == 0) : ?>
                                            <p class="text-danger text-uppercase fw-bold fs-5 mb-0">
                                                Sprzedany
                                            </p>
                                            <?php elseif ($i == 1) : ?>

                                            <p class="text-warning text-uppercase fw-bold fs-5 mb-0">
                                                Zarezerwowany
                                            </p>
                                            <?php else : ?>
                                            <p class="text-success text-uppercase fw-bold fs-5 mb-0">
                                                Dostępny
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="position-relative z-2 col-xl-auto">
                                            <a class="btn btn-primary btn-with-icon text-nowrap" href="#">
                                                Sprawdź
                                                <svg xmlns="http://www.w3.org/2000/svg" width="6.073" height="11.062"
                                                    viewBox="0 0 6.073 11.062">
                                                    <path id="chevron_right_FILL0_wght100_GRAD0_opsz24"
                                                        d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z"
                                                        transform="translate(-356 684)" fill="currentColor" />
                                                </svg>

                                            </a>
                                        </div>
                                        <a class="stretched-link d-contents" href="#">
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="grid-tab-pane" role="tabpanel" aria-labelledby="grid-tab"
                            tabindex="0">
                            <div class="row row-cols-1 row-cols-sm-2 row-gap-4">
                                <?php foreach ([[], [], [], []] as $i => $row) : ?>
                                <div class="col">
                                    <div
                                        class="invest-card-horizontal position-relative d-flex flex-column-reverse flex-lg-row justify-content-between  bg-white my-0">
                                        <div
                                            class="text-secondary invest-card-horizontal-left flex-fill min-w-md-down-auto">
                                            <p class="h4 mb-0">
                                                Mieszkanie A/3
                                            </p>
                                            <p class="fs-10 text-uppercase fw-900 mb-0">
                                                Na falistej
                                            </p>
                                            <p class="mb-2">
                                                <small>
                                                    Etap: I
                                                </small>
                                            </p>


                                            <p
                                                class="h4 mb-1 d-flex flex-wrap align-items-center column-gap-2 ff-secondary">
                                                <span>
                                                    611 000 Zł
                                                </span>
                                                <span
                                                    class="text-body-emphasis opacity-50 fs-6 align-middle text-decoration-line-through">
                                                    640 000 Zł
                                                </span>
                                            </p>
                                            <p class="fs-8 text-black mb-0">
                                                Najniższa cena z ostatnich 30 dni:....
                                            </p>

                                            <?php if ($i == 3) : ?>
                                            <p class="text-danger text-uppercase fw-bold">
                                                Sprzedany
                                            </p>
                                            <?php elseif ($i == 2) : ?>

                                            <p class="text-warning text-uppercase fw-bold">
                                                Zarezerwowany
                                            </p>
                                            <?php else : ?>
                                            <p class="text-success text-uppercase fw-bold">
                                                Dostępny
                                            </p>
                                            <?php endif; ?>
                                            <div class="small mb-40">
                                                <table class="w-100">
                                                    <tbody>
                                                        <tr>
                                                            <td class="td-with-icon"><img src="img/tile.svg"
                                                                    alt="" loading="lazy" decoding="async"
                                                                    class="w-10 h-10 object-fit-contain" width="12"
                                                                    height="12"></td>
                                                            <td>Piętro</td>
                                                            <td class="text-end">Parter</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-with-icon"><img src="img/blueprint.svg"
                                                                    alt="" loading="lazy" decoding="async"
                                                                    class="w-10 h-10 object-fit-contain" width="12"
                                                                    height="12"></td>
                                                            <td>Metraż</td>
                                                            <td class="text-end">83,78 m<sup>2</sup></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-with-icon"><img src="img/rooms.svg"
                                                                    alt="" loading="lazy" decoding="async"
                                                                    class="w-10 h-10 object-fit-contain" width="12"
                                                                    height="12"></td>
                                                            <td>Liczba Pokoi</td>
                                                            <td class="text-end">4</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="position-relative z-2">
                                                <a class="btn btn-primary btn-with-icon text-nowrap" href="#">
                                                    Sprawdź
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="6.073"
                                                        height="11.062" viewBox="0 0 6.073 11.062">
                                                        <path id="chevron_right_FILL0_wght100_GRAD0_opsz24"
                                                            d="M360.989-678.469,356-683.458l.542-.542,5.531,5.531-5.531,5.531L356-673.48Z"
                                                            transform="translate(-356 684)" fill="currentColor" />
                                                    </svg>

                                                </a>
                                            </div>
                                        </div>

                                        <div class="invest-card-horizontal-right">
                                            <img src="img/investment_plan.png" alt="" loading="lazy"
                                                decoding="async" class="w-100 h-100 object-fit-contain" width="440"
                                                height="310">
                                        </div>
                                        <a class="stretched-link" href="#">
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
