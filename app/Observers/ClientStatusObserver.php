<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\ClientStatusHistory;
use Illuminate\Support\Facades\Auth;

class ClientStatusObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client)
    {
        if ($client->isDirty('status')) {
            $oldStatus = $client->getOriginal('status');
            $newStatus = $client->status;
            $userId = Auth::id(); // Pobierz ID aktualnie zalogowanego użytkownika

            // Zapisz historię zmiany statusu
            ClientStatusHistory::create([
                'client_id' => $client->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => $userId,
                'changed_at' => now(), // Aktualny czas
            ]);
        }
    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "restored" event.
     */
    public function restored(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     */
    public function forceDeleted(Client $client): void
    {
        //
    }
}
