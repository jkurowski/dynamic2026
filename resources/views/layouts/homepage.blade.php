<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {!! settings()->get("scripts_head") !!}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ settings()->get("page_title") ?: 'Dynamic Development - mieszkania i domy w Warszawie' }}</title>
    <meta name="description" content="{{ settings()->get("page_description") }}">
    <meta name="robots" content="{{ settings()->get("page_robots") }}">
    <meta name="author" content="{{ settings()->get("page_author") }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon-180.png') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('style')
</head>
<body class="strona-glowna">
{!! settings()->get("scripts_afterbody") !!}

@include('layouts.partials.header')

<main id="tresc">
    @yield('content')
</main>

@include('layouts.partials.footer')

@auth
    @include('layouts.partials.inline')
@endauth

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

@stack('scripts')

<script src="{{ asset('js/animacje.js') }}"></script>
<script src="{{ asset('js/glowny.js') }}"></script>

{!! settings()->get("scripts_beforebody") !!}
</body>
</html>
