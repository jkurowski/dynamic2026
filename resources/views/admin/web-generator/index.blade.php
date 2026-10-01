<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {!! settings()->get("scripts_head") !!}

    <title>@hasSection('seo_title')@yield('seo_title')@else{{ settings()->get("page_title") }} - @yield('meta_title')@endif</title>

    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@hasSection('seo_description')@yield('seo_description')@else{{ settings()->get("page_description") }}@endif">
    <meta name="robots" content="@hasSection('seo_robots')@yield('seo_robots')@else{{ settings()->get("page_robots") }}@endif">
    <meta name="author" content="{{ settings()->get("page_author") }}">

    @hasSection('opengraph')@yield('opengraph')@endif
    @hasSection('schema')@yield('schema')@endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/styles.min.css') }}" rel="stylesheet">

    @stack('style')

</head>

<style>
    main {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        margin: 0 auto;
    }

    main > * {
        flex: 1 1 100%;
    }

    @media (min-width: 576px) {
        main {
            max-width: 540px;
        }
    }

    @media(min-width: 768px) {
        main {
            max-width: 720px;
        }
    }

    @media(min-width: 992px) {
        main {
            max-width: 960px;
        }
    }

    @media(min-width: 1200px) {
        main {
            max-width: 1140px;
        }
    }

    @media(min-width: 1400px) {
        main {
            max-width: 1320px;
        }
    }
</style>

<body>
    <main class="container">
        {!! $html !!}
    </main>

</body>

</html>
