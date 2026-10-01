<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class PasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Witaj,')
            ->subject(settings()->get("page_title").' - reset hasła')
            ->line(new HtmlString('Niniejszy e-mail wysłany został ze względu na zainicjowaną procedurę zmiany hasła.<br><br>Kliknij przycisk poniżej, aby przejść do ekranu zmiany hasła.'))
            ->action('Zmiana hasła', url('password/reset', $this->token))
            ->line('Jeżeli nie wykonywałeś dyspozycji resetu hasła, zignoruj tę wiadomość.')
            ->salutation(new HtmlString('<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation"><tr><td>'.Markdown::parse('To jest wiadomość automatyczna, nie odpowiadaj na tego maila. Jeśli masz jakiekolwiek pytania, bądź wątpliwości dotyczące rejestracji nie wahaj się i napisz do nas: '.settings()->get("page_email")).'</td></tr></table>'));
    }
}
