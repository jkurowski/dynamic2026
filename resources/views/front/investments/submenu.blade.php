@php
    $menuIds = is_string($menuIds) ? json_decode($menuIds, true) : $menuIds;
    $activeMenuId = $activeMenuId ?? null; // Ensure $activeMenuId is set
    if (request()->routeIs('developro.show')) {
        $links = collect([
            ['id' => '1', 'class' =>'scroll', 'title' => 'Opis inwestycji', 'href' => '#mainContent'],
            ['id' => '8', 'class' =>'', 'title' => 'Galeria', 'href' => route('developro.investment.gallery', $investment->slug).'#mainContent'],
            ['id' => '2', 'class' =>'', 'title' => ($investment->status == 3) ? 'Poznaj rzuty' : 'Wyszukaj z rzutu', 'href' => route('developro.plan', $investment->slug).'?status=1#mainContent'],
            ['id' => '3', 'class' =>'', 'title' => ($investment->status == 3) ? 'Zobacz model 3D' : 'Wyszukaj z modelu 3D', 'href' => route('developro.mockup', $investment->slug).'#mainContent'],
            ['id' => '4', 'class' =>'scroll', 'title' => 'Lokalizacja', 'href' => '#lokalizacja'],
            ['id' => '5', 'class' =>'scroll', 'title' => 'Atuty', 'href' => '#atuty'],
            ['id' => '6', 'class' =>'', 'title' => 'Dziennik inwestycji', 'href' => route('developro.investment.news', $investment->slug).'#mainContent'],
            ['id' => '7', 'class' =>'scroll', 'title' => 'Kontakt', 'href' => '#kontakt'],
        ])
        ->whereIn('id', $menuIds)
        ->map(function ($link) use ($activeMenuId) {
            $link['active'] = $link['id'] == $activeMenuId; // Set active by ID
            return $link;
        });
    } else {
            $links = collect([
            ['id' => '1', 'class' =>'scroll', 'title' => 'Opis inwestycji', 'href' => route('developro.show', $investment->slug).'#mainContent'],
            ['id' => '8', 'class' =>'', 'title' => 'Galeria', 'href' => route('developro.investment.gallery', $investment->slug).'#mainContent'],
            ['id' => '2', 'class' =>'', 'title' => ($investment->status == 3) ? 'Poznaj rzuty' : 'Wyszukaj z rzutu', 'href' => route('developro.plan', $investment->slug).'?status=1#mainContent'],
            ['id' => '3', 'class' =>'', 'title' => ($investment->status == 3) ? 'Zobacz model 3D' : 'Wyszukaj z modelu 3D', 'href' => route('developro.mockup', $investment->slug).'#mainContent'],
            ['id' => '4', 'class' =>'scroll', 'title' => 'Lokalizacja', 'href' => route('developro.show', $investment->slug).'#lokalizacja'],
            ['id' => '5', 'class' =>'scroll', 'title' => 'Atuty', 'href' => route('developro.show', $investment->slug).'#atuty'],
            ['id' => '6', 'class' =>'', 'title' => 'Dziennik inwestycji', 'href' => route('developro.investment.news', $investment->slug).'#mainContent'],
            ['id' => '7', 'class' =>'scroll', 'title' => 'Kontakt', 'href' => route('developro.show', $investment->slug).'#kontakt'],
        ])
        ->whereIn('id', $menuIds)
        ->map(function ($link) use ($activeMenuId) {
            $link['active'] = $link['id'] == $activeMenuId; // Set active by ID
            return $link;
        });
    }
@endphp

<div id="submenu">
    <nav class="fixed-top-menu bg-white" id="navbar-secondary">
        <ul class="navbar-nav with-underline-active nav-snap-md-down flex-row justify-content-center py-3"
            style="--bs-nav-link-color: var(--bs-secondary); --bs-nav-link-hover-color: var(--bs-primary); --bs-navbar-active-color: var(--bs-secondary);">
            @foreach ($links as $link)
                <li class="nav-item">
                    <a class="nav-link {{ $link['active'] ? 'active' : '' }} {{ $link['class'] }}" href="{{ $link['href'] }}">{{ $link['title'] }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
</div>
