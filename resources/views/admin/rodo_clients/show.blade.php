@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title row"><i class="fe-inbox"></i>RODO: Lista użytkowników</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">

                    </div>
                </div>
            </div>

            <div class="card-header border-bottom card-nav">
                <nav class="nav">
                    <a class="nav-link {{ Request::routeIs('admin.inbox.index') ? ' active' : '' }}" href="{{ route('admin.inbox.index') }}"><span class="fe-list"></span> Lista wiadomości</a>
                    <a class="nav-link {{ Request::routeIs('admin.rodo.rules.index') ? ' active' : '' }}" href="{{ route('admin.rodo.rules.index') }}"><span class="fe-check-square"></span> RODO: regułki</a>
                    <a class="nav-link {{ Request::routeIs('admin.rodo.clients.show') ? ' active' : '' }}" href="{{ route('admin.rodo.clients.index') }}"><span class="fe-users"></span> RODO: użytkownicy</a>
                    <a class="nav-link" href="{{ route('admin.rodo.settings.index') }}"><span class="fe-settings"></span> RODO: ustawienia</a>
                </nav>
            </div>

        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow inbox-message">
                    <div class="inbox-message-header">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-circle"><span class="initials">{{substr($client->name, 0, 1)}}</span></div>
                                <h4>{{ $client->name }}</h4>
                                <span>{{ $client->mail }}</span>
                            </div>
                            <div class="col-6 d-flex align-items-end justify-content-end text-right">
                                <span>{{ $client->created_at }} @isset($client->ip) <br> IP: {{ $client->ip }} @endisset</span>
                            </div>
                            <div class="col-12">

                            </div>
                        </div>
                    </div>
                    <div class="inbox-message-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-overflow">
                                    <table class="table mb-0" id="sortable">
                                        <thead class="thead-default">
                                        <tr>
                                            <th>Nazwa</th>
                                            <th class="text-center">Data podpisania</th>
                                            <th>Miejsce podpisania</th>
                                            <th class="text-center">Termin wygaśniecia</th>
                                            <th class="text-center">IP</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody class="content">
                                        @foreach ($list as $p)
                                            <tr>
                                                <td>{{ ($p->rules) ? $p->rules->title : 'Regułka usunięta' }}</td>
                                                <td class="text-center">{{ $p->created_at }}</td>
                                                <td>{{ $p->url }}</td>
                                                <td class="text-center">{{ date('Y-m-d', $p->duration) }}</td>
                                                <td class="text-center">{{ $p->ip }}</td>
                                                <td class="text-center">{!! status($p->status) !!}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="inbox-message-footer pt-4">
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('admin.rodo.clients.index')}}" class="btn action-button">Wróć do listy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
