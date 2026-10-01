@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title row"><i class="fe-inbox"></i>Wiadomości</h4>
                    </div>
                </div>
            </div>

            <div class="card-header border-bottom card-nav">
                <nav class="nav">
                    <a class="nav-link {{ Request::routeIs('admin.inbox.index') ? ' active' : '' }}" href=""><span class="fe-list"></span> Lista wiadomości</a>
                    <a class="nav-link" href="{{ route('admin.rodo.rules.index') }}"><span class="fe-check-square"></span> RODO: regułki</a>
                    <a class="nav-link" href="{{ route('admin.rodo.clients.index') }}"><span class="fe-users"></span> RODO: użytkownicy</a>
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
                            <th>#</th>
                            <th>Imię</th>
                            <th>E-mail</th>
                            <th>Temat</th>
                            <th>Miejsce</th>
                            <th>Data</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $index => $p)
                            <tr>
                                <th class="position" scope="row">{{ $index+1 }}</th>
                                <td>{{ $p->data->form_name }}</td>
                                <td>{{ $p->data->form_email }}</td>
                                <td>{{ isset($p->data->form_subject) ? $p->data->form_subject : '-' }}</td>
                                <td>@isset($p->data->url)<a href="{{ $p->data->url }}" target="_blank">{{ $p->data->page_name }}</a>@endisset</td>
                                <td>{{ $p->updated_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{route('admin.inbox.show', $p->id)}}" class="btn action-button me-1" data-toggle="tooltip"data-placement="top" title="Pokaż wiadomość"><i class="fe-mail"></i></a>
                                        <form method="POST" action="">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn action-button confirm" data-toggle="tooltip" data-placement="top" title="Usuń wiadomość" data-id="{{ $p->id }}"><i class="fe-trash-2"></i></button>
                                        </form>
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
