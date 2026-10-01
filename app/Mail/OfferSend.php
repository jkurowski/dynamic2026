<?php

namespace App\Mail;

use App\Helpers\EmailTemplatesJsonParser\EmailTemplateParser;
use App\Models\Client;
use App\Models\EmailTemplate;
use App\Models\Investment;
use App\Models\Offer;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class OfferSend extends Mailable
{
    use Queueable, SerializesModels;

    private $request;
    /**
     * @var Client
     */
    private $client;
    private $selectedProperties;
    private  $offer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, Client $client, Offer $offer)
    {
        $this->request = $request;
        $this->client = $client;
        $this->offer = $offer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = $this->request->title;

        $investmentId = $this->getOffersInvestmentId($this->offer);

        $investment = Investment::find($investmentId);
        $template_id = $investment->investmentTemplates()->get()->first()->template_offer_mail;


        if ($template_id) {
            $template = EmailTemplate::find($template_id);
            $templateParser = new EmailTemplateParser($template->content);
            $templateParser->prepareBlocks();
            $templateParser->setButtonOfferLink( route('front.show', $this->offer));

            return $this->subject($subject)->view('emails.dynamicTemplate', [
                'request' => $this->request,
                'client' => $this->client,
                'signature' => Auth::user()->signature,
                'offer' => $this->offer,
                'selectedProperties' => $this->selectedProperties,
                'htmlContent' => $templateParser->renderAsTableLayout()
            ]);
        }




        return $this->subject($subject)->view(
            'emails.offer',
            [
                'request' => $this->request,
                'client' => $this->client,
                'signature' => Auth::user()->signature,
                'offer' => $this->offer,
                'selectedProperties' => $this->selectedProperties
            ]
        );
    }



    private function getOffersInvestmentId(Offer $offer)
    {
        $result = [];
        foreach (json_decode($offer->properties, true) as $index => $property_id) {
            $property = Property::find($property_id);
            if ($property) {
                $result[] = $property->investment_id;
            }
        }
        $investment_id = collect($result)->countBy()->sortDesc()->keys()->first();


        return $investment_id;
    }
}
