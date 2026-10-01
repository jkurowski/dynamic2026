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
                    <a class="nav-link {{ Request::routeIs('admin.rodo.clients.index') ? ' active' : '' }}" href="{{ route('admin.rodo.clients.index') }}"><span class="fe-users"></span> RODO: użytkownicy</a>
                    <a class="nav-link" href="{{ route('admin.rodo.settings.index') }}"><span class="fe-settings"></span> RODO: ustawienia</a>
                </nav>
            </div>

        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0" id="sortable">
                        <thead class="thead-default">
                        <tr>
                            <th>Nazwa</th>
                            <th class="text-center">Adres e-mail</th>
                            <th class="text-center">IP</th>
                            <th class="text-center">Przeglądarka</th>
                            <th>Data utworzenia</th>
                            <th>Data aktualizacji</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td class="text-center">{{ $p->mail }}</td>
                                <td class="text-center">{{ $p->ip }}</td>
                                <td class="text-center">{{ $p->browser }}</td>
                                <td>{{ $p->created_at }}</td>
                                <td>{{ $p->updated_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.rodo.clients.show', $p->id) }}" class="btn action-button mr-1" data-toggle="tooltip" data-placement="top" title="Historia"><i class="fe-archive"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
