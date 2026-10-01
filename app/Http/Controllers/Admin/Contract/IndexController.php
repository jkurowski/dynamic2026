<?php

namespace App\Http\Controllers\Admin\Contract;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;

// CMS
use App\Repositories\ContractRepository;
use App\Http\Requests\ContractFormRequest;
use App\Services\ContractService;
use App\Models\ContractTemplate;
use App\Models\Contract;


class IndexController extends Controller
{
    private ContractRepository $repository;
    private ContractService $service;

    public function __construct(ContractRepository $repository, ContractService $service)
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
        $this->service = $service;
    }

    public function index()
    {
        // Retrieve all contracts
        $contracts = Contract::all()->map(function ($contract) {
            $contract['type'] = 1;
            return $contract;
        });

        $templates = Template::all()->map(function ($template) {
            $template['type'] = 2;
            return $template;
        });

        $list = $contracts->concat($templates);
        return view('admin.contract.index', ['list' => $list]);
    }

    public function generate(Request $request, Contract $contract)
    {
        $requestData = $request->except('_token', 'submit');
        $fileName = $contract->template;
        $fileNameFilled = Str::slug($contract->name).'_'.date('His') . '_template.docx';
        $file_path = public_path('uploads/contract/templates/' .$fileName);
        $templateProcessor = new TemplateProcessor($file_path);

        foreach($requestData as $key => $value){
            $templateProcessor->setValue($key, $value);
        }

        $fileStorage = public_path('uploads/storage/' . $fileNameFilled);
        $templateProcessor->saveAs($fileStorage);

        $file = new \Illuminate\Http\File($fileStorage);

        //Load word file
        $Content = \PhpOffice\PhpWord\IOFactory::load($file);

        //Save it into PDF
        $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content,'PDF');
        $PDFWriter->save(public_path('uploads/storage/result3.pdf'));

        $fileSize = $file->getSize();
        $fileExtension = $file->getExtension();
        $fileMimeType = $file->getMimeType();

        $file = new File();
        $file->parent_id = 14;
        $file->user_id = auth()->id();
        $file->type = 0;
        $file->name = $contract->name .' '.$request->get('number');
        $file->description = 'Dokument wygenerowany';
        $file->file = $fileNameFilled;
        $file->size = $fileSize;
        $file->extension = $fileExtension;
        $file->mime = $fileMimeType;

        $file->save();

        return redirect(route('admin.contract.index'))->with('success', 'Nowy dokument został wygenerowany');
    }

    public function create()
    {
        return view('admin.contract.form', [
            'cardTitle' => 'Dodaj dokument',
            'backButton' => route('admin.contract.index')
        ])->with('entry', Contract::make());
    }

    public function store(ContractFormRequest $request)
    {
        $validatedData = $request->validated();
        $contract = $this->repository->create($validatedData);

        $this->updateArticleFiles($request, $contract, 'file', 'upload', false);

        return redirect(route('admin.contract.index'))->with('success', 'Nowy dokument dodany');
    }

    public function show(Contract $contract)
    {
        $placeholders = $contract->contractTemplates;

        return view('admin.contract.generate', [
            'entry' => $contract,
            'cardTitle' => $contract->name,
            'placeholders' => json_decode($placeholders->first()->placeholders, true),
            'backButton' => route('admin.contract.index')
        ]);
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function settings(Contract $contract)
    {
        $templateFile = $contract->template;
        $templateFilePath = public_path('uploads/contract/templates/' . $templateFile);
        $templateProcessor = new TemplateProcessor($templateFilePath);
        $placeholders = $templateProcessor->getVariables();
        $contractTemplates = $contract->contractTemplates;

        $placeholdersData = $contractTemplates->isNotEmpty()
            ? json_decode($contractTemplates->first()->placeholders, true)
            : $placeholders;

        return view('admin.contract.settings', [
            'entry' => $contract,
            'cardTitle' => $contract->name . ' - Ustawienia dokumentu',
            'placeholders' => $placeholdersData,
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function saveSettings(Request $request, Contract $contract)
    {
        $requestData = $request->except('_token', 'submit');
        ContractTemplate::updateOrCreate(
            ['contract_id' => $contract->id],
            ['placeholders' => json_encode($requestData)]
        );

        return redirect(route('admin.contract.index'))->with('success', 'Zmiany zapisane');
    }

    public function edit(Contract $contract)
    {
        return view('admin.contract.form', [
            'entry' => $contract,
            'cardTitle' => 'Edytuj dokument',
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function update(ContractFormRequest $request, Contract $contract)
    {
        $this->repository->update($request->validated(), $contract);

        $this->updateArticleFiles($request, $contract, 'file', 'upload', true);

        return redirect(route('admin.contract.index'))->with('success', 'Dokument zaktualizowany');
    }

    public function destroy($id)
    {
        //
    }

    private function updateArticleFiles(ContractFormRequest $request, object $contract, string $fileField, string $uploadMethod, bool $delete)
    {
        if ($request->hasFile($fileField)) {
            $this->service->$uploadMethod($request->name, $request->file($fileField), $contract, $delete);
        }
    }
}
