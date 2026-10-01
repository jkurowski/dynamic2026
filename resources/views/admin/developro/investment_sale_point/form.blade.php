@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.developro.investment-sale-point.edit'))
        <form method="POST" action="{{route('admin.developro.investment-sale-point.update', $entry)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.developro.investment-sale-point.store')}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-shopping-bag"></i><a href="{{route('admin.developro.investment-sale-point.index')}}">Punkty sprzedaży</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group">
                                    <div class="col-4">
                                        @include('form-elements.html-select', [
                                            'label' => 'Województwo',
                                            'name' => 'province',
                                            'selected' => $entry->province,
                                            'select' => [
                                                'dolnoslaskie' => 'Dolnośląskie',
                                                'kujawsko-pomorskie' => 'Kujawsko-Pomorskie',
                                                'lubelskie' => 'Lubelskie',
                                                'lubuskie' => 'Lubuskie',
                                                'lodzkie' => 'Łódzkie',
                                                'malopolskie' => 'Małopolskie',
                                                'mazowieckie' => 'Mazowieckie',
                                                'opolskie' => 'Opolskie',
                                                'podkarpackie' => 'Podkarpackie',
                                                'podlaskie' => 'Podlaskie',
                                                'pomorskie' => 'Pomorskie',
                                                'slaskie' => 'Śląskie',
                                                'swietokrzyskie' => 'Świętokrzyskie',
                                                'warminsko-mazurskie' => 'Warmińsko-Mazurskie',
                                                'wielkopolskie' => 'Wielkopolskie',
                                                'zachodniopomorskie' => 'Zachodniopomorskie',
                                            ]
                                        ])
                                    </div>
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Powiat', 'name' => 'district', 'value' => $entry->district])
                                    </div>
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Gmina', 'name' => 'municipality', 'value' => $entry->municipality])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    <div class="col-3">
                                        @include('form-elements.html-input-text', ['label' => 'Kod pocztowy', 'name' => 'postal_code', 'value' => $entry->postal_code])
                                    </div>
                                    <div class="col-9">
                                        @include('form-elements.html-input-text', ['label' => 'Miejscowość', 'name' => 'city', 'value' => $entry->city])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Ulica', 'name' => 'street', 'value' => $entry->street])
                                    </div>
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Budynek', 'name' => 'building_number', 'value' => $entry->building_number])
                                    </div>
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Nr lokalu', 'name' => 'apartment_number', 'value' => $entry->apartment_number])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    <div class="col-12">
                                        @include('form-elements.html-input-text', ['label' => 'Dodatkowe lokalizacje', 'name' => 'additional_locations', 'value' => $entry->additional_locations])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    <div class="col-12">
                                        @include('form-elements.html-input-text', ['label' => 'Metody kontaktu z klientem', 'name' => 'contact_method', 'value' => $entry->contact_method])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
        @endsection
