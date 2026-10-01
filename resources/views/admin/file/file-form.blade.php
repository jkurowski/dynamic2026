@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.file.edit'))
        <form method="POST" action="{{route('admin.file.update', $entry->id)}}" enctype="multipart/form-data">
    @method('PUT')
    @else
        <form method="POST" action="{{route('admin.file.store')}}" enctype="multipart/form-data">
            @endif
            @csrf
            <div class="container">
                <div class="card">
                    <div class="card-head container">
                        <div class="row">
                            <div class="col-12 pl-0">
                                <h4 class="page-title"><i class="fe-grid"></i><a href="{{route('admin.file.index')}}" class="p-0">Pliki</a><span class="d-inline-flex me-2 ms-2">/</span>{!! $cardTitle !!}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    @include('form-elements.back-route-button')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @include('form-elements.html-input-text-count', ['label' => 'Nazwa', 'name' => 'name', 'value' => $entry->name, 'maxlength' => 190, 'required' => 1])
                                @include('form-elements.html-input-text-count', ['label' => 'Opis', 'name' => 'description', 'value' => $entry->description, 'maxlength' => 190])
                                @if(Route::is('admin.file.edit'))
                                    @include('form-elements.html-input-file', ['label' => 'Plik', 'name' => 'file'])
                                @else
                                    @include('form-elements.html-input-file', ['label' => 'Plik', 'name' => 'file', 'required' => 1])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($parent_id))
                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
            @endif
            @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
        </form>
@endsection
