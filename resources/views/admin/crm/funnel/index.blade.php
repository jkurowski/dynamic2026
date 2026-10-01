@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-filter"></i>Lejek sprzedaży</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.crm.clients.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.clients.index') }}"><span class="fe-home"></span>Klienci</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.funnel.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.funnel.index') }}"><span class="fe-filter"></span>Lejek sprzedaży</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.offer.*') ? ' active' : '' }}"
                    href="{{ route('admin.crm.offer.index') }}"><span class="fe-file"></span>Oferty</a>
            </nav>
        </div>

        <div class="card mt-3">
            <div class="card-header card-nav mt-0">
                <nav class="nav">
                    <div class="container-fluid">
                        <form class="row">

                            <div class="col">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="form_date_from" class="form-label">Data od</label>
                                        <input type="text" class="form-control" id="form_date_from" name="date_from" value="{{ request()->get('date_from') }}">
                                    </div>
                                    <div class="col-6">
                                        <label for="form_date_to" class="form-label">Data do</label>
                                        <input type="text" class="form-control" id="form_date_to" name="date_to" value="{{ request()->get('date_to') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" id="form_button">Pokaż</button>
                            </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="table-overflow funnel-bg p-5">
                <div class="funnel w-100"></div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}"></script>
        <script src="{{ asset('js/funnel-graph.min.js') }}"></script>

        <link rel="stylesheet" href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}">
        <link rel="stylesheet" href="{{ asset('cms/funnel.min.css') }}">
        <script>
            $('#form_date_to, #form_date_from').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                language: "pl"
            });

            const graph = new FunnelGraph({
                container: '.funnel',
                gradientDirection: 'vertical',
                data: {
                    labels: [
                        'Kontakt wstępny',
                        'Wysłana Oferta',
                        'Umówione Spotkanie',
                        'Negocjacje',
                        'Umowa Rezerwacyjna',
                        'Umowa Deweloperska',
                        'Zmiany Lokatorskie',
                        'Odbiór Techniczny',
                        'Umowa przeniesienia Własności',
                        'Wydanie Lokalu',
                    ],
                    colors: [
                        [
                            '#4682B4', // Kontakt wstępny
                            '#4E95A7', // Wysłana Oferta
                            '#56A79A', // Umówione Spotkanie
                            '#5EB98D', // Negocjacje
                            '#66CC80', // Umowa Rezerwacyjna
                            '#6FDF73', // Umowa Deweloperska
                            '#77F266', // Zmiany Lokatorskie
                            '#80FF59', // Odbiór Techniczny
                            '#89FF4C', // Umowa przeniesienia Własności
                            '#32CD32'  // Wydanie Lokalu
                        ],
                    ],
                    values: [
                        @for ($i = 0; $i < 10; $i++)
                            @if (isset($counts[$i]) && $counts[$i]->count > 0)
                            [{{ $counts[$i]->count }}],
                                @else
                            [0],
                            @endif
                        @endfor
                    ]
                },
                displayPercent: false,
                direction: 'vertical'
            });

            graph.draw();

            window.onresize = graph.updateWidth();
        </script>
    @endpush
@endsection
