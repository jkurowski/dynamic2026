@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.developro.investment-company.edit'))
        <form method="POST" action="{{route('admin.developro.investment-company.update', $entry)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.developro.investment-company.store')}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-share-2"></i><a href="{{route('admin.developro.investment-company.index')}}">Spółki celowe</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group">
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', ['label' => 'Nazwa spółki', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                    </div>
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', ['label' => 'Forma prawna', 'name' => 'legal_form', 'value' => $entry->legal_form, 'required' => 1])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    <div class="col-3">
                                        @include('form-elements.html-input-text', ['label' => 'Nr KRS', 'name' => 'krs_number', 'value' => $entry->krs_number, 'required' => 1])
                                    </div>
                                    <div class="col-3">
                                        @include('form-elements.html-input-text', ['label' => 'Nr wpisu do CEiDG', 'name' => 'ceidg_number', 'value' => $entry->ceidg_number, 'required' => 0])
                                    </div>
                                    <div class="col-3">
                                        @include('form-elements.html-input-text', ['label' => 'NIP', 'name' => 'nip', 'value' => $entry->nip, 'required' => 1])
                                    </div>
                                    <div class="col-3">
                                        @include('form-elements.html-input-text', ['label' => 'REGON', 'name' => 'regon', 'value' => $entry->regon, 'required' => 1])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Telefon', 'name' => 'phone', 'value' => $entry->phone, 'required' => 1])
                                    </div>
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Email', 'name' => 'email', 'value' => $entry->email, 'required' => 1])
                                    </div>
                                    <div class="col-4">
                                        @include('form-elements.html-input-text', ['label' => 'Fax', 'name' => 'fax', 'value' => $entry->fax])
                                    </div>
                                </div>
                                <div class="row w-100 form-group">
                                    <div class="col-12">
                                        @include('form-elements.html-input-text', ['label' => 'Strona internetowa', 'name' => 'website', 'value' => $entry->website])
                                    </div>
                                </div>
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
                            </div>
                        </div>
                    </div>
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
        @endsection
