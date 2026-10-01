@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.rodo.rules.edit'))
        <form method="POST" action="{{ route('admin.rodo.rules.update', $entry) }}" id="rodoForm" class="validateForm">
            @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.rodo.rules.store') }}" id="rodoForm">
                    @endif
                    @csrf

                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-home"></i><a href="{{ route('admin.rodo.rules.index') }}">Rodo: regułki</a><span class="d-inline-flex me-2 ms-2">-</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-select', ['label' => 'Status', 'name' => 'active', 'selected' => $entry->active, 'select' => ['1' => 'Pokaż na liście', '0' => 'Ukryj na liście']])
                                </div>
                                @if(Route::is('admin.rodo.rules.edit') && $entry->locked)
                                    <div class="row w-100 form-group">
                                        @include('form-elements.html-select', ['label' => 'Wymagane', 'name' => 'required', 'selected' => $entry->required, 'select' => ['1' => 'Tak', '0' => 'Nie'], 'disabled' => 'disabled'])
                                    </div>
                                    <div class="row w-100 form-group">
                                        @include('form-elements.html-input-text', ['label' => 'Nazwa regułki', 'name' => 'title', 'value' => $entry->title, 'required' => 1, 'readonly' => 'readonly'])
                                    </div>
                                @else
                                    <div class="row w-100 form-group">
                                        @include('form-elements.html-select', ['label' => 'Wymagane', 'name' => 'required', 'selected' => $entry->required, 'select' => ['1' => 'Tak', '0' => 'Nie']])
                                    </div>
                                    <div class="row w-100 form-group">
                                        @include('form-elements.html-input-text', ['label' => 'Nazwa regułki', 'name' => 'title', 'value' => $entry->title, 'required' => 1])
                                    </div>
                                @endif
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Czas trwania regułki', 'name' => 'time', 'value' => $entry->time, 'required' => 1])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.textarea', ['label' => 'Treść regułki', 'name' => 'text', 'value' => $entry->text, 'cols' => 11, 'rows' => 11, 'class' => 'tinymce'])
                                </div>
                            </div>
                        </div>
                        @if(Route::is('admin.rodo.rules.edit'))
                            @include('form-elements.submit-confirm', ['name' => 'submit', 'value' => 'Zapisz regułkę'])
                        @else
                            @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz regułkę'])
                        @endif
                    </div>
                </form>
                @include('form-elements.tintmce')
                @endsection
