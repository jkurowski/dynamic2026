<?php

namespace App\Observers;

use Illuminate\Http\Request;

//CMS
use App\Models\Client;
use App\Models\ClientRules;
use App\Models\RodoRules;
use Illuminate\Support\Facades\Log;

class ClientObserver
{

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function created(Client $client)
    {
        Log::info('ClientObserver - created: ' . $client->id);

        $checkboxes = preg_grep("/rule_([0-9])/i", array_keys($this->request->all()));

        if (!$checkboxes) {
            // Klient powstał POZA formularzem zgód: lead z maila z portalu, import,
            // ręczne dodanie przez handlowca. Nikt niczego nie zaznaczył, więc NIE
            // zakładamy żadnych zgód.
            //
            // Do 2026-07-31 działo się tu coś odwrotnego: system tworzył wpisy dla
            // WSZYSTKICH aktywnych klauzul — łącznie z marketingową — z ip 127.0.0.1
            // i źródłem „E-mail lead”. Powstawał dowód zgody, której nikt nie udzielił,
            // a od 2026-07-30 to właśnie na tych wpisach opiera się wysyłka mailingu.
            //
            // Podstawą obsługi takiego zgłoszenia jest wniosek osoby (działania przed
            // zawarciem umowy), a nie zgoda. Zgoda marketingowa zostaje pusta, dopóki
            // klient naprawdę jej nie udzieli — przez formularz albo przez handlowca.
            Log::info('ClientObserver - brak pól rule_*, nie zakładam zgód dla klienta: ' . $client->id);

            return;
        }

        Log::info('Checkbox: ' . print_r($checkboxes, true));

        $this->storeConsents($client, $checkboxes);
    }

    public function updated(Client $client)
    {
        Log::info('ClientObserver - updated: ' . $client->id);

        $checkboxes = preg_grep("/rule_([0-9])/i", array_keys($this->request->all()));

        if ($checkboxes) {

            ClientRules::where('client_id', $client->id)->whereStatus(1)->update(['status' => 2, 'canceled_at' => now()]);

            $this->storeConsents($client, $checkboxes);
        }
    }

    /**
     * Zapisuje zgody zaznaczone w formularzu.
     *
     * Wspólne dla `created` i `updated` — wcześniej ten sam blok stał w dwóch miejscach
     * i rozjeżdżał się w drobiazgach (przy tworzeniu status miał wartość domyślną,
     * przy edycji nie).
     *
     * Klauzule pobieramy JEDNYM zapytaniem zamiast `find()` na każdy checkbox.
     *
     * @param array $checkboxes nazwy pól `rule_X` z żądania
     */
    private function storeConsents(Client $client, array $checkboxes): void
    {
        $ruleIds = array_map(
            fn ($field) => (int) preg_replace('/[^0-9]/', '', $field),
            $checkboxes
        );

        $rules = RodoRules::whereIn('id', $ruleIds)->get()->keyBy('id');

        $source = (is_object($this->request) && method_exists($this->request, 'header'))
            ? $this->request->header('referer')
            : null;

        foreach ($checkboxes as $field) {
            $ruleId = (int) preg_replace('/[^0-9]/', '', $field);
            $rule = $rules->get($ruleId);

            if (!$rule) {
                continue;
            }

            ClientRules::create([
                'client_id' => $client->id,
                'rule_id' => $ruleId,
                'ip' => $this->request->ip(),
                'source' => $source,
                // Data w formacie czterocyfrowego roku — wcześniej było `y-m-d`, co PHP
                // rozumiał poprawnie tylko dzięki regule dwucyfrowych lat (00–69 → 20xx).
                'duration' => strtotime('+' . $rule->time . ' months', strtotime(date('Y-m-d'))),
                'months' => $rule->time,
                'status' => $this->request[$field] ?? 1,
                'text' => strip_tags($rule->text),
            ]);
        }
    }
}
