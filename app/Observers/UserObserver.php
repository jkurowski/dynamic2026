<?php

namespace App\Observers;

// CMS
use App\Models\User;
use App\Notifications\UserChangeStatusNotification;
use App\Notifications\UserCreatedNotification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $user->notify(new UserCreatedNotification($user));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if ($user->isDirty('active')) {
            $active = $user->active;
            $user->notify(new UserChangeStatusNotification($active, $user));
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
