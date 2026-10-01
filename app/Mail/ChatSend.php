<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class ChatSend extends Mailable
{
    use Queueable, SerializesModels;

    private $request;
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, Client $client, Property $property = null)
    {
        $this->request = $request;
        $this->client = $client;
        $this->property = $property;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('DeveloPro - masz nową wiadomość ze strony: '.$this->request->page)->view('admin.crm.client.chat.mail-template',
            [
                'request' => $this->request,
                'client' => $this->client,
                'property' => $this->property,
                'signature' => optional(Auth::user())->signature,
            ]);
    }
}
