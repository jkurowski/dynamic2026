<?php

namespace App\Http\Controllers\Admin\Crm\Inbox;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// CMS
use App\Repositories\InboxRepository;
use Yajra\DataTables\DataTables;

use App\Models\ClientMessage;
use App\Models\Investment;
use App\Models\Greylist;
use App\Models\Floor;
use App\Models\Offer;
use App\Models\Event;

use App\Models\Client;
use App\Models\ClientFile;
use App\Models\ClientNote;
use App\Models\ClientRules;

class IndexController extends Controller
{
    private InboxRepository $repository;

    public function __construct(InboxRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('admin.crm.inbox.index', ['list' => $this->repository->all()]);
    }

    public function datatable(Request $request)
    {
        $query = ClientMessage::orderByDesc('created_at')
            ->whereUserId(0)
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

        if ($request->filled('rooms')) {
            $rooms = $request->input('rooms');
            $query->whereRaw("JSON_EXTRACT(arguments, '$.rooms') = ?", $rooms);
        }

        if ($request->filled('areaFrom')) {
            $areaFrom = $request->input('areaFrom');
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(arguments, '$.area')) + 0 >= ?", [$areaFrom]);
        }

        if ($request->filled('areaTo')) {
            $areaTo = $request->input('areaTo');
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(arguments, '$.area')) + 0 <= ?", [$areaTo]);
        }

        if ($request->filled('invest')) {
            $invest = $request->input('invest');
            $query->whereRaw("JSON_EXTRACT(arguments, '$.investment_id') = ?", $invest);
        }

        $list = $query->get();
        $investments = Investment::pluck('name', 'id')->all();
        $floors = Floor::pluck('number', 'id')->all();
        //dd($list);

        return Datatables::of($list)
            ->editColumn('name', function ($row) {
                return '<a href="' . route('admin.crm.clients.chat.show', $row->client->id) . '">' . $row->client->name . '</a>';
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
            ->addColumn('campaign', function ($clientMessage) {
                $arguments = json_decode($clientMessage->arguments, true);
                return $arguments['dp_campaign'] ?? null;
            })
            ->addColumn('investment_name', function ($clientMessage) use ($investments) {
                $arguments = json_decode($clientMessage->arguments, true);
                $investmentId = $arguments['investment_id'] ?? null;
                if ($investmentId && isset($investments[$investmentId])) {
                    return $investments[$investmentId];
                } else {
                    return '-';
                }
            })
            ->addColumn('floor', function ($clientMessage) use ($floors) {
                $arguments = json_decode($clientMessage->arguments, true);
                $floorId = $arguments['floor_id'] ?? null;
                if ($floorId && isset($floors[$floorId])) {
                    return $floors[$floorId];
                }
                return null;
            })
            ->addColumn('rooms', function ($clientMessage) {
                $arguments = json_decode($clientMessage->arguments, true);
                return $arguments['rooms'] ?? null;
            })
            ->addColumn('area', function ($clientMessage) {
                $arguments = json_decode($clientMessage->arguments, true);
                return $arguments['area'] ?? null;
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
                return view('admin.crm.inbox.tableActions', ['row' => $row]);
            })
            ->rawColumns(['name', 'created_at', 'actions'])
            ->make();
    }

    public function destroy(int $id)
    {
        //dd($id);
        $message = ClientMessage::find($id);

        //Remove all messages with client_id
        ClientMessage::where('client_id', $message->client_id)->delete();

        //Remove all files with client_id
        ClientFile::where('client_id', $message->client_id)->delete();

        //Remove all note with client_id
        ClientNote::where('client_id', $message->client_id)->delete();

        //Remove all rodo rules with client_id
        ClientRules::where('client_id', $message->client_id)->delete();

        //Remove all events with client_id
        Event::where('client_id', $message->client_id)->delete();

        //Remove all offers with client_id
        Offer::where('client_id', $message->client_id)->delete();

        //Remove client
        Client::where('id', $message->client_id)->delete();

        //Add IP to blacklist
        $greylist = new Greylist();
        $greylist->address = $message->ip;
        $greylist->reason = 'Spam';
        $greylist->save();

        return response()->json('Deleted');
    }
}
