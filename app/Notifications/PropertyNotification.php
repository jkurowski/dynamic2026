<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

use App\Models\Property;
use App\Notifications\Concerns\DescribesFormSubmission;

/**
 * Powiadomienie w panelu o zapytaniu dotyczącym konkretnego mieszkania.
 *
 * Zgłoszenie BEZ lokalu (podstrona inwestycji, strona kontaktu) obsługuje
 * `ContactNotification` — treść obu składa `DescribesFormSubmission`.
 *
 * Jak `ContactNotification` — kanał `database`, więc świadomie bez kolejki
 * (patrz komentarz tam).
 */
class PropertyNotification extends Notification
{
    use Queueable, DescribesFormSubmission;

    private Request $request;
    private Property $property;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request, Property $property)
    {
        $this->request = $request;
        $this->property = $property;
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
        return $this->submissionPayload($this->request, $this->property, $this->property->investment);
    }
}
