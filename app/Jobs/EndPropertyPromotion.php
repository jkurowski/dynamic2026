<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// CMS
use App\Models\Property;
use Illuminate\Support\Facades\Log;

class EndPropertyPromotion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $propertyId;

    public function __construct($propertyId)
    {
        $this->propertyId = $propertyId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $property = Property::find($this->propertyId);

        if ($property) {
            $property->highlighted = 0;
            $property->save();
        } else {
            Log::warning('Property not found with ID: ' . $this->propertyId);
        }
    }
}
