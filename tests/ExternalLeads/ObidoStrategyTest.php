<?php

namespace Tests\ExternalLeads;

use App\Services\Leads\ObidoStrategy;
use Tests\TestCase;

class ObidoStrategyTest extends TestCase
{
    private $strategy;

    private $email1 = "obido

	
obido

	


	

PROŚBA O KONTAKT KLIENTA obido.pl 
<https://obido.pl?utm_source=obido&utm_medium=email&utm_campaign=forwarded_client&utm_content=contact_request'> 


Przypominamy, że współpraca z obido.pl <https://obido.pl> nie opiera się 
na zasadach prowizyjnych. Klient właśnie poprosił o kontakt - dziękujemy 
za opiekę nad nim.


        Dane klienta:

  * • Imię i nazwisko: Kasia Kamasz (informacja wpisana przez klienta)
  * • Adres e-mail: (informacja wpisana przez klienta)
  * • Numer telefonu: +48 000 <tel:+48506939969>000 000 (informacja
    wpisana przez klienta)
  * • Inwestycja: Na Falistej
    <https://obido.pl/rynek-pierwotny-lodz/na-falistej/?utm_source=obido&utm_medium=email&utm_campaign=forwarded_client&utm_content=contact_request>

  * • mieszkanie: 129
    <https://obido.pl/rynek-pierwotny-lodz/na-falistej/mieszkanie-d_129n.html?utm_source=obido&utm_medium=email&utm_campaign=forwarded_client&utm_content=contact_request>


napisz e-mail do klienta <mailto:kamaszkatarzyna@gmail.com>


        Wiadomość od klienta:

Dzień dobry, jestem w trakcie poszukiwań mieszkania z rynku 
deweloperskiego w pobliżu Państwa inwestycji. Przeglądając obido 
zastanawiam się nad Państwa inwestycją - szczególnie mieszkaniem 129. Z 
góry dziękuję za wsparcie

Kampania tej inwestycji jest realizowana z wykorzystaniem zaawansowanych 
algorytmów targetowania klientów w sieci partnerskiej obido, w tym w 
serwisach grupy OLX: Otodom, OLX.pl, Otomoto.

Klient wyraził zgody niezbędne do zgodnego z aktualnymi przepisami 
przetwarzania przez Państwa jego danych osobowych. Przedstawiliśmy mu 
również klauzulę informacyjną zgodną z RODO. Jeśli mają Państwo 
jakiekolwiek pytania lub uwagi - prosimy o kontakt z opiekunem obido lub 
bezpośrednio z Dyrektorem Działu Obsługi Klienta w obido.pl 
<https://obido.pl>:

Justyna Lachowska
justyna.lachowska@obido.pl
+48 533 312 414 <tel:+48 533 312 414>

Pozdrawiamy,
Zespół obido.pl <https://obido.pl/>

-- 
Jeśli masz jakiekolwiek pytania lub wątpliwości - jesteśmy do Twojej 
dyspozycji. Możesz się z nami skontaktować, pisząc na adres: 
pomoc@obido.pl.

Operatorem serwisu jest Grupa OLX Sp. z o. o. z siedzibą w Poznaniu, ul. 
Królowej Jadwigi 43, Sąd Rejonowy Poznań – Nowe Miasto i Wilda w 
Poznaniu, Wydział VIII Gospodarczy KRS, KRS: 0000568963, NIP: 
7792433421, REGON: 362117960, Kapitał zakładowy: 1 510 000,00 PLN.";
    private $emailWithNoItems = 'Test email with no items';

    protected function setUp(): void
    {
        $this->strategy = new ObidoStrategy();
    }

    public function testProcess()
    {
        $expected = [
            'portal_name' => 'obido.pl',
            'name' => 'Kasia Kamasz',
            'email' => 'kamaszkatarzyna@gmail.com',
            'phone' => '+48506939969',
            'investment_name' => 'Na Falistej',
            'property_name' => '129',
            'message' => $this->email1
        ];
        $expectedNoItems = [
            'portal_name' => 'obido.pl',
            'name' => null,
            'email' => null,
            'phone' => null,
            'investment_name' => null,
            'property_name' => null,
            'message' => $this->emailWithNoItems
        ];

        $result = $this->strategy->process($this->email1);
        $this->assertEquals($expected, $result);

        $result = $this->strategy->process($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $result);
    }

    public function testFindCustomerName()
    {
        $expected = 'Kasia Kamasz';
        $result = $this->strategy->findCustomerName($this->email1);
        $this->assertEquals($expected, $result);

        $expected = null;
        $result = $this->strategy->findCustomerName($this->emailWithNoItems);
        $this->assertEquals($expected, $result);
    }

    public function testFindCustomerEmail()
    {

        $expected = 'kamaszkatarzyna@gmail.com';
        $result = $this->strategy->findCustomerEmail($this->email1);
        $this->assertEquals($expected, $result);

        $expected = null;
        $result = $this->strategy->findCustomerEmail($this->emailWithNoItems);
        $this->assertEquals($expected, $result);
    }
    public function testFindCustomerPhone()
    {
        $expected = '+48506939969';
        $result = $this->strategy->findCustomerPhone($this->email1);
        $this->assertEquals($expected, $result);

        $expected = null;
        $result = $this->strategy->findCustomerPhone($this->emailWithNoItems);
        $this->assertEquals($expected, $result);
    }
    public function testFindInvestmentName()
    {
        $expected = 'Na Falistej';
        $result = $this->strategy->findInvestmentName($this->email1);
        $this->assertEquals($expected, $result);

        $expected = null;
        $result = $this->strategy->findInvestmentName($this->emailWithNoItems);
        $this->assertEquals($expected, $result);
    }
    public function testFindPropertyName()
    {
        $expected = '129';
        $result = $this->strategy->findPropertyName($this->email1);
        $this->assertEquals($expected, $result);

        $expected = null;
        $result = $this->strategy->findPropertyName($this->emailWithNoItems);
        $this->assertEquals($expected, $result);
    }
}
