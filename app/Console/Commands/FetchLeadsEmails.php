<?php

namespace App\Console\Commands;

use App\Jobs\ProcessLeads;
use Illuminate\Console\Command;


// Do testów
class FetchLeadsEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pobierz emaile ze skrzynki z leadami i zapisz je w bazie danych.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ProcessLeads::dispatchSync();
    }
}
