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

        // Fetch activity logs for each ClientRules
        foreach ($clientRules as $rule) {
            $rule->activityLogs = Activity::where('subject_type', 'App\Models\ClientRules')
                ->where('subject_id', $rule->id)
                ->where('causer_id', $client->id)
                ->get();
        }

        return view('admin.crm.client.rodo.index', [
            'client' => $client,
            'list' => $clientRules
        ]);
    }
}
