<?php

namespace App\Observers;

// CMS
use App\Models\Board;

class BoardObserver
{
    public function deleted(Board $board)
    {
        // Delete related stages
        $board->allStages()->delete();

        // Delete related tasks
        $board->tasks()->delete();
    }
}