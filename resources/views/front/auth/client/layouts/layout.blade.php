<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>DeveloPro @yield('meta_title')</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex, nofollow">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Prefetch -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/admin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/client.min.css') }}">

    @stack('style')
</head>
<body class="lang-pl">
<div id="admin">
    <div class="sidemenu-holder">
        <div id="sidemenu">
            <ul class="list-unstyled mb0">
                <li class="active">
                    <a href="#">
                        <i class="fe-user"></i>
                        <span> Strefa klienta </span>
                    </a>
                    <ul class="sub-menu p-0">
                        @if($client->status >= 5)
                        <li {{ Request::routeIs('front.client.area.chat') ? 'class=active' : '' }}>
                            <a href="{{ route('front.client.area.chat') }}">
                                <i class="fe-mail me-2"></i> Czat</a>
                        </li>
                        <li {{ Request::routeIs('front.client.area.files') ? 'class=active' : '' }}>
                            <a href="{{ route('front.client.area.files') }}">
                                <i class="fe-inbox me-2"></i> Pliki</a>
                        </li>
                        <li {{ Request::routeIs('front.client.area.calendar') ? 'class=active' : '' }}>
                            <a href="{{ route('front.client.area.calendar') }}">
                                <i class="fe-calendar me-2"></i> Kalendarz</a>
                        </li>
                        <li {{ Request::routeIs('admin.settings.*') ? 'class=active' : '' }}>
                            <a href="#">
                                <i class="fe-tablet me-2"></i> Mieszkania</a>
                        </li>
                        <li {{ Request::routeIs('front.client.area.offer') ? 'class=active' : '' }}>
                            <a href="{{ route('front.client.area.offer') }}">
                                <i class="fe-file-text me-2"></i> Oferty</a>
                        </li>
                        @endif
                            <li {{ Request::routeIs('front.client.area.special') ? 'class=active' : '' }}>
                                <a href="{{ route('front.client.area.special') }}">
                                    <i class="fe-file-text me-2"></i> Oferty specjalne</a>
                            </li>
                        <li {{ Request::routeIs('front.client.area.rodo') ? 'class=active' : '' }}>
                            <a href="{{ route('front.client.area.rodo') }}">
                                <i class="fe-list me-2"></i> Zgody RODO</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="content">
        <header id="header-navbar">
            <h1><a href="" class="logo"><span>DeveloPro</span></a></h1>
            <div class="user">
                <ul>
                    <li><span class="fe-calendar"></span> <span id="livedate"><?=date('d-m-Y');?></span></li>
                    <li><span class="fe-clock"></span> <span id="liveclock"></span></li>
                    <li><span class="fe-user"></span> Witaj: &nbsp;<b>{{ $client->name }}</b></li>
                    <li><a title="Idź do strony" href="/" target="_blank"><span class="fe-monitor"></span> Idź do strony</a></li>
                    <li>
                        <a title="Wyloguj" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span class="fe-lock"></span> Wyloguj</a>
                        <form id="logout-form" action="{{ route('front.client.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </header>
        <div class="content">
            @yield('submenu')

            @yield('content')
        </div>
    </div>
</div>

<!--Google font style-->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- jQuery -->
<script src="{{ asset('/js/jquery.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/jquery-ui.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/cms.min.js') }}" charset="utf-8"></script>
@stack('scripts')

</body>
</html>
