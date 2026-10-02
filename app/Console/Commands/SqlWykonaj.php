<?php

namespace App\Console\Commands;

use App\Services\SqlPliki;
use Illuminate\Console\Command;

/**
 * Zmiany w bazie z database/sql/*.sql - to samo co panel /admin/sql.
 *
 *   php artisan sql:wykonaj            wykonuje wszystkie niewykonane pliki (po kolei, stop na pierwszym błędzie)
 *   php artisan sql:wykonaj --lista    tylko stan plików
 *   php artisan sql:wykonaj --oznacz   oznacza wszystkie pliki jako wykonane BEZ uruchamiania
 *                                      (baza, w której pliki puszczono wcześniej ręcznie)
 */
class SqlWykonaj extends Command
{
    protected $signature = 'sql:wykonaj {--lista : Pokaż stan plików} {--oznacz : Oznacz wszystkie jako wykonane bez uruchamiania}';

    protected $description = 'Wykonuje niewykonane pliki database/sql/*.sql (zamiast migracji)';

    public function handle(SqlPliki $sql): int
    {
        if ($this->option('lista')) {
            $this->table(['Plik', 'Wykonano', 'Kto'], collect($sql->lista())->map(fn ($p) => [$p['plik'], $p['wykonano'] ?? '- czeka -', $p['kto']])->all());

            return self::SUCCESS;
        }

        $oczekujace = $sql->oczekujace();
        if (!$oczekujace) {
            $this->info('Wszystkie pliki są już wykonane.');

            return self::SUCCESS;
        }

        if ($this->option('oznacz')) {
            foreach ($oczekujace as $plik) {
                $sql->oznacz($plik, 'konsola (oznaczone)');
                $this->line("oznaczony: {$plik}");
            }

            return self::SUCCESS;
        }

        foreach ($oczekujace as $plik) {
            try {
                $ile = $sql->wykonaj($plik, 'konsola');
                $this->info("OK {$plik} ({$ile} zapytań)");
            } catch (\Throwable $e) {
                $this->error('BŁĄD ' . $e->getMessage());
                $this->warn('Zatrzymano - kolejne pliki nie zostały wykonane.');

                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}
