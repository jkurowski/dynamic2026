<?php

namespace App\Http\Controllers\Admin\Developro\Property;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Client;
use App\Models\Investment;
use App\Models\PropertiesHistory;
use App\Models\Property;
use App\Models\User;

class HistoryController extends Controller
{
    public function show(Investment $investment, Property $property)
    {
        if (request()->ajax()) {
            // Retrieve the history records for the property.
            $historyRecords = PropertiesHistory::where('property_id', $property->id)->get();

            // Loop through the history records to prepare data for the view.
            $historyData = [];
            foreach ($historyRecords as $historyRecord) {
                $user = User::find($historyRecord->user_id); // Replace 'User' with your User model.
                $client = Client::find($historyRecord->client_id); // Replace 'Client' with your Client model.

                $historyData[] = [
                    'previous_status' => $historyRecord->previous_status,
                    'new_status' => $historyRecord->new_status,
                    'timestamp' => $historyRecord->created_at,
                    'user' => $user,
                    'client' => $client,
                ];
            }

            // Pass the investment, property, and history data to the view.
            return view('admin.developro.modal.property-history', compact('investment', 'property', 'historyData'))->render();
        }
    }
}
