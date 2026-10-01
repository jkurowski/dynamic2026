<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\ChatSend;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use App\Notifications\PropertyNotification;
use App\Repositories\Client\ClientRepository;
use App\Repositories\InvestmentRepository;
use App\Services\Strategies\HousesStrategy;
use App\Services\Strategies\SingleBuildingStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class IframePageController extends Controller
{
    private $strategy;
    private $repository;

    private ClientRepository $clientRepository;

    public function __construct(InvestmentRepository $investmentRepository, ClientRepository $clientRepository)
    {
        $this->repository = $investmentRepository;
        $this->clientRepository = $clientRepository;
        $this->strategy = null;
    }

    public function index(Request $request, Investment $investment, Property $property)
    {


        switch ($investment->type) {
            case 2:
                $this->strategy = new SingleBuildingStrategy($request, $investment);
                break;
            case 3:
                $this->strategy = new HousesStrategy($request, $investment);
                break;
            default:
                abort(404);
        }


        $properties = $this->strategy->handle();

        if (empty($properties)) {
            abort(404);
        }

        $uniqueRooms = $this->repository->getUniqueRooms($properties);
        $custom_css = $investment->iframe_css;

        return view('front.iframe.index', compact('investment', 'properties', 'uniqueRooms', 'custom_css'));
    }

    public function single(Request $request, Investment $investment, Property $property)
    {
        $custom_css = $investment->iframe_css;
        return view('front.iframe.single', compact('investment', 'property', 'custom_css'));
    }

    public function apartmentBuilding(Request $request, Investment $investment, Floor $floor, Property $property)

    {
        $custom_css = $investment->iframe_css;
        return view('front.iframe.single', compact('investment', 'property', 'floor', 'custom_css'));
    }

    public function contact(Request $request, $id)
    {
        try {
            $property = Property::find($id);
            $client = $this->clientRepository->createClient($request, $property);
            $property->notify(new PropertyNotification($request, $property));
            Mail::to(settings()->get("page_email"))->send(new ChatSend($request, $client, $property));

            if (count(Mail::failures()) == 0) {
                $cookie_name = 'dp_';
                foreach ($_COOKIE as $name => $value) {
                    if (stripos($name, $cookie_name) === 0) {
                        Cookie::queue(
                            Cookie::forget($name)
                        );
                    }
                }
            }
        } catch (\Throwable $exception) {
        }
        return response()->json(['status' => 'success', 'message' => "Wiadomość została wysłana. Dziękujemy za kontakt!"], 201);
    }

    public function token(Request $request)
    {

        return response()->json(['token' => csrf_token()]);
    }
}
