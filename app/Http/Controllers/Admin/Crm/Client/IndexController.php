<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\ClientFormRequest;
use App\Http\Requests\ClientNoteFormRequest;
use App\Mail\ChatSend;
use App\Repositories\Client\ClientRepository;
use App\Models\Client;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{

    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    function index()
    {
        return view('admin.crm.client.index');
    }

    public function show(Client $client)
    {
        return view('admin.crm.client.show.index', [
            'client' => $client
        ]);
    }

    public function datatable()
    {
        return $this->repository->getDataTable();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.new-client', [
            ])->with('entry', Client::make())->render();
        }
    }

    public function update(ClientFormRequest $request, Client $client)
    {
        if (request()->ajax()) {
            dd("Its for client update ajax");
        } else {

            $this->repository->update($request->validated(), $client);
            return redirect(route('admin.crm.clients.show', $client))->with('success', 'Klient zaktualizowany');
        }
    }

    public function store(ClientFormRequest $request){
        if (request()->ajax()) {
            try {
                $client = $this->repository->createClient($request);
                //Mail::to(settings()->get("page_email"))->send(new ChatSend($request, $client));
                return response()->json(['success' => true]);

            } catch (\Throwable $exception) {

            }
        }
    }
}
