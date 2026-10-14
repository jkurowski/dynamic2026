<?php

namespace App\Console\Commands;

use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use App\Models\PropertyPrice;
use App\Models\PropertyPriceComponent;
use App\Services\PropertyService;
use App\Support\MapaStatyczna;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Wersja demo inwestycji dla klienta: Dom Hygge Twin z lokalami z hyggemokotow.pl (database/demo/hygge)
 * + trzy inwestycje tylko jako karty (Konstancin, Zielona Polana, Lake Village - dane z makiety).
 *
 *   php artisan demo:hygge
 *
 * Można puścić ponownie - inwestycje szuka po slugu, lokale po numerze i nadpisuje (z rzutami i historią cen).
 * Bez zdarzeń modeli: observer lokalu dopisywałby historię cen przy każdej zmianie, a dziennik - setki wpisów.
 */
class DemoHygge extends Command
{
    protected $signature = 'demo:hygge';

    protected $description = 'Demo inwestycji: Dom Hygge Twin z lokalami (database/demo/hygge) + 3 inwestycje jako karty';

    private const KATALOG = 'database/demo/hygge/';

    /** Koszty dodatkowe z makiety karty lokalu - składniki ceny (1 obowiązkowy, 2 opcjonalny) */
    private const SKLADNIKI_CENY = [
        ['Koszt aktu notarialnego', 1],
        ['Koszty utrzymania nieruchomości do czasu powstania wspólnoty', 1],
        ['Opłaty do wspólnoty', 1],
        ['Wykończenie pod klucz', 2],
    ];

    public function handle(PropertyService $propertyService): int
    {
        $dane = json_decode(File::get(base_path(self::KATALOG . 'lokale.json')), true);

        Property::withoutEvents(function () use ($dane, $propertyService) {
            Floor::withoutEvents(function () use ($dane, $propertyService) {
                Investment::withoutEvents(function () use ($dane, $propertyService) {
                    DB::transaction(function () use ($dane, $propertyService) {
                        $hygge = $this->inwestycje();
                        $pietra = $this->pietra($hygge);
                        $this->lokale($hygge, $pietra, $dane['lokale'], $propertyService);
                    });
                });
            });
        });

        $this->info('Gotowe. Źródło lokali: ' . $dane['zrodlo']);

        return self::SUCCESS;
    }

    /** @return Investment Dom Hygge Twin */
    private function inwestycje(): Investment
    {
        // [slug, nazwa, miejsce, adres, wstęp, strona zewnętrzna, zdjęcie z szablonu, pinezka z makiety (% left, top), dane]
        $inwestycje = [
            ['dom-hygge-twin', 'Dom Hygge Twin', 'WARSZAWA, MOKOTÓW', 'ul. Bobrowiecka 4B', 'Mieszkania na sprzedaż w stylu Hygge', null, 'lista-hygge', [52.4, 42.3],
                ['type' => 2, 'city_id' => 1, 'date_end' => 'IV kwartał 2025', 'show_prices' => 1,
                 'meta_title' => 'Dom Hygge Twin - mieszkania na Mokotowie', 'meta_description' => 'Apartamenty i lokale usługowe w Domu Hygge Twin przy ul. Bobrowieckiej 4B na warszawskim Mokotowie.']],
            ['konstancin-riverside-house', 'Konstancin Riverside House', 'CHYLICE', 'Konstancin-Jeziorna', 'Domy w Konstancinie-Jeziornej', null, 'lista-konstancin', [59.3, 79.9], ['type' => 3]],
            ['zespol-willowy-zielona-polana', 'Zespół willowy Zielona Polana', 'NOWA WOLA', 'gm. Lesznowola', 'Domy w Nowej Woli', 'http://www.zielonapolana.pl/', 'lista-zielona-polana', [45.5, 81.3], ['type' => 3]],
            ['segmenty-lake-village', 'Segmenty Lake Village', 'ZALESIE GÓRNE', 'gm. Piaseczno', 'Segmenty w Zalesiu Górnym', 'http://lakevillage.pl/', 'lista-lake-village', [44.6, 78.3], ['type' => 3]],
        ];

        $hygge = null;
        foreach ($inwestycje as $kolejnosc => [$slug, $nazwa, $miejsce, $adres, $wstep, $zewnetrzny, $zdjecie, [$left, $top], $inne]) {
            $inwestycja = Investment::firstOrNew(['slug' => $slug]);
            $inwestycja->fill(array_merge([
                'name' => $nazwa,
                'status' => 1,
                'location_label' => $miejsce,
                'address' => $adres,
                'entry_content' => $wstep,
                'external_url' => $zewnetrzny,
                'sort' => $kolejnosc + 1,
            ], MapaStatyczna::wspolrzedne($left, $top), $inne));
            $inwestycja->slug = $slug;
            $inwestycja->file_thumb = $this->zdjecieKarty($slug, $zdjecie);
            if ($slug === 'dom-hygge-twin') {
                $inwestycja->file_header = $this->zdjecieNaglowka($slug, 'inw-hero');
            }
            $inwestycja->save();

            $this->line("inwestycja: {$nazwa}" . ($zewnetrzny ? " (karta -> {$zewnetrzny})" : ''));
            $hygge = $hygge ?? $inwestycja;
        }

        return $hygge;
    }

    /** Zdjęcie karty prosto z szablonu (JPG + WebP 843x464) - bez powiększania do kadru z panelu */
    private function zdjecieKarty(string $slug, string $zdjecie): string
    {
        File::ensureDirectoryExists(public_path(Investment::KATALOG_KART . 'webp'));
        File::copy(public_path("img/{$zdjecie}.jpg"), public_path(Investment::KATALOG_KART . "demo-{$slug}.jpg"));
        File::copy(public_path("img/{$zdjecie}.webp"), public_path(Investment::KATALOG_KART . "webp/demo-{$slug}.webp"));

        return "demo-{$slug}.jpg";
    }

    /** Nagłówek podstrony z szablonu (1019x650) */
    private function zdjecieNaglowka(string $slug, string $zdjecie): string
    {
        File::ensureDirectoryExists(public_path('investment/header/webp'));
        File::copy(public_path("img/{$zdjecie}.jpg"), public_path("investment/header/demo-{$slug}.jpg"));
        File::copy(public_path("img/{$zdjecie}.webp"), public_path("investment/header/webp/demo-{$slug}.webp"));

        return "demo-{$slug}.jpg";
    }

    /** Piętra inwestycji budynkowej: parter + 1-5 → [numer => id] */
    private function pietra(Investment $hygge): array
    {
        $pietra = [];
        foreach (range(0, 5) as $numer) {
            $pietro = Floor::firstOrNew(['investment_id' => $hygge->id, 'number' => (string) $numer]);
            $pietro->fill([
                'building_id' => 0,
                'name' => $numer === 0 ? 'Parter' : "Piętro {$numer}",
                'position' => $numer,
                'type' => 1,
                'active' => 1,
            ])->save();
            $pietra[$numer] = $pietro->id;
        }

        return $pietra;
    }

    private function lokale(Investment $hygge, array $pietra, array $lokale, PropertyService $propertyService): void
    {
        $skladniki = collect(self::SKLADNIKI_CENY)->map(fn ($s) => [PropertyPriceComponent::firstOrCreate(['name' => $s[0]])->id, $s[1]]);

        // Kolejność jak w klatkach: A.1, A.4 ... B.1 ... C.15, U.1
        usort($lokale, fn ($a, $b) => [$a['klatka'], (int) Str::after($a['numer'], '.')] <=> [$b['klatka'], (int) Str::after($b['numer'], '.')]);

        foreach ($lokale as $kolejnosc => $l) {
            $uslugowy = ($l['rodzaj'] ?? null) === 'lokal_uslugowy';
            $nazwa = ($uslugowy ? 'Lokal usługowy ' : 'Mieszkanie ') . $l['numer'];
            [$balkon1, $balkon2, $balkon3] = array_pad($l['balkony'], 3, null);

            $lokal = Property::firstOrNew(['investment_id' => $hygge->id, 'number' => $l['numer']]);
            $lokal->fill([
                'floor_id' => $pietra[$l['pietro']],
                'building_id' => 0,
                'type' => $uslugowy ? Property::TYP_LOKAL_USLUGOWY : Property::TYP_MIESZKANIE,
                'status' => $l['status'],
                'name' => $nazwa,
                'name_list' => $nazwa,
                'number_order' => $kolejnosc + 1,
                'rooms' => $l['pokoje'],
                'area' => $l['powierzchnia'],
                'area_search' => $l['powierzchnia'],
                'price' => $l['cena'],
                'price_brutto' => $l['cena'],
                'price_search' => $l['cena'],
                'vat' => 0,
                'balcony_area' => $balkon1,
                'balcony_area_2' => $balkon2,
                // Trzeci balkon (C.1) - w panelu nie ma pola „Balkon 3”, najbliższy jest taras
                'terrace_area' => $balkon3,
                'walk_3d' => $l['spacer_3d'],
                'promotion_price_show' => 0,
                'active' => 1,
            ])->save();

            $this->pliki($lokal, $l, $propertyService);
            $this->historiaCen($lokal, $l);

            $lokal->priceComponents()->sync($uslugowy ? [] : $skladniki->mapWithKeys(fn ($s) => [$s[0] => ['category' => $s[1]]])->all());

            $this->line(sprintf('  %-22s %s', $nazwa, $l['cena'] ? number_format($l['cena'], 2, ',', ' ') . ' zł' : 'cena na zapytanie'));
        }
    }

    /** Rzut (karta, kafelek, lista + WebP) i karta PDF - tak samo jak przy wgrywaniu w panelu */
    private function pliki(Property $lokal, array $l, PropertyService $propertyService): void
    {
        $tytul = 'hygge-' . $l['numer'];

        $rzut = base_path(self::KATALOG . 'rzuty/' . $l['rzut']);
        if (File::isFile($rzut)) {
            $propertyService->upload($tytul, new UploadedFile($rzut, $l['rzut'], 'image/jpeg', null, true), $lokal, true);
        }

        $pdf = $l['pdf'] ? base_path(self::KATALOG . 'pdf/' . $l['pdf']) : null;
        if ($pdf && File::isFile($pdf)) {
            File::ensureDirectoryExists(public_path(Property::KATALOG_RZUTOW . 'pdf'));
            $propertyService->uploadPdf($tytul, new UploadedFile($pdf, $l['pdf'], 'application/pdf', null, true), $lokal, true);
        }
    }

    /**
     * Historia cen z hyggemokotow.pl (od najnowszej): każda kolejna para to zmiana stara -> nowa cena.
     * Najstarszy wpis to cena początkowa - bez zmiany.
     */
    private function historiaCen(Property $lokal, array $l): void
    {
        PropertyPrice::where('property_id', $lokal->id)->delete();

        $historia = $l['historia_cen'];
        for ($i = 0; $i < count($historia) - 1; $i++) {
            PropertyPrice::create([
                'property_id' => $lokal->id,
                'price_brutto' => $historia[$i + 1][1],
                'new_price_brutto' => $historia[$i][1],
                'area' => $l['powierzchnia'],
                'promotion' => 0,
                'changed_at' => $historia[$i][0],
                'changed_by' => null,
            ]);
        }
    }
}
