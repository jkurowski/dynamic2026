@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.floors.index', $investment)}}">{{$investment->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>Wiadomości - {{ $property->name }}</h4>

                </div>
            </div>
        </div>

        @include('admin.developro.investment_shared.menu')

        @foreach($property->roomsNotifications as $message)
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow inbox-message">
                    <div class="inbox-message-header">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-circle"><span class="initials">{{substr($message->data->form_name, 0, 1)}}</span></div>
                                <h4>{{ $message->data->form_name }}</h4>
                                <span>{{ $message->data->form_email }}</span>
                            </div>
                            <div class="col-6 d-flex align-items-end justify-content-end text-right">
                                <span>{{ $message->created_at }} @isset($message->data->ip) <br> IP: {{ $message->data->ip }} @endisset</span>
                            </div>
                            <div class="col-12">
                                <h2>{{ isset($message->data->form_subject) ? $message->data->form_subject : 'Brak tematu' }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="inbox-message-body">
                        <div class="row">
                            <div class="col-12">
                                {!! nl2br($message->data->form_message) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection
