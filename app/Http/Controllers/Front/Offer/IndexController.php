<?php

namespace App\Http\Controllers\Front\Offer;

use App\Helpers\EmailTemplatesJsonParser\WebTemplateParser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Carbon\Carbon;

//CMS
use App\Models\Offer;
use App\Models\Property;


class IndexController extends Controller
{
    private string $pageTemplateView = 'admin.web-generator.index';
    public function show(Offer $offer)
    {


        if ($offer->status == 2) {
            throw new NotFoundHttpException(); // Throws a 404 exception
        }

        if ($offer->status != 3) {
            $offer->status = 3;
            $offer->readed_at = Carbon::now();
            $offer->save();
        }

        if ($offer->properties) {
            $propertyIds = json_decode($offer->properties);
            if ($propertyIds) {
                $selectedOffer = Property::whereIn('id', $propertyIds)->get();
            } else {
                $selectedOffer = collect();
            }
        } else {
            $selectedOffer = collect();
        }

        //    check if offer has template
        if (!$offer->template_id) {
            return view('front.offer.show', compact('offer', 'selectedOffer'));
        }

        $template = EmailTemplate::find($offer->template_id);
        $templateParser = new WebTemplateParser($template->content);
        $templateParser->prepareBlocks();
        $templateParser->prepeareOfferList(view('front.offer.property_list', ['properties' => $selectedOffer])->render());
        $html = $templateParser->render();
       
        return view($this->pageTemplateView, ['html' => $html]);
        // return view('front.offer.show', compact('offer', 'selectedOffer', 'html'));
    }
}
