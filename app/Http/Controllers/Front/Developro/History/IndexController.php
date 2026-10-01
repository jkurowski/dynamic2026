<?php

namespace App\Http\Controllers\Front\Developro\History;

use App\Http\Controllers\Controller;

//CMS
use App\Models\Property;

class IndexController extends Controller
{
    public function show($lang, Property $property)
    {
//        if (!request()->ajax()) {
//            abort(403, 'Tylko AJAX');
//        }

        return view('front.developro.investment_shared.pricehistory', compact('property'))->render();
    }

    public function others($lang, Property $property)
    {
//        if (!request()->ajax()) {
//            abort(403, 'Tylko AJAX');
//        }

        $query = Property::query()
            ->where('status', 1)
            ->where('investment_id', $property->investment_id);
            //->when($property->building_id, fn($q) => $q->where('building_id', $property->building_id));

        if ($property->visitor_related_type == 3) {
            $relatedIds = $property->visitorRelatedProperties()->pluck('related_property_id');
            $query->whereIn('id', $relatedIds);
        } else {
            // Default behavior for type 1 & 2
            $query->where('type', '!=', 1)
                ->whereNotIn('id', function ($query) {
                    $query->select('related_property_id')->from('property_property');
                });
        }

        $others = $query->get();

        return view('front.developro.investment_shared.others', compact('others'))->render();
    }

    public function other($lang, Property $property)
    {
//        if (!request()->ajax()) {
//            abort(403, 'Tylko AJAX');
//        }

        return view('front.developro.investment_shared.other', compact('property'))->render();
    }

    public function otherTable($lang, Property $property)
    {
//        if (!request()->ajax()) {
//            abort(403, 'Tylko AJAX');
//        }

        return view('front.developro.investment_shared.other-table', compact('property'))->render();
    }
}
