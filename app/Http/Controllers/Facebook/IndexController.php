<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use Spatie\Valuestore\Valuestore;

// CMS
use App\Repositories\Facebook\FacebookRepository;
use App\Models\FacebookPage;

class IndexController extends Controller
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new FacebookRepository();
    }

    public function redirectToProvider()
    {
        return redirect($this->facebook->redirectTo());
    }

    public function handleProviderCallback()
    {
        $accessToken = $this->facebook->handleCallback();
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $settings->put(['fb_access_token' => $accessToken]);
        return redirect()->route('admin.settings.facebook.index')->with('success', 'Aplikacja podłączona');
    }

    public function delete($access_token)
    {
        $accessToken = settings()->get("fb_access_token");
        try {
            $this->facebook->deletePage($accessToken);
            return redirect()->route('admin.settings.facebook.index')->with('success', 'Aplikacja usunięta');
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function post()
    {
        $page = FacebookPage::find(1);

        if($page){
            $cos = $this->facebook->postLink($page->page_id, $page->access_token, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec molestie lacus odio, rutrum dictum odio vestibulum in. Aliquam erat volutpat. Maecenas aliquet tellus sit amet diam ultricies, auctor finibus neque rutrum. Nam volutpat tempus tellus, et sodales mi fringilla vel. Sed ac lectus tincidunt, laoreet orci nec, luctus diam. Nulla facilisi. Suspendisse nec imperdiet elit, et feugiat magna. Fusce et lorem dui.', 'https://develocrm.test/');
            dd($cos);
        }
    }
}
