<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Services\SMS\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OffersSmsReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private SMSService $sms_service;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sms_service = app(SMSService::class);
        $offers = Offer::where('sended_at', '<=', now()->subDays(2))->whereNull('readed_at')->get();
        $clients_phone_numbers = $offers->pluck('client.phone')->toArray();
        $numbersString = implode(',', $clients_phone_numbers);


        $this->sms_service->sendOfferReminderInfo($numbersString);
    }
}
