<?php

namespace Tests\ExternalLeads;

use App\Services\Leads\RynekPierwotnyStrategy;
use PHPUnit\Framework\TestCase;

class RynekPierwotnyStrategyTest extends TestCase
{

    private $strategy;
    private $email1 = 'W Twoim Panelu Dewelopera znajduje się nowe zgłoszenie dotyczące 
*Mieszkanie* w inwestycji *Roosevelta 9* .

Treść wiadomości

Klienta [Imie nazwisko] interesuje: Cena Mieszkania, Dostępność miejsc 
parkingowych i komórek lokatorskich wraz z ceną, Prognozowana wysokość 
czynszu. Poniżej pełna wiadomość Dzień dobry! Spodobało mi się 
mieszkanie nr M6 w Państwa inwestycji Roosevelta 9. Najbardziej zależy 
mi na uzyskaniu poniższych informacji: Cena Mieszkania, Dostępność 
miejsc parkingowych i komórek lokatorskich wraz z ceną, Prognozowana 
wysokość czynszu. Dziękuję i pozdrawiam.

Skontaktuj się z klientem

Zgłoszenie zostało wysłane dnia 30 stycznia 2023, o godz. 14:03.

<http://url5033.esticrm.pl/ls/click?upn=iMk5zmbuQFMb3L9mE2CYxxGVmgCAr6JoM8KNiJSDgNL3g64rV9Irp5gBAH3aelpHXYNsjQ2-2Fc0n464jEflfBA8c2aDZlX-2FPG65DGeQRF2TY-3DG7l8_XKOxuUXlSt3cOPB3daLAdGzXYUhn4wD7Uc0O5fKFYYb7DQTvN4zOUOkupdIaFgSt5QUU5rTlLfQmxVKviiZaiVUKUYpZc7aYywmPa-2FWAJ-2B-2BWOKQ4C-2FF239OsWGCRpUns2mul-2BZEKjoif9erNwtb0hhdd7IQQgzF4dHiydtT-2Fy0wykP92WoPmnw3hdNyZ6TX9PGIS-2B1CFEf-2BaeWIbP6UFJeq66bcVvCRQMs-2FpydC84t3H6NzXcw-2BYCX6gril8xneQaMwvR-2FerXnahg8tXG2Bvb0e1Mp5PqpaY8RlQf1F45V8bV-2FNMUwdCXEjUIrfIjUmm> 


	

Nasza wskazówka

*Kontakt w ciągu godziny od zgłoszenia* zwiększa szansę na sprzedaż aż 
*siedmiokrotnie!* Pamiętaj, że większość osób nie lubi długo czekać na 
kontakt telefoniczny. Internauci są szczególnie niecierpliwi i oczekują 
bardzo szybkiej reakcji na swoje zapytanie. Jeśli tylko możesz, nie 
odkładaj rozmowy z potencjalnym klientem „na później”.

Masz pytania dotyczące systemu Panel Dewelopera?

Aleksandra Furga 	
	533 212 700

	panel@rynekpierwotny.pl

Marka Panel Dewelopera należy do:

Property Group 	Property Group
Jeżeli nie oczekiwałeś niniejszej wiadomości, możesz bezpiecznie ją 
zignorować. Wiadomość została wygenerowana automatycznie, prosimy na nią 
nie odpowiadać. Wydawcą Panelu Dewelopera jest Property Group Sp. z o.o.';
    private $email2 = 'W Twoim Panelu Dewelopera znajduje się nowe zgłoszenie dotyczące *Lokal 
handlowo-usługowy* w inwestycji *Roosevelta 9* .

Treść wiadomości

Klienta [imie naziwsko] interesuje: Cena Mieszkania. Poniżej pełna 
wiadomość Witam! Proszę o przedstawienie szczegółowych informacji na 
temat mieszkania nr U2 w Państwa inwestycji Roosevelta 9. Zależy mi na 
poniższych informacjach: Cena Mieszkania. Dziękuję i pozdrawiam.

Skontaktuj się z klientem

Zgłoszenie zostało wysłane dnia 17 lutego 2023, o godz. 18:07.

<http://url5033.esticrm.pl/ls/click?upn=iMk5zmbuQFMb3L9mE2CYxxGVmgCAr6JoM8KNiJSDgNL3g64rV9Irp5gBAH3aelpHkt2BfxSfJPs1usMkmlANHl54-2FrzWBQ4O2dOAL3gBKB0-3D9pS1_XKOxuUXlSt3cOPB3daLAdGzXYUhn4wD7Uc0O5fKFYYb7DQTvN4zOUOkupdIaFgStvKRMpYmUR-2FvzbJ4ja43i1I3BMd0JC-2F95hCqICBRiqjecs43EakZf-2FfXzQ-2F6o8vek-2FqwZLZ9QNtx8uQdihU9vdhGCPZHG9ztdJmNKiwnw-2FRUesPMINms3UEenGPtuiyDMVbnf0d-2FfuWjkIIMblm0xt-2FYsYwWbf-2FHbUDsvRcM-2BMp1O-2F2x969MPTwZdq7Pt2D-2BSYW6UdDSo4uyX7JQdK3OgJIVS0njaOtDR-2B1EsukqcO-2FF-2BHh0gLh5XyrBTHueA9ze-2B> 


	

Nasza wskazówka

*Kontakt w ciągu godziny od zgłoszenia* zwiększa szansę na sprzedaż aż 
*siedmiokrotnie!* Pamiętaj, że większość osób nie lubi długo czekać na 
kontakt telefoniczny. Internauci są szczególnie niecierpliwi i oczekują 
bardzo szybkiej reakcji na swoje zapytanie. Jeśli tylko możesz, nie 
odkładaj rozmowy z potencjalnym klientem „na później”.

Masz pytania dotyczące systemu Panel Dewelopera?

Aleksandra Furga 	
	533 212 700

	panel@rynekpierwotny.pl

Marka Panel Dewelopera należy do:

Property Group 	Property Group
Jeżeli nie oczekiwałeś niniejszej wiadomości, możesz bezpiecznie ją 
zignorować. Wiadomość została wygenerowana automatycznie, prosimy na nią 
nie odpowiadać. Wydawcą Panelu Dewelopera jest Property Group Sp. z o.o.';

    private $emailWithNoItems = 'Test email with no items';

    protected function setUp(): void
    {
        $this->strategy = new RynekPierwotnyStrategy();
    }

    public function testProcess()
    {

        $expected1 = [
            'portal_name' => 'rynekpierwotny.pl',
            'name' => '[Imie nazwisko]',
            'email' => null,
            'phone' => null,
            'investment_name' => 'Roosevelta 9',
            'property_name' => 'M6',
            'message' => $this->email1
        ];

        $expected2 = [
            'portal_name' => 'rynekpierwotny.pl',
            'name' => '[imie naziwsko]',
            'email' => null,
            'phone' => null,
            'investment_name' => 'Roosevelta 9',
            'property_name' => 'U2',
            'message' => $this->email2
        ];

        $expectedNoItems = [
            'portal_name' => 'rynekpierwotny.pl',
            'name' => null,
            'email' => null,
            'phone' => null,
            'investment_name' => null,
            'property_name' => null,
            'message' => $this->emailWithNoItems
        ];

        $result1 = $this->strategy->process($this->email1);
        $this->assertEquals($expected1, $result1);

        $result2 = $this->strategy->process($this->email2);
        $this->assertEquals($expected2, $result2);

        $resultNoItems = $this->strategy->process($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $resultNoItems);
    }

    public function testFindCustomerName()
    {
        $expected1 = '[Imie nazwisko]';
        $result1 = $this->strategy->findCustomerName($this->email1);
        $this->assertEquals($expected1, $result1);

        $expected2 = '[imie naziwsko]';
        $result2 = $this->strategy->findCustomerName($this->email2);
        $this->assertEquals($expected2, $result2);

        $expected3 = null;
        $result3 = $this->strategy->findCustomerName($this->emailWithNoItems);
        $this->assertEquals($expected3, $result3);
    }

    public function testFindInvestmentName()
    {
        $expected1 = 'Roosevelta 9';
        $result1 = $this->strategy->findInvestmentName($this->email1);
        $this->assertEquals($expected1, $result1);

        $expected2 = 'Roosevelta 9';
        $result2 = $this->strategy->findInvestmentName($this->email2);
        $this->assertEquals($expected2, $result2);

        $expected3 = null;
        $result3 = $this->strategy->findInvestmentName($this->emailWithNoItems);
        $this->assertEquals($expected3, $result3);
    }

    public function testFindPropertyName()
    {
        $expected1 = 'M6';
        $result1 = $this->strategy->findPropertyName($this->email1);
        $this->assertEquals($expected1, $result1);

        $expected2 = 'U2';
        $result2 = $this->strategy->findPropertyName($this->email2);
        $this->assertEquals($expected2, $result2);

        $expected3 = null;
        $result3 = $this->strategy->findPropertyName($this->emailWithNoItems);
        $this->assertEquals($expected3, $result3);
    }
}
