# Instalacja dynamic-cms na nowym serwerze

Wymagania: PHP 8.2 (rozszerzenia: pdo_mysql, mbstring, gd, fileinfo, openssl, intl, zip), MySQL/MariaDB,
Composer, **HTTPS** (logowanie do panelu działa tylko po https - ciasteczko sesji ma `secure`).

## 1. Kod
```
git clone https://github.com/jkurowski/dynamic2026.git .
composer install --no-dev --optimize-autoloader
```
Katalog główny domeny (document root) → **`public/`**. Inaczej widać pliki projektu, w tym `.env`.

## 2. Baza danych
Pusta baza w `utf8mb4` / `utf8mb4_unicode_ci`, potem import pliku instalacyjnego:
```
mysql -u<user> -p --default-character-set=utf8mb4 <baza> < lar_dynamic_instalacja.sql
```
(albo phpMyAdmin → Import). Plik **nie jest w repozytorium** (zawiera konto administratora) - lokalnie:
`database/instalacja/lar_dynamic_instalacja.sql`, przenieść na serwer ręcznie i po imporcie usunąć z serwera.

Plik zawiera całą bazę (61 tabel) z już wykonanymi plikami `database/sql/*` (tabela `sql_wykonane`) - po imporcie
nie trzeba niczego uruchamiać. **Nie uruchamiać `php artisan migrate`** (tabela `migrations` jest pusta - migracje
próbowałyby tworzyć istniejące tabele).

Konto: tylko administrator (id 1, to samo logowanie co lokalnie). Pozostałych użytkowników dodać w panelu.

## 3. `.env`
Skopiować `.env.example` do `.env` i ustawić co najmniej:
```
APP_NAME="Dynamic Development"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<domena>

DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

BROADCAST_DRIVER=log
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=...
MAIL_FROM_NAME="Dynamic Development"
```
Potem:
```
php artisan key:generate
php artisan passport:keys
php artisan permission:cache-reset
```

## 4. `public/.htaccess` (nie ma go w repo - `.gitignore`)
Standardowy plik Laravela:
```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## 5. Uprawnienia do zapisu (serwer WWW)
- `storage/` i `bootstrap/cache/` (całe),
- `public/uploads/` - założyć, jeśli nie istnieje (zdjęcia aktualności, slidera, sekcji, kody QR biur; podkatalogi
  tworzą się same). `public/uploads` nie jest w repo - zdjęcia wpisów `[TEST]` są tylko lokalnie
  (`public/uploads/articles`); bez nich wpisy pokażą zdjęcie zastępcze. Wpisy testowe usunąć w panelu przed startem.

## 6. Po instalacji - w panelu (`https://<domena>/admin`)
- Ustawienia → SEO: **adres e-mail strony** (`page_email`) - na niego idą zgłoszenia z formularzy bez inwestycji.
- Ustawienia: klucze reCAPTCHA v3 (bez nich formularz działa bez reCAPTCHA).
- Cookiebot: domenę serwera dopisać w Cookiebot Manager (konto klienta) - inaczej Polityka prywatności pokaże błąd
  „domain is not authorized”.
- `/admin/sql` - powinno być „wykonany” przy wszystkich plikach.

## 7. Opcjonalnie: cron
Harmonogram (pobieranie leadów z poczty co 6 h, `ProcessLeads`) - tylko jeśli używany:
```
* * * * * cd /ścieżka/do/projektu && php artisan schedule:run >> /dev/null 2>&1
```

## Kolejne aktualizacje
```
git pull
composer install --no-dev --optimize-autoloader
```
Nowe pliki `database/sql/*.sql` → panel `/admin/sql` → „Wykonaj wszystkie oczekujące” (tylko Administrator)
albo `php artisan sql:wykonaj`. Przed wykonaniem kopia bazy.
