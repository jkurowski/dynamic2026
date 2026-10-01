<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ChatSend;
use App\Models\Investment;
use App\Models\Page;
use App\Models\Property;
use App\Models\User;
use App\Notifications\ContactNotification;
use App\Notifications\PropertyNotification;
use App\Repositories\Client\ClientRepository;
use App\Services\Mail\NotificationRecipients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

/**
 * Formularz kontaktowy frontu - jeden dla wszystkich kontekstów (wzorzec: poligonowa).
 *
 * Kontekst przychodzi w polach ukrytych: `page` (nazwa strony), `investment_id`, `property_id`,
 * `back` ("Wyślij i wróć": po wysyłce powrót na stronę formularza zamiast na /kontakt).
 * Każde zgłoszenie zakłada / aktualizuje klienta, jego wiadomość i zgody RODO (ClientObserver).
 */
class ContactController extends Controller
{
    private const SENT_MESSAGE = 'Twoja wiadomość została wysłana. W najbliższym czasie skontaktujemy się z Państwem celem omówienia szczegółów!';

    private ClientRepository $repository;
    private NotificationRecipients $recipients;

    public function __construct(ClientRepository $repository, NotificationRecipients $recipients)
    {
        $this->repository = $repository;
        $this->recipients = $recipients;
    }

    public function index()
    {
        return view('front.contact.index', [
            'page' => Page::where('uri', 'kontakt')->first(),
        ]);
    }

    public function send(ContactFormRequest $request)
    {
        $property = $request->filled('property_id') ? Property::find($request->input('property_id')) : null;

        $this->store($request, $property);

        if ($request->boolean('back')) {
            return redirect()->back()->with('success', self::SENT_MESSAGE);
        }

        return redirect()->route('contact')->with('success', self::SENT_MESSAGE);
    }

    /**
     * Stara trasa formularza lokalu (POST /kontakt/{property}) - zostawiona dla zgodności,
     * nowe formularze wysyłają `property_id` na contact.send.
     */
    public function property(ContactFormRequest $request, $id)
    {
        $this->store($request, Property::findOrFail($id));

        return redirect()->back()->with('success', self::SENT_MESSAGE);
    }

    private function store(Request $request, ?Property $property = null): void
    {
        $investment = $property?->investment ?? ($request->filled('investment_id') ? Investment::find($request->input('investment_id')) : null);

        try {
            $client = $this->repository->createClient($request, $property);

            // Adresy biura inwestycji, a gdy ich brak - adres główny z ustawień
            $emailAddresses = $this->recipients->for($investment);

            if ($client && $emailAddresses) {
                Mail::to($emailAddresses)->send(new ChatSend($request, $client, $property));
            } elseif (!$emailAddresses) {
                Log::channel('email')->error('Formularz kontaktowy: brak poprawnych adresów odbiorców (page_email / office_emails).');
            }
        } catch (\Throwable $exception) {
            Log::channel('email')->error('Email sending failed', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        }

        $this->notifyPanel($request, $property, $investment);
    }

    /**
     * Powiadomienie w panelu: lokal -> lokal, inwestycja -> inwestycja, inaczej administratorzy.
     */
    private function notifyPanel(Request $request, ?Property $property, ?Investment $investment): void
    {
        try {
            if ($property) {
                $property->notify(new PropertyNotification($request, $property));
            } elseif ($investment) {
                $investment->notify(new ContactNotification($request, $investment));
            } else {
                Notification::send(User::role('Administrator')->get(), new ContactNotification($request));
            }
        } catch (\Throwable $exception) {
            Log::error('Formularz kontaktowy: nie udało się zapisać powiadomienia w panelu', [
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
