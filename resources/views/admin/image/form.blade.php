@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
<form method="POST" action="{{route('admin.image.update', $entry->id)}}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="container">
        <div class="card-head container">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-grid"></i><a href="{{route('admin.box.index')}}" class="p-0">Edytuj zdjęcie</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            @include('form-elements.back-route-button')
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        @include('form-elements.html-input-text', ['label' => 'Nazwa', 'name' => 'name', 'value' => $entry->name])
                        @include('form-elements.html-input-file', ['label' => 'Zdjęcie', 'name' => 'file'])
                        @include('form-elements.html-input-text', ['label' => 'Atrybut ALT zdjęcia', 'name' => 'file_alt', 'value' => $entry->file_alt])
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
</form>
@endsection
