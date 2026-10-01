<?php

namespace App\Http\Controllers\Front\Client\Special;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        $client = Auth::guard('client')->user();

        if ($client) {

            $properties = Property::where('highlighted', '=', 1)->get();


            return view('front.auth.client.special.index', compact('client', 'properties'));

        }

        return redirect()->route('front.login')->with('error', 'Musisz być zalogowany');
    }
}
