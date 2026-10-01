<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertySubscriptionConfirmation extends Notification
{
    use Queueable;

    protected $unsubscribeUrl;

    public function __construct($unsubscribeUrl)
    {
        $this->unsubscribeUrl = $unsubscribeUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[DeveloPro] Potwierdzenie zapisu do powiadomień')
            ->line('Dziękujemy za zapisanie się do naszych powiadimień. Jeśli status wybranego mieszkania / domu zmieni się - poinformujemy Ciebie o tym.')
            ->line('Jeśli chcesz wypisać się z tych powiadmień, użyj linku:')
            ->action('Wypisz mnie', $this->unsubscribeUrl);
    }
}
