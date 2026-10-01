<?php
if (! function_exists('parse_text')) {
    function parse_text($string, $json = false)
    {
        if ($json) {
            // Decode the JSON and get the content based on the current locale
            $locale = app()->getLocale();
            $data = json_decode($string, true);

            // If locale exists in JSON, use it; otherwise, fallback to original string
            $string = $data[$locale] ?? $string;
        }

        $output = preg_replace_callback('/\[galeria=(.*)](.*)\[\/galeria\]/', 'makeGallery', $string);

        return str_replace(
            array("</div>\n</p>", "<p><div"),
            array("</div>", "<div"),
            $output
        );
    }
}

if (! function_exists('makeGallery')) {
    function makeGallery($input)
    {
        $photos = \App\Models\Image::where('gallery_id', $input[2])->orderBy("sort")->get();

        if ($input[1] == 'gallery') {
            return view('front.parse.gallery', ['list' => $photos])->render();
        }

        if ($input[1] == 'galeria') {
            return view('front.parse.gallery', ['list' => $photos])->render();
        }

        if ($input[1] == 'carousel') {
            return view('front.parse.slick-carousel', ['list' => $photos])->render();
        }

        if ($input[1] == 'slider') {
            return view('front.parse.slider', ['list' => $photos])->render();
        }
    }
}
