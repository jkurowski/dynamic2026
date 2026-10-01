<?php

namespace App\Http\Controllers\Front\Clipboard;

use App\Http\Controllers\Controller;

use App\Mail\ChatSend;
use App\Mail\ClipboardSend;
use App\Models\RodoSettings;
use App\Notifications\ContactNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

use Cookie;

// CMS
use App\Repositories\Client\ClientRepository;
use App\Http\Requests\ClipboardFormRequest;
use App\Models\Page;
use App\Models\Property;
use App\Models\Recipient;
use App\Models\RodoRules;

class IndexController extends Controller
{
    private ClientRepository $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $page = Page::find(10);
        $items = session('clipboard.items');
        //$items = [867, 846, 845, 878, 867];

        $properties = collect();

        if (!empty($items)) {
            $ids = array_map('intval', $items);
            $properties = Property::whereIn('id', $ids)->get();
        }

        return view('front.clipboard.index', [
            'page' => $page,
            'properties' => $properties,
            'obligation' => RodoSettings::find(1),
            'rules' => RodoRules::orderBy('sort')->whereActive(1)->get()
        ]);
    }

    public function send(ClipboardFormRequest $request, Recipient $recipient)
    {
        //$recipient->notify(new ContactNotification($request));

        $items = session('clipboard.items');
        $properties = collect();

        if (!empty($items)) {
            $ids = array_map('intval', $items);
            $properties = Property::whereIn('id', $ids)->get();
        }

        $emailsData = settings()->get("page_email");

        if (!is_array($emailsData)) {
            $emailsData = json_decode($emailsData, true); // Decode JSON if necessary
        }

        $emails = collect($emailsData)
            ->map(function ($item) {
                return isset($item['value']) ? trim($item['value']) : null; // Ensure 'value' exists and is not null
            })
            ->filter() // Remove null or empty values
            ->toArray();

        if (!empty($emails)) {
            Mail::to($emails)->send(new ClipboardSend($request, $properties));
        } else {
            Log::error('No valid emails found in settings()->get("page_email")');
        }

//        if( count(Mail::failures()) == 0 ) {
//            $cookie_name = 'dp_';
//            foreach ($_COOKIE as $name => $value) {
//                if (stripos($name, $cookie_name) === 0) {
//                    Cookie::queue(
//                        Cookie::forget($name)
//                    );
//                }
//            }
//        }

        return redirect()->back()->with(
            'success',
            'Twoja wiadomość została wysłana.'
        );
    }

    public function store(Request $request)
    {
        $id = $request->get('id');
        $items = $request->session()->get('clipboard.items', []);

        if (!in_array($id, $items)) {
            $items[] = $id;
            $request->session()->put('clipboard.items', array_unique($items));
        }

        return response()->json(['message' => '<div class="alert alert-success border-0 mt-3">Mieszkanie dodane do ulubionych</div>', 'count' => count($items), 'raw' => 'Mieszkanie dodane do ulubionych']);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $items = $request->session()->get('clipboard.items', []);

        // Find the index of the item in the clipboard
        $index = array_search($id, $items);

        if ($index !== false) {
            // Remove the item from the clipboard
            $removed = $request->session()->pull("clipboard.items.$index");

            // Check if the item was actually removed
            if ($removed !== null) {
                return response()->json([
                    'message' => '<div class="alert alert-success border-0 mt-3">Mieszkanie usunięte z ulubionych</div>',
                    'count' => count($items) - 1
                ]);
            }
        }

        return response()->json([
            'message' => '<div class="danger alert-danger border-0 mt-3">Wybrane mieszkanie nie istnieje w ulubionych</div>',
            'count' => count($items)
        ]);
    }
}
