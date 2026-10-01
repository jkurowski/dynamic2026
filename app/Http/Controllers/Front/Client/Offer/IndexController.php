<?php

namespace App\Http\Controllers\Front\Client\Offer;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        $client = Auth::guard('client')->user();

        if ($client) {

            $offers = Offer::with('user')
                ->where('client_id', $client->id)
                ->get();

            return view('front.auth.client.offer.index', compact('offers', 'client'));

        }

        return redirect()->route('front.login')->with('error', 'Musisz być zalogowany');
    }
}
