<?php

namespace App\Http\Controllers\Admin\Developro\Property;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Investment $investment, Property $property)
    {
        $floor = Floor::find($property->floor_id);

        return view('admin.developro.investment_property.message', [
            'property' => $property->load('roomsNotifications'),
            'investment' => $investment,
            'floor' => $floor,
        ]);
    }
}
