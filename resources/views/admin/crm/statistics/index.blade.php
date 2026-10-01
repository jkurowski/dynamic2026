@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-bar-chart-line-"></i>Statystyki</h4>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.crm.statistics.index') ? 'active' : '' }}" href="{{ route('admin.crm.statistics.index') }}"><span class="fe-inbox"></span> Formularze</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.statistics.rooms') ? ' active' : '' }}" href="{{ route('admin.crm.statistics.rooms') }}"><span class="fe-home"></span> Mieszkania</a>
            </nav>
        </div>

        <div class="card mt-3">
            <div class="card-header card-nav mt-0">
                <nav class="nav">
                    <div class="container-fluid">
                        <form class="row">
                            <div class="col">
                                <label for="form_campaign" class="form-label">Inwestycja</label>
                                <select class="form-select" id="form_investment" name="investment">
                                    <option value="">Wszystkie</option>
                                    @foreach($investments as $name => $id)
                                        <option value="{{ $id }}" @if(request('investment') == $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="form_campaign" class="form-label">Kampania</label>
                                <select class="form-select" id="form_campaign" name="campaign">
                                    <option value="">Brak</option>
                                    @foreach($campaigns as $c)
                                        <option value="{{ $c }}" @if(request()->get('campaign') == $c) selected @endif>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="form_source" class="form-label">Źródło</label>
                                <select class="form-select" id="form_source" name="source">
                                    <option value="">Brak</option>
                                    @foreach($sources as $s)
                                        <option value="{{ $s }}" @if(request()->get('source') == $s) selected @endif>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                <button type="submit" class="btn btn-primary w-100" id="form_button">Generuj</button>
                            </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div id="portlets" class="card mt-3 bg-transparent shadow-none">
            <div class="card-body card-body-rem p-0">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Ilość wiadomości</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <span class="display-1">{{ $messages->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Źródło</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach($messages_sources as $key => $mc)
                                        <li class="list-group-item">
                                            <div class="ms-0 me-auto">
                                                {!! !empty($key) ? $key : 'Brak' !!}
                                            </div>
                                            <span class="badge">{{ $mc }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body text-center">
                                {!! $sources_chart->container() !!}
                                {!! $sources_chart->script() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Kampania</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach($messages_campaigns as $key => $mc)
                                        <li class="list-group-item">
                                            <div class="ms-0 me-auto">
                                                {!! !empty($key) ? $key : 'Brak' !!}
                                            </div>
                                            <span class="badge">{{ $mc }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body text-center">
                                {!! $campaigns_chart->container() !!}
                                {!! $campaigns_chart->script() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Pokoje</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach($messages_rooms as $key => $mc)
                                        <li class="list-group-item">
                                            <div class="ms-0 me-auto">
                                                {!! !empty($key) ? $key : 'Brak' !!}
                                            </div>
                                            <span class="badge">{{ $mc }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body text-center">
                                {!! $rooms_chart->container() !!}
                                {!! $rooms_chart->script() !!}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-4 mt-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Wg. godzin</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach($hourlyStatistics as $key => $mc)
                                        <li class="list-group-item">
                                            <div class="ms-0 me-auto">
                                                {!! !empty($key) ? $key : 'Brak' !!}
                                            </div>
                                            <span class="badge">{{ $mc }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body text-center">
                                {!! $hourly_chart->container() !!}
                                {!! $hourly_chart->script() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mt-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Wg. dni</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach($weeklyStatistics as $key => $mc)
                                        <li class="list-group-item">
                                            <div class="ms-0 me-auto">
                                                {!! !empty($key) ? $key : 'Brak' !!}
                                            </div>
                                            <span class="badge">{{ $mc }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body text-center">
                                {!! $weekly_chart->container() !!}
                                {!! $weekly_chart->script() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mt-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="portlet-title">Wg. miesięcy</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach($monthlyStatistics as $key => $mc)
                                        <li class="list-group-item">
                                            <div class="ms-0 me-auto">
                                                {!! !empty($key) ? $key : 'Brak' !!}
                                            </div>
                                            <span class="badge">{{ $mc }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body text-center">
                                {!! $monthly_chart->container() !!}
                                {!! $monthly_chart->script() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <script>
            $('#form_date_to, #form_date_from').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                language: "pl"
            });
        </script>
    @endpush
@endsection
