<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Models\City;
use App\Models\Inline;
use App\Models\Investment;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;

//CMS
use App\Mail\ChatSend;
use App\Models\Page;
use App\Models\Property;
use App\Models\RodoRules;
use App\Notifications\PropertyNotification;
use App\Repositories\Client\ClientRepository;

class ContactController extends Controller
{

    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    function index()
    {
        $page = Page::where('id', 1)->first();
        $inline = Inline::whereIdPlace(2)->get()->toArray();

        return view('front.contact.index', [
            'rules' => RodoRules::orderBy('sort')->whereActive(1)->get(),
            'page' => $page,
            'array' => $inline
        ]);
    }

    function send(ContactFormRequest $request)
    {
        try {
            $client = $this->repository->createClient($request);

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

// Initialize office emails as empty
            $officeEmails = [];

            if ($request->has('investment_id')) {
                $investment = Investment::find($request->input('investment_id'));

                if ($investment && !is_array($investment->office_emails)) {
                    $officeEmailsData = json_decode($investment->office_emails, true);
                } else {
                    $officeEmailsData = $investment->office_emails ?? [];
                }

                $officeEmails = collect($officeEmailsData)
                    ->map(fn($item) => isset($item['value']) ? trim($item['value']) : null)
                    ->filter()
                    ->values()
                    ->toArray();
            }

// ✅ Merge both email arrays and remove duplicates
            $allEmails = array_unique(array_merge($emails, $officeEmails));

// ✅ Send mail if we have any recipients
            if (!empty($allEmails)) {
                Mail::to($allEmails)->send(new ChatSend($request, $client));
            } else {
                Log::error('No valid emails found in settings()->get("page_email")');
            }

            // Clear cookies if mail is sent successfully
            $cookie_name = 'dp_';
            foreach ($_COOKIE as $name => $value) {
                if (stripos($name, $cookie_name) === 0) {
                    Cookie::queue(Cookie::forget($name));
                }
            }
        } catch (\Throwable $exception) {
            Log::channel('email')->error('Email sending failed', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        return $request->has('back') && $request->get('back') == true
            ? redirect()->back()->with(
                'success',
                'Twoja wiadomość została wysłana. W najbliższym czasie skontaktujemy się z Państwem celem omówienia szczegółów!'
            )
            : redirect()->route('contact')->with(
                'success',
                'Twoja wiadomość została wysłana. W najbliższym czasie skontaktujemy się z Państwem celem omówienia szczegółów!'
            );
    }

    function property(ContactFormRequest $request, $lang, $id)
    {
        try {
            $property = Property::find($id);

            $client = $this->repository->createClient($request, $property);

            $property->notify(new PropertyNotification($request, $property));

            $emailsData = $property->investment->office_emails;

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
                Mail::to($emails)->send(new ChatSend($request, $client, $property));
            } else {
                Log::error('No valid emails found in settings()->get("page_email")');
            }

            // Clear cookies if mail is sent successfully
            $cookie_name = 'dp_';
            foreach ($_COOKIE as $name => $value) {
                if (stripos($name, $cookie_name) === 0) {
                    Cookie::queue(Cookie::forget($name));
                }
            }
        } catch (\Throwable $exception) {
            Log::channel('email')->error('Email sending failed', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }

        return redirect()->back()->with(
            'success',
            'Twoja wiadomość została wysłana. W najbliższym czasie skontaktujemy się z Państwem celem omówienia szczegółów!'
        );
    }

    public function showToken() {
        echo csrf_token();
    }
}
