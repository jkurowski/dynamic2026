@extends('front.auth.client.layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-file-text"></i>Oferty specjalne</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if($properties->count() == 0)
                    <div class="col-12 mt-4">
                        <div class="card card-special p-4 text-center">
                            <h5 class="mb-0">Obecnie brak ofert specjalnych. <br>Jeśli pojawią się nowe, poinformujemy Państwa mailowo.</h5>
                        </div>
                    </div>
                @endif
            </div>
            @foreach($properties as $p)
            <div class="col-12 mt-4">
                <div class="card card-special">
                    <div class="row p-4">
                        <div class="col-3">
                            <a href="#">
                                @if($p->file)
                                    <a href="{{ asset('/investment/property/'.$p->file) }}" class="swipebox">
                                        <picture>
                                            <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$p->file_webp) }}">
                                            <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$p->file) }}">
                                            <img src="{{ asset('/investment/property/thumbs/'.$p->file) }}" alt="{{$p->name}}">
                                        </picture>
                                    </a>
                                @else
                                    <img src="https://placehold.co/600x400" alt="">
                                @endif
                            </a>
                        </div>
                        <div class="col-6 pe-4">
                            <div class="card-logo d-flex align-items-center mb-4 pb-4">
                                <img src="https://placehold.co/100x80" alt=""> <a href="">{{ $p->investment->name }} <br> <span>{{ $p->investment->city->name }}</span></a>
                            </div>
                            <h2><a href="#">{{ $p->name }}</a></h2>
                            <ul>
                                <li>Powierzchnia: <span>{{ $p->area }} m<sup>2</sup></span></li>
                                <li>Ilość pokoi: <span>{{ $p->rooms }}</span></li>
                                <li>Piętro: <span>{{ $p->floor->number }}</span></li>
                            </ul>
                        </div>
                        <div class="col-3 ps-0">
                            <div class="card-options ps-4">
                                <div class="d-flex align-items-center justify-content-center h-100 text-center">
                                    <div>
                                        <p><s>Cena: @money($p->price)</s></p>
                                        <p class="text-success">Nowa cena: @money($p->promotion_price)</p>
                                        <p class="small mt-0 mb-5">({{ squareMeterPrice($p->promotion_price, $p->area) }} zł za m<sup>2</sup>)</p>
                                        @if($p->file_pdf)
                                            <a href="{{ asset('/investment/property/pdf/'.$p->file_pdf) }}" target="_blank" class="btn btn-primary m-2">Pobierz kartę .pdf</a>
                                        @endif

                                        <a href="#" class="btn btn-primary m-2">Zobacz mieszkanie</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
