<?php namespace App\Repositories\Facebook;

use App\Models\FacebookPage;
use App\Repositories\BaseRepository;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Mockery\CountValidator\Exception;
use Spatie\Valuestore\Valuestore;

class FacebookRepository
{
//    protected $facebook;
//    protected $repository;
//
//    public function __construct()
//    {
//        $this->facebook = new Facebook([
//            'app_id' => config('providers.facebook.app_id'),
//            'app_secret' => config('providers.facebook.app_secret'),
//            'default_graph_version' => 'v15.0'
//        ]);
//    }
//
//    public function checkValidate($fb_app_id, $accessToken)
//    {
//        try {
//            return $this->facebook->get('/'.$fb_app_id.'/', $accessToken);
//        } catch (\Exception $exception) {
//            return false;
//        }
//    }
//
//    public function redirectTo(): string
//    {
//        $helper = $this->facebook->getRedirectLoginHelper();
//
//        $permissions = [
//            'pages_manage_posts',
//            'pages_read_engagement'
//        ];
//
//        $redirectUri = config('app.url') . '/auth/facebook/callback';
//
//        return $helper->getLoginUrl($redirectUri, $permissions);
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function handleCallback(): string
//    {
//        $helper = $this->facebook->getRedirectLoginHelper();
//
//        if (request('state')) {
//            $helper->getPersistentDataHandler()->set('state', request('state'));
//        }
//
//        try {
//            $accessToken = $helper->getAccessToken();
//        } catch(FacebookResponseException $e) {
//            throw new Exception("Graph returned an error: {$e->getMessage()}");
//        } catch(FacebookSDKException $e) {
//            throw new Exception("Facebook SDK returned an error: {$e->getMessage()}");
//        }
//
//        if (!isset($accessToken)) {
//            throw new Exception('Access token error');
//        }
//
//        if (!$accessToken->isLongLived()) {
//            try {
//                $oAuth2Client = $this->facebook->getOAuth2Client();
//                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
//            } catch (FacebookSDKException $e) {
//                throw new Exception("Error getting a long-lived access token: {$e->getMessage()}");
//            }
//        }
//
//        $accessToken = $accessToken->getValue();
//        $pages = $this->getPages($accessToken);
//
//        FacebookPage::query()->truncate();
//        foreach($pages as $page){
//            FacebookPage::create($page);
//            $url = 'https://graph.facebook.com/'.$page["page_id"].'/picture?type=large';
//            $data = file_get_contents($url);
//            $fileName = $page["page_id"].'.jpg';
//            $file_path = public_path('uploads/facebook/' . $fileName);
//            $file = fopen($file_path , 'w+');
//            fputs($file, $data);
//            fclose($file);
//        }
//
//        return $accessToken;
//    }
//
//    public function getPages($accessToken)
//    {
//        try {
//            $pages = $this->facebook->get('/me/accounts', $accessToken);
//            $pages = $pages->getGraphEdge()->asArray();
//            return array_map(function ($item) {
//                return [
//                    'access_token' => $item['access_token'],
//                    'page_id' => $item['id'],
//                    'name' => $item['name']
//                ];
//            }, $pages);
//        } catch (\Exception $exception) {
//            return false;
//        }
//    }
//
//    public function deletePage($accessToken)
//    {
//        $settings = Valuestore::make(storage_path('app/settings.json'));
//        try {
//            $response = $this->facebook->delete('/me/permissions', [], $accessToken);
//            $settings->forget('fb_page_token');
//            $settings->forget('fb_page_id');
//
//            return $response->getDecodedBody();
//        } catch (\Exception $exception) {
//            echo $exception;
//            return false;
//        }
//    }
//
//    /**
//     */
//    public function getPageAccessToken($page_id, $accessToken)
//    {
//        try {
//            $response = $this->facebook->get($page_id.'?fields=access_token', $accessToken);
//            return $response->getAccessToken();
//        } catch (\Exception $exception) {
//            echo $exception;
//            return false;
//        }
//    }
//
//    /**
//     * @throws Exception
//     * @throws FacebookSDKException
//     */
//    public function post($accountId, $accessToken, $content, $images = [])
//    {
//        $data = ['message' => $content];
//
//        $attachments = $this->uploadImages($accountId, $accessToken, $images);
//
//        foreach ($attachments as $i => $attachment) {
//            $data["attached_media[$i]"] = "{\"media_fbid\":\"$attachment\"}";
//        }
//
//        try {
//            return $this->postData($accessToken, "$accountId/feed", $data);
//        } catch (\Exception $exception) {
//            echo $exception->getMessage();
//            return false;
//        }
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function postLink($accountId, $accessToken, $content, $url)
//    {
//        $data = [
//            'message' => $content,
//            'link' => $url
//        ];
//
//        try {
//            return $this->postData($accessToken, "$accountId/feed", $data);
//        } catch (\Exception $exception) {
//            echo $exception->getMessage();
//            return false;
//        }
//    }
//
//    /**
//     * @throws FacebookSDKException
//     */
//    private function uploadImages($accountId, $accessToken, $images = []): array
//    {
//        $attachments = [];
//
//        foreach ($images as $image) {
//            if (!file_exists($image)) continue;
//
//            $data = [
//                'source' => $this->facebook->fileToUpload($image),
//            ];
//
//            try {
//                $response = $this->postData($accessToken, "$accountId/photos?published=false", $data);
//                if ($response) $attachments[] = $response['id'];
//            } catch (\Exception $exception) {
//                throw new Exception("Error while posting: {$exception->getMessage()}", $exception->getCode());
//            }
//        }
//
//        return $attachments;
//    }
//
//    /**
//     * @throws Exception
//     */
//    private function postData($accessToken, $endpoint, $data): \Facebook\GraphNodes\GraphNode
//    {
//        try {
//            $response = $this->facebook->post(
//                $endpoint,
//                $data,
//                $accessToken
//            );
//            return $response->getGraphNode();
//
//        } catch (FacebookResponseException|FacebookSDKException $e) {
//            throw new Exception($e->getMessage(), $e->getCode());
//        }
//    }
}
