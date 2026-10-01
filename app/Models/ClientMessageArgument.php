<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ClientMessageArgument extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_msg_arguments';
    protected $fillable = ['msg_id', 'argument', 'value', 0];

    public function investment()
    {
        return $this->belongsTo(Investment::class, 'value', 'id');
    }

    public function clientMessage()
    {
        return $this->belongsTo(ClientMessage::class, 'msg_id');
    }
}
