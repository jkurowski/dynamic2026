{{-- Sekcja kontaktu z formularzem - wspólna dla 6 podstron szablonu (index, finansowanie, inwestycja, lokal, poznaj-nas, wykończenie). --}}
<section class="kontakt sekcja-karta" id="kontakt">

	<div class="kontakt-lewa pojawia-sie">
		<x-etykieta>KONTAKT</x-etykieta>
		<h2 class="tytul-sekcji rozjasnia-sie">Masz pytania?<br><span class="akcent">Skontaktuj się z Biurem Sprzedaży</span></h2>
		<p class="kontakt-wstep">Masz pytania dotyczące mieszkań lub inwestycji?<br>Skontaktuj się z nami - chętnie doradzimy i pomożemy znaleźć najlepsze rozwiązanie</p>

		<div class="zakladki-biur">
			<button type="button" class="aktywna" data-biuro="warszawa">WARSZAWA</button>
			<button type="button" data-biuro="nowa-wola">NOWA WOLA</button>
		</div>

		<div class="dane-biura widoczne" data-biuro="warszawa">
			<ul class="lista-danych">
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-adres.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc">
						<strong>Biuro w Warszawie</strong><br>
						ul. Bobrowiecka 1B/U3<br>
						00-728 Warszawa<br>
						<a href="https://www.google.com/maps/dir/?api=1&amp;destination=ul.%20Bobrowiecka%201B%2FU3%2C%2000-728%20Warszawa" target="_blank" rel="noopener"><strong>Wyznacz trasę</strong></a>
					</span>
					<span class="ramka-kodu"><picture><source type="image/webp" srcset="{{ asset('img/kod-qr.webp') }}"><img class="kod-qr" src="{{ asset('img/kod-qr.png') }}" width="131" height="130" alt="Kod QR z trasą do biura"></picture></span>
				</li>
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-zegar.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc">
						<strong>Godziny otwarcia:</strong><br>
						poniedziałek-piątek 9:00-17:00<br>
						sobota - po wcześniejszej rezerwacji<br>
						Niedziela - nieczynne
					</span>
				</li>
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-sluchawka.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc mocny">
						Telefon<br>
						<a href="tel:+48576786666">+48 576 786 666</a>
					</span>
					<span class="znak-kontaktu" aria-hidden="true"></span>
				</li>
			</ul>
		</div>

		<div class="dane-biura" data-biuro="nowa-wola">
			<ul class="lista-danych">
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-adres.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc">
						<strong>Biuro w Nowej Woli</strong><br>
						ul. Maciejki 8/2<br>
						05-515 Nowa Wola<br>
						<a href="https://www.google.com/maps/dir/?api=1&amp;destination=ul.%20Maciejki%208%2F2%2C%2005-515%20Nowa%20Wola" target="_blank" rel="noopener"><strong>Wyznacz trasę</strong></a>
					</span>
					<span class="ramka-kodu"><picture><source type="image/webp" srcset="{{ asset('img/kod-qr.webp') }}"><img class="kod-qr" src="{{ asset('img/kod-qr.png') }}" width="131" height="130" alt="Kod QR z trasą do biura"></picture></span>
				</li>
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-zegar.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc">
						<strong>Godziny otwarcia:</strong><br>
						poniedziałek-piątek 9:00-17:00
					</span>
				</li>
				<li>
					<span class="ikona-ramka"><img src="{{ asset('img/ikona-sluchawka.svg') }}" width="69" height="69" alt=""></span>
					<span class="tresc mocny">
						Telefon<br>
						<a href="tel:+48512379056">+48 512 379 056</a>
					</span>
					<span class="znak-kontaktu" aria-hidden="true"></span>
				</li>
			</ul>
		</div>
	</div>

	<div class="karta-formularza pojawia-sie opoznienie-1">
		<h3>Wyślij wiadomość</h3>

		<form id="formularzKontaktowy" action="{{ route('contact.send') }}" method="post" novalidate>
			@csrf
			<div class="pole">
				<input type="text" name="imie" placeholder="Imię i nazwisko*" required>
				<img src="{{ asset('img/ikona-uzytkownik.svg') }}" width="31" height="31" alt="">
				<span class="blad">Podaj imię i nazwisko.</span>
			</div>
			<div class="pole">
				<input type="tel" name="telefon" placeholder="Telefon*" required>
				<img src="{{ asset('img/ikona-telefon-form.svg') }}" width="31" height="31" alt="">
				<span class="blad">Podaj numer telefonu (min. 9 cyfr).</span>
			</div>
			<div class="pole">
				<input type="email" name="email" placeholder="E-mail">
				<img src="{{ asset('img/ikona-koperta.svg') }}" width="31" height="31" alt="">
				<span class="blad">Podaj poprawny adres e-mail.</span>
			</div>
			<div class="pole">
				<textarea name="wiadomosc" placeholder="Wiadomość*" required></textarea>
				<img src="{{ asset('img/ikona-olowek.svg') }}" width="31" height="31" alt="">
				<span class="blad">Napisz wiadomość.</span>
			</div>

			<label class="zgoda">
				<input type="checkbox" name="zgoda-rodo" required>
				<span>Zapoznałem się z <a href="#" data-strona="polityka-prywatnosci">Polityką prywatności</a> i zawartą w niej Informacją na temat przetwarzania danych osobowych</span>
			</label>

			<label class="zgoda">
				<input type="checkbox" name="zgoda-marketing">
				<span>Wyrażam zgodę na otrzymywanie od Dynamic Development sp. z o.o. informacji marketingowych, przekazywanych za pomocą telekomunikacyjnych urządzeń końcowych oraz tzw. automatycznych systemów wywołujących drogą elektroniczną na podany powyżej adres e-mail</span>
			</label>

			<button type="submit" class="formularz-przycisk">
				Wyślij wiadomość
				<x-ikona.strzalka />
			</button>

			<p class="komunikat-formularza" role="status"></p>
		</form>
	</div>

</section>
