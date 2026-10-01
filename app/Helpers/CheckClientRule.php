<?php

use App\Models\ClientRules;

if (! function_exists('clientRule')) {
    function clientRule(int $rule_id, int $client_id)
    {
        $rule = ClientRules::where('client_id', $client_id)
            ->where('rule_id', $rule_id)
            ->latest()
            ->first(); // Use first() instead of get() to retrieve only one record

        if ($rule) {
            return roomStatusBadge($rule->status); // Access the status attribute of the latest ClientRules record
        } else {
            // Handle the case where no matching record is found
            return 'No record found';
        }
    }

    function roomStatusBadge(int $number)
    {
        switch ($number) {
            case '1':
                return '<span class="status-1 float-end">Tak</span>';
            case '2':
                return '<span class="status-2 float-end">Nie</span>';
        }
    }
}