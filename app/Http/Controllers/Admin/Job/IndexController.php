<?php

namespace App\Http\Controllers\Admin\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//CMS
use App\Repositories\JobRepository;
use App\Http\Requests\JobFormRequest;
use App\Models\Job;

class IndexController extends Controller
{
    private JobRepository $repository;

    public function __construct(JobRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.job.index', ['list' => $this->repository->allSort('ASC')]);
    }

    public function create()
    {
        return view('admin.job.form', [
            'cardTitle' => 'Dodaj wpis',
            'backButton' => route('admin.job.index')
        ])->with('entry', Job::make());
    }

    public function store(JobFormRequest $request)
    {
        $this->repository->create($request->validated());
        return redirect(route('admin.job.index'))->with('success', 'Nowy wpis dodany');
    }

    public function edit(Job $job)
    {
        if(request()->get('lang')) {
            app()->setLocale(request()->get('lang'));
        }

        return view('admin.job.form', [
            'entry' => $this->repository->find($job->id),
            'cardTitle' => 'Edytuj wpis',
            'backButton' => route('admin.job.index')
        ]);
    }

    public function update(JobFormRequest $request, Job $job)
    {
        if(request()->get('lang')) {
            app()->setLocale(request()->get('lang'));
        }

        $this->repository->update($request->validated(), $job);
        return redirect(route('admin.job.index'))->with('success', 'Wpis zaktualizowany');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function sort(Request $request)
    {
        $this->repository->updateOrder($request->get('recordsArray'));
    }
}
