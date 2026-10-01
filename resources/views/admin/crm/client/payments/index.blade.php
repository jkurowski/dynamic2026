@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-calendar"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span
                                class="d-inline-flex me-2 ms-2">/</span><a
                                href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span
                                class="d-inline-flex me-2 ms-2">/</span>Mieszkania</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-4">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-8">

                @foreach($client->properties as $p)
                <div class="card mt-3">
                    <div class="card-body card-body-rem">
                        <div class="row">
                            <div class="col-3">
                                <img src="https://placehold.co/600x400" alt="">
                            </div>
                            <div class="col-6">
                                <h2>{{ $p->name }}</h2>
                                <h4>{{ $p->investment->name }}</h4>
                                <hr>
                                <ul>
                                    <li>Powierzchnia: {{ $p->area }}m<sup>2</sup></li>
                                    <li>Ilość pokoi: {{ $p->rooms }}</li>
                                    <li>Piętro: {{ $p->floor->number }}</li>
                                    <li>Aneks kuchenny</li>
                                    <li>Data podpisania umowy: 03.07.2024</li>
                                </ul>
                            </div>
                            <div class="col-3 d-flex justify-content-center align-items-center text-center">
                                <div>
                                    @if($p->price)
                                        <h4>Kwota za całość:</h4>
                                        <h3>{{ number_format($p->price, 2, '.', ' ') }} zł</h3>
                                    @endif
                                    @if($p->payments->count() > 0)
                                    <h4>Kwota najbliższej płatności:</h4>
                                    <h3>{{ number_format($p->latestPayment->amount, 2, ',', ' ') }} zł</h3>
                                    <h4 class="mt-3">Najbliższy termin płatności:</h4>
                                    <h3>{{ \Illuminate\Support\Carbon::parse($p->latestPayment->due_date)->format('Y-m-d') }}</h3>
                                    @endif
                                    <a href="{{ route('admin.crm.clients.payments.show', [$client, $p]) }}" class="btn btn-primary mt-3">Pokaż harmonogram</a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 pt-3 border-top">
                            <div class="col-2 d-flex align-items-center text-center">
                                <h4>Powiązane produkty:</h4>
                            </div>
                            <div class="col-3 border-start text-center">
                                <h4><i class="fe-check-circle text-success"></i> Komórka lokatorska nr. 4</h4>
                            </div>
                            <div class="col-3 border-start text-center">
                                <h4><i class="fe-check-circle text-success"></i> Miejsce postojowe nr. 28</h4>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
@endsection
