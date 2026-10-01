<?php

namespace App\Notifications\Concerns;

use App\Models\Investment;
use App\Models\Property;
use Illuminate\Http\Request;

/**
 * Wspólna treść powiadomienia o zgłoszeniu z formularza kontaktowego.
 *
 * Formularz jest jeden i uniwersalny (`<x-contact-form>`), ale zgłoszenie może przyjść
 * w trzech odmianach: z karty lokalu, z podstrony inwestycji albo ze strony ogólnej.
 * Różni je wyłącznie kontekst, więc kształt wpisu w dzwonku trzymamy w jednym miejscu —
 * `PropertyNotification` i `ContactNotification` różnią się już tylko tym, do kogo są
 * adresowane.
 *
 * **Klucze `message` i `submessage` są OBOWIĄZKOWE** — to jedyne, które renderuje
 * `admin/partials/notification-bell.blade.php` i `public/js/panel-notifications.js`.
 * Do 2026-08-04 `PropertyNotification` ich nie zapisywało, więc zgłoszenie z formularza
 * dodawało do dzwonka pozycję PUSTĄ: klikalny wiersz bez ani jednego słowa treści.
 * Sprawdzone na prawdziwym wierszu w bazie dev.
 *
 * **Klucz `investment_id` decyduje o widoczności** — `NotificationBell` na jego podstawie
 * pokazuje zgłoszenie sprzedawcom przypisanym do inwestycji. Bez niego powiadomienie widzi
 * wyłącznie administrator.
 */
trait DescribesFormSubmission
{
    /**
     * Zawartość kolumny `notifications.data`.
     */
    private function submissionPayload(Request $request, ?Property $property, ?Investment $investment): array
    {
        return [
            'message' => 'Nowe zapytanie z formularza',
            'submessage' => $this->submissionSummary($request, $property, $investment),

            // Pole ukryte formularza nazywa się `page`. Wcześniej czytaliśmy tu
            // `form_page` — nazwę z formularza poprzedniej generacji — więc nazwa strony
            // w powiadomieniu była ZAWSZE pusta. Stara nazwa zostaje jako zapasowa,
            // bo używa jej jeszcze widok podglądu iframe.
            'page_name' => $request->input('page') ?: $request->input('form_page'),
            'form_name' => $this->submissionPerson($request),
            'form_email' => $request->input('email'),
            'form_message' => $request->input('message'),
            'form_phone' => $request->input('phone'),
            'property_id' => $property?->id,
            'investment_id' => $investment?->id ?? $property?->investment_id,
            'ip' => $request->ip(),
            'url' => $request->headers->get('referer'),
        ];
    }

    /**
     * Drugi wiersz pozycji w dzwonku: czego dotyczy zgłoszenie i od kogo.
     *
     * Kontekst schodzi od najbardziej szczegółowego: lokal → inwestycja → nazwa strony.
     * Zgłoszenie ze strony kontaktu nie ma żadnego z nich, więc zostaje sama nazwa
     * formularza — pozycja bez treści byłaby gorsza niż ogólnikowa.
     */
    private function submissionSummary(Request $request, ?Property $property, ?Investment $investment): string
    {
        $context = $property?->name
            ?: ($investment?->name
                ?: ($request->input('page') ?: 'Formularz kontaktowy'));

        $person = $this->submissionPerson($request);

        return $person === '' ? $context : $context . ' - ' . $person;
    }

    /**
     * Imię i nazwisko z formularza. Oba pola są wymagane przez `ContactFormRequest`,
     * ale powiadomienie nie może paść na zgłoszeniu przyjętym inną drogą (iframe).
     */
    private function submissionPerson(Request $request): string
    {
        return trim($request->input('name') . ' ' . $request->input('lastname'));
    }
}
