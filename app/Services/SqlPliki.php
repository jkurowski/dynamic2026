<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Zmiany w bazie z plików database/sql/*.sql (zamiast migracji Laravela).
 *
 * Wykonane pliki zapisujemy w tabeli `sql_wykonane` - każdy plik idzie raz, w kolejności nazw
 * (RRRR_MM_DD_NN_opis.sql). Uruchamianie: panel /admin/sql albo `php artisan sql:wykonaj`.
 *
 * Plik dzielimy na pojedyncze zapytania i wykonujemy po kolei: PDO przy kilku zapytaniach naraz
 * zgłasza tylko błąd pierwszego, a resztę mogłoby pominąć po cichu.
 * UWAGA: ALTER/CREATE w MySQL zatwierdzają się od razu - plik przerwany w połowie nie cofa się sam.
 */
class SqlPliki
{
    public const TABELA = 'sql_wykonane';

    public function katalog(): string
    {
        return database_path('sql');
    }

    /** Tabela ze spisem wykonanych plików - zakładana przy pierwszym użyciu */
    public function przygotuj(): void
    {
        if (Schema::hasTable(self::TABELA)) {
            return;
        }

        DB::statement('CREATE TABLE IF NOT EXISTS `' . self::TABELA . '` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `plik` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
            `kto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `wykonano_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `sql_wykonane_plik_unique` (`plik`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    /** @return array<int, array{plik: string, wykonano: ?string, kto: ?string}> wszystkie pliki, po kolei */
    public function lista(): array
    {
        $this->przygotuj();
        $wykonane = DB::table(self::TABELA)->get()->keyBy('plik');

        return collect(File::files($this->katalog()))
            ->filter(fn ($f) => $f->getExtension() === 'sql')
            ->map(fn ($f) => $f->getFilename())
            ->sort()
            ->values()
            ->map(fn ($plik) => [
                'plik' => $plik,
                'wykonano' => optional($wykonane->get($plik))->wykonano_at,
                'kto' => optional($wykonane->get($plik))->kto,
            ])
            ->all();
    }

    /** @return string[] pliki jeszcze niewykonane */
    public function oczekujace(): array
    {
        return collect($this->lista())->whereNull('wykonano')->pluck('plik')->all();
    }

    /**
     * Wykonuje jeden plik i zapisuje go jako wykonany.
     * @throws \Throwable błąd zapytania - plik NIE zostaje oznaczony
     */
    public function wykonaj(string $plik, ?string $kto = null): int
    {
        $sciezka = $this->sciezka($plik);
        $this->przygotuj();

        if (DB::table(self::TABELA)->where('plik', $plik)->exists()) {
            throw new \RuntimeException("Plik {$plik} był już wykonany.");
        }

        $zapytania = self::podziel(File::get($sciezka));
        foreach ($zapytania as $i => $zapytanie) {
            try {
                DB::unprepared($zapytanie);
            } catch (\Throwable $e) {
                throw new \RuntimeException("{$plik}, zapytanie " . ($i + 1) . ' z ' . count($zapytania) . ': ' . $e->getMessage(), 0, $e);
            }
        }

        $this->oznacz($plik, $kto);

        return count($zapytania);
    }

    /** Zapis „wykonany” bez uruchamiania (np. pliki puszczone wcześniej ręcznie z konsoli mysql) */
    public function oznacz(string $plik, ?string $kto = null): void
    {
        $this->sciezka($plik);
        $this->przygotuj();

        DB::table(self::TABELA)->insertOrIgnore(['plik' => $plik, 'kto' => $kto, 'wykonano_at' => now()]);
    }

    /** Tylko pliki z katalogu database/sql, bez ścieżek z zewnątrz */
    private function sciezka(string $plik): string
    {
        if (!preg_match('/^[A-Za-z0-9_\-]+\.sql$/', $plik) || !File::isFile($this->katalog() . DIRECTORY_SEPARATOR . $plik)) {
            throw new \InvalidArgumentException("Nie ma pliku database/sql/{$plik}");
        }

        return $this->katalog() . DIRECTORY_SEPARATOR . $plik;
    }

    /**
     * Podział pliku na zapytania po średnikach - z pominięciem średników w tekstach ('...', "...", `...`)
     * i komentarzach (-- , #, /* *\/). Komentarze wycinamy.
     *
     * @return string[]
     */
    public static function podziel(string $sql): array
    {
        $zapytania = [];
        $biezace = '';
        $dl = strlen($sql);

        for ($i = 0; $i < $dl; $i++) {
            $z = $sql[$i];
            $nast = $sql[$i + 1] ?? '';

            // Komentarz do końca linii
            if (($z === '-' && $nast === '-' && in_array($sql[$i + 2] ?? ' ', [' ', "\t", "\n", "\r"], true)) || $z === '#') {
                $koniec = strpos($sql, "\n", $i);
                $i = $koniec === false ? $dl : $koniec;
                $biezace .= "\n";
                continue;
            }

            // Komentarz blokowy
            if ($z === '/' && $nast === '*') {
                $koniec = strpos($sql, '*/', $i + 2);
                $i = $koniec === false ? $dl : $koniec + 1;
                $biezace .= ' ';
                continue;
            }

            // Tekst w cudzysłowie - przepisujemy w całości (z ucieczkami \' i '')
            if ($z === "'" || $z === '"' || $z === '`') {
                $biezace .= $z;
                for ($i++; $i < $dl; $i++) {
                    $biezace .= $sql[$i];
                    if ($sql[$i] === '\\' && $z !== '`') {
                        $biezace .= $sql[++$i] ?? '';
                        continue;
                    }
                    if ($sql[$i] === $z) {
                        if (($sql[$i + 1] ?? '') === $z) {
                            $biezace .= $sql[++$i];
                            continue;
                        }
                        break;
                    }
                }
                continue;
            }

            if ($z === ';') {
                if (trim($biezace) !== '') {
                    $zapytania[] = trim($biezace);
                }
                $biezace = '';
                continue;
            }

            $biezace .= $z;
        }

        if (trim($biezace) !== '') {
            $zapytania[] = trim($biezace);
        }

        return $zapytania;
    }
}
