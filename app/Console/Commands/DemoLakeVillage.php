<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\Property;
use App\Models\PropertyPrice;
use App\Models\PropertyPriceComponent;
use App\Observers\PropertyObserver;
use App\Services\InvestmentService;
use App\Services\PropertyService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Wersja demo segmentów (uwaga klienta 27.10: pełny przykład domu/segmentu od wyszukania do karty).
 * Segmenty Lake Village dostają podstronę u nas (zamiast linku do lakevillage.pl).
 * Od 17.11: 16 domów etapu V z https://lakevillage.pl/etap-5 (numery, powierzchnie, działki, statusy, ceny)
 * + plan osiedla z obrysami domów - zapis jak z panelu: plan inwestycji (investments_plans) i pola
 * „Współrzędne punktów” (cords) / „HTML” (html) domu. Pokoje, taras i rzut nadal robocze.
 * Segmenty spoza pliku (robocze 1A-2D z 27.10) są usuwane.
 *
 *   php artisan demo:lake-village     (po demo:hygge - inwestycja musi istnieć)
 *
 * Można puścić ponownie - segmenty szuka po numerze i nadpisuje. Bez zdarzeń modeli (jak demo:hygge).
 */
class DemoLakeVillage extends Command
{
    protected $signature = 'demo:lake-village';

    protected $description = 'Demo segmentów: Lake Village - 16 domów etapu V z planem osiedla (database/demo/lake-village)';

    private const KATALOG = 'database/demo/lake-village/';

    private const SLUG = 'segmenty-lake-village';

    /** Składniki ceny segmentu (1 obowiązkowy, 2 opcjonalny) */
    private const SKLADNIKI_CENY = [
        ['Koszt aktu notarialnego', 1],
    ];

    /** Część działki pod budynkiem i podjazdem - reszta to ogród */
    private const ZABUDOWA = 70;

    public function handle(PropertyService $propertyService, InvestmentService $investmentService): int
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
                    $this->usunPozostale($inwestycja, $dane);
                });
            });
        });

        // plan osiedla - jak „Plan inwestycji” w panelu (stary plik usuwa sam serwis)
        $plan = base_path(self::KATALOG . $dane['plan']);
        $investmentService->uploadPlan($inwestycja, new UploadedFile($plan, $dane['plan'], 'image/jpeg', null, true));

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
                'cords' => $this->cords($s['obrys']),
                'html' => $this->html($s['obrys'], $nazwa),
                'area' => $typ['powierzchnia'],
                'area_search' => $typ['powierzchnia'],
                'price' => $s['cena'],
                'price_brutto' => $s['cena'],
                'price_search' => $s['cena'],
                'vat' => 0,
                'plot_area' => $s['dzialka'],
                'garden_area' => round($s['dzialka'] - self::ZABUDOWA, 2),
                'terrace_area' => $typ['taras'],
                'promotion_price_show' => 0,
                'active' => 1,
            ])->save();

            $rzut = base_path(self::KATALOG . 'rzuty/' . $typ['rzut']);
            $propertyService->upload('lake-village-' . $s['numer'], new UploadedFile($rzut, $typ['rzut'], 'image/jpeg', null, true), $lokal, true);

            $this->historiaCen($lokal, $s, $typ['powierzchnia']);
            $lokal->priceComponents()->sync($skladniki);

            $this->line(sprintf('  %-14s działka %6.2f m², %s', $nazwa, $s['dzialka'], $s['cena'] ? number_format($s['cena'], 0, ',', ' ') . ' zł' : 'bez ceny'));
        }
    }

    /** Obrys w formacie edytora planu w panelu (mappa-backbone: getAreaTag) */
    private function cords(array $obrys): string
    {
        $punkty = implode(',', array_map(fn ($p) => '{"x":' . $p[0] . ',"y":' . $p[1] . '}', $obrys));

        return '{"shape": "poly","selected": false,"points":[' . $punkty . ']},' . "\n";
    }

    /** Obrys jako znacznik <area> (mappa-backbone: getHTMLAreaTag) - z niego czyta front */
    private function html(array $obrys, string $nazwa): string
    {
        $coords = implode(',', array_map(fn ($p) => $p[0] . ',' . $p[1], $obrys));

        return " <area\n shape=\"poly\"\n href=\"\"\n alt=\"" . e($nazwa) . "\"\n coords=\"" . $coords . "\"\n />\n";
    }

    /** Segmenty inwestycji, których nie ma w pliku (robocze 1A-2D) - razem z rzutami, historią cen i składnikami */
    private function usunPozostale(Investment $inwestycja, array $dane): void
    {
        $numery = array_column($dane['segmenty'], 'numer');

        Property::where('investment_id', $inwestycja->id)->whereNotIn('number', $numery)->get()
            ->each(function (Property $lokal) {
                PropertyPrice::where('property_id', $lokal->id)->delete();
                $lokal->priceComponents()->detach();
                $lokal->delete();
                app(PropertyObserver::class)->deleted($lokal); // pliki rzutu (zdarzenia wyłączone)
                $this->line('  usunięty ' . $lokal->name);
            });
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
