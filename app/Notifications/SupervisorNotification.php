<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupervisorNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected  $submessage;

    public function __construct($title, $message, $submessage = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->submessage = $submessage;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject($this->title)
            ->line($this->message);

        if (!empty($this->submessage)) {
            $mailMessage->line('**' . $this->submessage . '**');
        }

        return $mailMessage;
    }
}
