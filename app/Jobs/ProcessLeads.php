<?php

namespace App\Jobs;

use App\Models\ClientMessage;
use App\Repositories\Client\ClientRepository;
use App\Services\AutoAssignLeadsService;
use App\Services\Leads\DominiumStrategy;
use App\Services\Leads\ObidoStrategy;
use App\Services\Leads\OwnStrategy;
use App\Services\Leads\RynekPierwotnyStrategy;
use App\Services\Leads\TabelaOfertStrategy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use Webklex\IMAP\Facades\Client as IMAPClient;


class ProcessLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $strategy;
    private $repository;
    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->repository = app(ClientRepository::class);
        try {
            $client = IMAPClient::account('default');
            $client->connect();
            $folder = $client->getFolder('INBOX');
            $this->repository = app(ClientRepository::class);

            // $unseenMessages = $folder->query()->unseen()->get(); // zmienić na unseen
            $unseenMessages = $folder->query()->all()->get(); // zmienić na unseen

            foreach ($unseenMessages as $message) {

                $this->chooseStrategy($message);
                if (!$this->strategy) {
                    continue;
                }

                $customer_data = $this->strategy->process($message->getTextBody());
                $mapped_customer_data = $this->mapCustomerDataToClient($customer_data);

                if ($mapped_customer_data['email'] && $mapped_customer_data['email'] !== '') {
                    $client = $this->repository->createClient($mapped_customer_data, null, 1,  $mapped_customer_data['portal_name']);

                    // Auto assignment
                    $clientMsgs = ClientMessage::where('client_id', $client->id)->latest()->get()[0];
                    $autoAssignService = new AutoAssignLeadsService($clientMsgs);
                    $autoAssignService->process();
                }
                $message->setFlag('Seen');
            }
        } catch (\Exception $e) {
            Log::error("Error processing leads: " . $e->getMessage());
            Log::error("Error processing leads: " . $e->getTraceAsString());
        }
    }


    public function chooseStrategy($message)
    {
        $decoded_subject = mb_decode_mimeheader($message->getSubject());
        $messageSubject = strtolower($decoded_subject);

        switch (true) {
            case str_contains($messageSubject, 'obido.pl'):
                $this->strategy = new ObidoStrategy();
                break;
            case str_contains($messageSubject, 'rynekpierwotny.pl'):
                $this->strategy = new RynekPierwotnyStrategy();
                break;
            case str_contains($messageSubject, 'dominium.pl'):
                $this->strategy = new DominiumStrategy();
                break;
            case str_contains($messageSubject, 'tabelaofert.pl'):
                $this->strategy = new TabelaOfertStrategy();
                break;
            case str_contains($messageSubject, 'Lead zewnętrzny'):
                $this->strategy = new OwnStrategy();
                break;
            default:
                $this->strategy = null;
        }
    }

    public function mapCustomerDataToClient($customer_data)
    {
        if (!$customer_data) {
            return null;
        }

        return [
            'name' => $customer_data['name'] ?? $customer_data['email'],
            'email' => $customer_data['email'],
            'phone' => $customer_data['phone'],
            'portal_name' => $customer_data['portal_name'],
            'message' => $customer_data['message'],
            'investment_name' => $customer_data['investment_name'],
            'property_name' => $customer_data['property_name'],
            'is_external_source' => true,
            'ip' => 'localhost'
        ];
    }
}
