@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-inbox"></i>RODO: regułki</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                    <a href="{{route('admin.rodo.rules.create')}}" class="btn btn-primary">Dodaj regułkę</a>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.settings.seo.index') ? 'active' : '' }}" href="{{ route('admin.settings.seo.index') }}"><span class="fe-globe"></span> Główne ustawienia</a>
                <a class="nav-link {{ Request::routeIs('admin.settings.social.index') ? ' active' : '' }}" href="{{ route('admin.settings.social.index') }}"><span class="fe-hash"></span> Social Media</a>
                <a class="nav-link {{ Request::routeIs('admin.log.*') ? 'active' : '' }}" href="{{route('admin.log.index')}}"><span class="fe-hard-drive"></span> Logi PA</a>
                <a class="nav-link {{ Request::routeIs('admin.settings.popup.index') ? 'active' : '' }}" href="{{route('admin.settings.popup.index')}}"><span class="fe-airplay"></span> Baner na start</a>
                <a class="nav-link {{ Request::routeIs('admin.settings.facebook.*') ? 'active' : '' }}" href="{{route('admin.settings.facebook.index')}}"><span class="fe-facebook"></span> Facebook</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.custom-fields.*') ? 'active' : '' }}" href="{{route('admin.crm.custom-fields.index')}}"><span class="fe-book-open"></span> Słowniki / etykiety</a>
                <a class="nav-link {{ Request::routeIs('admin.rodo.rules.index') ? ' active' : '' }}" href="{{ route('admin.rodo.rules.index') }}"><span class="fe-check-square"></span> RODO: regułki</a>
                <a class="nav-link {{Request::routeIs('admin.rodo.settings.index') ? ' active' : ''}}"  href="{{ route('admin.rodo.settings.index') }}"><span class="fe-settings"></span> RODO: ustawienia</a>
            </nav>
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    @if (session('success'))
                        <div class="alert alert-success border-0 mb-0">
                            {{ session('success') }}
                            <script>setTimeout(function(){$(".alert").slideUp(500,function(){$(this).remove()})},3000)</script>
                        </div>
                    @endif
                    <table class="table mb-0" id="sortable">
                        <thead class="thead-default">
                        <tr>
                            <th>#</th>
                            <th>Nazwa</th>
                            <th class="text-center">Czas trwania</th>
                            <th class="text-center">Wymagane</th>
                            <th class="text-center">Status</th>
                            <th>Data utworzenia</th>
                            <th>Data edycji</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list as $index => $p)
                            <tr>
                                <th class="position" scope="row">{{ $index+1 }}</th>
                                <td>{{ $p->title }}</td>
                                <td class="text-center">{{ $p->time }}</td>
                                <td class="text-center">{!! required($p->required) !!}</td>
                                <td class="text-center">{!! status($p->active) !!} </td>
                                <td>{{ $p->created_at }}</td>
                                <td>{{ $p->updated_at }}</td>
                                <td class="option-120 text-end">
                                    <div class="btn-group">
                                        <a href="{{route('admin.rodo.rules.edit', $p)}}" class="btn action-button me-1" data-toggle="tooltip" data-placement="top" title="Edytuj"><i class="fe-edit"></i></a>
                                        @if(!$p->locked)
                                        <form method="POST" action="{{route('admin.rodo.rules.destroy', $p)}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn action-button confirm" data-toggle="tooltip" data-placement="top" title="Usuń wiadomość" data-id="{{ $p->id }}"><i class="fe-trash-2"></i></button>
                                        </form>
                                        @endif
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
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{route('admin.rodo.rules.create')}}" class="btn btn-primary">Dodaj regułkę</a>
                </div>
            </div>
        </div>
    </div>
@endsection
