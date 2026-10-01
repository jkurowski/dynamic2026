<?php

namespace App\Services\Activity;

use App\Models\Client;
use App\Models\Department;
use App\Models\Investment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Zamienia surowy zapis zmian z dziennika aktywności na zdanie po polsku.
 *
 * Dziennik zapisywał dotąd wyłącznie „ZAKTUALIZOWANO” + nazwę rekordu, więc z listy
 * nie dało się odczytać, CO właściwie ktoś zmienił — trzeba było zgadywać albo
 * porównywać ręcznie. Tutaj powstaje jedno źródło etykiet i formatowania wartości,
 * używane zarówno przy zapisie wpisu (opis zdarzenia), jak i przy jego wyświetlaniu.
 */
class ActivityChangeDescriber
{
    /**
     * Czy wolno zapamiętywać odczytane rekordy — ustawiane wyłącznie przez `pairs()`
     * na czas jednego wywołania. Szczegóły i uzasadnienie: tamże.
     */
    private static bool $reuseLookups = false;

    /** @var array<string, Model|null> zapamiętane odczyty: `Klasa:id => rekord albo null` */
    private static array $lookups = [];

    /**
     * Pola opisywane po ludzku: `pole => [etykieta, sposób formatowania wartości]`.
     * Pole spoza tej listy trafi do opisu pod własną nazwą i bez formatowania —
     * lepiej pokazać surowo niż pominąć zmianę.
     */
    private const FIELDS = [
        Property::class => [
            'status' => ['Status', 'status'],
            'price_brutto' => ['Cena brutto', 'money'],
            'price_netto' => ['Cena netto', 'money'],
            'promotion_price' => ['Cena promocyjna', 'money'],
            'promotion_end_date' => ['Koniec promocji', 'plain'],
            'promotion_price_show' => ['Pokazuj cenę promocyjną', 'bool'],
            'price_hidden' => ['Ukryj cenę na stronie', 'bool'],
            'highlighted' => ['Promocja', 'bool'],
            'area' => ['Powierzchnia', 'area'],
            'rooms' => ['Liczba pokoi', 'plain'],
            'client_id' => ['Klient', 'client'],
            'active' => ['Widoczne na stronie', 'bool'],
            'name' => ['Nazwa', 'plain'],
            'number' => ['Numer', 'plain'],
            'reservation_until' => ['Rezerwacja do', 'plain'],
            'saled_at' => ['Data sprzedaży', 'plain'],
        ],

        Client::class => [
            'name' => ['Imię', 'plain'],
            'lastname' => ['Nazwisko', 'plain'],
            'mail' => ['E-mail', 'plain'],
            'mail2' => ['E-mail dodatkowy', 'plain'],
            'phone' => ['Telefon', 'plain'],
            'phone2' => ['Telefon dodatkowy', 'plain'],
            'status' => ['Etap sprzedaży', 'plain'],
            'source' => ['Źródło kontaktu', 'plain'],
            'source_additional' => ['Źródło — uzupełnienie', 'plain'],
            'purpose' => ['Cel zakupu', 'plain'],
            'user_id' => ['Opiekun', 'user'],
            'active' => ['Aktywny', 'bool'],
            'budget' => ['Budżet', 'money'],
            'area' => ['Poszukiwana powierzchnia', 'area'],
            'room' => ['Poszukiwana liczba pokoi', 'plain'],
            'is_company' => ['Firma', 'bool'],
            'company_name' => ['Nazwa firmy', 'plain'],
            'nip' => ['NIP', 'plain'],
            'address' => ['Adres', 'plain'],
            'city' => ['Miasto', 'plain'],
            'post_code' => ['Kod pocztowy', 'plain'],
            'street' => ['Ulica', 'plain'],
            'house_number' => ['Numer domu', 'plain'],
            'apartment_number' => ['Numer mieszkania', 'plain'],
            // Dane identyfikacyjne — patrz `MASKED` niżej.
            'pesel' => ['PESEL', 'masked'],
            'id_serie' => ['Seria dowodu', 'masked'],
            'id_number' => ['Numer dowodu', 'masked'],
            'birth_date' => ['Data urodzenia', 'masked'],
        ],

        Investment::class => [
            'name' => ['Nazwa', 'plain'],
            'status' => ['Status', 'investment_status'],
            'type' => ['Rodzaj', 'investment_type'],
            'address' => ['Adres', 'plain'],
            'date_start' => ['Rozpoczęcie', 'plain'],
            'date_end' => ['Zakończenie', 'plain'],
            'show_prices' => ['Pokazuj ceny', 'bool'],
            'show_properties' => ['Pokazuj powierzchnie', 'bool'],
            'office_address' => ['Adres biura sprzedaży', 'plain'],
            'office_emails' => ['Adresy biura sprzedaży', 'list'],
            'supervisors' => ['Opiekunowie inwestycji', 'list'],
            'bank_account' => ['Numer konta', 'plain'],
            'popup_status' => ['Wyskakujące okno', 'bool'],
            'areas_amount' => ['Liczba powierzchni', 'plain'],
            'area_range' => ['Zakres powierzchni', 'plain'],
        ],

    ];

    /**
     * Pola, których nie pokazujemy nigdy — techniczne albo wrażliwe.
     */
    private const HIDDEN = ['password', 'remember_token', 'email_tracking_id', 'updated_at', 'created_at'];

    /**
     * Pola, przy których pokazujemy **fakt zmiany, ale nie wartość**.
     *
     * Dziennik zmian jest w panelu dostępny szeroko (`admin.log`), a karta klienta zawiera
     * komplet danych identyfikacyjnych. Wpisywanie PESEL-u i numeru dowodu do dziennika
     * zrobiłoby z niego drugie, trwałe miejsce przechowywania tych danych — poza teczką
     * klienta, poza eksportem RODO i poza `Client::fullDelete()`. Audytowo liczy się to,
     * ŻE ktoś zmienił numer dowodu i kiedy; sama wartość jest w karcie klienta.
     *
     * Formatowanie `masked` działa niezależnie od mapy `FIELDS`, więc chroni też modele,
     * które trafią tu w przyszłości bez własnych etykiet.
     */
    private const MASKED = ['pesel', 'id_serie', 'id_number', 'id_issued_by', 'birth_date', 'birth_place'];

    /**
     * Opis zdarzenia zapisywany razem z wpisem w dzienniku.
     */
    public static function describe(Model $model, string $event): string
    {
        $label = class_basename($model);

        if ($event !== 'updated') {
            $name = $model->name ?? $model->title ?? ('#' . $model->getKey());

            return ($event === 'created' ? 'Dodano: ' : 'Usunięto: ') . $name;
        }

        $pairs = self::pairs(
            get_class($model),
            $model->getOriginal(),
            $model->getChanges()
        );

        if (empty($pairs)) {
            return 'Zapisano bez zmian';
        }

        $parts = array_map(
            fn (array $pair) => "{$pair['label']}: {$pair['old']} → {$pair['new']}",
            $pairs
        );

        return 'Zmieniono — ' . implode('; ', $parts);
    }

    /**
     * Lista zmian gotowa do wyświetlenia: etykieta + wartość przed i po.
     *
     * ## `$reuseLookups` — po co i kiedy go włączać
     *
     * Pola wskazujące na inny rekord (`user_id`, `client_id`, `investment_id`…) są
     * zamieniane na nazwę, co kosztuje jedno zapytanie **na wartość** — a że zamieniana
     * jest i stara, i nowa, to po dwa na każde takie pole. Przy wyświetlaniu jednej zmiany
     * to nic; przy tabeli dziennika liczącej tysiące wierszy to były **286 zapytań**,
     * niemal w kółko o tych samych kilkunastu użytkowników (zmierzone 2026-08-09).
     *
     * Włączony `$reuseLookups` zapamiętuje odczytane rekordy na czas jednego wywołania
     * `pairs()` w górę stosu — czyli na czas budowania jednej tabeli.
     *
     * **Domyślnie WYŁĄCZONY i tak ma zostać przy zapisie do dziennika.** Opis zdarzenia
     * powstaje w tym samym żądaniu, które zmieniło dane; gdyby to samo żądanie zmieniło
     * dany rekord po raz drugi, zapamiętana nazwa trafiłaby do bazy jako **nieaktualna
     * i już nigdy nienaprawialna**. Przy wyświetlaniu gotowego dziennika tego ryzyka nie ma
     * — stamtąd tylko czytamy.
     *
     * @param  array<string, mixed>  $old  wartości sprzed zmiany
     * @param  array<string, mixed>  $new  wartości po zmianie (tylko te, które się zmieniły)
     * @param  bool  $reuseLookups  włącz TYLKO przy wyświetlaniu listy, nigdy przy zapisie
     * @return array<int, array{label: string, old: string, new: string}>
     */
    public static function pairs(
        ?string $subjectType,
        array $old,
        array $new,
        bool $reuseLookups = false
    ): array {
        $map = self::FIELDS[$subjectType] ?? [];
        $pairs = [];

        $previousMode = self::$reuseLookups;
        self::$reuseLookups = $reuseLookups;

        try {
            $pairs = self::buildPairs($map, $old, $new);
        } finally {
            self::$reuseLookups = $previousMode;
        }

        return $pairs;
    }

    /** @return array<int, array{label: string, old: string, new: string}> */
    private static function buildPairs(array $map, array $old, array $new): array
    {
        $pairs = [];

        foreach ($new as $field => $newValue) {
            if (in_array($field, self::HIDDEN, true)) {
                continue;
            }

            [$label, $format] = $map[$field] ?? [$field, 'plain'];

            // Maskowanie wygrywa z mapą — także dla modeli bez własnych etykiet.
            if (in_array($field, self::MASKED, true)) {
                $format = 'masked';
            }

            $oldValue = $old[$field] ?? null;

            if (self::same($oldValue, $newValue)) {
                continue;
            }

            $pairs[] = [
                'label' => $label,
                'old' => self::format($oldValue, $format),
                'new' => self::format($newValue, $format),
            ];
        }

        return $pairs;
    }

    /**
     * Porównanie luźne, ale bez pułapki `'0' == ''`: w bazie sporo pól trzyma liczby
     * jako tekst, więc `100` i `'100'` to ta sama wartość i nie jest żadną zmianą.
     */
    private static function same($a, $b): bool
    {
        if ($a === null || $b === null) {
            return $a === $b;
        }

        /*
         * 🔴 W dzienniku bywają wartości NIESKALARNE i wtedy rzutowanie na napis sypie
         * ostrzeżeniem „Array to string conversion", a ekran dziennika wraca z kodem 200
         * i dziurą w wierszu. Skąd się biorą: pola wielokrotne (`meta`) oraz — do 23.08 —
         * pola plikowe wpuszczane do zapisu jako `UploadedFile` (patrz `PomijaPolaPlikow`).
         * Tamten zapis jest już naprawiony, ale **stare wpisy w dzienniku zostają na zawsze**,
         * więc porównanie musi je znieść. Złapane przelotem po ekranach 23.08.
         */
        if (is_array($a) || is_array($b) || is_object($a) || is_object($b)) {
            return json_encode($a) === json_encode($b);
        }

        if (is_numeric($a) && is_numeric($b)) {
            return (float) $a === (float) $b;
        }

        return (string) $a === (string) $b;
    }

    private static function format($value, string $format): string
    {
        if ($value === null || $value === '') {
            return '—';
        }


        switch ($format) {
            case 'money':
                return is_numeric($value)
                    ? number_format((float) $value, 0, ',', ' ') . ' zł'
                    : (string) $value;

            case 'area':
                return is_numeric($value)
                    ? number_format((float) $value, 2, ',', ' ') . ' m²'
                    : (string) $value;

            case 'status':
                return function_exists('roomStatus') ? (string) roomStatus($value) : (string) $value;

            case 'bool':
                return $value ? 'Tak' : 'Nie';

            case 'client':
                $client = self::lookup(Client::class, $value);

                return $client
                    ? trim($client->name . ' ' . $client->lastname) . ' (#' . $client->id . ')'
                    : 'klient #' . $value;

            case 'masked':
                return '(zmieniono)';

            case 'user':
                return self::nameOf(self::lookup(User::class, $value), 'użytkownik', $value);

            case 'department':
                return self::nameOf(self::lookup(Department::class, $value), 'dział', $value);

            case 'investment':
                return self::nameOf(self::lookup(Investment::class, $value), 'inwestycja', $value);

            case 'property':
                return self::nameOf(self::lookup(Property::class, $value), 'powierzchnia', $value);

            case 'investment_status':
                return function_exists('investmentStatus')
                    ? (string) (investmentStatus((int) $value) ?? $value)
                    : (string) $value;

            case 'investment_type':
                return function_exists('investmentType')
                    ? (string) (investmentType((int) $value) ?? $value)
                    : (string) $value;

            case 'list':
                // Pola tagify trzymają JSON-a (`[{"value":"a@b.pl"},…]`) albo listę po przecinku.
                // Surowy JSON w dzienniku jest nieczytelny, a to często zmieniane pola.
                return self::readableList($value);

            case 'longtext':
                // Treść oferty czy opis zgłoszenia potrafią mieć kilka tysięcy znaków —
                // w dzienniku liczy się fakt zmiany, nie cały tekst.
                $plain = trim(strip_tags((string) $value));

                return mb_strlen($plain) > 80
                    ? mb_substr($plain, 0, 80) . '…'
                    : ($plain === '' ? '—' : $plain);

            default:
                /*
                 * 🔴 Wartość może być TABLICĄ, nie tylko napisem.
                 *
                 * Dziennik zapisuje pola przez `logFillable()`, a część modeli rzutuje
                 * pole na tablicę (`protected $casts = ['meta' => 'array']`). Taka wartość
                 * trafiała wprost do `(string)` i dawała ostrzeżenie PHP
                 * **„Array to string conversion"** — ekran dziennika wracał z kodem 200,
                 * więc nikt tego nie widział poza dziennikiem serwera.
                 *
                 * Znalezione 2026-08-14 przez `panel:smoke`, na pierwszym w historii wpisie
                 * dziennika dla szablonu wiadomości (`EmailTemplate::meta`). Czyli usterka
                 * czekała od początku i odsłoniła ją dopiero pierwsza taka zmiana.
                 */
                if (is_array($value)) {
                    return self::readableArray($value);
                }

                return (string) $value;
        }
    }

    /**
     * Odczyt rekordu, na który wskazuje zmienione pole.
     *
     * Zapamiętuje wynik **tylko wtedy**, gdy woła nas wyświetlanie listy (patrz
     * `$reuseLookups` w `pairs()`). Zapamiętywany jest także brak rekordu — pytanie
     * o skasowanego użytkownika w kółko byłoby tym samym marnotrawstwem co pytanie
     * o istniejącego.
     *
     * @param  class-string<Model>  $class
     */
    private static function lookup(string $class, $value): ?Model
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!self::$reuseLookups) {
            return $class::find($value);
        }

        $key = $class . ':' . $value;

        if (!array_key_exists($key, self::$lookups)) {
            self::$lookups[$key] = $class::find($value);
        }

        return self::$lookups[$key];
    }

    /**
     * Nazwa powiązanego rekordu albo czytelny zastępnik, gdy rekordu już nie ma
     * (skasowany użytkownik, usunięta inwestycja) — wpis w dzienniku ma przetrwać
     * usunięcie tego, do czego się odwołuje.
     */
    private static function nameOf(?Model $model, string $noun, $value): string
    {
        if (!$model) {
            return $noun . ' #' . $value;
        }

        $name = trim(($model->name ?? '') . ' ' . ($model->lastname ?? ''));

        return ($name !== '' ? $name : $noun) . ' (#' . $model->getKey() . ')';
    }

    /**
     * Tablica zapisana w dzienniku, podana po ludzku.
     *
     * Dwa różne kształty wymagają dwóch różnych zapisów:
     *  - **lista** (`["a", "b"]`) — sama treść, oddzielona przecinkami,
     *  - **tablica z kluczami** (`{"template_type": "20"}`) — `klucz: wartość`, bo sam „20"
     *    nie mówi nic o tym, co się zmieniło.
     *
     * Zagnieżdżenia głębsze niż jeden poziom skracamy do `[…]` — dziennik ma powiedzieć,
     * ŻE coś się zmieniło, a nie odtworzyć całą strukturę.
     */
    private static function readableArray(array $value): string
    {
        if ($value === []) {
            return '—';
        }

        if (array_is_list($value)) {
            return self::readableList($value);
        }

        $pary = [];

        foreach ($value as $klucz => $wartosc) {
            $pary[] = $klucz . ': ' . (is_array($wartosc) ? '[…]' : (string) $wartosc);
        }

        return implode(', ', $pary);
    }

    private static function readableList($value): string
    {
        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (!is_array($decoded)) {
            return (string) $value;
        }

        $items = array_filter(array_map(
            fn ($item) => is_array($item) ? ($item['value'] ?? $item['name'] ?? null) : $item,
            $decoded
        ));

        return $items ? implode(', ', $items) : '—';
    }
}
