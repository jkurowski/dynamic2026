<?php

namespace App\Http\Controllers\Api\History;

use App\Helpers\PropertyAreaTypes;
use App\Http\Controllers\Controller;
use App\Models\Investment;
use DOMDocument;
use DOMXPath;
use League\Csv\Writer;
use SplTempFileObject;

class IndexController extends Controller
{
    public function createXml()
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        // root
        $datasets = $doc->createElementNS("urn:otwarte-dane:harvester:1.13", "ns2:datasets");
        $doc->appendChild($datasets);

        // dataset
        $dataset = $doc->createElement("dataset");
        $dataset->setAttribute("status", "published");
        $datasets->appendChild($dataset);

        $extIdent = bin2hex(random_bytes(18));
        $dataset->appendChild($doc->createElement("extIdent", $extIdent));

        $company = "Kalter Nieruchomości Sp. z o.o.";
        $year = 2025;

        // title
        $title = $doc->createElement("title");
        $title->appendChild($doc->createElement("polish", "Ceny ofertowe mieszkań dewelopera {$company} w {$year} r."));
        $title->appendChild($doc->createElement("english", "Offer prices of apartments of developer {$company} in {$year}."));
        $dataset->appendChild($title);

        // description
        $description = $doc->createElement("description");
        $description->appendChild($doc->createElement("polish", "Zbiór danych zawiera informacje o cenach ofertowych mieszkań dewelopera {$company} udostępniane zgodnie z art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)."));
        $description->appendChild($doc->createElement("english", "The dataset contains information on offer prices of apartments of the developer {$company} made available in accordance with art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)."));
        $dataset->appendChild($description);

        $dataset->appendChild($doc->createElement("updateFrequency", "daily"));
        $dataset->appendChild($doc->createElement("hasDynamicData", "false"));
        $dataset->appendChild($doc->createElement("hasHighValueData", "true"));
        $dataset->appendChild($doc->createElement("hasHighValueDataFromEuropeanCommissionList", "false"));
        $dataset->appendChild($doc->createElement("hasResearchData", "false"));

        $categories = $doc->createElement("categories");
        $category = $doc->createElement("category", "ECON");
        $categories->appendChild($category);
        $dataset->appendChild($categories);

        // resources
        $resources = $doc->createElement("resources");
        $dataset->appendChild($resources);

        $tags = $doc->createElement("tags");
        $tag = $doc->createElement("tag", "Deweloper");
        $tag->setAttribute("lang", "pl");
        $tags->appendChild($tag);
        $dataset->appendChild($tags);

        $path = public_path('datasets/developer.xml');

        if (!file_exists($path)) {
            $doc->save($path);
        } else {
            throw new \Exception("Plik developer.xml już istnieje!");
        }

        return response()->json(['message' => 'Plik XML utworzony', 'path' => $path]);
    }

    /**
     * Aktualizuje istniejący XML dopisując nowy <resource>
     */
    public function updateXml()
    {
        $xmlPath  = public_path('datasets/developer.xml');

        if (!file_exists($xmlPath)) {
            return response()->json(['error' => 'Plik XML nie istnieje. Najpierw uruchom createXml()'], 404);
        }

        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false; // usuwa stare wcięcia
        $doc->formatOutput = true;         // włącza ładne wcięcia
        $doc->load($xmlPath);

        $xpath = new DOMXPath($doc);
        $resources = $xpath->query("//resources")->item(0);

        if (!$resources) {
            return response()->json(['error' => 'Brak sekcji <resources> w XML'], 500);
        }

        $today = now()->format('Y-m-d');
        $investments = Investment::where('status', 1)->with(['company', 'salePoint'])->get();
        foreach ($investments as $investment) {

            $filename = "ceny-ofertowe-" . $investment->slug . "-" . $today . ".csv";
            $csvPath = public_path("datasets/{$filename}");

            // Tworzymy CSV w pamięci
            $csv = Writer::createFromPath($csvPath, 'w+');
            $headers = [
                "Nazwa dewelopera",
                "Forma prawna dewelopera",
                "Nr KRS",
                "Nr wpisu do CEiDG",
                "Nr NIP",
                "Nr REGON",
                "Nr telefonu",
                "Adres poczty elektronicznej",
                "Nr faxu",
                "Adres strony internetowej dewelopera",

                "Województwo adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
                "Powiat adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
                "Gmina adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
                "Miejscowość adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
                "Ulica adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
                "Nr nieruchomości adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
                "Nr lokalu adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
                "Kod pocztowy adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",

                "Województwo adresu lokalu, w którym prowadzona jest sprzedaż",
                "Powiat adresu lokalu, w którym prowadzona jest sprzedaż",
                "Gmina adresu lokalu, w którym prowadzona jest sprzedaż",
                "Miejscowość adresu lokalu, w którym prowadzona jest sprzedaż",
                "Ulica adresu lokalu, w którym prowadzona jest sprzedaż",
                "Nr nieruchomości adresu lokalu, w którym prowadzona jest sprzedaż",
                "Nr lokalu adresu lokalu, w którym prowadzona jest sprzedaż",
                "Kod pocztowy adresu lokalu, w którym prowadzona jest sprzedaż",
                "Dodatkowe lokalizacje, w których prowadzona jest sprzedaż",
                "Sposób kontaktu nabywcy z deweloperem",

                "Województwo lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
                "Powiat lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
                "Gmina lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
                "Miejscowość lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
                "Ulica lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
                "Nr nieruchomości lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
                "Kod pocztowy lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",

                "Rodzaj nieruchomości: lokal mieszkalny, dom jednorodzinny",
                "Nr lokalu lub domu jednorodzinnego nadany przez dewelopera",
                "Cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego [zł]",
                "Data od której cena obowiązuje cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego",
                "Cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni [zł]",
                "Data od której cena obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni",
                "Cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3) [zł]",
                "Data od której obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3)",
                "Rodzaj części nieruchomości będących przedmiotem umowy",
                "Oznaczenie części nieruchomości nadane przez dewelopera",
                "Cena części nieruchomości, będących przedmiotem umowy [zł]",
                "Data od której obowiązuje cena części nieruchomości, będących przedmiotem umowy",
                "Rodzaj pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
                "Oznaczenie pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
                "Wyszczególnienie cen pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali [zł]",
                "Data od której obowiązuje cena wyszczególnionych pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
                "Wyszczególnienie praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego",
                "Wartość praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego [zł]",
                "Data od której obowiązuje cena wartości praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego",
                "Wyszczególnienie rodzajów innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność",
                "Wartość innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność [zł]",
                "Data od której obowiązuje cena wartości innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność",
                "Adres strony internetowej, pod którym dostępny jest prospekt informacyjny"
            ];
            $csv->insertOne($headers);
            $baseUrl = config('app.url');

            foreach ($investment->activeProperties as $property) {
                $row = [
                    $investment->company->name ?? 'X',
                    $investment->company->legal_form ?? 'X',
                    $investment->company->krs_number ?? 'X',
                    $investment->company->ceidg_number ?? 'X',
                    $investment->company->nip ?? 'X',
                    $investment->company->regon ?? 'X',
                    $investment->company->phone ?? 'X',
                    $investment->company->email ?? 'X',
                    $investment->company->fax ?? 'X',
                    $investment->company->website ?? 'X',
                    $investment->company->province ?? 'X',
                    $investment->company->district ?? 'X',
                    $investment->company->municipality ?? 'X',
                    $investment->company->city ?? 'X',
                    $investment->company->street ?? 'X',
                    $investment->company->building_number ?? 'X',
                    $investment->company->apartment_number ?? 'X',
                    $investment->company->postal_code ?? 'X',

                    $investment->salePoint->province ?? 'X',
                    $investment->salePoint->district ?? 'X',
                    $investment->salePoint->municipality ?? 'X',
                    $investment->salePoint->city ?? 'X',
                    $investment->salePoint->street ?? 'X',
                    $investment->salePoint->building_number ?? 'X',
                    $investment->salePoint->apartment_number ?? 'X',
                    $investment->salePoint->postal_code ?? 'X',
                    $investment->salePoint->additional_locations ?? 'X',
                    $investment->salePoint->contact_method ?? 'X',

                    $investment->inv_province ?? 'X',
                    $investment->inv_county ?? 'X',
                    $investment->inv_municipality ?? 'X',
                    $investment->inv_city ?? 'X',
                    $investment->inv_street ?? 'X',
                    $investment->inv_property_number ?? 'X',
                    $investment->inv_postal_code ?? 'X',

                    //Rodzaj nieruchomości: lokal mieszkalny, dom jednorodzinny
                    ($property->type == 1)
                        ? (PropertyAreaTypes::getStatusText($property->type) ?? 'X')
                        : 'X',

                    //Nr lokalu lub domu jednorodzinnego nadany przez dewelopera
                    ($property->type == 1)
                        ? ($property->number ?? 'X')
                        : 'X',

                    //Cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego [zł]
                    (isset($property->area, $property->price_brutto)
                        && is_numeric($property->area) && $property->area > 0
                        && $property->type == 1)
                        ? round((float)$property->price_brutto / (float)$property->area, 2)
                        : 'X',

                    //Data od której cena obowiązuje cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego
                    'X',

                    //Cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni [zł]
                    $property->display_price,

                    //Data od której cena obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni
                    'X',

                    //Cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3) [zł]
                    $property->total_with_related_price,

                    //Data od której obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3)
                    'X',

                    //Rodzaj części nieruchomości będących przedmiotem umowy (piwnice, garaże, komórki lokatorskie, strychy, miejsce postojowe)
                    ($property->type != 1)
                        ? (PropertyAreaTypes::getStatusText($property->type) ?? 'X')
                        : 'X',

                    //Oznaczenie części nieruchomości nadane przez dewelopera
                    ($property->type != 1)
                        ? ($property->number ?? 'X')
                        : 'X',

                    //Cena części nieruchomości, będących przedmiotem umowy [zł]
                    ($property->type != 1)
                        ? ($property->price_brutto ?? 'X')
                        : 'X',

                    //Data od której obowiązuje cena części nieruchomości, będących przedmiotem umowy
                    'X',

                    //Rodzaj pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali (piwnice, garaże, komórki lokatorskie, strychy, miejsce postojowe)
                    ($property->type == 1)
                        ? $property->related_numbers
                        : 'X',

                    //Oznaczenie pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali
                    ($property->type == 1)
                        ? $property->related_numbers
                        : 'X',

                    //Wyszczególnienie cen pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali [zł]
                    ($property->type == 1)
                        ? $property->related_prices
                        : 'X',

                    //Data od której obowiązuje cena wyszczególnionych pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali
                    'X',

                    //Wyszczególnienie praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego
                    'X',

                    //Wartość praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego [zł]
                    'X',

                    //Data od której obowiązuje cena wartości praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego
                    'X',

                    //Wyszczególnienie rodzajów innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność
                    $property->priceComponents->isNotEmpty()
                        ? $property->priceComponents->pluck('pivot.category')->map(fn($c) => $c ?? 'X')->implode(', ')
                        : 'X',

                    //Wartość innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność [zł]
                    $property->priceComponents->isNotEmpty()
                        ? $property->priceComponents->pluck('pivot.value')->map(fn($v) => $v ?? 'X')->implode(', ')
                        : 'X',

                    //Data od której obowiązuje cena wartości innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność
                    'X',

                    //Adres strony internetowej, pod którym dostępny jest prospekt informacyjny
                    isset($investment->file_brochure)
                        ? $baseUrl . "investment/brochure/".$investment->file_brochure
                        : 'X',
                ];

                $csv->insertOne($row);
            }

            $data = [
                'company_name' => $investment->company->name ?? 'Brak nazwy firmy',
                'file_csv' => $filename
            ];

            $this->appendResource($doc, $resources, $data);
        }

        $doc->save($xmlPath);
        $this->generateMD5();

        return response()->json(['message' => 'Dodano nowy resource', 'path' => $xmlPath]);
    }

    public function showAsTable(Investment $investment)
    {
        $today = now()->format('Y-m-d');
        //$investments = Investment::where('status', 1)->with(['company', 'salePoint', 'properties'])->get();
        $investment->with(['company', 'salePoint', 'properties'])->get();

        $baseUrl = config('app.url');

        echo '<table border="1">';
        // Table header
        $headers = [
            "Nazwa dewelopera",
            "Forma prawna dewelopera",
            "Nr KRS",
            "Nr wpisu do CEiDG",
            "Nr NIP",
            "Nr REGON",
            "Nr telefonu",
            "Adres poczty elektronicznej",
            "Nr faxu",
            "Adres strony internetowej dewelopera",

            "Województwo adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
            "Powiat adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
            "Gmina adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
            "Miejscowość adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
            "Ulica adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
            "Nr nieruchomości adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
            "Nr lokalu adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",
            "Kod pocztowy adresu siedziby/głównego miejsca wykonywania działalności gospodarczej dewelopera",

            "Województwo adresu lokalu, w którym prowadzona jest sprzedaż",
            "Powiat adresu lokalu, w którym prowadzona jest sprzedaż",
            "Gmina adresu lokalu, w którym prowadzona jest sprzedaż",
            "Miejscowość adresu lokalu, w którym prowadzona jest sprzedaż",
            "Ulica adresu lokalu, w którym prowadzona jest sprzedaż",
            "Nr nieruchomości adresu lokalu, w którym prowadzona jest sprzedaż",
            "Nr lokalu adresu lokalu, w którym prowadzona jest sprzedaż",
            "Kod pocztowy adresu lokalu, w którym prowadzona jest sprzedaż",
            "Dodatkowe lokalizacje, w których prowadzona jest sprzedaż",
            "Sposób kontaktu nabywcy z deweloperem",

            "Województwo lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
            "Powiat lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
            "Gmina lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
            "Miejscowość lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
            "Ulica lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
            "Nr nieruchomości lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",
            "Kod pocztowy lokalizacji przedsięwzięcia deweloperskiego lub zadania inwestycyjnego",

            "Rodzaj nieruchomości: lokal mieszkalny, dom jednorodzinny",
            "Nr lokalu lub domu jednorodzinnego nadany przez dewelopera",
            "Cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego [zł]",
            "Data od której cena obowiązuje cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego",
            "Cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni [zł]",
            "Data od której cena obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni",
            "Cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3) [zł]",
            "Data od której obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3)",
            "Rodzaj części nieruchomości będących przedmiotem umowy",
            "Oznaczenie części nieruchomości nadane przez dewelopera",
            "Cena części nieruchomości, będących przedmiotem umowy [zł]",
            "Data od której obowiązuje cena części nieruchomości, będących przedmiotem umowy",
            "Rodzaj pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
            "Oznaczenie pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
            "Wyszczególnienie cen pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali [zł]",
            "Data od której obowiązuje cena wyszczególnionych pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali",
            "Wyszczególnienie praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego",
            "Wartość praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego [zł]",
            "Data od której obowiązuje cena wartości praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego",
            "Wyszczególnienie rodzajów innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność",
            "Wartość innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność [zł]",
            "Data od której obowiązuje cena wartości innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność",
            "Adres strony internetowej, pod którym dostępny jest prospekt informacyjny"
        ];

        echo '<thead><tr>';
        foreach ($headers as $header) {
            echo "<th>{$header}</th>";
        }
        echo '</tr></thead>';

        // Table body
        echo '<tbody>';

        foreach ($investment->activeProperties as $property) {

            $row = [
                $investment->company->name ?? 'X',
                $investment->company->legal_form ?? 'X',
                $investment->company->krs_number ?? 'X',
                $investment->company->ceidg_number ?? 'X',
                $investment->company->nip ?? 'X',
                $investment->company->regon ?? 'X',
                $investment->company->phone ?? 'X',
                $investment->company->email ?? 'X',
                $investment->company->fax ?? 'X',
                $investment->company->website ?? 'X',
                $investment->company->province ?? 'X',
                $investment->company->district ?? 'X',
                $investment->company->municipality ?? 'X',
                $investment->company->city ?? 'X',
                $investment->company->street ?? 'X',
                $investment->company->building_number ?? 'X',
                $investment->company->apartment_number ?? 'X',
                $investment->company->postal_code ?? 'X',

                $investment->salePoint->province ?? 'X',
                $investment->salePoint->district ?? 'X',
                $investment->salePoint->municipality ?? 'X',
                $investment->salePoint->city ?? 'X',
                $investment->salePoint->street ?? 'X',
                $investment->salePoint->building_number ?? 'X',
                $investment->salePoint->apartment_number ?? 'X',
                $investment->salePoint->postal_code ?? 'X',
                $investment->salePoint->additional_locations ?? 'X',
                $investment->salePoint->contact_method ?? 'X',

                $investment->inv_province ?? 'X',
                $investment->inv_county ?? 'X',
                $investment->inv_municipality ?? 'X',
                $investment->inv_city ?? 'X',
                $investment->inv_street ?? 'X',
                $investment->inv_property_number ?? 'X',
                $investment->inv_postal_code ?? 'X',

                //Rodzaj nieruchomości: lokal mieszkalny, dom jednorodzinny
                ($property->type == 1)
                    ? (PropertyAreaTypes::getStatusText($property->type) ?? 'X')
                    : 'X',

                //Nr lokalu lub domu jednorodzinnego nadany przez dewelopera
                ($property->type == 1)
                    ? ($property->number ?? 'X')
                    : 'X',

                //Cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego [zł]
                (isset($property->area, $property->price_brutto)
                    && is_numeric($property->area) && $property->area > 0
                    && $property->type == 1)
                    ? round((float)$property->price_brutto / (float)$property->area, 2)
                    : 'X',

                //Data od której cena obowiązuje cena m2 powierzchni użytkowej lokalu mieszkalnego / domu jednorodzinnego
                'X',

                //Cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni [zł]
                $property->display_price,

                //Data od której cena obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego będących przedmiotem umowy stanowiąca iloczyn ceny m2 oraz powierzchni
                'X',

                //Cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3) [zł]
                $property->total_with_related_price,

                //Data od której obowiązuje cena lokalu mieszkalnego lub domu jednorodzinnego uwzględniająca cenę lokalu stanowiącą iloczyn powierzchni oraz metrażu i innych składowych ceny, o których mowa w art. 19a ust. 1 pkt 1), 2) lub 3)
                'X',

                //Rodzaj części nieruchomości będących przedmiotem umowy (piwnice, garaże, komórki lokatorskie, strychy, miejsce postojowe)
                ($property->type != 1)
                    ? (PropertyAreaTypes::getStatusText($property->type) ?? 'X')
                    : 'X',

                //Oznaczenie części nieruchomości nadane przez dewelopera
                ($property->type != 1)
                    ? ($property->number ?? 'X')
                    : 'X',

                //Cena części nieruchomości, będących przedmiotem umowy [zł]
                ($property->type != 1)
                    ? ($property->price_brutto ?? 'X')
                    : 'X',

                //Data od której obowiązuje cena części nieruchomości, będących przedmiotem umowy
                'X',

                //Rodzaj pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali (piwnice, garaże, komórki lokatorskie, strychy, miejsce postojowe)
                ($property->type == 1)
                    ? ($property->related_types->implode(', ') ?? 'X')
                    : 'X',

                //Oznaczenie pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali
                ($property->type == 1)
                    ? ($property->related_numbers ?? 'X')
                    : 'X',

                //Wyszczególnienie cen pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali [zł]
                ($property->type == 1)
                    ? ($property->related_prices ?? 'X')
                    : 'X',

                //Data od której obowiązuje cena wyszczególnionych pomieszczeń przynależnych, o których mowa w art. 2 ust. 4 ustawy z dnia 24 czerwca 1994 r. o własności lokali
                'X',

                //Wyszczególnienie praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego
                'X',

                //Wartość praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego [zł]
                'X',

                //Data od której obowiązuje cena wartości praw niezbędnych do korzystania z lokalu mieszkalnego lub domu jednorodzinnego
                'X',

                //Wyszczególnienie rodzajów innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność
                $property->priceComponents->isNotEmpty()
                    ? $property->priceComponents->pluck('pivot.category')->map(fn($c) => $c ?? 'X')->implode(', ')
                    : 'X',

                //Wartość innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność [zł]
                $property->priceComponents->isNotEmpty()
                    ? $property->priceComponents->pluck('pivot.value')->map(fn($v) => $v ?? 'X')->implode(', ')
                    : 'X',

                //Data od której obowiązuje cena wartości innych świadczeń pieniężnych, które nabywca zobowiązany jest spełnić na rzecz dewelopera w wykonaniu umowy przenoszącej własność
                'X',

                //Adres strony internetowej, pod którym dostępny jest prospekt informacyjny
                isset($investment->file_brochure)
                    ? $baseUrl . "investment/brochure/".$investment->file_brochure
                    : 'X',
            ];

            echo '<tr>';
            foreach ($row as $cell) {
                // If the cell is a collection (like related_types or priceComponents), implode it
                if (is_object($cell) && method_exists($cell, 'implode')) {
                    $cell = $cell->implode(', ');
                } elseif (is_array($cell)) {
                    $cell = implode(', ', $cell);
                }
                echo "<td>{$cell}</td>";
            }
            echo '</tr>';
        }
        echo '</tbody>';

        echo '</table>';
    }

    public function generateMD5()
    {
        // Ścieżka do XML
        $xmlPath = public_path('datasets/developer.xml');

        // Sprawdź czy plik istnieje
        if (!file_exists($xmlPath)) {
            throw new \Exception("Plik XML nie istnieje: " . $xmlPath);
        }

        // Oblicz hash MD5 z pliku
        $md5Hash = md5_file($xmlPath);

        // Ścieżka do pliku .md5
        $md5Path = public_path('datasets/developer.md5');

        // Zapisz hash do pliku
        file_put_contents($md5Path, $md5Hash);

        return "Plik MD5 wygenerowany: " . $md5Path;
    }

    /**
     * Helper: tworzy <resource>
     */
    private function appendResource(DOMDocument $doc, $resourcesNode, $data)
    {
        $today = now()->format('Y-m-d');

        $resource = $doc->createElement("resource");
        $resource->setAttribute("status", "published");

        $resource->appendChild($doc->createElement("extIdent", $this->generateExtIdent()));

        $baseUrl = config('app.url');
        $resource->appendChild(
            $doc->createElement(
                "url",
                $baseUrl . "datasets/".$data['file_csv']
            )
        );

        $title = $doc->createElement("title");
        $title->appendChild($doc->createElement("polish", "Ceny ofertowe mieszkań dewelopera {$data['company_name']} {$today}"));
        $title->appendChild($doc->createElement("english", "Offer prices for developer's apartments {$data['company_name']} {$today}"));
        $resource->appendChild($title);

        $description = $doc->createElement("description");
        $description->appendChild($doc->createElement("polish", "Dane dotyczące cen ofertowych mieszkań dewelopera {$data['company_name']} udostępnione {$today} zgodnie z art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)”[1]."));
        $description->appendChild($doc->createElement("english", "Data on offer prices of apartments of the developer {$data['company_name']} made available {$today} in accordance with art. 19b. ust. 1 Ustawy z dnia 20 maja 2021 r. o ochronie praw nabywcy lokalu mieszkalnego lub domu jednorodzinnego oraz Deweloperskim Funduszu Gwarancyjnym (Dz. U. z 2024 r. poz. 695)."));
        $resource->appendChild($description);

        $resource->appendChild($doc->createElement("availability", "remote"));
        $resource->appendChild($doc->createElement("dataDate", $today));

        $specialSigns = $doc->createElement("specialSigns");
        $specialSign = $doc->createElement("specialSign", "X");
        $specialSigns->appendChild($specialSign);
        $resource->appendChild($specialSigns);

        $resource->appendChild($doc->createElement("hasDynamicData", "false"));
        $resource->appendChild($doc->createElement("hasHighValueData", "true"));
        $resource->appendChild($doc->createElement("hasHighValueDataFromEuropeanCommissionList", "false"));
        $resource->appendChild($doc->createElement("hasResearchData", "false"));
        $resource->appendChild($doc->createElement("containsProtectedData", "false"));

        $resourcesNode->appendChild($resource);
    }

    private function generateExtIdent($prefix = 'dewab_') {
        $date = date('Ymd'); // YYYYMMDD
        $lengthRandom = 36 - strlen($prefix) - strlen($date); // 36 - 6 - 8 = 22
        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, $lengthRandom);
        return $prefix . $randomString . $date;
    }
}
