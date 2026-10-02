{{-- Sekcja kontaktu z formularzem - wspólna dla 6 podstron szablonu (index, finansowanie, inwestycja, lokal, poznaj-nas, wykończenie).
     Propsy przekazywane do <x-formularz-kontaktowy>. back=true domyślnie: sekcja stoi na innej stronie,
     więc po wysyłce wracamy na nią ("Wyślij i wróć"), zamiast przenosić na /kontakt. --}}
@props(['strona' => 'Kontakt', 'investmentId' => null, 'propertyId' => null, 'back' => true])
<section class="kontakt sekcja-karta" id="kontakt">

	<div class="kontakt-lewa pojawia-sie">
		<x-etykieta>KONTAKT</x-etykieta>
		<h2 class="tytul-sekcji rozjasnia-sie">Masz pytania?<br><span class="akcent">Skontaktuj się z Biurem Sprzedaży</span></h2>
		<p class="kontakt-wstep">Masz pytania dotyczące mieszkań lub inwestycji?<br>Skontaktuj się z nami - chętnie doradzimy i pomożemy znaleźć najlepsze rozwiązanie</p>

		{{-- Biura z panelu (Miasta): zakładki + dane; pierwsze aktywne biuro widoczne na start --}}
		@php $biura = \App\Models\City::biura(); @endphp
		<div class="zakladki-biur">
			@foreach($biura as $biuro)
				<button type="button" @class(['aktywna' => $loop->first]) data-biuro="{{ $biuro->slug }}">{{ mb_strtoupper($biuro->name) }}</button>
			@endforeach
		</div>

		@foreach($biura as $biuro)
		<div @class(['dane-biura', 'widoczne' => $loop->first]) data-biuro="{{ $biuro->slug }}">
			<ul class="lista-danych">
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-adres.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc">
						<strong>Biuro {{ $biuro->name }}</strong><br>
						{!! $biuro->address !!}
						@if($biuro->map_link)<br>
						<a href="{{ $biuro->map_link }}" target="_blank" rel="noopener"><strong>Wyznacz trasę</strong></a>@endif
					</span>
					<span class="ramka-kodu"><picture><source type="image/webp" srcset="{{ $biuro->qr(true) }}"><img class="kod-qr" src="{{ $biuro->qr() }}" width="131" height="130" alt="Kod QR z trasą do biura"></picture></span>
				</li>
				@if($biuro->working_hours)
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-zegar.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc">
						<strong>Godziny otwarcia:</strong><br>
						{!! $biuro->working_hours !!}
					</span>
				</li>
				@endif
				@if($biuro->phone)
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-sluchawka.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc mocny">
						Telefon<br>
						{!! $biuro->telefonZLinkami() !!}
					</span>
					<span class="znak-kontaktu" aria-hidden="true"></span>
				</li>
				@endif
			</ul>
		</div>
		@endforeach
	</div>

	<x-formularz-kontaktowy :strona="$strona" :investment-id="$investmentId" :property-id="$propertyId" :back="$back" />

</section>
