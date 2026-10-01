@extends('layouts.page', ['body_class' => 'homepage'])

@section('content')
    <div id="page-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Oferta numer: {{ $offer->id }} z dnia {{ date('Y-m-d', strtotime($offer->created_at)) }}</h1>
                    <hr>
                    {!! $offer->message !!}
                </div>
            </div>
        </div>
        @if($selectedOffer)
            @include('front.offer.property_list', ['properties' => $selectedOffer])
        @endif
    </div>
@endsection
