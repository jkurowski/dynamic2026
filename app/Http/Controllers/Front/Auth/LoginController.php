<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\VerificationCode;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('front.auth.login');
    }

    public function requestCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:clients,mail'
        ]);

        $client = Client::where('mail', $request->email)->first();

        if ($client->status != 1) {
            return back()->withErrors(['email' => 'Klient nie jest aktywny']);
        }

        $code = rand(100000, 999999);
        $expires_at = Carbon::now()->addMinutes(10);

        VerificationCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => $expires_at
        ]);

        Mail::raw("Oto Twój kod weryfikacyjny: $code", function ($message) use ($request) {
            $message->to($request->email)->subject('DeveloPro - Kod weryfikacyjny');
        });

        session()->forget(['status', 'code', 'email']);

        return back()->with(['status' => 'Wiadomość z 6-cyfrowym kodem została wysłana na podany adres e-mail.<br><br><b>Kod ważny jest przez 10 min.</b>', 'code' => 1, 'mail' => $request->email]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:clients,mail',
            'code' => 'required|numeric|digits:6'
        ]);

        $verificationCode = VerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$verificationCode || $verificationCode->isExpired()) {
            return back()->withErrors(['code' => 'Wpisany kod jest zły.']);
        }

        $client = Client::where('mail', $request->email)->first();

        // Log the client in
        Auth::guard('client')->login($client);

        // Optionally, delete the used verification code
        $verificationCode->delete();

        return redirect()->route('front.client.area');
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/client/login');
    }
}
