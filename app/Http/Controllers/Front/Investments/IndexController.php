<?php

namespace App\Http\Controllers\Front\Investments;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        $investments = Investment::where('status', 1)->get(); // status 1 - active

        return view('front.investments.index', compact('investments'));
    }

    public function downtown(Request $request)
    {
        $investment = Investment::where('slug', 'downtown')->with('city')->first();


        return view('front.investments.downtown.index', compact('investment'));
    }

    public function downtownProperties(Request $request)
    {
        $investment = Investment::where('slug', 'downtown')->with('city')->first();

        return view('front.investments.downtown.properties', compact('investment'));
    }

    public function naSkraju(Request $request)
    {
        $investment = Investment::where('slug', 'na-skraju')->with('city')->first();

        return view('front.investments.na-skraju.index', compact('investment'));
    }

    public function naSkrajuProperties(Request $request)
    {
        $investment = Investment::where('slug', 'na-skraju')->with('city')->first();

        return view('front.investments.na-skraju.properties', compact('investment'));
    }

    public function slonimskaResidenceII(Request $request)
    {
        $investment = Investment::where('slug', 'slonimska-residence-ii')->with('city')->first();

        return view('front.investments.slonimska-residence-ii.index', compact('investment'));
    }

    public function slonimskaResidenceIIProperties(Request $request)
    {
        $investment = Investment::where('slug', 'slonimska-residence-ii')->with('city')->first();

        return view('front.investments.slonimska-residence-ii.properties', compact('investment'));
    }

    public function ogrodyAndersena(Request $request)
    {
        $investment = Investment::where('slug', 'ogrody-andersena')->with('city')->first();

        return view('front.investments.ogrody-andersena.index', compact('investment'));
    }

    public function ogrodyAndersenaProperties(Request $request)
    {
        $investment = Investment::where('slug', 'ogrody-andersena')->with('city')->first();

        return view('front.investments.ogrody-andersena.properties', compact('investment'));
    }
}
