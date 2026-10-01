<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyStatusChanged extends Notification
{
    use Queueable;

    protected $property;
    protected $unsubscribeUrl;

    public function __construct($property, $unsubscribeUrl)
    {
        $this->property = $property;
        $this->unsubscribeUrl = $unsubscribeUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[DeveloPro] Powiadomienie: zmiana statusu')
            ->line('Status '.$this->property->name.' zmienił się na: **'.roomStatus($this->property->status).'**')
            ->line('Jeśli chcesz wypisać się z tych powiadmień, użyj linku:')
            ->action('Wypisz mnie', $this->unsubscribeUrl);
    }
}
