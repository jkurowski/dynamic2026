@extends('admin.layout')
@section('meta_title', '- ' . $sekcja->nazwa())

@section('content')
    <div class="container">
        <div class="card-head container">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-layout"></i><a href="{{ route('admin.sekcja.index') }}" class="p-0">Sekcje stron</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $sekcja->nazwa() }}</h4>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            @include('form-elements.back-route-button')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                @if(!empty($sekcja->schemat()['strona']))
                    <p class="text-muted">Na stronie: <a href="{{ url($sekcja->schemat()['strona']) }}" target="_blank">{{ url($sekcja->schemat()['strona']) }}</a> - po zalogowaniu sekcję można też edytować bezpośrednio na stronie (przycisk „Edytuj sekcję”).</p>
                @endif
                @include('admin.sekcja.pola', ['sekcja' => $sekcja])
            </div>
        </div>
    </div>

    <script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/sekcje-edycja.js') }}"></script>
    <script>sekcjaEdytor(document);</script>
@endsection
