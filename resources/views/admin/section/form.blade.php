@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.section.edit'))
        <form method="POST" action="{{route('admin.section.update', $entry->id)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.section.store')}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card">
                            <div class="card-head container">
                                <div class="row">
                                    <div class="col-12 pl-0">
                                        <h4 class="page-title row"><i class="fe-grid"></i><a href="{{route('admin.section.index')}}" class="p-0">Sekcje strony</a><span class="d-inline-flex ml-2 mr-2">/</span>{{ $cardTitle }}</h4>
                                    </div>
                                </div>
                            </div>
                            @include('form-elements.back-route-button')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        @include('form-elements.html-select', ['label' => 'Miejsce', 'name' => 'place_id', 'selected' => $entry->place_id, 'select' => [
                                            '1' => 'Atuty lokalizacji'
                                        ]])
                                        @include('form-elements.html-input-text', ['label' => 'Tytuł sekcji', 'name' => 'title', 'value' => $entry->title, 'required' => 1])
                                        @include('form-elements.html-input-file', ['label' => 'Obrazek', 'sublabel' => '(wymiary: '.config('images.section.width').'px / '.config('images.section.height').'px)', 'name' => 'file'])
                                        @include('form-elements.textarea-fullwidth', ['label' => 'Treść', 'name' => 'content', 'value' => $entry->content, 'rows' => 11, 'class' => 'tinymce', 'required' => 1])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
                @include('form-elements.tintmce')
@endsection
