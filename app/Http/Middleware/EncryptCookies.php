<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        "dp_fbclid",
        "dp_gclid",
        "dp_source",
        "dp_medium",
        "dp_campaign",
        "dp_content",
        "dp_term"
    ];
}
