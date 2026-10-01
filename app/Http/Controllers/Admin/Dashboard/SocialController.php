<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialFormRequest;
use App\Services\OpenGraphService;
use Spatie\Valuestore\Valuestore;

class SocialController extends Controller
{
    private $service;

    public function __construct(OpenGraphService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.settings.social.index');
    }

    public function store(SocialFormRequest $request)
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $settings->put($request->except(['_token', 'submit']));

        if ($request->hasFile('og_file')) {
            $this->service->upload($request->file('og_file'));
        }

        return redirect(route('admin.settings.social.index'))->with('success', 'Ustawienia zostały zapisane');
    }
}
