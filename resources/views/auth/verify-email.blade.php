@extends('layouts.auth.layout')

@section('content')
    <div class="apla">
        <div class="row">
            <div class="col-md-8 mb-4">
                <img src="{{ asset('/cms/logo-biale.png') }}" alt="DeveloPro">
            </div>
            <div class="col-12">
                @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ __('Nowy link weryfikacyjny został wysłany na Twój adres e-mail.') }}
                    </div>
                @endif

                <p>Twoje konto nie ma jeszcze potwierdzenia. Sprawdź swoją skrzynkę pocztową i kliknij link weryfikacyjny.</p>
                    <hr>
                    <p>Jeśli nie otrzymałeś wiadomości:</p>
            </div>
        </div>
        <div class="form-group row mb-0">
            <div class="col-md-12 d-flex">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn">Wyślij wiadomość z linkiem</button>
                </form>

                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn">Wyloguj</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection
