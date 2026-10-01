<?php

namespace App\Http\Controllers\Admin\Crm\AssignLeads;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Valuestore\Valuestore;

class IndexController extends Controller
{
    public function index()
    {
        $investments = Investment::all()->map(function ($item) {
            return [
                'value' => $item->id,
                'label' => $item->name,
            ];
        })->toArray();
        $initialValues = settings()->get('assign_leads');


        return view('admin.crm.assign-leads.index', compact('investments', 'initialValues'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'condition' => 'required|string',
            'rules' => 'required|array',
            'rules.*.field' => 'required|string',
            'rules.*.type' => 'required|string',
            'rules.*.input' => 'required|string',
            'rules.*.operator' => 'required|string',
            'rules.*.value' => 'required|string',
            'rules.*.investment_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => "Sprawdź czy któreś z pól nie zostało puste lub nie ma domyślnej wartości.", 'errors' => $validator->errors()], 422);
        }


        $settings = Valuestore::make(storage_path('app/settings.json'));
        $settings->put('assign_leads', $validator->validated());




        Log::info('Query Builder Data:', $request->all());

        return response()->json(['success' => true, 'message' => 'Zapisano', 'data' => $request->all()]);
    }
}
