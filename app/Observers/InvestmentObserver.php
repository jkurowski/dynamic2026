<?php

namespace App\Observers;

// CMS
use App\Models\Investment;
use Illuminate\Support\Facades\File;

class InvestmentObserver
{
    /**
     * Handle the Investment "deleted" event.
     *
     * @param Investment $model
     * @return void
     */
    public function deleted(Investment $model)
    {
        if (File::isFile(public_path('investment/thumbs/' . $model->file_thumb))) {
            File::delete(public_path('investment/thumbs/' . $model->file_thumb));
        }
        if ($model->plan()->exists()) {
            if (File::isFile(public_path('investment/plan/' . $model->plan()->first()->file))) {
                File::delete(public_path('investment/plan/' . $model->plan()->first()->file));
            }
        }
    }
}
