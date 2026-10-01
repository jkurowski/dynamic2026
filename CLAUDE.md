# dynamic-cms — notatki projektu

## ZASADA: zmiany w bazie
- NIE używamy migracji Laravela. Zmiany struktury i danych → pliki `.sql` w `database/sql/` (nazwa `RRRR_MM_DD_NN_opis.sql`), puszczane ręcznie: `mysql -uroot --default-character-set=utf8mb4 lar_dynamic < database/sql/plik.sql`.
- Wykonane lokalnie (2026-10-01): `01_rodo_rules_zawezanie`, `02_clients_is_random_email`, `03_rodo_rules_teksty_dynamic`, `04_pages_front`, `05_aktualnosci` (articles.category + pages id 5), `06_articles_old_id_default` (naprawa 500 przy dodawaniu artykułu), `07_activity_log_nazwa_inwestycji`.

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

## Front (nowy szablon) — zasady i mapa sekcji
Zasada: sekcje powtarzające się na kilku podstronach → komponenty Blade `x-...` (anonimowe, `resources/views/components`). Header i footer → `resources/views/layouts/partials`.

### Zasoby (skopiowane z dynamic-front 2026-10-01)
- `public/css/style.css` (+ `style.min.css`), `public/js/{glowny,animacje,formularz,kalkulator,karuzele,mapa,slider,aktualnosci,aktualnosci-domowa}.js`, `public/img/` (222 pliki), `public/fonty/` (Inter).
- Źródła LESS: `resources/less/front/*.less` (31 plików, `style.less` = same importy). Kompilacja: `npx lessc resources/less/front/style.less public/css/style.css` (url-e `../img`, `../fonty` zostają bez zmian i pasują do public/css).
- Bootstrap i jQuery z lokalnych plików CMS (`css/bootstrap.min.css` 5.3.8, `js/jquery.min.js` 3.7.1, `js/bootstrap.bundle.min.js`), nie z CDN.
- W `slider.js`, `karuzele.js`, `aktualnosci.js` ścieżki `img/` zmienione na `/img/` (inaczej psują się na podstronach w podkatalogach).
- Strona NIE jest wielojęzyczna — trasy frontu bez prefiksu języka (`/finansowanie`, nie `/pl/finansowanie`). Grupa Front w `routes/web.php` nie ma już `{locale?}`; z sygnatur metod kontrolerów `Front/*` usunięty parametr `$lang`/`$locale` (Laravel przekazuje parametry trasy po kolei — zostawiony `$locale` dostawałby wartość `{uri}`).
- Linki przez `route()`: `route('index')`, `route('contact')`, `route('aktualnosci.index')`, strony statyczne `route('menu.show', ['uri' => 'finansowanie'])`.
- Trasy frontu zaczynające się od parametru (`{uri}` menu.show, `{slug}/{page}`, `{slug}/d/{property}`, `{property}/notifications`) mają `where(... '(?!admin(?:/|$))...')` — web.php ładuje się PRZED admin.php, bez tego przechwytywały `/admin/...`.
- Z `routes/admin.php` usunięta zdublowana trasa `{uri}` → MenuController (nadpisywała `menu.show`).

### Wspólne sekcje szablonu (analiza wszystkich 10 stron)
| Sekcja | Gdzie w szablonie | W CMS |
|---|---|---|
| Nagłówek + menu mobilne (offcanvas) | wszystkie | `layouts/partials/header.blade.php` (menu z tablicy, aktywna pozycja z `request()->is()`) |
| Stopka | wszystkie | `layouts/partials/footer.blade.php` (menu z tablicy, aktywna pozycja z adresu) |
| Kontakt z formularzem (`section.kontakt`) — identyczna | index, finansowanie, inwestycja, lokal, poznaj-nas, wykończenie | `<x-sekcje.kontakt />` (form → `route('contact.send')` + @csrf; nazwy pól z szablonu: imie, telefon, email, wiadomosc, zgoda-rodo — NIE pasują jeszcze do ContactFormRequest) |
| Kafle Finansowanie / Wykończenie | index (`div.kafle`), inwestycja (`section.kafle.oferta-uzupelniajaca`) | `<x-sekcje.kafle />`, na inwestycji `<x-sekcje.kafle tag="section" class="oferta-uzupelniajaca" aria-label="Dodatkowe usługi" />` |
| Sterowanie mapy (GPS + zoom) — identyczne | index, inwestycja, kontakt | `<x-mapa-sterowanie />` |
| Etykieta sekcji (pinezka + napis) | ~40× na wszystkich | `<x-etykieta class="...">TEKST</x-etykieta>` |
| Przycisk „pigułka” ze strzałką | ~16× | `<x-przycisk-pigulka href="..." class="na-tle">TEKST</x-przycisk-pigulka>` (bez href → span) |
| Ikony SVG: daszek, strzałka przycisku, telefon | nagłówek, wyszukiwarka, hero, formularz | `<x-ikona.daszek />`, `<x-ikona.strzalka />`, `<x-ikona.telefon />` |
| Karta aktualności (karuzela) | index | `<x-karta-aktualnosci :tytul :zajawka :data :link :kategoria :obrazek :webp />` |
| Hero podstrony `section.fin-hero` (ten sam szkielet, inna treść) | finansowanie, poznaj-nas, wykończenie (+ podobny `inwestycja-hero`) | `<x-hero-podstrony class="hero-finansowanie" :sciezka="['Finansowanie' => null]" zdjecie="fin-hero" alt="...">treść kolumny</x-hero-podstrony>` |
| Pasek boczny (FB, IG, motyw, ulubione) — identyczny | wszystkie 10 | `<x-pasek-boczny />` |
| Zdjęcie po lewej + kolumna tekstu `section.partner-wykonczenia` | wykończenie (partner), poznaj-nas (`partner-misja`, `partner-o-nas`) | `<x-sekcje.zdjecie-tekst class="partner-misja" zdjecie="poznaj-misja" alt="...">treść</x-sekcje.zdjecie-tekst>` (opcjonalnie szerokosc/wysokosc, domyślnie 1370×750) |
| Okruszki `nav.okruszki` | wszystkie podstrony | `<x-okruszki :sciezka="['Inwestycje' => route(...), 'Nazwa' => null]" />` („Strona główna” dokładana sama) |
| DO ZROBIENIA: nagłówek podstrony `section.naglowek-strony` (okruszki już są) | aktualnosci, inwestycje, kontakt, lokal, wyszukiwarka (+ okruszki też w fin-hero) | planowane `<x-naglowek-strony>`, `<x-okruszki>` |
| DO ZROBIENIA: nagłówek galerii `naglowek-galerii` | inwestycja, wykończenie (różna treść) | do decyzji |
| Liczby: `section.liczby` (index) vs `section.liczby-firmy` (poznaj-nas) | — | różne, NIE wspólne (index: inline) |
| Wyszukiwarka-pasek (index) vs filtry (wyszukiwarka.html) | — | różne |

### Layouty frontu
- `layouts/front.blade.php` — wspólny (head z SEO, header, footer, skrypty). SEO jak w starym CMS: `@section('seo_title')`, `seo_description`, `seo_robots`, albo `meta_title` → tytuł „{page_title} - {meta_title}”. Klasa body: `@section('body_class', '...')`.
- `layouts/homepage.blade.php` → extends front, body `strona-glowna`. `layouts/page.blade.php` → extends front (podstrony).
- Skrypty wspólne w layoucie: jquery, bootstrap, (stack scripts), animacje.js, glowny.js. Skrypty strony: `@push('scripts')`.

### Strony statyczne (MenuController)
- Rekord w `pages` (model `Page` — observer ustawia slug/uri z tytułu) + widok `resources/views/front/menupage/{uri}.blade.php`. `Front/MenuController@index` (trasa `menu.show`, `/{uri}`) szuka strony po `uri` i widoku o tej nazwie.
- Finansowanie (2026-10-01): `pages.id=1`, uri `finansowanie`, meta z szablonu; widok `front/menupage/finansowanie.blade.php` (hero, kalkulator raty — js/kalkulator.js, partner kredytowy, kontakt). Struktura HTML = `dynamic-front/finansowanie.html` (329 znaczników, 0 różnic).
- Wykończenie pod klucz (2026-10-01): `pages.id=2`, uri `wykonczenie-pod-klucz`, widok `front/menupage/wykonczenie-pod-klucz.blade.php` (hero, korzyści, partner Complex, galeria realizacji — js/karuzele.js, kontakt). Struktura = szablon (320 znaczników, 0 różnic).
- Poznaj nas (2026-10-01): `pages.id=3`, uri `poznaj-nas`, widok `front/menupage/poznaj-nas.blade.php` (hero, liczby firmy, misja i o nas przez `x-sekcje.zdjecie-tekst`, cytat, nagrody, historia firmy — js/karuzele.js, kontakt). Struktura = szablon (351 znaczników, 0 różnic).
- Stopka: menu z tablicy `$menuStopki`, klasa `aktywny` wyliczana z adresu (jak w szablonie na bieżącej podstronie).
- Konwerter użyty do podstron: scratchpad `podstrona.py` (zamienia img/ → asset(), linki .html → route(), etykiety/pigułki/hero/zdjęcie-tekst/kontakt/kafle → komponenty). Wywołanie: `python podstrona.py <plik> "<Nazwa>" <body_class|""> <js1,js2>`.

### Strona główna (2026-10-01)
- `layouts/homepage.blade.php` (body `strona-glowna` — wymagane przez motyw jasny), `front/homepage/index.blade.php`.
- Treść statyczna z makiety (baza pusta). Do podpięcia: slider hero (`js/slider.js` ma tablicę slajdów), inwestycje w sprzedaży, mapa inwestycji, aktualności (`$aktualnosci` w @php na górze widoku), formularz kontaktowy.
- Weryfikacja: HTML strony głównej ma tę samą strukturę co `dynamic-front/index.html` (622 znaczniki, 0 różnic w tekście; jedyna różnica — „Inwestycje” nie są aktywne na stronie głównej). 81 zasobów → 200.
- Chrome nie ufa certyfikatowi Laragona dla https://dynamic-cms.test (strona błędu) — test w przeglądarce wymaga zaufania cert. Laragon (Menu → SSL).

## Ustalenia z klientem — Aktualności (poczta, folder „Dynamic Investment”, maile 20.05–21.08.2026)
- Umowa, Załącznik nr 1 (Etap 1): klient sam edytuje w CMS treści, grafiki, **aktualności**, dane kontaktowe i treść raty. Do tego instrukcja CMS i jedno szkolenie.
- 15.07 (decyzja): podstrona Aktualności i publikowanie zostają, ale **na start nie ma ich w menu głównym**. Dostęp przez sekcję na stronie głównej i link w stopce (tak jest w szablonie). Pozycję w menu można przywrócić, jeśli będą publikować regularnie.
- Strona główna: desktop — karuzela ze strzałkami (wpisów może być więcej niż 3); mobile (09.08) — tylko 1 wpis.
- Klient przewiduje rzadkie publikacje, więc sekcja musi dobrze wyglądać przy 1–3 wpisach. Treści dostarcza klient, obecne teksty to draft.
- NIE ustalono w mailach: kategorii/plakietek, pól wpisu, paginacji, filtrów, SEO. Źródło: Figma (desktop 9tV8G3SpEvQRq4vmZG3osH, podstrona Aktualności node 802-175; mobile 6hOLzQF6peoLxnX9C8i59d) albo szablon `dynamic-front/aktualnosci.html` — lub dopytać klienta.

## Formularze kontaktowe — wzorzec z C:\laragon\www\poligonowa (analiza 2026-10-01)
Uwaga: w poligonowa poprawki formularzy są NIEZACOMMITOWANE (git log ich nie pokazuje) — źródłem jest drzewo robocze.
- Jeden formularz dla wszystkich kontekstów (kontakt, inwestycja, lokal, schowek) → `POST /kontakt` (`contact.send`), throttle 10/min. Kontroler `Front/Contact/IndexController@send` → `store()`.
- Różnice kontekstów = ukryte pola: `page` (nazwa strony → client_msg.source), `investment_id`, `property_id`, `back`, `clipboard`. Budynek/piętro/lokal backend bierze z modelu Property (nie z formularza).
- „Wyślij i wróć” = ukryte pole `back=1` → `redirect()->back()->with('success')`; bez niego → `redirect()->route('contact')`.
- Klient: `ClientRepository::createClient` — `firstOrNew` po e-mailu (brak e-maila → `noemail_{uuid}@example.com`, `is_random_email`), `ClientMessage` z `user_id=0` (Skrzynka szuka 0), `arguments` (cast array): investment/building/floor/property_id, rooms, area + UTM.
- RODO: `ClientObserver` czyta pola `rule_{id}` z żądania → `client_rules` (status 1/2, duration z `rodo_rules.time`, ip, referer, tekst klauzuli). Bez `rule_*` NIE tworzy zgód (w dynamic-cms obecny observer tworzy fałszywe zgody 1–3 — do naprawy). Klauzule w formularzu z `RodoRules::forFormAndInvestment()`, walidacja `requiredForFormAndInvestment()` (ten sam zestaw).
- Mail do biura: `NotificationRecipients` (office_emails inwestycji, inaczej `page_email`), `ChatSend`. Powiadomienie w panelu: `PropertyNotification` / `ContactNotification` (trait `DescribesFormSubmission`).
- reCAPTCHA v3 tylko gdy klucze w panelu (`ReCaptchaV3::isConfigured()`).
- Pomijamy (zależne od usuniętych modułów / opcjonalne): `LeadAutoResponder` (EmailTemplate), RemarketingPayload, LeadAssigner, touchpoints, CampaignTracker.
### Wdrożone w dynamic-cms (2026-10-01)
- Decyzje: jedno pole „Imię i nazwisko” (`name`), telefon wymagany, e-mail opcjonalny (brak → `noemail_{uuid}@example.com` + `clients.is_random_email=1`), klauzule RODO z bazy. BEZ: auto-maila do klienta, remarketingu, przypisywania handlowca, UTM/ścieżki kampanii.
- `Front\ContactController`: `index` (strona Kontakt, `pages.uri=kontakt`), `send` (wszystkie formularze), `property` (stara trasa, zgodność), `store` → `ClientRepository::createClient` + mail `ChatSend` (adresaci `Services\Mail\NotificationRecipients`: office_emails inwestycji, inaczej `page_email` z ustawień) + powiadomienie w panelu (lokal → `PropertyNotification`, inwestycja → `ContactNotification`, inaczej użytkownicy z rolą „Administrator”).
- `ContactFormRequest`: name, phone, email (nullable), message, page, back, investment_id, property_id; reCAPTCHA tylko gdy klucze w panelu; `rule_{id}` wymagane = te same klauzule co w widoku (`RodoRules::requiredForFormAndInvestment`).
- `ClientRepository::createClient`: `client_msg.user_id=0`, `source` = pole `page`, `arguments` (JSON jako tekst — panel czyta `json_decode`) z lokalu (investment/building/floor/property_id, rooms, area) albo `investment_id` z formularza; zgodność z ProcessLeads/API/panelem zachowana.
- `ClientObserver` (z poligonowa): zgody tylko z pól `rule_*`; przy ponownym zgłoszeniu stare zgody → status 2 + canceled_at.
- Widoki: `<x-formularz-kontaktowy strona="" :investment-id :property-id :back />` (karta formularza, jedna na stronę), `<x-sekcje.kontakt :strona="$page->title" />` (back=true domyślnie — „Wyślij i wróć”), strona `front/contact/index.blade.php` (main class `ma-pasek-boczny` przez `@section('main_class')`).
- `public/js/formularz.js`: walidacja name/phone/email/message i zgód `[data-wymagana]`, prawdziwy POST, token reCAPTCHA v3 przed wysyłką (`data-recaptcha`), blokada podwójnej wysyłki, przewinięcie do komunikatu po powrocie.
- Trasy POST /kontakt i /kontakt/{property}: `throttle:10,1`.
- UWAGA: w ustawieniach panelu brak `page_email` — formularz bez inwestycji zapisze klienta, ale mail nie wyjdzie (błąd w logu `email`). Ustawić w Ustawienia → SEO.
- Do zrobienia: podstrona Polityki prywatności (link w klauzuli ma href="#"); obowiązek informacyjny w `rodo_settings` to „Lorem ipsum”.

## Aktualności (2026-10-01)
- Panel (`admin/article`): pole Kategoria (`articles.category`, lista `Article::KATEGORIE`: NOWA INWESTYCJA, DZIENNIK INWESTYCJI, PORADNIK). Zdjęcie przycinane do 2 rozmiarów, każdy JPG (q85) + WebP (q80), z `orientate()` (EXIF z telefonu):
  - big 1170×602 → `uploads/articles/` + `uploads/articles/webp/` (strona wpisu),
  - thumb 896×504 → `uploads/articles/thumbs/` + `thumbs/webp/` (karuzela na stronie głównej: kadr 896×504; lista: kadr 350×320 / panorama 683×360 na telefonie, object-fit: cover).
  Rozmiary: `config/images.php` → `article`. Kod: `ArticleService::upload`.
- Front: `/aktualnosci` (lista, 6 na stronę, paginacja serwerowa `?strona=N` — komponent `<x-paginacja>`; `js/aktualnosci.js` z szablonu to atrapa paginacji, NIE dołączamy), `/aktualnosci/{slug}` (wpis; 404 dla ukrytych), karuzela na stronie głównej (9 najnowszych; sekcja znika, gdy brak wpisów). Trasy tylko index/show.
- Model `Article`: `scopeOpublikowane()` (status 1, wg „Data wyświetlenia”, inaczej data dodania), `link()`, `dataPublikacji()`, `zdjecie($rozmiar, $webp)` (bez zdjęcia → obrazek z szablonu).
- Komponenty: `<x-karta-wpisu :wpis>` (lista), `<x-karta-aktualnosci>` (karuzela).
- Strona wpisu (`front/article/show`) NIE ma szablonu HTML (tylko Figma, node 802-175) — złożona z elementów szablonu + `resources/less/front/wpis.less` (typografia treści). Do porównania z Figmą.
- LESS → CSS: `C:/laragon/www/dynamic-front/node_modules/.bin/lessc resources/less/front/style.less public/css/style.css` oraz `--clean-css ... public/css/style.min.css` (uruchamiać z katalogu dynamic-front). Kompilacja odtwarza style.css 1:1 (różnią się tylko końce linii).
- Wpisy testowe `[TEST] ...` (id 1–3) dodane przez panel do sprawdzenia frontu — usunięcie: `php <scratchpad>/test_aktualnosci.php usun` albo z panelu.

## Dziennik aktywności (LogsActivity, wzorzec poligonowa, 2026-10-01)
- `AppServiceProvider`: `Activity::saving` robi `merge` danych żądania (wcześniej `collect` NADPISYWAŁ properties → ginęła lista zmian).
- Modele z LogsActivity (logFillable + logOnlyDirty + dontSubmitEmptyLogs): Budynki, Piętra, Aktualności inwestycji, Firmy inwestycji, Podstrony inwestycji, Płatności inwestycji, Biura sprzedaży, Plany inwestycji, Składniki ceny, Kalendarz, Inwestycje (było „Investycje”), Powierzchnia, Klienci (bez password/remember_token), Użytkownicy (bez password/remember_token), Zgody RODO (tylko status/duration/months/canceled_at), Galerie, Strony, Slider, + spoza poligonowa: Aktualności, Miasta, Klauzule RODO.
  Inwestycje/Powierzchnia/Klienci: opis zmian po polsku z `App\Services\Activity\ActivityChangeDescriber` (skopiowany z poligonowa, bez Offer/Issue). Galerie/Strony/Slider/Aktualności: polskie opisy + `subject_title` w properties.
- Ekran `admin/logs`: `LogRepository` i widok z poligonowa — kolumny Nazwa, Akcja (DODANO/ZAKTUALIZOWANO/USUNIĘTO), Co się zmieniło; bez przycisków Excel/CSV (jak w poligonowa). Etykiety `badge-method-created/updated/deleted` w `admin.less` (+ dopisane na końcu `admin.min.css` — ten plik był minifikowany innym narzędziem, nie przebudowywać go lessc).
- `InvestmentRepository::getDataTable` (log inwestycji): `data_get`, „System” bez sprawcy. `Crm/Client/RodoController`: historia zgód bez błędnego filtra `causer_id = client_id`.

## Przeglądarka do testów
- Do testów używać Chrome na **Windows** (ten sam komputer co Laragon). Podłączone są dwie przeglądarki: Windows i macOS — domyślnie sesja bywa podpięta pod macOS, który NIE widzi `*.test` (strona błędu).
- Przed testem: `list_connected_browsers` → `select_browser` z przeglądarką `osPlatform: "Windows"` (2026-10-01: deviceId `930cfff7-d5f6-4563-bea9-c1d60da2f426`, „Browser 1”).

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
