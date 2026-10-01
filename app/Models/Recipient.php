<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    use Notifiable;

    protected $recipient;
    protected $email;
    protected $notifiable_id;

    public function __construct() {
        $this->recipient = config('mail.from.name');
        $this->email = config('mail.from.address');
    }
}
