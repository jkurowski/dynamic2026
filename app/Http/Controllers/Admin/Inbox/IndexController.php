<?php

namespace App\Http\Controllers\Admin\Inbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Notification;

class IndexController extends Controller
{
    public function index()
    {
        return view('admin.inbox.index', ['list' => Notification::orderBy('created_at', 'desc')->get()]);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('admin.inbox.show', ['message' => Notification::whereId($id)->get()]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
