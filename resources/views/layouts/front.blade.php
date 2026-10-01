{{-- Wspólny layout frontu (szablon dynamic-front). Strona główna: layouts.homepage, podstrony: layouts.page. --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {!! settings()->get("scripts_head") !!}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('seo_title')@yield('seo_title')@elseif($__env->hasSection('meta_title')){{ settings()->get("page_title") }} - @yield('meta_title')@else{{ settings()->get("page_title") ?: 'Dynamic Development' }}@endif</title>
    <meta name="description" content="@hasSection('seo_description')@yield('seo_description')@else{{ settings()->get("page_description") }}@endif">
    <meta name="robots" content="@hasSection('seo_robots')@yield('seo_robots')@else{{ settings()->get("page_robots") }}@endif">
    <meta name="author" content="{{ settings()->get("page_author") }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon-180.png') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('style')
</head>
<body class="@yield('body_class')">
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
