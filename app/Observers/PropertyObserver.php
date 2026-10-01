<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

// CMS
use App\Repositories\InvestmentRepository;
use App\Models\PropertiesHistory;
use App\Models\PropertyPrice;
use App\Models\Property;

class PropertyObserver
{
    private $investmentRepository;

    public function __construct(InvestmentRepository $investmentRepository)
    {
        $this->investmentRepository = $investmentRepository;
    }

    /**
     * Handle the Property "deleted" event.
     *
     * @param Property $property
     * @return void
     */
    public function deleted(Property $property)
    {
        if (File::isFile(public_path('investment/property/' . $property->file))) {
            File::delete(public_path('investment/property/' . $property->file));
        }
        if (File::isFile(public_path('investment/property/thumbs/' . $property->file))) {
            File::delete(public_path('investment/property/thumbs/' . $property->file));
        }
        if (File::isFile(public_path('investment/property/list/' . $property->file))) {
            File::delete(public_path('investment/property/list/' . $property->file));
        }
        if (File::isFile(public_path('investment/property/webp/' . $property->file_webp))) {
            File::delete(public_path('investment/property/webp/' . $property->file_webp));
        }
        if (File::isFile(public_path('investment/property/thumbs/webp/' . $property->file_webp))) {
            File::delete(public_path('investment/property/thumbs/webp/' . $property->file_webp));
        }
        if (File::isFile(public_path('investment/property/list/webp/' . $property->file_webp))) {
            File::delete(public_path('investment/property/list/webp/' . $property->file_webp));
        }
        if (File::isFile(public_path('investment/property/pdf/' . $property->file_pdf))) {
            File::delete(public_path('investment/property/pdf/' . $property->file_pdf));
        }
    }

    /**
     * Handle the Property "updated" event.
     *
     * @param Property $property
     * @return void
     */
    public function updated(Property $property)
    {
        $originalStatus = $property->getOriginal('status');
        $originalPrice = $property->getOriginal('price_brutto');
        $originalArea = $property->getOriginal('area');

        // Set flags for changes
        $statusChanged = $priceChanged = $areaChanged = false;
        $message = sprintf('Zmiana parametrów: <b>%s</b>.', $property->name);

        // Handle status change
        if ($property->isDirty('status')) {
            $newStatus = $property->status;
            if ($originalStatus !== $newStatus) {
                $message .= sprintf('<br>Status: "<b>%s</b>" -> "<b>%s</b>"', roomStatus($originalStatus), roomStatus($newStatus));
                $statusChanged = true;
            }
        }

        // Handle price change
        if (
            $property->isDirty('price_brutto') ||
            $property->isDirty('promotion_price') ||
            $property->isDirty('highlighted')
        ) {
            $newPrice = $property->price_brutto;
            $originalPrice = $property->getOriginal('price_brutto');
            $originalPromotionPrice = $property->getOriginal('promotion_price');
            $originalHighlighted = $property->getOriginal('highlighted');

            $promotionPrice = $property->promotion_price;
            $highlighted = $property->highlighted;

            // ✅ Case 1: Promotion added or changed
            if (
                $highlighted &&
                $promotionPrice !== null &&
                $promotionPrice != $newPrice
            ) {
                $message .= sprintf(
                    '<br>Promocja: "<b>%s zł</b>" -> "<b>%s zł</b>".',
                    ($originalPrice !== null && $originalPrice !== '')
                        ? number_format($originalPrice, 0, '.', ' ')
                        : 'brak',
                    number_format($promotionPrice, 0, '.', ' ')
                );

                PropertyPrice::create([
                    'property_id' => $property->id,
                    'price_brutto' => is_numeric($originalPrice) ? $originalPrice : 0,
                    'new_price_brutto' => $promotionPrice,
                    'area' => $property->area,
                    'promotion' => true,
                    'changed_at' => now(),
                    'changed_by' => Auth::id(),
                ]);

                $priceChanged = true;

                // ✅ Case 2: Promotion removed (highlighted was true, now false)
            } elseif (
                !$highlighted &&
                $originalHighlighted &&
                $originalPromotionPrice !== null &&
                $originalPromotionPrice != $newPrice
            ) {
                $message .= sprintf(
                    '<br>Koniec promocji: "<b>%s zł</b>" -> "<b>%s zł</b>".',
                    number_format($originalPromotionPrice, 0, '.', ' '),
                    number_format($newPrice, 0, '.', ' ')
                );

                PropertyPrice::create([
                    'property_id' => $property->id,
                    'price_brutto' => $originalPromotionPrice,
                    'new_price_brutto' => $newPrice,
                    'area' => $property->area,
                    'promotion' => false,
                    'changed_at' => now(),
                    'changed_by' => Auth::id(),
                ]);

                $priceChanged = true;

                // ✅ Case 3: Regular price change (no promotion involved)
            } elseif ($originalPrice !== $newPrice) {
                $message .= sprintf(
                    '<br>Cena: "<b>%s zł</b>" -> "<b>%s zł</b>".',
                    ($originalPrice !== null && $originalPrice !== '')
                        ? number_format($originalPrice, 0, '.', ' ')
                        : 'brak',
                    number_format($newPrice, 0, '.', ' ')
                );

                PropertyPrice::create([
                    'property_id' => $property->id,
                    'price_brutto' => is_numeric($originalPrice) ? $originalPrice : 0,
                    'new_price_brutto' => $newPrice,
                    'area' => $property->area,
                    'promotion' => false,
                    'changed_at' => now(),
                    'changed_by' => Auth::id(),
                ]);

                $priceChanged = true;
            }
        }
//        if ($property->isDirty('price_brutto')) {
//            $newPrice = $property->price_brutto;
//            if ($originalPrice !== $newPrice) {
//                $message .= sprintf('<br>Cena: "<b>%s zł</b>" -> "<b>%s zł</b>".',
//                    ($originalPrice !== null && $originalPrice !== '')
//                        ? number_format($originalPrice, 0, '.', ' ')
//                        : 'brak',
//                    number_format($newPrice, 0, '.', ' ')
//                );
//
//                $originalPrice = is_numeric($originalPrice) ? $originalPrice : 0;
//
//                PropertyPrice::create([
//                    'property_id' => $property->id,
//                    'price_brutto' => $originalPrice,
//                    'new_price_brutto' => $newPrice,
//                    'area' => $property->area,
//                    'changed_at' => now(),
//                    'changed_by' => Auth::id(),
//                ]);
//
//                $priceChanged = true;
//            }
//        }

        // Handle area change
        if ($property->isDirty('area')) {
            $newArea = $property->area;
            if ($originalArea !== $newArea) {
                $message .= sprintf('<br>Powierzchnia: "<b>%s m2</b>" -> "<b>%s m2</b>"', $originalArea, $newArea);
                $areaChanged = true;
            }
        }

        // Send the notification only if a change occurred
        if ($statusChanged || $priceChanged || $areaChanged) {
            // Assuming you have a method to fetch the investment or any other context needed
            $investment = $property->investment; // or whatever logic to get the investment
            $this->sendMessageToInvestmentSupervisors($investment, $message);
        }

        // Update related properties
        $relatedProperties = $property->relatedProperties; // Assume this is an empty collection or array.

        foreach ($relatedProperties as $relatedProperty) {
            $relatedProperty->status = $property->status;
            $relatedProperty->client_id = $property->client_id;
            $relatedProperty->save();
        }

        // Save the history for status change
        if ($property->isDirty('status')) {
            $newStatus = $property->status;
            $previousStatus = $property->getOriginal('status');

            // Handle status change actions
            if ($previousStatus == 2 && $newStatus == 1) {
                // If status changed from 2 (reservation) to 1 (for sale)
                $property->reservation_until = null;
                $property->client_id = null;
            }

            if ($previousStatus == 3 && $newStatus == 1) {
                // If status changed from 3 (sold) to 1 (for sale)
                $property->saled_at = null;
                $property->client_id = null;
            }

            if ($previousStatus == 3 && $newStatus == 2) {
                // If status changed from 3 (sold) to 2 (reservation)
                $property->saled_at = null;
            }

            if ($previousStatus == 2 && $newStatus == 3) {
                // If status changed from 2 (reservation) to 3 (sold)
                $property->reservation_until = null;
            }

            // Save property status change history
            PropertiesHistory::create([
                'property_id' => $property->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'created_at' => now(),
                'user_id' => auth()->id(),
                'client_id' => $property->client_id
            ]);
        }

        if ($property->isDirty('client_id')) {
            if ($property->isDirty('status')) {
                $latestHistory = PropertiesHistory::where('property_id', $property->id)->latest()->first();
                if ($latestHistory) {
                    $latestHistory->client_id = $property->client_id;
                    $latestHistory->save();
                }
            }
            else {
                $latestHistory = PropertiesHistory::where('property_id', $property->id)->latest()->first();
                if ($latestHistory) {
                    $newHistory = $latestHistory->replicate();
                    $newHistory->previous_status = $latestHistory->new_status;
                    $newHistory->client_id = $property->client_id;
                    $newHistory->created_at = now();
                    $newHistory->save();
                }
            }
        }
    }

    protected function sendMessageToInvestmentSupervisors($investment, $message): void
    {
        $this->investmentRepository->sendMessageToInvestmentSupervisors($investment, $message);
    }
}
