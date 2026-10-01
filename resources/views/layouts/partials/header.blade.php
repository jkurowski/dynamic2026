@php
    // Menu główne. Aktywna pozycja wyliczana z adresu (w makiecie była na sztywno przy "Inwestycjach").
    $menu = [
        ['nazwa' => 'Inwestycje', 'link' => url('inwestycje'), 'wzorzec' => 'inwestycje*', 'daszek' => true],
        ['nazwa' => 'Finansowanie', 'link' => url('finansowanie'), 'wzorzec' => 'finansowanie*'],
        ['nazwa' => 'Wykończenie pod klucz', 'link' => url('wykonczenie-pod-klucz'), 'wzorzec' => 'wykonczenie-pod-klucz*'],
        ['nazwa' => 'Poznaj nas', 'link' => url('poznaj-nas'), 'wzorzec' => 'poznaj-nas*'],
        ['nazwa' => 'Kontakt', 'link' => route('contact'), 'wzorzec' => 'kontakt*'],
    ];
    $telefon = '+48 576 786 666';
@endphp
<a href="#tresc" class="tylko-czytnik">Przejdź do treści</a>

<!-- ============ NAGLOWEK ============ -->
<header class="naglowek">
	<div class="kontener-naglowka">

		<a href="{{ route('index') }}" class="logo" aria-label="Dynamic Development - strona główna">
			<picture>
				<source srcset="{{ asset('img/logo.webp') }}" type="image/webp">
				<img src="{{ asset('img/logo.png') }}" width="275" height="93" alt="Dynamic Development">
			</picture>
		</a>

		<nav class="menu-glowne" aria-label="Menu główne">
			<ul>
				@foreach($menu as $pozycja)
					<li @class(['aktywna' => request()->is($pozycja['wzorzec'], '*/' . $pozycja['wzorzec'])])>
						<a href="{{ $pozycja['link'] }}">
							{{ $pozycja['nazwa'] }}
							@if(!empty($pozycja['daszek']))<x-ikona.daszek />@endif
						</a>
					</li>
				@endforeach
			</ul>
		</nav>

		<a href="tel:{{ str_replace(' ', '', $telefon) }}" class="telefon-naglowek d-none d-md-inline-flex">
			<x-ikona.telefon />
			{{ $telefon }}
		</a>

		<button class="hamburger" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuMobilne" aria-label="Otwórz menu">
			<span></span><span></span><span></span>
		</button>

	</div>
</header>

<!-- menu na telefony - offcanvas Bootstrapa, w Figmie tego nie ma -->
<div class="offcanvas offcanvas-end menu-mobilne" tabindex="-1" id="menuMobilne">
	<div class="offcanvas-header">
		<span class="fw-bold">Menu</span>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Zamknij"></button>
	</div>
	<div class="offcanvas-body">
		<ul>
			@foreach($menu as $pozycja)
				<li><a href="{{ $pozycja['link'] }}">{{ $pozycja['nazwa'] }}</a></li>
			@endforeach
		</ul>
		<a href="tel:{{ str_replace(' ', '', $telefon) }}" class="telefon-mobilny">
			<x-ikona.telefon />
			{{ $telefon }}
		</a>
	</div>
</div>
