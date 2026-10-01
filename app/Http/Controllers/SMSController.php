<?php

namespace App\Http\Controllers;

use App\Services\SMS\SMSService;
use Illuminate\Http\Request;

// Do testowania integracji z API SMSAPI
class SMSController extends Controller
{
    protected $smsService;
    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index() {
        return csrf_token();
    }
    public function send(Request $request)
    {
        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'message' => 'required|string|max:255',
        ]);

        $response = $this->smsService->sendSMS($validated['to'], $validated['message']);
        return response()->json($response);
    }
}
