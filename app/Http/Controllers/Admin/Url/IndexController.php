<?php

namespace App\Http\Controllers\Admin\Url;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\UrlFormRequest;
use App\Models\Url;
use App\Models\Page;
use App\Repositories\UrlRepository;

class IndexController extends Controller
{
    private $repository;

    public function __construct(UrlRepository $repository)
    {
//        $this->middleware('permission:page-list|page-create|page-edit|page-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:page-create', ['only' => ['create','store']]);
//        $this->middleware('permission:page-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:page-delete', ['only' => ['destroy']]);

        $this->repository = $repository;
    }

    public function create()
    {
        return view('admin.page.form_link', [
            'selectMenu' => Page::pluck('title', 'id')->prepend('Brak podstrony', 0),
            'cardTitle' => 'Dodaj adres URL',
            'backButton' => route('admin.page.index')
        ])->with('entry', Url::make());
    }

    public function store(UrlFormRequest $request)
    {
        $this->repository->create($request->validated());
        return redirect(route('admin.page.index'))->with('success', 'Link dodany');
    }

    public function edit(Url $url)
    {
        return view('admin.page.form_link', [
            'entry' => $url,
            'selectMenu' => Page::where('id', '!=', $url->id)->pluck('title', 'id')->prepend('Brak podstrony', 0),
            'cardTitle' => 'Edytuj adres URL',
            'backButton' => route('admin.page.index')
        ]);
    }

    public function update(UrlFormRequest $request, int $id)
    {
        $page = $this->repository->find($id);
        $this->repository->update($request->validated(), $page);

        return redirect(route('admin.page.index'))->with('success', 'Link zaktualizowany');
    }
}
