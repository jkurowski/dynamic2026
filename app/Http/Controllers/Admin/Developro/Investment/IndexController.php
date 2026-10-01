<?php

namespace App\Http\Controllers\Admin\Developro\Investment;

use App\Helpers\InvestmentHelpers;
use App\Helpers\ProvinceTypes;
use App\Helpers\TemplateTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvestmentFormRequest;
use App\Models\City;
use App\Models\EmailTemplate;
use App\Models\Gallery;
use App\Models\Investment;
use App\Models\InvestmentTemplates;
use App\Models\User;
use App\Notifications\SupervisorNotification;
use App\Repositories\InvestmentRepository;
use App\Services\InvestmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// CMS

class IndexController extends Controller
{
    private $repository;
    private $service;

    public function __construct(InvestmentRepository $repository, InvestmentService $service)
    {
        //        $this->middleware('permission:investment-list|investment-create|investment-edit|investment-delete', [
        //            'only' => ['index','store']
        //        ]);
        //        $this->middleware('permission:investment-create', [
        //            'only' => ['create','store']
        //        ]);
        //        $this->middleware('permission:investment-edit', [
        //            'only' => ['edit','update']
        //        ]);
        //        $this->middleware('permission:investment-delete', [
        //            'only' => ['destroy']
        //        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        $user = Auth::user();
        $investments = $this->repository->allSortBy('status', 'asc');
        $role = Role::find(13);

        if ($user && $user->hasRole($role)) {
            $filteredInvestments = $investments;
        } else {
            $filteredInvestments = $investments->filter(function ($investment) use ($user) {
                $permissionName = 'view-investment-' . $investment->id;
                return $user->hasPermissionTo($permissionName);
            });
        }

        return view('admin.developro.investment.index', ['list' => $filteredInvestments]);
    }

    public function create()
    {
        $emptyInvestment = Investment::make();
        $cities_form = City::get()->pluck('name', 'id');
        $galeries_form = Gallery::get()->pluck('name', 'id')->prepend('Brak', 0);

        return view('admin.developro.investment.form', [
            'users' => [],
            'selected' => ['' => 'Brak'],
            'cardTitle' => 'Dodaj inwestycje',
            'cities_form' => $cities_form,
            'galeries_form' => $galeries_form,
            'backButton' => route('admin.developro.investment.index'),
            'companies' => InvestmentHelpers::getCompanies(),
            'salePoints' => InvestmentHelpers::getSalePoints(),
            'provinces' => ProvinceTypes::getProvinces()
        ])->with('entry', $emptyInvestment);
    }

    public function store(InvestmentFormRequest $request)
    {
        $investment = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->uploadThumb($request->name, $request->file('file'), $investment);
        }

        if ($request->hasFile('file_logo')) {
            $this->service->uploadLogo($request->name, $request->file('file_logo'), $investment);
        }

        if ($request->hasFile('file_header')) {
            $this->service->uploadLogo($request->name, $request->file('file_header'), $investment);
        }

        if ($request->hasFile('file_brochure')) {
            $this->service->uploadBrochure($request->name, $request->file('file_brochure'), $investment);
        }

        $this->repository->createPermissionName($investment->id);

        return redirect(route('admin.developro.investment.index'))->with('success', 'Inwestycja zapisana');
    }

    public function edit(int $id)
    {
        $investment = $this->repository->find($id);
        $cities_form = City::all()->pluck('name', 'id');
        $galeries_form = Gallery::get()->pluck('name', 'id')->prepend('Brak', 0);

        return view('admin.developro.investment.form', [
            'entry' => $investment,
            'cities_form' => $cities_form,
            'galeries_form' => $galeries_form,
            'cardTitle' => 'Edytuj inwestycję',
            'backButton' => route('admin.developro.investment.index'),
            'companies' => InvestmentHelpers::getCompanies(),
            'salePoints' => InvestmentHelpers::getSalePoints(),
            'provinces' => ProvinceTypes::getProvinces()
        ]);
    }

    public function update(InvestmentFormRequest $request, int $id)
    {

        $investment = $this->repository->find($id);
        $this->repository->update($request->validated(), $investment);

        if ($request->hasFile('file')) {
            $this->service->uploadThumb($request->name, $request->file('file'), $investment, true);
        }

        if ($request->hasFile('file_logo')) {
            $this->service->uploadLogo($request->name, $request->file('file_logo'), $investment, true);
        }

        if ($request->hasFile('file_header')) {
            $this->service->uploadHeader($request->name, $request->file('file_header'), $investment, true);
        }

        if ($request->hasFile('file_brochure')) {
            $this->service->uploadBrochure($request->name, $request->file('file_brochure'), $investment, true);
        }

        return redirect(route('admin.developro.investment.index'))->with('success', 'Inwestycja zaktualizowana');
    }

    public function log(Investment $investment)
    {
        return view('admin.developro.investment.log', ['investment' => $investment]);
    }
    public function events(Investment $investment)
    {
        return view('admin.developro.investment.events', ['investment' => $investment]);
    }
    public function eventtable(Investment $investment, Request $request)
    {
        return $this->repository->getEventsAsTable($investment, $request);
    }
    public function datatable(Investment $investment, Request $request)
    {
        return $this->repository->getDataTable($investment, $request->input('minDate'), $request->input('maxDate'));
    }
    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json(['status' => 'deleted'], 201);
    }

    public function convert(){
        // Fetch data from the old table
        $old = DB::connection('old_mysql')->table('inwestycje')->orderBy('id')->get();

        // Define the default locale
        $defaultLocale = 'pl';

        foreach ($old as $o) {


            $entry = new Investment();

            $entry->fill([
                'slug' => $o->slug ?? null,
                'file_thumb' => $o->plik_thumb ?? null,
                'file_logo' => $o->plik_logo ?? null,
                'file_header' => $o->plik_header ?? null,
                'type' => $o->typ ?? null,
                'city_id' => $o->miasto ?? null,
                'status' => $o->status ?? null,
                'gallery_id' => $o->galeria_id ?? null,
                'date_start' => $o->data_start ?? null,
                'date_end' => $o->data_koniec ?? null,
                'area_range' => $o->zakres_powierzchnia ?? null,
                'floor_range' => $o->zakres_pietra ?? null,
                'room_range' => $o->zakres_pokoje ?? null,
                'office_address' => $o->biuro_adres ?? null,
                'commercial' => $o->uslugowe ?? null,
                'created_at' => $o->data_dodania ?? null,
                'updated_at' => $o->data_edycji ?? null,
                'sort' => $o->sort ?? null,
                'popup_status' => 0,
                'popup_mode' => 1,
                'popup_timeout' => 6000,
                'popup_text' => null,
                'address' => null,
                'end_content' => null,
                'areas_amount' => 0,
                'show_prices' => 0,
                'show_properties' => 1,
                'supervisors' => null
            ]);

            $entry->setTranslation('name', $defaultLocale, $o->nazwa);
            $entry->setTranslation('entry_content', $defaultLocale, $o->lista);
            $entry->setTranslation('content', $defaultLocale, $o->tekst);
            $entry->setTranslation('end_content', $defaultLocale, '');
            $entry->setTranslation('meta_title', $defaultLocale, $o->meta_tytul);
            $entry->setTranslation('meta_description', $defaultLocale, $o->meta_opis);
            $entry->setTranslation('popup_text', $defaultLocale, '');
            $entry->save();
        }

        return redirect(route('admin.developro.investment.index'))->with('success', 'Wpisy przetłumaczone');
    }

}
