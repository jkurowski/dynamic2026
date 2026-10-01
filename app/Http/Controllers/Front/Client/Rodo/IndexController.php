<?php

namespace App\Http\Controllers\Front\Client\Rodo;

use App\Http\Controllers\Controller;
use App\Models\ClientRules;
use App\Models\RodoRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        $client = Auth::guard('client')->user();

        if ($client) {

            $list = ClientRules::where('client_id', $client->id)
                //->whereIn('rule_id', [1, 2, 3])
                ->latest('created_at')
                ->get()
                ->unique('rule_id');
            return view('front.auth.client.rodo.index', compact('client', 'list'));

        }

        return redirect()->route('front.login')->with('error', 'Musisz być zalogowany');
    }

    public function editRule(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $client = Auth::guard('client')->user();

        if ($client) {

            $rule = ClientRules::where('client_id', $client->id)->where('id', $id)->first();

            $prev_status = $rule->status;

            if ($rule && $rule->status == 1 && $status == 2) {
                // Update status to 2 and set canceled_at timestamp
                $rule->status = 2;
                $rule->canceled_at = now(); // Assuming you have 'canceled_at' as a timestamp field
                $rule->save();

                // Log the activity
                activity()
                    ->useLog('Regułki klienta')
                    ->causedBy($client)
                    ->performedOn($rule)
                    ->withProperties(['previous_status' => $prev_status, 'new_status' => $rule->status])
                    ->log('updated');
            }

            if ($rule && $rule->status == 2 && $status == 1) {
                $rodo_rule = RodoRules::whereId($rule->rule_id)->first();

                $rule = ClientRules::create([
                    'client_id' => $client->id,
                    'rule_id' => $rodo_rule->id,
                    'ip' => $request->ip(),
                    'source' => $request->headers->get('referer'),
                    'duration' => strtotime("+".$rodo_rule->time." months", strtotime(date("y-m-d"))),
                    'months' => $rodo_rule->time,
                    'status' => 1,
                    'text' => strip_tags($rodo_rule->text)
                ]);

                // Log the activity
                activity()
                    ->useLog('Regułki klienta')
                    ->causedBy($client)
                    ->performedOn($rule)
                    ->withProperties(['previous_status' => $prev_status, 'new_status' => 1])
                    ->log('created');
            }

        }

        // Example: Return a response
        return response()->json([
            'id' => $id,
            'prev_status' => $prev_status,
            'new_status' => $rule->status,
            'message' => 'Received id and status successfully'
        ]);
    }
}
