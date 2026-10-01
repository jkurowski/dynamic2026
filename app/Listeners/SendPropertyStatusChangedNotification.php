<?php
namespace App\Listeners;

use App\Models\PropertyNotification;
use App\Notifications\PropertyStatusChanged;

class SendPropertyStatusChangedNotification
{
    public function handle($event)
    {
        $property = $event;

        if ($property) {
            $notifications = PropertyNotification::where('property_id', $property->id)->get();

            foreach ($notifications as $notification) {
                $unsubscribeUrl = route('front.developro.properties.notifications.unsubscribe', $notification->hash);
                $notification->notify(new PropertyStatusChanged($property, $unsubscribeUrl));
            }
        }
    }
}