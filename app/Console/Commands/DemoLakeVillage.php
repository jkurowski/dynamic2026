<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Property;
use App\Models\PropertyPrice;
use App\Models\PropertyPriceComponent;
use App\Services\PropertyService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Wersja demo segmentów (uwaga klienta 27.10: pełny przykład domu/segmentu od wyszukania do karty).
 * Segmenty Lake Village dostają podstronę u nas (zamiast linku do lakevillage.pl) i 8 segmentów
 * z danymi roboczymi i roboczymi rzutami (database/demo/lake-village).
 *
 *   php artisan demo:lake-village     (po demo:hygge - inwestycja musi istnieć)
 *
 * Można puścić ponownie - segmenty szuka po numerze i nadpisuje. Bez zdarzeń modeli (jak demo:hygge).
 */
class DemoLakeVillage extends Command
{
    protected $signature = 'demo:lake-village';

    protected $description = 'Demo segmentów: Lake Village z 8 segmentami (dane robocze, database/demo/lake-village)';

    private const KATALOG = 'database/demo/lake-village/';

    private const SLUG = 'segmenty-lake-village';

    /** Składniki ceny segmentu (1 obowiązkowy, 2 opcjonalny) */
    private const SKLADNIKI_CENY = [
        ['Koszt aktu notarialnego', 1],
        ['Wykończenie pod klucz', 2],
    ];

    /** Część działki pod budynkiem i podjazdem - reszta to ogród */
    private const ZABUDOWA = 70;

    public function handle(PropertyService $propertyService): int
    {
        $inwestycja = Investment::where('slug', self::SLUG)->first();
        if (!$inwestycja) {
            $this->error('Brak inwestycji ' . self::SLUG . ' - najpierw php artisan demo:hygge');

            return self::FAILURE;
        }

        $dane = json_decode(File::get(base_path(self::KATALOG . 'lokale.json')), true);

        Property::withoutEvents(function () use ($inwestycja, $dane, $propertyService) {
            Investment::withoutEvents(function () use ($inwestycja, $dane, $propertyService) {
                DB::transaction(function () use ($inwestycja, $dane, $propertyService) {
                    $inwestycja->fill([
                        'external_url' => null,
                        'show_prices' => 1,
                        'date_end' => 'II kwartał 2027',
                        'meta_title' => 'Segmenty Lake Village - Zalesie Górne',
                        'meta_description' => 'Segmenty z ogrodami w Zalesiu Górnym, gm. Piaseczno.',
                    ]);
                    $inwestycja->file_header = $this->zdjecieNaglowka('lista-lake-village');
                    $inwestycja->save();

                    $this->segmenty($inwestycja, $dane, $propertyService);
                });
            });
        });

        $this->info('Gotowe. Źródło: ' . $dane['zrodlo']);

        return self::SUCCESS;
    }

    /** Nagłówek podstrony - wizualizacja karty z szablonu */
    private function zdjecieNaglowka(string $zdjecie): string
    {
        File::ensureDirectoryExists(public_path('investment/header/webp'));
        File::copy(public_path("img/{$zdjecie}.jpg"), public_path('investment/header/demo-' . self::SLUG . '.jpg'));
        File::copy(public_path("img/{$zdjecie}.webp"), public_path('investment/header/webp/demo-' . self::SLUG . '.webp'));

        return 'demo-' . self::SLUG . '.jpg';
    }

    private function segmenty(Investment $inwestycja, array $dane, PropertyService $propertyService): void
    {
        $skladniki = collect(self::SKLADNIKI_CENY)
            ->mapWithKeys(fn ($s) => [PropertyPriceComponent::firstOrCreate(['name' => $s[0]])->id => ['category' => $s[1]]])
            ->all();

        foreach ($dane['segmenty'] as $kolejnosc => $s) {
            $typ = $dane['typy'][$s['typ']];
            $nazwa = 'Segment ' . $s['numer'];

            $lokal = Property::firstOrNew(['investment_id' => $inwestycja->id, 'number' => $s['numer']]);
            $lokal->fill([
                'floor_id' => null,
                'building_id' => 0,
                'type' => Property::TYP_SEGMENT,
                'status' => $s['status'],
                'name' => $nazwa,
                'name_list' => $nazwa,
                'number_order' => $kolejnosc + 1,
                'rooms' => $typ['pokoje'],
                'area' => $typ['powierzchnia'],
                'area_search' => $typ['powierzchnia'],
                'price' => $s['cena'],
                'price_brutto' => $s['cena'],
                'price_search' => $s['cena'],
                'vat' => 0,
                'plot_area' => $s['dzialka'],
                'garden_area' => $s['dzialka'] - self::ZABUDOWA,
                'terrace_area' => $typ['taras'],
                'promotion_price_show' => 0,
                'active' => 1,
            ])->save();

            $rzut = base_path(self::KATALOG . 'rzuty/' . $typ['rzut']);
            $propertyService->upload('lake-village-' . $s['numer'], new UploadedFile($rzut, $typ['rzut'], 'image/jpeg', null, true), $lokal, true);

            $this->historiaCen($lokal, $s, $typ['powierzchnia']);
            $lokal->priceComponents()->sync($skladniki);

            $this->line(sprintf('  %-12s typ %s, działka %d m², %s zł', $nazwa, $s['typ'], $s['dzialka'], number_format($s['cena'], 0, ',', ' ')));
        }
    }

    /** Historia cen od najnowszej - każda para to zmiana stara -> nowa (jak w demo:hygge) */
    private function historiaCen(Property $lokal, array $s, float $powierzchnia): void
    {
        PropertyPrice::where('property_id', $lokal->id)->delete();

        $historia = $s['historia_cen'];
        for ($i = 0; $i < count($historia) - 1; $i++) {
            PropertyPrice::create([
                'property_id' => $lokal->id,
                'price_brutto' => $historia[$i + 1][1],
                'new_price_brutto' => $historia[$i][1],
                'area' => $powierzchnia,
                'promotion' => 0,
                'changed_at' => $historia[$i][0],
                'changed_by' => null,
            ]);
        }
    }
}
