<?php

namespace App\Http\Controllers\Admin\MassMail;

use App\Helpers\EmailTemplatesJsonParser\EmailTemplateParser;
use App\Helpers\TemplateTypes;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    public function index()
    {
        $users = Client::whereNotNull('mail')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->mail,
            ];
        });

        $templates = $this->getEmailTemplates(TemplateTypes::EMAIL);


        return view('admin.mass-mail.index', compact('users', 'templates'));
    }


    private function getEmailTemplates(string $templateType)
    {
        return EmailTemplate::where('user_id', auth()->id())
            ->get()
            ->filter(function ($template) use ($templateType) {
                if ($template->meta) {
                    $meta = $template->meta;
                    if (isset($meta['template_type']) && $meta['template_type'] === $templateType) {
                        return $template;
                    }
                }
            })
            ->pluck('name', 'id')
            ->toArray();
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            "users" => "required|array",
            "subject" => "required|string",
            "content" => "nullable|string",
            "template" => "nullable|exists:email_templates,id"
        ], [
            "users.required" => "Musisz wybrać przynajmniej jednego użytkownika",
            "users.array" => "Musisz wybrać przynajmniej jednego użytkownika",
            "subject.required" => "Musisz wpisać temat",
            "subject.string" => "Musisz wpisać temat",
            "content.required" => "Musisz wpisać treść",
            "content.string" => "Musisz wpisać treść",
        ]);

        if (!$validated['template']) {
            $this->sendEmail($validated['subject'], $validated['content'], $this->getUsersEmails($validated['users']));
            return redirect()->back()->with('success', 'Wiadomość została wysłana');
        }


        $template = EmailTemplate::find($validated['template']);
        $templateParser = new EmailTemplateParser($template->content);
        $templateParser->prepareBlocks();

        Mail::send('emails.dynamicTemplate', [
            'htmlContent' => $templateParser->renderAsTableLayout(),
        ], function ($message) use ($validated) {
            $message->to($this->getUsersEmails($validated['users']))->subject($validated['subject']);
        });


        return redirect()->back()->with('success', 'Wiadomość została wysłana');
    }




    private function getUsersEmails(array $usersIds): array
    {
        return Client::whereIn('id', $usersIds)->pluck('mail')->toArray();
    }

    private function sendEmail(string $subject, string $content, array $emails)
    {
        Mail::send('emails.mass-mail', compact('subject', 'content'), function ($message) use ($subject, $emails) {
            $message->to($emails)->subject($subject);
        });
    }
}
