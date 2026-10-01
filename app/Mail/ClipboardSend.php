<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClipboardSend extends Mailable
{
    use Queueable, SerializesModels;

    private $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $properties)
    {
        $this->request = $request;
        $this->properties = $properties;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('app.name').' - wiadomość wysłana z: '.$this->request->form_page)->view('front.mail.clipboard', ['request' => $this->request, 'properties' => $this->properties]);
    }
}
