<?php

namespace App\Http\Controllers\Admin\Email;

use App\Helpers\TemplateTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Http\Requests\EmailTemplateFormRequest;
use App\Repositories\EmailTemplateRepository;
use App\Services\EmailGeneratorService;
use App\Models\EmailTemplateSection;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\Offer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class GeneratorController extends Controller
{
    private EmailGeneratorService $service;
    private EmailTemplateRepository $repository;




    public function __construct(EmailGeneratorService $service, EmailTemplateRepository $repository)
    {
        //        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
        //            'only' => ['index','store']
        //        ]);
        //        $this->middleware('permission:box-create', [
        //            'only' => ['create','store']
        //        ]);
        //        $this->middleware('permission:box-edit', [
        //            'only' => ['edit','update']
        //        ]);
        //        $this->middleware('permission:box-delete', [
        //            'only' => ['destroy']
        //        ]);

        $this->service = $service;
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.email.generator.index', ['list' => $this->repository->idDesc()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.email.form', [
            'cardTitle' => 'Stwórz szablon',
            'backButton' => route('admin.email.generator.index'),
            'templateTypes' => TemplateTypes::getTypes(),
            'selectedTemplate' => 0,
        ])->with('entry', EmailTemplate::make());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTemplateFormRequest $request)
    {
        $validatedData = $request->validated();
        $user_id = auth()->id();
        $validatedData['user_id'] = $user_id;
        $this->repository->create($validatedData);

        return redirect(route('admin.email.generator.index'))->with('success', 'Nowy szablon dodany');
    }

    public function getTemplate(Request $request)
    {
        $type = $request->input('type');
        $template = '';

        $section = new EmailTemplateSection();
        $section->email_template_id = 1; // Assuming email_template_id is 1
        $section->uuid = uuid_create(); // You need to generate UUID, if you're not using a package for this
        $section->type = $type;

        switch ($type) {
            case 'title':
                $template = view('admin.email.html-blocks.title', ['uuid' => $section->uuid])->render();
                break;
            case 'text':
                $template = view('admin.email.html-blocks.text', ['uuid' => $section->uuid])->render();
                break;
            case 'image':
                $template = view('admin.email.html-blocks.image', ['uuid' => $section->uuid])->render();
                break;
            default:
                // Handle unknown type
                break;
        }

        $section->content = $template;
        $section->save();

        return response()->json(['template' => $template, 'success' => 'Nowy element dodany', 'uuid' => $section->uuid]);
    }

    public function updateOrder(Request $request)
    {
        $uuids = $request->input('uuids');
        foreach ($uuids as $index => $uuid) {
            EmailTemplateSection::where('uuid', $uuid)->update(['position' => $index + 1]);
        }
        return response()->json(['success' => 'Kolejność elementów zapisana']);
    }

    public function getSettings(Request $request)
    {
        $uuid = $request->input('uuid');

        // Get EmailTemplateSection based on uuid
        $emailTemplateSection = EmailTemplateSection::where('uuid', $uuid)->first();

        if (!$emailTemplateSection) {
            return response()->json(['error' => 'Email template section not found'], 404);
        }

        // Determine type of email template section
        $type = $emailTemplateSection->type;

        // Render view based on type
        $template = '';

        switch ($type) {
            case 'title':
                $template = view('admin.email.settings.title', ['emailTemplateSection' => $emailTemplateSection])->render();
                break;
            case 'text':
                $template = view('admin.email.settings.text', ['emailTemplateSection' => $emailTemplateSection])->render();
                break;
            case 'image':
                $template = view('admin.email.settings.image')->render();
                break;
            default:
                // Handle unknown type
                break;
        }

        return response()->json(['template' => $template, 'type' => $type]);
    }

    public function updateSettings(Request $request)
    {
        $uuid = $request->input('uuid');
        $content = $request->input('content');
        EmailTemplateSection::where('uuid', $uuid)
            ->update(['content' => $content]);
        return response()->json(['success' => 'Szablon zaktualizowany']);
    }

    public function uploadImage(Request $request)
    {
        $uuid = $request->input('uuid');
        $entry = EmailTemplateSection::where('uuid', $uuid)->first();

        $updatedFilePath = null;

        if ($request->hasFile('qqfile')) {
            $updatedFilePath = $this->service->upload($uuid, $request->file('qqfile'), $entry, true);
        }

        return response()->json(['upload' => 'complete', 'success' => 'Szablon zaktualizowany', 'url' => $updatedFilePath]);
    }

    public function show(EmailTemplate $generator)
    {
        $emailTemplateType = $generator->meta ?? json_decode($generator->meta, true);
        $templateType = TemplateTypes::mapTypeToLayout($emailTemplateType['template_type']);

        return view('admin.email.generator.form', [
            'id' => $generator->id,
            'cardTitle' => 'Kreator szablonu: ' . $generator->name,
            'backButton' => route('admin.email.generator.index'),
            'template' => $generator->content,
            'template_json' => json_decode($generator->content, true) ?? '',
            'template_type' => $templateType,
        ]);
    }

    public function edit(int $id)
    {


        $template = $this->repository->find($id);
        $selectedTemplate = $template->meta['template_type'] ?? null;


        return view('admin.email.form', [
            'entry' => EmailTemplate::find($id),
            'cardTitle' => 'Edytuj szablon',
            'backButton' => route('admin.email.generator.index'),
            'templateTypes' => TemplateTypes::getTypes(),
            'selectedTemplate' => $selectedTemplate,
        ]);
    }

    public function update(EmailTemplateFormRequest $request, string $id)
    {
        $template = $this->repository->find($id);
        $validated = $request->validated();

        $template->update($validated);


        return redirect(route('admin.email.generator.index'))->with('success', 'Szablon zaktualizowany');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function destroyBlock(Request $request)
    {
        $uuid = $request->input('uuid');

        $emailTemplateSection = EmailTemplateSection::where('uuid', $uuid)->first();
        if (!$emailTemplateSection) {
            return response()->json(['error' => 'Element nie znaleziony'], 404);
        }

        $emailTemplateSection->delete();

        return response()->json(['success' => 'Element usunięty']);
    }

    public function copy(EmailTemplate $email)
    {
        $newTemplate = $email->replicate();
        $newTemplate->user_id = $email->user_id;
        $newTemplate->name = $email->name . ' - kopia';
        $newTemplate->description = 'Kopia szablonu: ' . $email->name;
        $newTemplate->created_at = now();

        //   Set offer to 0 to prevent multiple templates with the same offer
        $template_meta = $newTemplate->meta;
        $template_meta['offer'] = 0;
        $newTemplate->meta = $template_meta;
        $newTemplate->save();

        return redirect()->route('admin.email.generator.index')->with('success', 'Szablon skopiowany');
    }

    public function updateTemplate(Request $request)
    {
        $template = EmailTemplate::find($request->input('template_id'));
        // $template->content = $request->input('template_html');
        $template->content = $request->input('template_json');

        $template->save();

        return response()->json(['success' => 'Szablon zaktualizowany']);
    }



    public function assignAttachment(Request $request)
    {
        $file_id = $request->input('file_id');
        $templateId = $request->input('template_id');

        $template = EmailTemplate::find($templateId);
        if (!$template) {
            return response()->json(['status' => 'error', 'message' => 'Szablon nie znaleziony'], 404);
        }

        $file = File::find($file_id);
        if (!$file) {
            return response()->json(['status' => 'error', 'message' => 'Plik nie znaleziony'], 404);
        }


        $new_attachments = array_merge($template->attachments ?? [], [$file_id]);
        $unique_attachments = array_unique($new_attachments);
        $template->attachments = $unique_attachments;
        $template->save();


        return response()->json(['status' => 'success', 'message' => 'Plik dołączony']);
    }

    public function unlinkAttachment(Request $request)
    {
        $file_id = $request->input('file_id');
        $templateId = $request->input('template_id');

        $template = EmailTemplate::find($templateId);
        if (!$template) {
            return response()->json(['status' => 'error', 'message' => 'Szablon nie znaleziony'], 404);
        }

        $file = File::find($file_id);
        if (!$file) {
            return response()->json(['status' => 'error', 'message' => 'Plik nie znaleziony'], 404);
        }

        $attachments = $template->attachments ?? [];
        $new_attachments = array_diff($attachments, [$file_id]);
        $template->attachments = $new_attachments;
        $template->save();

        return response()->json(['status' => 'success', 'message' => 'Plik odłączony']);
    }

    private function mapFileAttributes($file, $templateId)
    {
        $file->is_attached = in_array($file->id, EmailTemplate::find($templateId)->attachments ?? []);
        return [
            'id' => $file->id,
            'name' => $file->name,
            'extension' => $file->extension,
            'is_attached' => $file->is_attached
        ];
    }
}
