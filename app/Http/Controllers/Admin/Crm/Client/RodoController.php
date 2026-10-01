<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;

//CMS
use App\Models\Client;
use App\Models\ClientRules;
use Spatie\Activitylog\Models\Activity;

class RodoController extends Controller
{
    public function show(Client $client)
    {
        $clientRules = ClientRules::where('client_id', $client->id)->latest()->get();

        // Historia zmian każdej zgody - jednym zapytaniem. Wcześniej filtr `causer_id = $client->id`
        // porównywał autora zmiany z numerem klienta, a autorem jest użytkownik panelu albo nikt
        // (formularz) - więc kolumna "Zmiany dokonał" była zawsze pusta. (wzorzec: poligonowa)
        $logs = Activity::query()
            ->where('subject_type', ClientRules::class)
            ->whereIn('subject_id', $clientRules->pluck('id'))
            ->latest()
            ->get()
            ->groupBy('subject_id');
        foreach ($clientRules as $rule) {
            $rule->activityLogs = $logs->get($rule->id, collect());
        }

        return view('admin.crm.client.rodo.index', [
            'client' => $client,
            'list' => $clientRules
        ]);
    }
}
