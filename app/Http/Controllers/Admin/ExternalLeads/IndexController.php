<?php

namespace App\Http\Controllers\Admin\ExternalLeads;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExternalLeadFormRequest;
use App\Models\Client;
use App\Models\ClientMessage;
use App\Models\Floor;
use App\Models\Investment;
use App\Repositories\Client\ClientRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class IndexController extends Controller
{
    private $repository;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->repository = $clientRepository;
    }

    public function index()
    {

        // // get only external leads
        $list = ClientMessage::where('arguments', 'like', '%"is_external\\":true%')->get();

        // Unique values of source column
        $select_options = $list->pluck('source')->unique();

        $investments = ClientMessage::where('arguments', 'like', '%"investment_name\\":%')->get();



        $result = $investments->pluck('arguments')->map(function ($item) {
            $item = json_decode($item, true);
            return $item['investment_name'];
        })->unique();


        return view('admin.external_leads.index', ['investments' => $result, 'select_options' => $select_options, 'select_users' => $this->getUsersWithRoleForTemplate('Opiekun inwestycji'), 'select_investments' => $this->getInvestmentsForTemplate()]);
    }

    private function getUsersWithRoleForTemplate(string $role)
    {
        $role = Role::findByName($role);
        $users = $role->users->pluck('name', 'id')->toArray();
        $result = ['' => 'Brak'] + $users;
        return $result;
    }

    private function getInvestmentsForTemplate()
    {
        $investments = Investment::pluck('name', 'id')->toArray();
        $result = ['' => 'Brak'] + $investments;
        return $result;
    }


    public function datatable(Request $request)
    {



        $query = ClientMessage::where('arguments', 'like', '%"is_external\\":true%')
            ->with([
                'client:id,name,mail,phone'
            ]);




        if ($request->filled('minDate')) {
            $minDate = Carbon::parse($request->input('minDate'))->startOfDay();
            $query->where('created_at', '>=', $minDate);
        }

        if ($request->filled('maxDate')) {
            $maxDate = Carbon::parse($request->input('maxDate'))->endOfDay();
            $query->where('created_at', '<=', $maxDate);
        }

        if ($request->filled('invest')) {
            $invest = $request->input('invest');
            $query->where('arguments', 'like', "%\"investment_name\":\"{$invest}\"%");
        }
        if ($request->filled('source')) {
            $source = $request->input('source');
            $query->where('source', $source);
        }

        if ($request->filled('user_assigned')) {


            // 1 - no, 2 - yes

            if ($request->input('user_assigned') == '1') {
                // Show rows where `assigned_user_id` is either not present or is `null`
                $query->where(function ($query) {
                    $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(arguments, '$.assigned_user_id')) is null")
                        ->orWhereRaw("JSON_UNQUOTE(JSON_TYPE(JSON_EXTRACT(arguments, '$.assigned_user_id'))) = 'NULL'");
                });
            } else {
                // Show rows where `assigned_user_id` is present and not null
                $query->whereRaw("JSON_UNQUOTE(JSON_TYPE(JSON_EXTRACT(arguments, '$.assigned_user_id'))) != 'NULL'")
                    ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(arguments, '$.assigned_user_id')) is not null");
            }
        }

        if ($request->filled('investment_assigned')) {
            // 1 - no, 2 - yes
            if ($request->input('investment_assigned') == 1) {
                // Show rows where `assigned_investment_id` is either not present or is `null`
                $query->where(function ($query) {
                    $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(arguments, '$.assigned_investment_id')) is null")
                        ->orWhereRaw("JSON_UNQUOTE(JSON_TYPE(JSON_EXTRACT(arguments, '$.assigned_investment_id'))) = 'NULL'");
                });
            } else {
                // Show rows where `assigned_investment_id` is present and not null
                $query->whereRaw("JSON_UNQUOTE(JSON_TYPE(JSON_EXTRACT(arguments, '$.assigned_investment_id'))) != 'NULL'")
                    ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(arguments, '$.assigned_investment_id')) is not null");
            }
        }
        $list = $query->orderBy('updated_at', 'desc')->get();
        $investments = Investment::pluck('name', 'id')->all();
        $floors = Floor::pluck('number', 'id')->all();


        return Datatables::of($list)
            ->editColumn('name', function ($row) {
                return '<a href="' . route('admin.crm.clients.chat.show', $row->client->id) . '">' . $row->client->name . ' ' . $row->client->lastname . '</a>';
            })
            ->editColumn('mail', function ($row) {
                return $row->client->mail;
            })
            ->editColumn('phone', function ($row) {
                return $row->client->phone;
            })

            ->addColumn('referrer', function ($clientMessage) {
                $arguments = json_decode($clientMessage->arguments, true);
                return $arguments['dp_source'] ?? null;
            })

            ->addColumn('invest', function ($clientMessage) use ($investments) {
                $arguments = json_decode($clientMessage->arguments, true);
                $investment_name = $arguments['investment_name'] ?? null;
                return $investment_name;
            })
            ->addColumn('property', function ($clientMessage) {
                $arguments = json_decode($clientMessage->arguments, true);
                $property = $arguments['property_name'] ?? null;
                return $property;
            })


            ->editColumn('created_at', function ($row) {
                $date = Carbon::parse($row->created_at)->format('Y-m-d');
                $now = Carbon::now()->format('Y-m-d');
                $diffForHumans = Carbon::createFromFormat('Y-m-d', $date)->diffForHumans();

                if ($date >= $now) {
                    return '<span>' . $date . '</span>';
                } else {
                    return '<span>' . $date . '</span><div class="form-text mt-0">' . $diffForHumans . '</div>';
                }
            })
            ->addColumn('actions', function ($row) {
                return view('admin.external_leads.actions', ['row' => $row]);
            })
            ->rawColumns(['name', 'created_at', 'actions'])
            ->make();
    }


    public function assign(Request $request)
    {


        $validated = $request->validate([
            'assign_message_id' => 'required|string',
            'assign_investment_id' => 'string|nullable',
            'assign_user_id' => 'string|nullable',
        ]);


        $client_message = ClientMessage::findOrFail((int)$validated['assign_message_id']);

        $decoded_arguments = json_decode($client_message->arguments, true);

        $decoded_arguments['assigned_investment_id'] = $validated['assign_investment_id'];
        $decoded_arguments['assigned_user_id'] = $validated['assign_user_id'];

        $client_message->arguments = json_encode($decoded_arguments);

        $client_message->save();

        return redirect()->route('admin.externalLeads.index')->with(['success' => 'Przypisano']);
    }

    public function getSelectedOptions(Request $request)
    {

        $validated = $request->validate([
            'message_id' => 'required|string',
        ]);

        $client_message = ClientMessage::findOrFail((int)$validated['message_id']);

        $decoded_arguments = json_decode($client_message->arguments, true);

        $filtered = [
            'selected_investment' => $decoded_arguments['assigned_investment_id'] ?? '',
            'selected_user' => $decoded_arguments['assigned_user_id'] ?? '',
        ];

        return response()->json($filtered);
    }
}
