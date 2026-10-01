<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>DeveloPro</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
</head>
<body class="lang-pl h-100 mb-0">
<div id="app" class="h-100">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-6 d-flex justify-content-center align-items-center">
                @yield('content')
            </div>
            <div id="loginBg" class="col-6">

            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/auth.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</body>
</html>


