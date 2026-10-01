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
                <a class="nav-link {{ Request::routeIs('admin.crm.settings.index') ? 'active' : '' }}" href="{{ route('admin.crm.settings.index') }}"><span class="fe-globe"></span> Główne ustawienia</a>
            </nav>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <h1>Test</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
