<?php

namespace App\Http\Controllers\Admin\Crm\Funnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// CMS
use App\Models\ClientStatusHistory;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve query parameters
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Build the query with optional date filtering
        $query = ClientStatusHistory::select('new_status', DB::raw('count(*) as count'))
            ->groupBy('new_status')
            ->orderBy('count', 'desc');

        if ($dateFrom) {
            $query->where('changed_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('changed_at', '<=', $dateTo);
        }

        $counts = $query->get();

        return view('admin.crm.funnel.index', compact('counts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
