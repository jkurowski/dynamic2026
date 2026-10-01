@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title row"><i class="fe-inbox"></i>Wiadomości</h4>
                    </div>
                </div>
            </div>

            <div class="card-header border-bottom card-nav">
                <nav class="nav">
                    <a class="nav-link {{ Request::routeIs('admin.inbox.show') ? ' active' : '' }}" href="{{route('admin.inbox.index')}}"><span class="fe-list"></span> Lista wiadomości</a>
                    <a class="nav-link" href="{{ route('admin.rodo.rules.index') }}"><span class="fe-check-square"></span> RODO: regułki</a>
                    <a class="nav-link" href="{{ route('admin.rodo.clients.index') }}"><span class="fe-users"></span> RODO: użytkownicy</a>
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
                                <div class="avatar-circle"><span class="initials">{{substr($message->first()->data->form_name, 0, 1)}}</span></div>
                                <h4>{{ $message->first()->data->form_name }}</h4>
                                <span>{{ $message->first()->data->form_email }}</span>
                            </div>
                            <div class="col-6 d-flex align-items-end justify-content-end text-right">
                                <span>{{ $message->first()->created_at }} @isset($message->first()->data->ip) <br> IP: {{ $message->first()->data->ip }} @endisset</span>
                            </div>
                            <div class="col-12">
                                <h2>{{ isset($message->first()->data->form_subject) ? $message->first()->data->form_subject : 'Brak tematu' }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="inbox-message-body">
                        <div class="row">
                            <div class="col-12">
                                {!! nl2br($message->first()->data->form_message) !!}
                            </div>
                        </div>
                    </div>
                    <div class="inbox-message-footer pt-4">
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('admin.inbox.index')}}" class="btn action-button">Wróć do listy</a>
                                <a href="" class="btn action-button">Usuń</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
