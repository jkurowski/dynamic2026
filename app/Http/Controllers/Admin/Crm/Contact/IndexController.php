<?php

namespace App\Http\Controllers\Admin\Crm\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// CMS
use App\Http\Requests\ContactBookFormRequest;
use App\Repositories\ContactRepository;
use Yajra\DataTables\DataTables;
use App\Models\Contact;

class IndexController extends Controller
{
    private $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.crm.contact.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.contact')->with('entry', Contact::make())->render();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactBookFormRequest $request)
    {
        if (request()->ajax()) {
            $this->repository->create($request->validated());
            return response()->json(['success' => true]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.contact', [
                'entry' => $contact
            ])->render();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactBookFormRequest $request, Contact $contact)
    {
        if (request()->ajax()) {
            $this->repository->update($request->validated(), $contact);
            return response()->json(['success' => true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        if (request()->ajax()) {
            $contact->delete();
            return response()->json(['success' => true], 201);
        }
    }


    public function datatable(Request $request)
    {
        $query = Contact::orderByDesc('created_at');

        if ($request->filled('minDate')) {
            $minDate = Carbon::parse($request->input('minDate'))->startOfDay();
            $query->where('created_at', '>=', $minDate);
        }

        if ($request->filled('maxDate')) {
            $maxDate = Carbon::parse($request->input('maxDate'))->endOfDay();
            $query->where('created_at', '<=', $maxDate);
        }

        if ($request->filled('category')) {
            $category = $request->input('category');
            $query->where('category_id', '<=', $category);
        }

        $list = $query->get();

        return Datatables::of($list)
            ->editColumn('created_at', function ($row){
                if($row->created_at){
                    $date = Carbon::parse($row->created_at)->format('Y-m-d');
                    $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();
                    return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
                } else {
                    return '-';
                }

            })
            ->editColumn('updated_at', function ($row){
                if($row->updated_at){
                    $date = Carbon::parse($row->updated_at)->format('Y-m-d');
                    $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();
                    return '<span>'.$date.'</span><div class="form-text mt-0">'.$diffForHumans.'</div>';
                } else {
                    return '-';
                }
            })
            ->addColumn('actions', function ($row) {
                return view('admin.crm.contact.tableActions', ['row' => $row]);
            })
            ->rawColumns(['created_at', 'updated_at', 'actions'])
            ->make();
    }
}
