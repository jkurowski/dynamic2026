@extends('layouts.auth.layout')

@section('content')
    @if(!session('status'))
        <form action="{{ route('login.request-code') }}" method="POST" autocomplete="off">

            <div class="row">
                <div class="col-12 mb-4">
                    <img src="{{ asset('/cms/logo-biale.png') }}" alt="DeveloPro">
                </div>
            </div>

            @csrf

            <div class="form-group row">
                <label for="email" class="col-12 col-form-label text-md-right">Adres e-mail</label>
                <div class="col-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                    @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn">Wyślij kod</button>
                </div>
            </div>
        </form>
    @endif

    @if(session('status') && session('code') == 1)
        <form action="{{ route('login.verify-code') }}" method="POST" autocomplete="off">

            <div class="row">
                <div class="col-12 mb-4">
                    <img src="{{ asset('/cms/logo-biale.png') }}" alt="DeveloPro">
                </div>
            </div>

            @if (session('status'))
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="alert alert-success alert-client" role="alert">
                            {!! session('status') !!}
                        </div>
                    </div>
                </div>
            @endif

            @csrf
            <input type="hidden" name="email" value="{{ session('mail') }}">

            <div class="form-group row">
                <label for="code" class="col-12 col-form-label text-md-right">Wpisz 6-cyfrowy kod</label>
                <div class="col-12">
                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required>
                    @error('code')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn">Gotowe</button>
                </div>
            </div>
        </form>
    @endif

@endsection