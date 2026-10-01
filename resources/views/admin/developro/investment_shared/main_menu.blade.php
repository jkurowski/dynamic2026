<div class="card-header card-nav">
    <nav class="nav">
        <a class="nav-link {{ Request::routeIs('admin.developro.investment.index') ? ' active' : '' }}" href="{{ route('admin.developro.investment.index') }}"><span class="fe-list"></span> Lista inwestycji</a>
        <a class="nav-link {{ Request::routeIs('admin.developro.investment-company.index') ? ' active' : '' }}" href="{{ route('admin.developro.investment-company.index') }}"><span class="fe-share-2"></span> Spółki celowe</a>
        <a class="nav-link {{ Request::routeIs('admin.developro.investment-sale-point.index') ? ' active' : '' }}" href="{{ route('admin.developro.investment-sale-point.index') }}"><span class="fe-shopping-bag"></span> Punkty sprzedaży</a>
        <a class="nav-link {{ Request::routeIs('admin.developro.property-price-components.index') ? ' active' : '' }}" href="{{ route('admin.developro.property-price-components.index') }}"><span class="fe-git-merge"></span> Składniki cen mieszkań</a>
    </nav>
</div>
