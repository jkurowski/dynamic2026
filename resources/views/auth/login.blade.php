@extends('layouts.auth.layout')

@section('content')
<form method="POST" action="{{ route('login') }}" autocomplete="off">
    @csrf
    <div class="row">
        <div class="col-md-8 offset-md-4 mb-4">
            <img src="{{ asset('/cms/logo-biale.png') }}" alt="DeveloPro">
        </div>
    </div>
    @if (session('verified'))
        <div class="row">
            <div class="col-md-8 offset-md-4">
                <div class="alert alert-success" role="alert">
                    Twoja konto zostało poprawnie aktywowane.
                </div>
            </div>
        </div>
    @endif
    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">Adres e-mail</label>
        <div class="col-md-8">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
            @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right">Hasło</label>
        <div class="col-md-8">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-8 offset-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Zapamiętaj mnie</label>
            </div>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn">{{ __('Login') }}</button>
            <hr>
            @if (Route::has('password.request'))
                <a class="btn btn-secondary" href="{{ route('password.request') }}">Zapomniałeś hasła?</a>
            @endif
        </div>
    </div>
</form>
@endsection
