# dynamic-cms — notatki projektu

## Repozytorium
- `origin` = https://github.com/jkurowski/dynamic2026.git (od 2026-10-01). Historia zaczyna się od nowa — pierwszy commit `Start projektu dynamic-cms`. Historia Kaltera (kalter2024) tylko lokalnie na gałęzi `kalter-history`, nie wypychać jej do origin.
- `public/remove/` jest w .gitignore (pliki do ręcznego usunięcia).

## Stos
- Laravel (PHP), widoki Blade w `resources/views`. Laravel Mix buduje tylko `resources/js/app.js` → `public/js/app.js` (czat, Echo/Pusher).
- Pliki `.less` w `public/css` kompilowane są poza Mixem (watcher w IDE) do par `.css` / `.min.css` (+ `.map`). Skrypty CMS też mają pary `x.js` / `x.min.js`.
- Panel: `resources/views/admin/layout.blade.php` (Bootstrap 5.3.8 lokalnie, jQuery, jQuery UI, `cms.min.js`, `admin.min.css`).
- Panel klienta: `resources/views/front/auth/client/layouts/layout.blade.php` (te same zasoby co panel + `client.min.css`).
- Logowanie: `resources/views/layouts/auth/layout.blade.php` (`auth.css`, `cms/logo-biale.png`, `cms/login-bg.png`).
- Stary front: `layouts/page.blade.php`, `layouts/homepage.blade.php`, `layouts/partials/*` (header, footer, cta, clipboard), `resources/views/front/*`, `resources/views/components/property-*`.

## Nowy szablon frontu: `C:\laragon\www\dynamic-front`
- Statyczny HTML (10 podstron) z Figmy. Opis i zasady: `dynamic-front/README.md`.
- Bootstrap 5.3.3 + jQuery 3.7.1 z CDN (CMS ma lokalnie Bootstrap 5.3.8 w `public/css/bootstrap.min.css`).
- Zasoby szablonu (nazwy nie kolidują z plikami CMS):
  - `css/style.css`, `css/style.min.css` (kompilowane z `less/style.less`, 31 plików LESS)
  - `js/`: glowny, animacje, formularz, kalkulator, karuzele, mapa, slider, aktualnosci, aktualnosci-domowa
  - `img/`: ~222 pliki (WebP + JPG/PNG fallback, SVG, favicony)
  - `fonty/`: Inter (woff2) — uwaga: katalog `fonty`, a nie `fonts` jak w CMS
  - `czesci/`: glowa, naglowek, stopka, kontakt → do zamiany na partiale Blade
- Strona główna wymaga klasy `strona-glowna` na `body`. Linki wewnętrzne (`*.html`) trzeba podmienić na trasy.

## Porządki przed instalacją szablonu (zadanie 1)
- `public/img/` — wyczyszczone w całości (stary front). Panel CMS nie korzysta z `public/img`.
- Do usunięcia (stary front albo pliki bez żadnych odwołań w kodzie) — 42 pliki PRZENIESIONE do `public/remove/` (z zachowaniem struktury katalogów), do ręcznego usunięcia:
  - css: `aos.*`, `glightbox.min.css`, `history.min.min.css` (duplikat), `slick.*`, `styles.*`
  - css/less-partials: `developro.less`, `footer.less`, `header.*`, `popup.*`, `slick.less`, `slider.*`
  - fonts: `fontawesome-webfont.woff2` (brak odwołań)
  - js: `aos.*`, `main.*`, `slick.*`, `glightbox.min.js`, `circle.js`, `custom.*`, `echo.*`, `notifications.*`, `sortable.*`, `jsonUsers.json` (używany tylko w nieładowanym `routes/admin_old.php`)
- Zostają (używa ich CMS):
  - css: `admin.*`, `auth.*`, `bootstrap.min.css(.map)`, `client.*`, `datatables.*`, `history.*` (historia ceny — WAŻNE, zostaje), `jquery-ui.min.css`, `leaflet.*`, `images/` (lightbox w admin.css + markery leaflet), `less-partials/inline.*` (edycja inline na froncie), `less-partials/modal-table.*`
  - js: `app.*`, `board.*`, `bootstrap.bundle.min.js(.map)`, `calendar.*`, `client-calendar.*`, `cms.*`, `datatables.*`, `fineuploader.*`, `funnel-graph.*`, `inputmask.min.js`, `jquery.min.js`, `jquery-ui.min.js`, `leaflet.*`, `moment.*`, `pl.js`, `polish.json`, `template-generator.*`, `typeahead.*`, `validation.*`, katalogi `editor/` (TinyMCE), `fullcalendar/`, `datepicker/`, `bootstrap-select/`, `ui/`, `plan/` (imagemapster — plany w panelu i na froncie)
  - fonts: feather, line-awesome (`la-*`), fontawesome (eot/svg/ttf/woff — dla inline.css)
  - `public/cms/` w całości

## Decyzje (2026-10-01)
- `admin/web-generator` i `layouts/iframe` (ładują stary `styles.min.css`) — nieważne, nie poprawiamy.
- `css/history.*` zostaje — używa go `front/developro/investment_property` (historia ceny).
- Starego frontu (`resources/views/front`, `layouts/page|homepage`, `layouts/partials`) na razie nie ruszamy.
- Naprawione: `layouts/app.blade.php` wskazywał nieistniejące `/css/bootstrap.css` i `/js/jquery.js` → teraz `asset()` do `bootstrap.min.css`, `admin.min.css`, `jquery.min.js`.
- Usunięte: `public/laragon.pem`, `public/laragon-łancuch.pem` (certyfikaty w katalogu publicznym, nigdzie nieużywane).
- Style popupu i slidera były tylko w starym `styles.less` — przy nowym froncie trzeba je odtworzyć, jeśli funkcje mają zostać.

## Wersje bibliotek w public (stan 2026-10-01, najnowsza wg npm)
Zasada: aktualizujemy tylko Bootstrap, jQuery, Inputmask, Moment, datepicker. Reszty (jQuery UI, DataTables, FullCalendar, TinyMCE, Underscore, typeahead itd.) NIE ruszamy bez decyzji.

| Biblioteka | Plik | Jest | Najnowsza | Uwagi |
|---|---|---|---|---|
| Bootstrap | css/bootstrap.min.css, js/bootstrap.bundle.min.js (+ .map) | **5.3.8** | 5.3.8 | zaktualizowane 2026-10-01; szablon frontu ma w CDN 5.3.3 |
| jQuery | js/jquery.min.js | 3.7.1 | 4.0.0 | ZOSTAJE 3.7.1 (najnowsza 3.x). jQuery 4 usuwa $.trim/$.isFunction/$.isArray/$.parseJSON — używają ich validation.js, typeahead, imagemapster, jQuery UI 1.12. Przejście na 4.x dopiero po aktualizacji tych wtyczek |
| jQuery UI | js/jquery-ui.min.js, css/jquery-ui.min.css | 1.12.1 | 1.14.2 | stara; w js/ui/ jest druga kopia 1.13.2 |
| Leaflet | js/leaflet.*, css/leaflet.* | 1.9.4 | 1.9.4 | aktualna |
| Moment | js/moment.js, js/moment.min.js | **2.31.0** | 2.31.0 | zaktualizowane; oficjalne pliki bez lokalizacji (jak wcześniej) |
| DataTables | js/datatables.* (bundle) | 1.12.1 | 3.x | bundle z Buttons 2.2.3, pdfmake 0.1.36, JSZip 2.5.0 |
| Inputmask | js/inputmask.min.js | **5.0.10** | 5.0.10 | zaktualizowane (dist/inputmask.min.js, wersja bez jQuery, `Inputmask(...)`) |
| typeahead.js | js/typeahead.* | 0.11.1 | 0.11.1 | projekt martwy od 2015 |
| TinyMCE | js/editor/ | 5.6.2 | 8.x | duży skok, EOL 5.x; własne pluginy filemanager |
| FullCalendar | js/fullcalendar/ | 5.11.2 | 7.x | |
| bootstrap-select | js/bootstrap-select/ | 1.14.0 | 1.13.18 (npm stable) | 1.14 to beta, zgodna z BS5 |
| bootstrap-datepicker | js/datepicker/ | **1.10.1** | 1.10.1 | zaktualizowane js, locale pl, css; pliki `*.min.min.*` to stare artefakty watchera 1.9.0, nieużywane |
| Underscore | js/plan/underscore.js | 1.5.1 | 1.13.8 | bardzo stara |
| Fine Uploader, imagemapster, funnel-graph, validationEngine | — | brak wersji w pliku | — | |
| Slick, AOS, GLightbox, Sortable | — | — | — | do usunięcia (stary front / nieużywane) |
