<?php namespace App\Repositories;

use App\Models\User;
use App\Services\Activity\ActivityChangeDescriber;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class LogRepository
{
    /**
     * Dziennik aktywności do tabeli — kto, kiedy i które pole zmienił.
     *
     * ## Dlaczego `with(['causer', 'subject'])`
     *
     * Bez tego każdy wiersz dociągał sprawcę i przedmiot **osobnym zapytaniem**: przy 2193
     * wpisach w bazie deweloperskiej ekran robił **2663 zapytania** i ładował się 1,6 s
     * (zmierzone `panel:smoke` 2026-08-09). Klasyczne N+1, tyle że schowane w wywołaniach
     * `$row->subject->title` wewnątrz kolumn.
     *
     * Obie relacje są polimorficzne (`morphTo`), więc Laravel nie potrafi ich pobrać jednym
     * zapytaniem — robi po jednym **na każdy typ**, a nie na każdy wiersz. Typów przedmiotu
     * jest dwadzieścia, sprawcy dwa, więc z 2663 zapytań robi się około dwudziestu kilku
     * i liczba ta **nie rośnie wraz z liczbą wpisów**.
     *
     * ## Czego to NIE naprawia
     *
     * Tabela nadal wczytuje **wszystkie** wpisy naraz (`serverSide: false` w widoku —
     * przeglądarka sama filtruje, sortuje i stronicuje, a listy wyboru nad kolumnami są
     * budowane z kompletu danych). Przy dzienniku liczonym w setkach tysięcy wierszy to
     * przestanie wystarczać niezależnie od liczby zapytań. Przejście na stronicowanie po
     * stronie serwera wymaga przebudowy filtrów i eksportu w widoku — osobne zadanie.
     */
    public function getDataTable(User $user = null, string $minDate = null, string $maxDate = null){

        $query = Activity::with(['causer', 'subject'])->orderByDesc("id");

        if ($user) {
            $query->where('causer_id', $user->id);
        }

        if ($minDate) {
            $minDateCarbon = Carbon::parse($minDate)->startOfDay();
            $query->where('created_at', '>=', $minDateCarbon);
        }

        if ($maxDate) {
            $maxDateCarbon = Carbon::parse($maxDate)->endOfDay();
            $query->where('created_at', '<=', $maxDateCarbon);
        }

        $list = $query->get();

        return Datatables::of($list)
            ->editColumn('name', function ($row) {
                $causerType = $row->causer_type;

                // Typ jest zapisany w kolumnie, ale samo konto mogło zostać w międzyczasie
                // usunięte — wtedy relacja zwraca `null`. Bez tego wiersz po skasowanym
                // użytkowniku wywracał ostrzeżeniem „Attempt to read property on null".
                if ($causerType && !$row->causer) {
                    return '<span data-filter="">Konto usunięte</span>';
                }

                if ($causerType === 'App\Models\User') {
                    return '<span data-filter="'. $row->causer->email .'">'
                        . $row->causer->name
                        . '<br>' . $row->causer->email
                        . '<br><strong>Typ: Użytkownik</strong></span>';
                } elseif ($causerType === 'App\Models\Client') {
                    return '<span data-filter="'. $row->causer->mail .'">'
                        . $row->causer->name.' - <a href="'.route('admin.crm.clients.show', $row->causer->id).'">Zobacz profil</a>'
                        . '<br>' . $row->causer->mail
                        . '<br><strong>Rola: Klient</strong></span>';
                } else {
                    return '<span data-filter="">-</span>';
                }
            })
            ->addColumn('subject', function ($row){

                if ($row->subject && isset($row->subject->title)) {
                    return $row->subject->title;
                }

                // Przedmiot bywa już usunięty — wtedy zostaje nazwa zapisana w chwili
                // zdarzenia. `data_get`, bo `properties` to kolekcja i brak klucza
                // dawałby ostrzeżenie „Undefined array key".
                return data_get($row->properties, 'subject_title', '-');
            })
            ->addColumn('action', function ($row){

                // 🔥 najpierw Spatie event
                if ($row->event) {
                    $map = [
                        'created' => 'DODANO',
                        'updated' => 'ZAKTUALIZOWANO',
                        'deleted' => 'USUNIĘTO',
                    ];

                    $label = $map[$row->event] ?? strtoupper($row->event);

                    return '<span data-filter="'. $label .'">
            <div class="badge badge-method badge-method-'.$row->event.'">'
                        . $label .
                        '</div>
        </span>';
                }

                // 🔥 fallback (stare logi)
                $method = data_get($row->properties, 'methodType', '-');

                $map = [
                    'POST' => 'DODANO',
                    'PUT' => 'ZAKTUALIZOWANO',
                    'PATCH' => 'ZAKTUALIZOWANO',
                    'DELETE' => 'USUNIĘTO',
                    'GET' => 'PODGLĄD',
                ];

                $label = $map[$method] ?? $method;

                return '<span data-filter="'. $label .'">
        <div class="badge badge-method badge-method-'.strtolower($method).'">'
                    . $label .
                    '</div>
    </span>';
            })
            /**
             * Kolumna „Co się zmieniło” — sedno zgłoszenia: z dziennika miało wynikać
             * KTÓRE pole ktoś ruszył i jak, a nie tylko „zaktualizowano”.
             * Wartości sprzed zmiany zapisuje Spatie w `properties.old`, nowe
             * w `properties.attributes` (tylko te faktycznie zmienione).
             */
            ->addColumn('changes', function ($row) {
                $pairs = ActivityChangeDescriber::pairs(
                    $row->subject_type,
                    data_get($row->properties, 'old', []),
                    data_get($row->properties, 'attributes', []),
                    // Wyświetlanie, nie zapis — wolno zapamiętywać odczytane nazwy.
                    // To druga połowa naprawy N+1: same odwołania do użytkowników
                    // i klientów w tej kolumnie dawały 286 zapytań.
                    reuseLookups: true
                );

                if (empty($pairs)) {
                    // Starsze wpisy (sprzed 2026-08-01) nie mają rozpisanych zmian —
                    // pokazujemy sam opis zdarzenia, żeby wiersz nie był pusty.
                    return '<span class="text-muted">' . e($row->description ?: '—') . '</span>';
                }

                return collect($pairs)
                    ->map(fn (array $pair) => '<strong>' . e($pair['label']) . ':</strong> '
                        . e($pair['old']) . ' → ' . e($pair['new']))
                    ->implode('<br>');
            })
            ->editColumn('method', function ($row){

                $method = data_get($row->properties, 'methodType', '-');

                return '<span data-filter="'. $method .'">
        <div class="badge badge-method badge-method-'.strtolower($method).'">'
                    . $method .
                    '</div>
    </span>';
            })
            /*
             * Wpisy zapisane przez model (`LogsActivity`), a nie przez podgląd żądań, nie
             * mają w `properties` kluczy `route`, `referer` ani `ipAddress` — odwołanie
             * wprost dawało dla nich ostrzeżenie PHP. Ta sama usterka co w dzienniku
             * inwestycji (`InvestmentRepository`), poprawiona 2026-08-09.
             */
            ->editColumn('route', function ($row){
                return data_get($row->properties, 'route', '');
            })
            ->editColumn('referer', function ($row){
                return data_get($row->properties, 'referer', '');
            })
            ->editColumn('ip', function ($row){
                return data_get($row->properties, 'ipAddress', '');
            })
            ->editColumn('created_at', function ($row){
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $now = Carbon::now()->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();

                if($date >= $now){
                    return '<span>'.$date.'</span>';
                } else {
                    return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
                }
            })
            ->rawColumns(['action', 'name', 'created_at', 'changes'])
            ->make();
    }
}
