<?php

namespace App\Http\Controllers\Front\Developro\Property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// CMS
use App\Models\PropertyNotification;
use App\Models\Property;
use App\Notifications\PropertySubscriptionConfirmation;

class NotificationController extends Controller
{
    public function store(Request $request, $propertyId)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $property = Property::findOrFail($propertyId);

        // Check if the email is already signed up for this property
        $existingNotification = PropertyNotification::where('property_id', $property->id)
            ->where('email', $request->input('email'))
            ->first();

        if ($existingNotification) {
            return response()->json(['success' => false, 'message' => 'Twój adres e-mail jest już zapisany do powiadomień.']);
        }

        $notification = new PropertyNotification();
        $notification->property_id = $property->id;
        $notification->email = $request->input('email');
        $notification->hash = Str::uuid();
        $notification->save();

        // Send subscription confirmation email with unsubscribe link
        $unsubscribeUrl = route('front.developro.properties.notifications.unsubscribe', $notification->hash);
        $notification->notify(new PropertySubscriptionConfirmation($unsubscribeUrl));

        return response()->json(['success' => true, 'message' => 'Twój adres e-mail został zapisany do powiadomień.']);
    }

    public function unsubscribe($hash)
    {
        $notification = PropertyNotification::where('hash', $hash)->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Zły link.']);
        }

        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Zostałeś usunięty z powiadomień.']);
    }
}
