<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContactNotification extends Notification
{
    use Queueable;

    private $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
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
        return [
            'page_name' => $this->request->input('form_page'),
            'form_name' => $this->request->input('form_name'),
            'form_email' => $this->request->input('form_email'),
            'form_message' => $this->request->input('form_message'),
            'form_subject' => $this->request->input('form_subject'),
            'form_phone' => $this->request->input('form_phone'),
            'ip' => $this->request->ip(),
            'url' => $this->request->headers->get('referer')
        ];
    }
}
