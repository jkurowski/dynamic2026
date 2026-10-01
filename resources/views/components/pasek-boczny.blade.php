{{-- Pionowy pasek przy prawej krawędzi hero: Facebook, Instagram, przełącznik motywu, ulubione. Identyczny na wszystkich 10 stronach szablonu. --}}
<div class="pasek-boczny">
	<a href="https://www.facebook.com/DynamicDevelopmentPolska/" class="ikona-okragla" target="_blank" rel="noopener" aria-label="Facebook">
		<svg viewBox="0 0 35 35" aria-hidden="true"><path d="M20.4168 19.6878H24.0627L25.521 13.8545H20.4168V10.9378C20.4168 9.43574 20.4168 8.02116 23.3335 8.02116H25.521V3.12116C25.0456 3.05845 23.2504 2.91699 21.3545 2.91699C17.3952 2.91699 14.5835 5.33345 14.5835 9.77116V13.8545H10.2085V19.6878H14.5835V32.0837H20.4168V19.6878Z" fill="white"/></svg>
	</a>

	<a href="https://www.instagram.com/dynamicdevelopmentpolska/" class="ikona-okragla" target="_blank" rel="noopener" aria-label="Instagram">
		<svg viewBox="0 0 32 32" aria-hidden="true" fill="none">
			<path d="M22.4 1.59997C26.8159 1.59997 30.3999 5.18395 30.3999 9.59992V22.3998C30.3999 26.8158 26.8159 30.3998 22.4 30.3998H9.60004C5.18408 30.3998 1.6001 26.8158 1.6001 22.3998V9.59992C1.6001 5.18395 5.18408 1.59997 9.60004 1.59997H16H22.4Z" stroke="white" stroke-width="3.11111" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M15.9996 9.60048C19.5355 9.60048 22.3995 12.4645 22.3995 16.0004C22.3995 19.5364 19.5355 22.4004 15.9996 22.4004C12.4636 22.4004 9.5996 19.5364 9.5996 16.0004C9.5996 12.4645 12.4636 9.60048 15.9996 9.60048Z" stroke="white" stroke-width="3.11111" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M23.9998 10.3998C25.3253 10.3998 26.3998 9.32524 26.3998 7.99977C26.3998 6.6743 25.3253 5.59979 23.9998 5.59979C22.6744 5.59979 21.5998 6.6743 21.5998 7.99977C21.5998 9.32524 22.6744 10.3998 23.9998 10.3998Z" fill="white"/>
		</svg>
	</a>

	<!-- Przełącznik motywu. Wygląd 1:1 z projektu, jasna paleta wprost z makiety
	     "STRONA GŁÓWNA - JASNA WERSJA", wybór zapamiętany w localStorage. -->
	<div class="przelacznik-motywu">
		<button type="button" class="ikona-okragla" id="przelacznikMotywu" aria-label="Zmień tryb na jasny">
			<picture>
				<source srcset="{{ asset('img/ikona-dzien-noc.webp') }}" type="image/webp">
				<img src="{{ asset('img/ikona-dzien-noc.png') }}" width="49" height="49" alt="">
			</picture>
		</button>
		<span class="dymek">Tryb nocny/dzienny</span>
	</div>

	<a href="{{ route('menu.show', ['uri' => 'wyszukiwarka']) }}" class="ikona-okragla" aria-label="Ulubione lokale">
		<svg viewBox="0 0 35 35" aria-hidden="true"><path d="M17.6457 27.0521L17.4998 27.1979L17.3394 27.0521C10.4123 20.7667 5.83317 16.6104 5.83317 12.3958C5.83317 9.47917 8.02067 7.29167 10.9373 7.29167C13.1832 7.29167 15.3707 8.75 16.1436 10.7333H18.8561C19.629 8.75 21.8165 7.29167 24.0623 7.29167C26.979 7.29167 29.1665 9.47917 29.1665 12.3958C29.1665 16.6104 24.5873 20.7667 17.6457 27.0521ZM24.0623 4.375C21.5248 4.375 19.0894 5.55625 17.4998 7.40833C15.9103 5.55625 13.4748 4.375 10.9373 4.375C6.44567 4.375 2.9165 7.88958 2.9165 12.3958C2.9165 17.8938 7.87484 22.4 15.3853 29.2104L17.4998 31.1354L19.6144 29.2104C27.1248 22.4 32.0832 17.8938 32.0832 12.3958C32.0832 7.88958 28.554 4.375 24.0623 4.375Z" fill="white"/></svg>
		<span class="licznik-ulubionych">0</span>
	</a>
</div>
