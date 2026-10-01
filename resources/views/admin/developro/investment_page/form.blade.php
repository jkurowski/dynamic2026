@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.developro.investment.page.edit'))
        <form method="POST" action="{{route('admin.developro.investment.page.update', [$investment, $entry])}}">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.developro.investment.page.store', $investment)}}">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.page.index', $investment)}}">{{$investment->name}}: Strony tekstowe</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group d-none">
                                    @include('form-elements.html-select', ['label' => 'Status', 'name' => 'active', 'selected' => $entry->active, 'select' => ['1' => 'Pokaż na liście', '0' => 'Ukryj na liście']])
                                </div>

                                <div class="row w-100 form-group d-none">
                                    @include('form-elements.html-select', ['label' => 'Formularz kontaktowy', 'name' => 'contact_form', 'selected' => $entry->contact_form, 'select' => ['0' => 'Nie', '1' => 'Tak']])
                                </div>

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Tytuł strony', 'name' => 'title', 'value' => $entry->title, 'required' => 1])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Nagłówek strony', 'sublabel'=> 'Meta tag - title', 'name' => 'meta_title', 'value' => $entry->meta_title])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Opis strony', 'sublabel'=> 'Meta tag - description', 'name' => 'meta_description', 'value' => $entry->meta_description])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Indeksowanie', 'sublabel'=> 'Meta tag - robots', 'name' => 'meta_robots', 'value' => $entry->meta_robots])
                                </div>


                                <div class="row w-100 form-group d-none">
                                    @include('form-elements.html-input-text', ['label' => 'Tekst CTA', 'sublabel'=> 'Sekcja CTA na dole strony', 'name' => 'cta_text', 'value' => $entry->cta_text])
                                </div>
                                <div class="row w-100 form-group d-none">
                                    @include('form-elements.html-input-text', ['label' => 'Button CTA', 'sublabel'=> 'Sekcja CTA na dole strony', 'name' => 'cta_button', 'value' => $entry->cta_button])
                                </div>
                                <div class="row w-100 form-group d-none">
                                    @include('form-elements.html-input-text', ['label' => 'Link CTA', 'sublabel'=> 'Sekcja CTA na dole strony', 'name' => 'cta_link', 'value' => $entry->cta_link])
                                </div>

                                <div class="row w-100 form-group">
                                    @include('form-elements.textarea-fullwidth', ['label' => 'Wprowadź tekst', 'name' => 'content', 'value' => $entry->content, 'rows' => 11, 'class' => 'tinymce', 'required' => 1])
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="investment_id" value="{{$investment->id}}">
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
        @include('form-elements.tintmce')
        @endsection
