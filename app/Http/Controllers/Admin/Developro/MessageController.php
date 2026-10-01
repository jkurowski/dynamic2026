<?php

namespace App\Http\Controllers\Admin\Developro;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;


class MessageController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:box-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:box-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:box-delete', [
//            'only' => ['destroy']
//        ]);

    }

    public function show(int $id)
    {
        $property = Property::find($id)->load('roomsNotifications');
        $investment = Investment::find($property->investment_id);
        $floor = Floor::find($property->floor_id);
        return view('admin.investment_property.message', [
            'property' => $property,
            'investment' => $investment,
            'floor' => $floor,
        ]);
    }
}
