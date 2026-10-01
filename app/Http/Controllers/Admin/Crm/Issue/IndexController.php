<?php

namespace App\Http\Controllers\Admin\Crm\Issue;

use App\Http\Controllers\Controller;
use App\Http\Requests\IssueFormRequest;
use App\Models\Department;
use App\Services\IssueService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

// CMS
use App\Repositories\IssueRepository;
use App\Models\Investment;
use App\Models\Issue;

class IndexController extends Controller
{
    private IssueRepository $repository;
    private IssueService $service;

    public function __construct(IssueRepository $repository, IssueService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.crm.issue.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.issue', [
                'investments' => Investment::select(['id', 'name'])->get(),
                'departments' => Department::all(),
            ])->with('entry', Issue::make())->render();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueFormRequest $request)
    {
        if (request()->ajax()) {
            $this->repository->create($request->validated());
            return response()->json(['success' => true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        $notes = $issue->notes;

        return view('admin.crm.issue.show', compact('issue', 'notes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.issue', [
                'investments' => Investment::select(['id', 'name'])->get(),
                'departments' => Department::all(),
                'entry' => $issue
            ])->render();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssueFormRequest $request, Issue $issue)
    {
        if (request()->ajax()) {
            $this->repository->update($request->validated(), $issue);
            return response()->json(['success' => true]);
        }
    }

    public function updateStatus(Issue $issue, Request $request){
        if ($request->ajax()) {
            $newStatus = $request->input('status');
            $oldHistory = json_decode($issue->history, true) ?: [];

            // Add the new change to history
            $oldHistory[] = [
                'datetime' => now(),
                'old_status' => $issue->status,
                'new_status' => $newStatus,
                'user_id' => auth()->id()
            ];

            $issue->update(['status' => $newStatus, 'history' => json_encode($oldHistory)]);

            return response()->json(['success' => true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        if (request()->ajax()) {
            $issue->delete();
            return response()->json(['success' => true], 201);
        }
    }

    public function datatable(Request $request)
    {
        $query = Issue::orderByDesc('created_at');

        if ($request->filled('minDate')) {
            $minDate = Carbon::parse($request->input('minDate'))->startOfDay();
            $query->where('created_at', '>=', $minDate);
        }

        if ($request->filled('maxDate')) {
            $maxDate = Carbon::parse($request->input('maxDate'))->endOfDay();
            $query->where('created_at', '<=', $maxDate);
        }

        if ($request->filled('investment')) {
            $investment = $request->input('investment');
            $query->where('investment_id', '<=', $investment);
        }

        $list = $query->with(['user', 'client', 'investment', 'property', 'department'])->get();

        return Datatables::of($list)
            ->editColumn('contact', function ($row){
                if($row->client)
                {
                    return $row->client->name.' '.$row->client->surname;
                }
            })
            ->editColumn('user', function ($row){
                if($row->user)
                {
                    return $row->user->name.' '.$row->user->surname;
                }
            })
            ->editColumn('investment', function ($row){
                if($row->investment)
                {
                    return $row->investment->name;
                }
            })
            ->editColumn('property', function ($row){
                if($row->property)
                {
                    return $row->property->name;
                }
            })
            ->editColumn('department', function ($row){
                if($row->department)
                {
                    return $row->department->name;
                }
            })
            ->editColumn('status', function ($row){
                if($row->status)
                {
                    return '<span class="badge issue-status-'.$row->status.'">'.issueStatus($row->status).'</span>';
                }
            })
            ->editColumn('created_at', function ($row){
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();
                return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
            })
            ->editColumn('updated_at', function ($row){
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();
                return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
            })
            ->addColumn('actions', function ($row) {
                return view('admin.crm.issue.tableActions', ['row' => $row]);
            })
            ->rawColumns([
                'status',
                'created_at',
                'updated_at',
                'actions'
            ])
            ->make();
    }
}
