<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserNotification extends Notification
{
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a new notification',
        ];
    }

    public function toBroadcast($notifiable)
    {
        // Add debug statement to check if the message is being sent
        \Log::debug('Broadcasting notification message.');

        return new BroadcastMessage([
            'message' => 'You have a new notification',
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('public-notification-channel');
    }
}

