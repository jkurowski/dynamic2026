@extends('layouts.auth.layout')

@section('content')
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="row">
        <div class="col-md-8 offset-md-4 mb-4">
            <img src="{{ asset('/cms/logo-biale.png') }}" alt="DeveloPro">
        </div>
    </div>
    @if (session('status'))
    <div class="row">
        <div class="col-md-8 offset-md-4">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">Adres e-mail:</label>
        <div class="col-md-8">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
            @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn w-100">Wyślij link do zmiany hasła</button>
        </div>
    </div>
</form>
@endsection
