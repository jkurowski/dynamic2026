<!-- ============ STOPKA ============ -->
<footer class="stopka">
	<div class="stopka-uklad">

		<div class="stopka-marka">
			<img class="logo-stopki" src="{{ asset('img/logo-stopka.svg') }}" width="286" height="78" alt="Dynamic Development">
			<p class="haslo">Kreujemy nowoczesną rzeczywistość</p>
			<div class="stopka-social">
				<a href="https://www.instagram.com/dynamicdevelopmentpolska/" target="_blank" rel="noopener" aria-label="Instagram">
					<img src="{{ asset('img/ikona-instagram-stopka.svg') }}" width="28" height="28" alt="">
				</a>
				<a href="https://www.facebook.com/DynamicDevelopmentPolska/" target="_blank" rel="noopener" aria-label="Facebook">
					<img src="{{ asset('img/ikona-facebook.svg') }}" width="28" height="28" alt="">
				</a>
			</div>
		</div>

		<nav class="stopka-kolumna" aria-labelledby="stopka-menu">
			<h2 id="stopka-menu"><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> MENU</h2>
			<ul>
				<li><a @class(['aktywny' => request()->routeIs('index')]) href="{{ route('index') }}">Strona główna</a></li>
				<li><a href="{{ url('inwestycje') }}">Inwestycje</a></li>
				<li><a href="{{ url('wyszukiwarka') }}">Wybierz mieszkanie</a></li>
				<li><a href="{{ url('poznaj-nas') }}">Poznaj nas</a></li>
				<li><a href="{{ route('aktualnosci.index') }}">Aktualności</a></li>
				<li><a href="{{ route('contact') }}">Kontakt</a></li>
			</ul>
		</nav>

		<nav class="stopka-kolumna" aria-labelledby="stopka-inwestycje">
			<h2 id="stopka-inwestycje"><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> INWESTYCJE</h2>
			<ul>
				<li><a href="{{ url('inwestycje') }}">Dom Hygge Twin</a></li>
				<li><a href="{{ url('inwestycje') }}">Konstancin Riverside House</a></li>
				<li><a href="{{ url('inwestycje') }}">Lake Village</a></li>
				<li><a href="{{ url('inwestycje') }}">Zielona Polana</a></li>
			</ul>
		</nav>

		<div class="stopka-kolumna" aria-labelledby="stopka-kontakt">
			<h2 id="stopka-kontakt"><img src="{{ asset('img/ikona-pinezka.svg') }}" width="12" height="19" alt=""> KONTAKT</h2>
			<p>
				Dynamic Development sp. z o.o.<br>
				ul. Plonowa 24, <span class="kod-pocztowy">05-515</span> Nowa Wola<br>
				KRS: 0000257514<br>
				NIP: 5213389378<br>
				REGON: 140557694
			</p>
		</div>

	</div>

	<p class="pasek-praw">Copyright © {{ date('Y') }} Dynamic Development All Rights Reserved</p>
</footer>
