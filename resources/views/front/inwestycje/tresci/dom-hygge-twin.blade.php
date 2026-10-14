{{-- Treści podstrony Dom Hygge Twin - na demo statycznie, 1:1 z dynamic-front/inwestycja.html
     (opis, Mokotów, lokalizacja z punktami na mapie, atuty, galeria). Formularze w panelu inwestycji dojdą po demo.
     Dołączane przez front/inwestycje/show, gdy istnieje plik o nazwie = slug inwestycji. --}}
	<!-- ============ OPIS INWESTYCJI ============ -->
	<section class="opis-inwestycji sekcja-karta" id="opis">
		<div class="opis-zdjecia pojawia-sie">
			<span class="ramka-opisu" aria-hidden="true"></span>
			<picture class="duze">
				<source type="image/webp" srcset="{{ asset('img/inw-opis-1.webp') }}">
				<img src="{{ asset('img/inw-opis-1.jpg') }}" width="540" height="561" alt="Wizualizacja inwestycji Dom Hygge Twin" loading="lazy">
			</picture>
			<picture class="stempel-hygge">
				<source type="image/webp" srcset="{{ asset('img/stempel-hygge.webp') }}">
				<img src="{{ asset('img/stempel-hygge.png') }}" width="91" height="161" alt="Dom Hygge Twin">
			</picture>
			<picture class="male">
				<source type="image/webp" srcset="{{ asset('img/inw-opis-2.webp') }}">
				<img src="{{ asset('img/inw-opis-2.jpg') }}" width="355" height="458" alt="Wnętrze apartamentu" loading="lazy">
			</picture>
		</div>

		<div class="opis-tresc pojawia-sie opoznienie-1">
			<x-etykieta>OPIS INWESTYCJI</x-etykieta>
			<h2 class="tytul-sekcji rozjasnia-sie">Celebruj życie zgodnie<br>z filozofią<span class="akcent"> Hygge</span></h2>
			<p>Zamieszkaj na zielonym Mokotowie, najbardziej prestiżowej dzielnicy Warszawy. Korzystaj z wysoce rozwiniętej infrastruktury. Ciesz się bogactwem przyjaznych przestrzeni, nietuzinkową atmosferą, wyjątkową architekturą, bliskością Łazienek Królewskich i sąsiedztwem z centrum stolicy.</p>
			<p>Nie bez powodu Mokotów plasuje się na podium, jeśli pod uwagę weźmiemy najbardziej preferowaną do zamieszkania część Warszawy.</p>
		</div>
	</section>

	<!-- ============ MOKOTÓW ============ -->
	<section class="nagrody mokotow-inwestycji">
		<span class="ramka-mokotowa" aria-hidden="true"></span>
		<div class="nagrody-zdjecie pojawia-sie z-prawej">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/inw-mokotow.webp') }}">
				<img src="{{ asset('img/inw-mokotow.jpg') }}" width="1032" height="750" alt="Mokotów - zielona dzielnica Warszawy" loading="lazy">
			</picture>
		</div>

		<div class="tresc">
			<div class="kolumna pojawia-sie">
				<x-etykieta>MOKOTÓW</x-etykieta>
				<h2 class="tytul-sekcji jasny rozjasnia-sie">Prestiż dzielnicy,<br><span class="akcent">blisko natury i miasta</span></h2>
				<p>Pragniesz domu, w którym mógłbyś naprawdę się odprężyć? Znajdziesz go w centrum stolicy, na Mokotowie – w dogodnej lokalizacji, w miejscu, z którego łatwo przemieścić się do Śródmieścia, na Ursynów, Wilanów i na Pragę Południe. Nieopodal znajdują się urokliwe Łazienki Królewskie z magicznymi ogrodami, Park Sielecki a wokół mnóstwo rowerowych ścieżek i stacji Veturilo.</p>
				<ul class="punkty-mokotowa">
					<li><img src="{{ asset('img/inw-ikona-lazienki.svg') }}" width="67" height="67" alt=""><span>Łazienki<br>Królewskie</span></li>
					<li><img src="{{ asset('img/inw-ikona-park.svg') }}" width="75" height="69" alt=""><span>Park<br>Sielecki</span></li>
					<li><img src="{{ asset('img/inw-ikona-auto.svg') }}" width="72" height="72" alt=""><span>Szybki dojazd<br>do centrum</span></li>
				</ul>
			</div>
		</div>
	</section>

	<!-- ============ LOKALIZACJA I ATUTY (wspólny ciemny pas) ============ -->
	<section class="lokalizacja-inwestycji" id="lokalizacja">
		<div class="mapa-gora pojawia-sie">
			<div>
				<x-etykieta>LOKALIZACJA</x-etykieta>
				<h2 class="tytul-sekcji rozjasnia-sie">W sercu Mokotowa,<br><span class="akcent">blisko tego co ważne</span></h2>
			</div>

			<ul class="filtry-mapy-inwestycji">
				<li><label><img src="{{ asset('img/mapa-ikona-szkola.svg') }}" width="28" height="28" alt=""><span>Szkoła</span><input type="checkbox" name="punkt" value="szkola" checked></label></li>
				<li><label><img src="{{ asset('img/mapa-ikona-przedszkole.svg') }}" width="28" height="28" alt=""><span>Przedszkole</span><input type="checkbox" name="punkt" value="przedszkole" checked></label></li>
				<li><label><img src="{{ asset('img/mapa-ikona-sklep.svg') }}" width="28" height="28" alt=""><span>Sklep</span><input type="checkbox" name="punkt" value="sklep" checked></label></li>
				<li><label><img src="{{ asset('img/mapa-ikona-komunikacja.svg') }}" width="28" height="28" alt=""><span>Komunikacja</span><input type="checkbox" name="punkt" value="komunikacja" checked></label></li>
				<li><label><img src="{{ asset('img/mapa-ikona-restauracja.svg') }}" width="28" height="28" alt=""><span>Restauracja</span><input type="checkbox" name="punkt" value="restauracja" checked></label></li>
				<li><label><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt=""><span>Park</span><input type="checkbox" name="punkt" value="park" checked></label></li>
				<li><label><img src="{{ asset('img/mapa-ikona-metro.svg') }}" width="28" height="28" alt=""><span>Metro</span><input type="checkbox" name="punkt" value="metro" checked></label></li>
				<li><label><img src="{{ asset('img/mapa-ikona-sport.svg') }}" width="28" height="28" alt=""><span>Sport</span><input type="checkbox" name="punkt" value="sport" checked></label></li>
			</ul>
		</div>

		<div class="mapa-plansza mapa-inwestycji pojawia-sie opoznienie-1">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/inw-mapa.webp') }}">
				<img src="{{ asset('img/inw-mapa.jpg') }}" width="1920" height="750" alt="Mapa okolicy inwestycji Dom Hygge Twin" loading="lazy">
			</picture>

			<span class="pinezka-inwestycji" data-inwestycja="hygge" style="left:53.305%;top:52.133%" aria-hidden="true">
				<img src="{{ asset('img/pinezka-mapa.svg') }}" width="55" height="69" alt="">
			</span>

			<div class="dymek-mapy" data-dymek="hygge">
				<span class="nazwa">Dom Hygge Twin</span>
			</div>

			<!-- punkty z okolicy - pozycje przepisane z makiety -->
			<span class="punkt-mapy" data-punkt="park" style="left:24.990%;top:11.107%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="przedszkole" style="left:43.740%;top:12.707%"><img src="{{ asset('img/mapa-ikona-przedszkole.svg') }}" width="28" height="28" alt="Przedszkole"></span>
			<span class="punkt-mapy" data-punkt="park" style="left:32.438%;top:14.440%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="park" style="left:64.000%;top:16.440%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="sklep" style="left:37.125%;top:24.707%"><img src="{{ asset('img/mapa-ikona-sklep.svg') }}" width="28" height="28" alt="Sklep"></span>
			<span class="punkt-mapy" data-punkt="przedszkole" style="left:57.854%;top:26.573%"><img src="{{ asset('img/mapa-ikona-przedszkole.svg') }}" width="28" height="28" alt="Przedszkole"></span>
			<span class="punkt-mapy" data-punkt="szkola" style="left:52.281%;top:28.707%"><img src="{{ asset('img/mapa-ikona-szkola.svg') }}" width="28" height="28" alt="Szkoła"></span>
			<span class="punkt-mapy" data-punkt="park" style="left:44.885%;top:37.507%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="park" style="left:23.115%;top:43.907%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="komunikacja" style="left:52.281%;top:46.173%"><img src="{{ asset('img/mapa-ikona-komunikacja.svg') }}" width="28" height="28" alt="Komunikacja"></span>
			<span class="punkt-mapy" data-punkt="komunikacja" style="left:44.417%;top:49.107%"><img src="{{ asset('img/mapa-ikona-komunikacja.svg') }}" width="28" height="28" alt="Komunikacja"></span>
			<span class="punkt-mapy" data-punkt="sport" style="left:29.938%;top:54.573%"><img src="{{ asset('img/mapa-ikona-sport.svg') }}" width="28" height="28" alt="Sport"></span>
			<span class="punkt-mapy" data-punkt="restauracja" style="left:50.875%;top:63.240%"><img src="{{ asset('img/mapa-ikona-restauracja.svg') }}" width="28" height="28" alt="Restauracja"></span>
			<span class="punkt-mapy" data-punkt="sklep" style="left:25.510%;top:74.707%"><img src="{{ asset('img/mapa-ikona-sklep.svg') }}" width="28" height="28" alt="Sklep"></span>
			<span class="punkt-mapy" data-punkt="przedszkole" style="left:55.250%;top:76.573%"><img src="{{ asset('img/mapa-ikona-przedszkole.svg') }}" width="28" height="28" alt="Przedszkole"></span>
			<span class="punkt-mapy" data-punkt="park" style="left:30.458%;top:76.840%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="park" style="left:23.531%;top:86.040%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="park" style="left:62.542%;top:89.640%"><img src="{{ asset('img/mapa-ikona-park.svg') }}" width="28" height="28" alt="Park"></span>
			<span class="punkt-mapy" data-punkt="sklep" style="left:52.542%;top:96.440%"><img src="{{ asset('img/mapa-ikona-sklep.svg') }}" width="28" height="28" alt="Sklep"></span>

			<x-mapa-sterowanie />
		</div>

		<div class="atuty-naglowek pojawia-sie" id="atuty">
			<div>
				<x-etykieta>ATUTY INWESTYCJI</x-etykieta>
				<h2 class="tytul-sekcji jasny rozjasnia-sie">Rozwiązania, które<br><span class="akcent">robią różnicę</span></h2>
			</div>
			<p class="atuty-wstep">Dom Hygge Twin łączy funkcjonalne rozwiązania, wysoki komfort i codzienną wygodę w sercu Mokotowa.</p>
		</div>

		<div class="kontener-atutow">
			<span class="ramka-atutow" aria-hidden="true"></span>
			<ul class="lista-atutow kolejno row list-unstyled">
				<li class="col-12 col-sm-6 col-md-4">
					<div class="kafel-atutu pojawia-sie ">
						<img src="{{ asset('img/atut-uklady.svg') }}" width="85" height="85" alt="">
						<h3>Funkcjonalne układy</h3>
						<p>Apartamenty od 27 do 90,5 m² zaprojektowane z myślą o codziennym komforcie</p>
					</div>
				</li>
				<li class="col-12 col-sm-6 col-md-4">
					<div class="kafel-atutu pojawia-sie opoznienie-1">
						<img src="{{ asset('img/atut-zielen.svg') }}" width="85" height="85" alt="">
						<h3>Zielony Mokotów</h3>
						<p>Blisko parków, zwłaszcza Parku Siedleckiego, tras rowerowych i miejsc sprzyjających odpoczynkowi</p>
					</div>
				</li>
				<li class="col-12 col-sm-6 col-md-4">
					<div class="kafel-atutu pojawia-sie opoznienie-2">
						<img src="{{ asset('img/atut-uslugi.svg') }}" width="85" height="85" alt="">
						<h3>Lokale usługowe</h3>
						<p>Wygoda codziennych spraw dzięki usługom dostępnym na parterze inwestycji</p>
					</div>
				</li>
				<li class="col-12 col-sm-6 col-md-4">
					<div class="kafel-atutu pojawia-sie ">
						<img src="{{ asset('img/atut-przeszklenia.svg') }}" width="85" height="85" alt="">
						<h3>Duże przeszklenia</h3>
						<p>Naturalne światło i jasne wnętrza budują przytulny klimat mieszkań</p>
					</div>
				</li>
				<li class="col-12 col-sm-6 col-md-4">
					<div class="kafel-atutu pojawia-sie opoznienie-1">
						<img src="{{ asset('img/atut-garaz.svg') }}" width="85" height="85" alt="">
						<h3>Garaż podziemny</h3>
						<p>Komfort parkowania i bezpośredni dostęp do budynku dla mieszkańców</p>
					</div>
				</li>
				<li class="col-12 col-sm-6 col-md-4">
					<div class="kafel-atutu pojawia-sie opoznienie-2">
						<img src="{{ asset('img/atut-komunikacja.svg') }}" width="85" height="85" alt="">
						<h3>Doskonała komunikacja</h3>
						<p>Szybki dojazd do centrum i wygodne połączenia z kluczowymi punktami<br class="lamanie-desktop"> w Warszawie</p>
					</div>
				</li>
			</ul>
		</div>
	</section>

	<!-- ============ GALERIA ============ -->
	<section class="galeria-inwestycji sekcja-karta" id="galeria">
		<div class="naglowek-galerii pojawia-sie">
			<div class="galeria-tytuly">
				<x-etykieta>GALERIA</x-etykieta>
				<h2 class="tytul-sekcji jasny rozjasnia-sie">Zobacz <span class="akcent">inwestycję</span></h2>
			</div>
			<!-- Makieta ma trzy zakładki galerii, ale materiał zdjęciowy dostaliśmy
			     tylko do wizualizacji. Pod "Wnętrza" i "Dziennik budowy" podstawione są
			     na razie zdjęcia z innych podstron - do podmiany po przekazaniu zdjęć. -->
			<div class="zakladki-galerii">
				<button type="button" class="wybrana" data-zdjecia="inw-galeria-3,inw-galeria-1,inw-galeria-2">WIZUALIZACJE</button>
				<button type="button" data-zdjecia="wyk-galeria-3,wyk-galeria-1,wyk-galeria-2">WNĘTRZA</button>
				<button type="button" data-zdjecia="akt-dziennik,inwestycja-2,inwestycja-3">DZIENNIK BUDOWY</button>
			</div>
		</div>

		<div class="tasma-realizacji pojawia-sie">
			<picture>
				<source type="image/webp" srcset="{{ asset('img/inw-galeria-3.webp') }}">
				<img src="{{ asset('img/inw-galeria-3.jpg') }}" width="905" height="552" alt="Wizualizacja inwestycji" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/inw-galeria-1.webp') }}">
				<img src="{{ asset('img/inw-galeria-1.jpg') }}" width="994" height="606" alt="Wizualizacja inwestycji" loading="lazy">
			</picture>
			<picture>
				<source type="image/webp" srcset="{{ asset('img/inw-galeria-2.webp') }}">
				<img src="{{ asset('img/inw-galeria-2.jpg') }}" width="905" height="552" alt="Wizualizacja inwestycji" loading="lazy">
			</picture>
		</div>

		<div class="nawigacja-realizacji pojawia-sie opoznienie-2">
			<button type="button" class="realizacje-poprzednia" aria-label="Poprzednie zdjęcie">
				<img src="{{ asset('img/strzalka-poprzednia-duza.svg') }}" width="40" height="40" alt="">
			</button>
			<button type="button" class="realizacje-nastepna" aria-label="Następne zdjęcie">
				<img src="{{ asset('img/strzalka-nastepna-duza.svg') }}" width="39" height="40" alt="">
			</button>
		</div>
	</section>

