<?php

use App\Repositories\Facebook\FacebookRepository;

if (! function_exists('fb_app_info')) {

    function fb_app_info()
    {
        $facebook = new FacebookRepository();
        $response = $facebook->checkValidate(settings()->get("fb_app_id"), settings()->get("fb_access_token"));

        if($response) {
            return $response->getDecodedBody();
        }
    }
}
