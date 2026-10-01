<?php

namespace App\Http\Controllers\Admin\Rodo;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Client;

class ClientController extends Controller
{

    public function index()
    {
        return response()->json(Client::latest()->get(['name', 'id', 'mail', 'phone']));
    }
}
