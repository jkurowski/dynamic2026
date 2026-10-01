<?php

namespace App\Http\Controllers\Front\Client\Chat;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\ClientMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        return view('front.auth.client.chat.index', [
            'client' => $client,
            'chat' => ClientMessage::where('client_id', '=', $client->id)->latest()->get()]);
    }

    public function fetchMessages($clientId)
    {
        return ClientMessage::where('client_id', $clientId)
            ->with('user')
            ->oldest()
            ->get()
            ->map(function ($message) {
                $message->created_at_diff = $message->created_at->diffForHumans();
                $message->created_date = Carbon::parse($message->created_at)->format('Y-m-d H:i');
                return $message;
            });
    }

    public function sendMessage(Request $request)
    {
        ClientMessage::create([
            'client_id' => Auth::guard('client')->id(),
            'user_id' => 0,
            'message' => $request->message,
            'source' => 'Chat',
            'ip' => $request->ip()
        ]);

        $message = $request->message;
        // Log before broadcasting
        Log::info('Broadcasting MessageSent event', ['message' => $message]);

        try {
            broadcast(new MessageSent($message, 0))->toOthers();
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
