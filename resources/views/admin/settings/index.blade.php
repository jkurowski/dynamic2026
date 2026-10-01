@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-sliders"></i>Ustawienia</h4>
                </div>
            </div>
        </div>

        <div class="card-header border-bottom card-nav">
            <nav class="nav">
                <a class="nav-link {{ Request::routeIs('admin.settings.seo.index') ? 'active' : '' }}" href="{{ route('admin.settings.seo.index') }}"><span class="fe-globe"></span> Główne ustawienia</a>
                <a class="nav-link {{ Request::routeIs('admin.settings.social.index') ? ' active' : '' }}" href="{{ route('admin.settings.social.index') }}"><span class="fe-hash"></span> Social Media</a>
                <a class="nav-link {{ Request::routeIs('admin.log.*') ? 'active' : '' }}" href="{{route('admin.log.index')}}"><span class="fe-hard-drive"></span> Logi PA</a>
                <a class="nav-link {{ Request::routeIs('admin.settings.popup.index') ? 'active' : '' }}" href="{{route('admin.settings.popup.index')}}"><span class="fe-airplay"></span> Baner na start</a>
                <a class="nav-link {{ Request::routeIs('admin.settings.facebook.*') ? 'active' : '' }}" href="{{route('admin.settings.facebook.index')}}"><span class="fe-facebook"></span> Facebook</a>
                <a class="nav-link {{ Request::routeIs('admin.crm.custom-fields.*') ? 'active' : '' }}" href="{{route('admin.crm.custom-fields.index')}}"><span class="fe-book-open"></span> Słowniki / etykiety</a>
                <a class="nav-link {{ Request::routeIs('admin.rodo.rules.index') ? ' active' : '' }}" href="{{ route('admin.rodo.rules.index') }}"><span class="fe-check-square"></span> RODO: regułki</a>
                <a class="nav-link {{Request::routeIs('admin.rodo.settings.index') ? ' active' : ''}}"  href="{{ route('admin.rodo.settings.index') }}"><span class="fe-settings"></span> RODO: ustawienia</a>
            </nav>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    @yield('settings')
                </div>
            </div>
        </div>
    </div>
@endsection
