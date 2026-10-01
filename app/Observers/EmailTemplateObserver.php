<?php

namespace App\Observers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// CMS
use App\Models\EmailTemplateSection;

class EmailTemplateObserver
{
    /**
     * Handle the article "deleted" event.
     *
     * @param EmailTemplateSection $emailTemplateSection
     * @return void
     */
    public function deleted(EmailTemplateSection $emailTemplateSection)
    {
        $file = public_path('uploads/email/' . $emailTemplateSection->file);

        if (File::isFile($file)) {
            File::delete($file);
        }
    }
}
