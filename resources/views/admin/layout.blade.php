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

    @stack('style')

</head>

<body class="lang-pl">
    <div id="admin">
        <div class="sidemenu-holder">
            <div id="sidemenu">
                <ul class="list-unstyled mb0">
                    <li class="active">
                        <a class='d-flex gap-1 align-items-center' href="{{ route('admin.settings.seo.index') }}">
                            <i class="fe-cpu"></i>
                            <span> CMS </span>
                            <i class='fe-settings ms-auto with-hover'></i>
                        </a>
                        <ul class="sub-menu">
                            <li {{ Request::routeIs('admin.page.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.page.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Strony</a>
                            </li>
                            <li {{ Request::routeIs('admin.article.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.article.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Aktualności</a>
                            </li>
                            <li {{ Request::routeIs('admin.slider.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.slider.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Slider</a>
                            </li>
                            <li {{ Request::routeIs('admin.map.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.map.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Mapa</a>
                            </li>
                            <li {{ Request::routeIs('admin.gallery.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.gallery.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Galeria</a>
                            </li>
                            <li {{ Request::routeIs('admin.user.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.user.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Użytkownicy</a>
                            </li>
                            <li {{ Request::routeIs('admin.greylist.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.greylist.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Blokada dostępu</a>
                            </li>
                            <li {{ Request::routeIs('admin.settings.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.settings.seo.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Ustawienia</a>
                            </li>
                            <li {{ Request::routeIs('admin.job.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.job.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Oferty pracy</a>
                            </li>
                            <li class="d-none">
                                <a href="">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Boksy</a>
                            </li>
                        </ul>
                    </li>
                    <li class="active">
                        <a class='d-flex gap-1 align-items-center' href="{{ route('admin.crm.settings.index') }}">
                            <i class="fe-cpu"></i>
                            <span> DeveloCRM </span>
                            <i class='fe-settings ms-auto with-hover'></i>
                        </a>
                        <ul class="sub-menu">
                            <li class="{{ Request::routeIs('admin.city.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.city.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Miasta
                                </a>
                            </li>
                            <li class="{{ Request::routeIs('admin.developro.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.developro.investment.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Inwestycje
                                </a>
                            </li>
                            <li {{ Request::routeIs('admin.crm.contact.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.crm.contact.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Kontakty</a>
                            </li>
                            <li {{ Request::routeIs('admin.crm.statistics.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.crm.statistics.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Statystyki</a>
                            </li>
                            <li {{ Request::routeIs('admin.crm.inbox.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.crm.inbox.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Leads</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="modalNewUser"></div>
        <div id="content">
            <header id="header-navbar">
                <h1><a href="" class="logo"><span>kCMS v4.2</span></a></h1>

                <a href="#" id="togglemenu"><span class="fe-menu"></span></a>
                <div class="user">
                    <ul>
                        <li class="pt-0 pb-0"><a href="{{ route('admin.crm.calendar.index') }}"
                                class="header-btn"><i class="fe-calendar"></i> Kalendarz</a></li>
                        <li class="pt-0 pb-0"><a href="#" class="header-btn btn-add-user"><i
                                    class="fe-plus-square"></i> Nowy klient</a></li>
                        <li class="pt-0 pb-0"><a href="{{ route('admin.crm.offer.create') }}" class="header-btn"><i
                                    class="fe-plus-square"></i> Nowa oferta</a></li>
                        <li><span class="fe-calendar"></span> <span id="livedate"><?= date('d-m-Y') ?></span></li>
                        <li><span class="fe-clock"></span> <span id="liveclock"></span></li>
                        <li><span class="fe-user"></span> Witaj: <b>{{ Auth::user()->name }}</b></li>
                        <li><a title="Idź do strony" href="/" target="_blank"><span class="fe-monitor"></span>
                                Idź do strony</a></li>
                        <li>
                            <a title="Wyloguj" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span
                                    class="fe-lock"></span> Wyloguj</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
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
    <script>
        $(document).ready(function() {
            $(".btn-add-user").click((d) => {
                d.preventDefault();
                const modalHolder = $('#modalNewUser');
                modalHolder.empty();

                jQuery.ajax({
                    url: '{{ route('admin.crm.clients.create') }}',
                    success: function(response) {
                        if (response) {
                            modalHolder.append(response);
                            initModal('store');
                        } else {
                            alert('Error');
                        }
                    }
                });
            });

            const token = '{{ csrf_token() }}';

            function initModal(action = 'update') {
                const modal = document.getElementById('portletModal'),
                    bootstrapModal = new bootstrap.Modal(modal),
                    form = document.getElementById('modalForm'),
                    inputName = $('#inputName'),
                    inputSurname = $('#inputSurname'),
                    inputEmail = $('#inputEmail'),
                    inputPhone = $('#inputPhone'),
                    inputInvestment = $('#inputInvestment');

                bootstrapModal.show();

                modal.addEventListener('shown.bs.modal', function() {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                        '[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl, {
                            trigger: 'hover'
                        })
                    });
                })

                modal.addEventListener('hidden.bs.modal', function() {
                    $('#portletModal').remove();
                })

                const alert = $('.alert-danger');

                const url = action === 'update' ? '' : '{{ route('admin.crm.clients.store') }}';
                const method = action === 'update' ? 'PUT' : 'POST';

                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const rules = {};
                    $('input[type=checkbox][name^=rule]').each(function() {
                        rules[$(this).attr('name')] = $(this).is(':checked') ? 1 : 0;
                    });

                    jQuery.ajax({
                        url: url,
                        method: method,
                        data: {
                            '_token': token,
                            'name': inputName.val(),
                            'lastname': inputSurname.val(),
                            'email': inputEmail.val(),
                            //'phone': inputPhone.val(),
                            'status': 1,
                            'source': 1,
                            ...rules
                        },
                        success: function() {
                            bootstrapModal.hide();
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.success("Wpis został zaktualizowany");
                        },
                        error: function(result) {
                            if (result.responseJSON.data) {
                                alert.html('');
                                $.each(result.responseJSON.data, function(key, value) {
                                    alert.show();
                                    alert.append('<span>' + value + '</span>');
                                });
                            }
                        }
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
