<?php

namespace App\Observers;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;

class MailTemplateObserver
{
    public function saving(EmailTemplate $emailTemplate): void
    {
        $emailTemplate->user_id = Auth::id();
    }
}
