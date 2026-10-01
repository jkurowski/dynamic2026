<div class="card-header border-bottom card-nav">
    <nav class="nav">
        <a class="nav-link {{ Request::routeIs('admin.crm.clients.index') ? ' active' : '' }}" href="{{ route('admin.crm.clients.index') }}"><span class="fe-users"></span> Lista klientów</a>
        <a class="nav-link {{ Request::routeIs('admin.crm.custom-fields.index') ? ' active' : '' }}" href="{{ route('admin.crm.custom-fields.index') }}"><span class="fe-list"></span> Pola własne</a>
        <a class="nav-link {{ Request::routeIs('admin.crm.board.index') ? ' active' : '' }}" href="{{ route('admin.crm.board.index') }}"><span class="fe-filter"></span> Lejek sprzedaży</a>
        <a class="nav-link {{ Request::routeIs('admin.crm.calendar.index') ? ' active' : '' }}" href="{{ route('admin.crm.calendar.index') }}"><span class="fe-calendar"></span> Kalendarz</a>
    </nav>
</div>
