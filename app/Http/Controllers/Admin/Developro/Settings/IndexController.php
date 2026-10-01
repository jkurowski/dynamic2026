<?php

namespace App\Http\Controllers\Admin\Developro\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('admin.developro.settings.index');
    }
}
