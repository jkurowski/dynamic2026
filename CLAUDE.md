# dynamic-cms — notatki projektu

## Repozytorium
- `origin` = https://github.com/jkurowski/dynamic2026.git (od 2026-10-01). Historia zaczyna się od nowa — pierwszy commit `Start projektu dynamic-cms`. Historia Kaltera (kalter2024) tylko lokalnie na gałęzi `kalter-history`, nie wypychać jej do origin.
- `public/remove/` jest w .gitignore (pliki do ręcznego usunięcia).

## Baza danych
- MySQL (Laragon, root bez hasła), baza **`lar_dynamic`** — kopia bazy Kaltera, wyczyszczona z danych i nieużywanych tabel (2026-10-01). 60 tabel.
- Settings (`App\Models\Settings`) to nie tabela, tylko plik `storage/app/settings.json` (Spatie Valuestore).
- Modele, których tabel NIE MA w `lar_dynamic` (moduły wyłączone / do decyzji — wejście w te ekrany panelu da błąd SQL):
  `Board` (boards), `Contact`, `Contract`, `ContractTemplate`, `EmailTemplate`, `EmailTemplateSection`, `Event` (kalendarz CRM),
  `FacebookPage`, `File` (files — ~45 odwołań), `InvestmentTemplates`, `Issue`, `IssueFile`, `Map`, `Note`, `Offer`,
  `Payment`, `PaymentSchedule`, `Recipient`, `Section`, `Template`.

## Środowisko lokalne
- URL: **https**://dynamic-cms.test (Laragon, Apache, PHP 8.2, Laravel 10.48). Vhost `C:/laragon/etc/apache2/sites-enabled/auto.dynamic-cms.test.conf` → ROOT musi wskazywać `.../dynamic-cms/public` (Laragon domyślnie ustawił katalog projektu — wtedy widać listing plików i `.env`).
- Logowanie działa TYLKO po https: `config/session.php` ma `secure=true` i `same_site=none`, po http ciasteczko sesji nie wraca (419 przy logowaniu).
- `.env` lokalny (nie w repo): APP_URL=https://dynamic-cms.test, DB_DATABASE=lar_dynamic, MAIL_MAILER=log.
- `public/.htaccess` jest w .gitignore — lokalnie standardowy plik Laravela.
- Tabela `migrations` w `lar_dynamic` jest pusta (71 migracji „Pending”) — NIE uruchamiać `php artisan migrate`, bo będzie tworzyć istniejące tabele.
- `config/broadcasting.php`: dodane połączenia `log` i `null` (bez nich `package:discover` się wywracał przy BROADCAST_DRIVER=log).

## Stan panelu (test 2026-10-01, wszystkie GET /admin/* bez parametrów jako Administrator)
- Działa (200): strony, galeria, slider, użytkownicy, role, artykuły, boksy, miasta, ustawienia/SEO/social/popup, logi, RODO, external-leads, CRM (klienci, kontakty, zgłoszenia, inbox, statystyki, lejek, kalendarz, oferty), DeveloPro (inwestycje, składniki ceny, firmy, punkty sprzedaży).
- 500 przez brak tabel w `lar_dynamic`: `/admin/file` (files), `/admin/map` (maps), `/admin/contract` (contracts), `/admin/job` (jobofferts), `/admin/settings/facebook` (facebook_pages), `/admin/email/generator` i `/admin/mass-mail` (email_templates), `/admin/crm/jobs` (jobs), `/admin/crm/board` (boards), oraz tabele danych w CRM: kontakty (contacts), zgłoszenia (issues), oferty (offers), kalendarz (events) — sam ekran się otwiera, tabela/kalendarz rzuca błąd.
- 500 bez znaczenia: `*/convert` i `floor/updateids` (konwersja ze starej bazy `old_mysql`), oraz `index`/`create` z `Route::resource` bez tych metod w kontrolerze.
- Zostawione `dd()` w kodzie: `Admin/User/IndexController.php:206` (trasa `admin/user/roles`), `Admin/Crm/Client/IndexController.php:56`, `Facebook/IndexController.php:51`.

## Menu panelu (`admin/layout.blade.php`)
- Usunięte z menu (2026-10-01): Mapa, Oferty pracy, Boksy, Kontakty, Statystyki. Statystyki (`admin/crm/statistics`) — tylko z menu, kod modułu zostaje.
- USUNIĘTE CAŁKOWICIE z kodu (2026-10-01): Mapa (`Map`), Oferty pracy (`Job`, tabela jobofferts), Boksy (`Boxes`), Kontakty CRM (`Contact`), Facebook (`/admin/settings/facebook` + OAuth `auth/facebook/*`, `FacebookPage`, helpery `FbAppInfo`/`FbGetPages`), Pola własne / Słowniki (`/admin/crm/custom-fields`, `CustomField`).
  Razem z kontrolerami, modelami, repozytoriami, requestami, widokami, migracjami (contacts, custom_fields, facebook_pages), zakładkami w ustawieniach/RODO/CRM, grupą ziggy `contact`, wpisem `box` w config/images.php.
  Front: martwe `Front/MapController`, `Front/LocationController` i widoki `front/map`, `front/location` usunięte; z `kariera.blade.php` usunięta sekcja ofert pracy; z homepage usunięte `$boxes`.
  Formularz użytkownika: usunięte pole „Miasto” (lista z custom_fields); kolumna `users.city` w bazie zostaje.
  UWAGA: `admin/crm/jobs` to kolejka zadań Laravela — NIE jest modułem ofert pracy, zostaje.
  Baza: tabele `boxes` i `custom_fields` usunięte (DROP), uprawnienia `box-*` usunięte z `permissions` i `role_has_permissions`, tłumaczenia usunięte. `lar_dynamic` ma teraz 58 tabel.
- Usunięte z górnej belki: Kalendarz, Nowy klient, Nowa oferta + `#modalNewUser` i skrypt modala nowego klienta (`btn-add-user`, `initModal`).
- Menu teraz: CMS (Strony, Aktualności, Slider, Galeria, Użytkownicy, Blokada dostępu, Ustawienia), DeveloCRM (Miasta, Inwestycje, Leads).

## Usunięte widoki starego frontu (2026-10-01, ręcznie)
- 45 widoków: `components/*` (formularze kontaktowe, karty lokali/inwestycji, iframes), `email-templates-json-parser/blocks/*`, `front/email-template-preview`, `front/howtobuy`, `front/offer`, `layouts/iframe`, `layouts/partials/*` (poza header/footer/inline/page-header), `shared/forms/form-note`.
- Panel bez nowych błędów. Do kodu, który nadal wskazuje na usunięte widoki (zadziała dopiero po wpięciu nowego frontu albo do usunięcia):
  front: `Front/HowToBuyController`, `Front/Offer/IndexController`, `Front/EmailTemplatePreviewController`, `front/iframe/*` + `app/View/Components/Iframes/*`, `front/developro/*`, `front/clipboard`, `front/gallery/show`, `front/menupage/kariera`, `layouts/homepage` (cta);
  panel: `app/Helpers/EmailTemplatesJsonParser/Blocks/*` i podgląd w `admin/email/generator/form` (generator i tak bez tabeli email_templates), `admin/crm/issue/show` (shared.forms.form-note; tabela issues nie istnieje);
  `Page::mainmenu()` / `Page::sidemenu()` — nieużywane.

## Usunięte moduły — etap 2 (2026-10-01)
- **Oferty CRM** (`Admin/Crm/Offer`, `Offer`, `OfferRepository`, `OfferService`, `OfferStatus`, `OfferSend`, job `OffersSmsReminder` + wpis w harmonogramie, widok `emails/offer`, panel klienta `Front/Client/Offer`, zakładka „Oferty” w CRM, relacja `Client::offers()`).
- **Zgłoszenia** (`Admin/Crm/Issue`, `Issue`, `IssueFile`, `IssueFileObserver`, `IssueRepository`, `IssueService`, `IssueStatus`, modal `crm/modal/issue`).
- **Generator maili i Mass-mail** (`Admin/Email/GeneratorController`, `Admin/MassMail`, `EmailTemplate(Section)`, observery, repozytorium, `EmailGeneratorService`, cały `app/Helpers/EmailTemplatesJsonParser`, widoki `admin/email`, `admin/mass-mail`, `emails/mass-mail`, `public/js/template-generator*.js`).
- **Szablony maili inwestycji** (`InvestmentTemplates`, widok `investment/templates`, trasy get/update-templates — metod i tak nie było, `template_id` z `$fillable` Investment).
- **Iframe inwestycji** (`Admin/Developro/Iframe`, `Front/IframePageController`, `IframeContactMiddleware`, `IframesRoutesTrait`, `app/View/Components/Iframes/*`, widoki `front/iframe`).
- Front: `HowToBuyController`, `Front/Offer`, `EmailTemplatePreviewController`, `front/menupage/kariera.blade.php`.
- Migracje usuniętych tabel (offers, issues*, issue_files, email_templates*, investment_templates, template_id w investments). W bazie tych tabel i tak nie było.
- `createIframeFromButton()` (app/Helpers/CreateIframeFromButton.php) ZOSTAJE — to osadzanie modelu 3D lokalu, nie moduł iframe.
- Panel po zmianach: 534 trasy, brak nowych błędów.

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
