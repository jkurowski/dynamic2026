<?php

namespace App\Http\Controllers\Front\Developro\Search;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $investment = $request->input('investment');
        $i = Investment::find($investment);

        if (!$i) {
            // Handle the case where the investment is not found
            return redirect()->back()->withErrors(['error' => 'Investment not found']);
        }

        // Collect query parameters
        $queryParams = [
            'area' => $request->input('area'),
            'rooms' => $request->input('rooms'),
            'floor' => $request->input('floor'),
        ];

        // Build the redirect URL
        $url = route('front.developro.show', ['slug' => $i->slug]) . '?' . http_build_query($queryParams) . '#properties';

        // Redirect to the constructed URL
        return redirect($url);
    }
}
