<?php

namespace App\Observers;

use App\Models\Stage;

class StageObserver
{
    public function creating(Stage $stage): void
    {
        $stage->user_id = auth()->id();
    }

    public function deleting(Stage $stage): void
    {
        $stage->tasks()->each(function($task) {
            $task->delete();
        });
    }
}
