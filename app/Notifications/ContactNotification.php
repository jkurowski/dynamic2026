<?php

namespace App\Notifications;

use App\Models\Investment;
use App\Notifications\Concerns\DescribesFormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

/**
 * Powiadomienie w panelu (dzwonek) o zgłoszeniu z formularza BEZ konkretnego lokalu —
 * z podstrony inwestycji albo ze strony kontaktu.
 *
 * **Do 2026-08-04 takie zgłoszenie nie tworzyło ŻADNEGO powiadomienia.**
 * `Contact\IndexController::store()` wołał `notify()` tylko wtedy, gdy formularz przysłał
 * `property_id`. Mail wychodził, lead lądował w CRM-ie, a dzwonek milczał — czyli osoba
 * pracująca w panelu nie miała jak zauważyć zapytania inaczej niż wchodząc do skrzynki.
 * Ta klasa istniała, ale nikt jej nie wołał, a jej pola (`form_name`, `form_email`)
 * pochodziły z formularza poprzedniej generacji i nie pasowały do niczego, co przysyła
 * dzisiejszy `<x-contact-form>`.
 *
 * Adresatem jest INWESTYCJA, gdy zgłoszenie jej dotyczy — dzięki `investment_id` w `data`
 * `NotificationBell` pokaże je sprzedawcom do niej przypisanym. Zgłoszenie ze strony
 * ogólnej nie ma właściciela, więc trafia wprost do administratorów.
 *
 * ŚWIADOMIE NIE JEST KOLEJKOWANE mimo pozycji w audycie „ujednolicić ShouldQueue”.
 * Kanał to `database`, czyli jeden INSERT — kolejka nic tu nie oszczędza, a że
 * worker startuje raz na minutę, powiadomienie pojawiałoby się w panelu z
 * opóźnieniem do minuty. Kolejkujemy powiadomienia wysyłane MAILEM (SMTP potrafi
 * zająć sekundy i wywrócić żądanie), nie zapisy do bazy.
 */
class ContactNotification extends Notification
{
    use Queueable, DescribesFormSubmission;

    private Request $request;
    private ?Investment $investment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request, ?Investment $investment = null)
    {
        $this->request = $request;
        $this->investment = $investment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->submissionPayload($this->request, null, $this->investment);
    }
}
