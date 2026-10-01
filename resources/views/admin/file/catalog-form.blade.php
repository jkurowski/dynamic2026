@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
@if(Route::is('admin.file-catalog.edit'))
<form method="POST" action="{{route('admin.file-catalog.update', $entry->id)}}">
    @method('PUT')
    @else
<form method="POST" action="{{route('admin.file-catalog.store')}}">
    @endif
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-grid"></i><a href="{{route('admin.file.index')}}" class="p-0">Pliki</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
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
                    </div>
                </div>
            </div>
        </div>
    @if(isset($parent_id))
        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
    @else
        <input type="hidden" name="parent_id" value="0">
    @endif
    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
</form>
@endsection
