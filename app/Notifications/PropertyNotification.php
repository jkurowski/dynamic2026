<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

use App\Models\Property;

class PropertyNotification extends Notification
{
    use Queueable;

    private $request;

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
        return [
            'page_name' => $this->request->input('form_page'),
            'form_name' => $this->request->input('name'),
            'form_email' => $this->request->input('email'),
            'form_message' => $this->request->input('message'),
            'form_phone' => $this->request->input('phone'),
            'property_id' => $this->property->id,
            'investment_id' => $this->property->investment_id,
            'ip' => $this->request->ip(),
            'url' => $this->request->headers->get('referer')
        ];
    }
}
