<?php

use App\Repositories\Facebook\FacebookRepository;

if (! function_exists('fb_get_pages')) {

    function fb_get_pages()
    {
        $facebook = new FacebookRepository();
        $response = $facebook->getPages(settings()->get("fb_access_token"));
        if($response) {
            return $response;
        }
    }
}
