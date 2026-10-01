<?php

namespace App\Services\SMS;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $client;
    protected $token;
    protected $sender;
    protected $apiUrl;



    public function __construct()
    {
        $this->client = new Client();
        $this->token = settings('sms_api_token');
        $this->sender = settings('sms_api_sender');
        $this->apiUrl = "https://api.smsapi.pl/sms.do";
    }

    public function isTokenAndSenderSet()
    {
        $isTokenSet = $this->token && strlen($this->token) > 0;
        $isSenderSet = $this->sender && strlen($this->sender) > 0;

        return $isTokenSet && $isSenderSet;
    }

    /**
     * Summary of sendSMS
     * @param string $to '48xxxxxxxxx' or '48xxxxxxxxx,48xxxxxxxxx'
     * @param string $message 
     * @return mixed
     */
    public function sendSMS(string $to, string $message)
    {
        if (!$this->isTokenAndSenderSet()) {
            Log::warning('SMS API token or sender is not set');
            return 'SMS API token or sender is not set';
        }


        try {
            Log::info('Start sending message: ' . $message . ' to: ' . $to);

            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'form_params' => [
                    'to' => $to,
                    'message' => $message,
                    'from' => $this->sender,
                    'encoding' => 'utf-8',
                    'format' => 'json',

                ]
            ]);

            $body = json_decode((string)$response->getBody(), true);
            Log::info('Message sent: ' . $message . ' to: ' . $to);

            return $body;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }
    /**
     * Summary
     * @param string $to '48xxxxxxxxx' or '48xxxxxxxxx,48xxxxxxxxx'
     */

    public function sendNewOfferInfo(string $to)
    {
        $message = "Dzień dobry, otrzymałeś nową ofertę. Sprawdź swoje konto na naszej stronie.";
        return $this->sendSMS($to, $message);
    }
    /**
     * Summary
     * @param string $to '48xxxxxxxxx' or '48xxxxxxxxx,48xxxxxxxxx'
     */
    public function sendOfferReminderInfo(string $to)
    {
        $message = "Dzień dobry, przypominamy o ofercie. Sprawdź swoje konto na naszej stronie.";
        return $this->sendSMS($to, $message);
    }
}
