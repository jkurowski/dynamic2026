<?php

namespace App\Http\Controllers\Admin\Contract;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// CMS
use App\Http\Requests\TemplateFormRequest;
use App\Repositories\TemplateRepository;
use App\Models\Template;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class TemplateController extends Controller
{
    private TemplateRepository $repository;
    public function __construct(TemplateRepository $repository)
    {
//        $this->middleware('permission:contract-list|contract-create|contract-edit|contract-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:contract-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:contract-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:contract-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        return view('admin.contract.template.form', [
            'cardTitle' => 'Dodaj dokument',
            'backButton' => route('admin.contract.index')
        ])->with('entry', Template::make());
    }

    public function store(TemplateFormRequest $request)
    {
        $validatedData = $request->validated();
        $this->repository->create($validatedData);
        return redirect(route('admin.contract.index'))->with('success', 'Nowy szablon dodany');
    }

    public function show(Template $template)
    {
        return view('admin.contract.template.generate', [
            'entry' => $template,
            'cardTitle' => $template->name,
            'placeholders' => json_decode($template->placeholders, true),
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function generate(Request $request, Template $template)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $requestData = $request->except('_token', 'submit');
        $fileNameFilled = Str::slug($template->name) . '_' . date('His') . '_template.docx';

// Iterate over request data to replace placeholders
        foreach ($requestData as $key => $value) {
            $template->text = str_replace("[{$key}]", $value, $template->text);
        }

// Add the modified HTML content to the document using addHtml() method
        Html::addHtml($section, $template->text);

        $fileStorage = public_path('uploads/storage/' . $fileNameFilled);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($fileStorage);

        $fileDocx = new \Illuminate\Http\File($fileStorage);

//        //Load word file
//        $Content = \PhpOffice\PhpWord\IOFactory::load($fileDocx);
//
//        //Save it into PDF
//        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');
//        $PDFWriter->save(public_path('uploads/storage/result3.pdf'));

        // Get file size, extension, and MIME type
        $fileSize = $fileDocx->getSize();
        $fileExtension = $fileDocx->getExtension();
        $fileMimeType = $fileDocx->getMimeType();

        // Create a new File record in the database
        $file = new File();
        $file->parent_id = 14;
        $file->user_id = auth()->id();
        $file->type = 1;
        $file->name = $template->name . ' ' . $request->get('number');
        $file->description = 'Dokument wygenerowany';
        $file->file = $fileNameFilled;
        $file->size = $fileSize;
        $file->extension = $fileExtension;
        $file->mime = $fileMimeType;
        $file->save();

        return redirect(route('admin.contract.index'))->with('success', 'Nowy dokument został wygenerowany');
    }

    public function settings(Template $template)
    {

        $tagsString = $template->tags;
        $placeholdersString = $template->placeholders;

        // Decode the JSON string into an array
        $tagsArray = json_decode($tagsString, true);

// If the decoding was successful and $tagsArray is an array, proceed
        if (is_array($tagsArray)) {
            // Convert the indexed array into an associative array
            $tagsArray = array_combine($tagsArray, $tagsArray);
        } else {
            // Handle the case where decoding fails or $tagsArray is not an array
            // For example, log an error message or set $tagsArray to an empty array
            $tagsArray = [];
        }

// Decode placeholders
        $placeholdersArray = $placeholdersString ? json_decode($placeholdersString, true) : [];

        return view('admin.contract.template.settings', [
            'entry' => $template,
            'cardTitle' => $template->name . ' - Ustawienia szablonu',
            'tagsArray' => $tagsArray,
            'placeholdersArray' => $placeholdersArray,
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function saveSettings(Request $request, Template $template)
    {
        $requestData = $request->except('_token', 'submit');
        $template->update(['placeholders' => json_encode($requestData)]);
        return redirect(route('admin.contract.index'))->with('success', 'Zmiany zapisane');
    }

    public function edit(Template $template)
    {
        return view('admin.contract.template.form', [
            'entry' => $template,
            'cardTitle' => 'Edytuj szablon',
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function update(TemplateFormRequest $request, Template $template)
    {
        $this->repository->update($request->validated(), $template);
        return redirect(route('admin.contract.index'))->with('success', 'Szablon zaktualizowany');
    }

    public function destroy(string $id)
    {
        //
    }
}
