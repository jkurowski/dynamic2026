<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Mail\ChatSend;
use App\Models\ClientMessage;
use Illuminate\Http\Request;

//CMS
use App\Repositories\Chat\ChatRepository;
use App\Http\Requests\ChatFormRequest;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    private $repository;

    public function __construct(ChatRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show(Client $client)
    {
        return view('admin.crm.client.chat.index', [
            'client' => $client,
            'chat' => ClientMessage::where('client_id', '=', $client->id)->where('user_id', '=', 0)->with('answers')->latest()->get()
        ]);
    }

    public function form(Request $request, Client $client)
    {
        if (request()->ajax()) {
            return view('admin.crm.modal.chat-form', ['client' => $client, 'id' => $request->input('id')])->render();
        }
    }

    public function mark(Request $request, Client $client)
    {
        if (request()->ajax()) {
            return $this->repository->markMessage($request, $client);
        }
    }

    public function create(ChatFormRequest $request, Client $client)
    {
        Mail::to($client->mail)->send(new ChatSend($request, $client));
        if( count(Mail::failures()) > 0 ) {
            return ['success' => false];
        } else {
            $this->repository->storeAnswer($request, $client);
            return ['success' => true];
        }
    }

    public function fetchMessages($clientId)
    {
        return ClientMessage::where('client_id', $clientId)->with('user')->oldest()->get();
    }

    public function sendMessage(Request $request)
    {
        ClientMessage::create([
            'client_id' => $request->client_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'source' => 'Chat',
            'ip' => $request->ip()
        ]);

        //broadcast(new MessageSent($message))->toOthers();

        // Your logic to handle the request and prepare the message
        $message = $request->message;

        // Log before broadcasting
        Log::info('Broadcasting MessageSent event', ['message' => $message]);

        try {
            broadcast(new MessageSent($message, 129))->toOthers();
            Log::info('MessageSent event broadcasted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to broadcast MessageSent event', [
                'message' => $message,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
