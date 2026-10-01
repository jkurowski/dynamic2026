<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Facebook\FacebookPageRepository;
use Spatie\Valuestore\Valuestore;

// CMS
use App\Http\Requests\FacebookFormRequest;

class FacebookController extends Controller
{
    private $repository;

    public function __construct(FacebookPageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {

        return view('admin.settings.facebook.index', ['list' => $this->repository->idDesc()]);
    }

    public function store(FacebookFormRequest $request)
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $settings->put($request->validated());
        return redirect(route('admin.settings.facebook.index'))->with('success', 'Ustawienia zostały zapisane');
    }
}
