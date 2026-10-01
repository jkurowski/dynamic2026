<?php

namespace Tests\ExternalLeads;

use App\Services\Leads\TabelaOfertStrategy;
use PHPUnit\Framework\TestCase;

class TabelaOfertStrategyTest extends TestCase
{
    private $strategy;

    private $email1 = "Mail 📧
https://tabelaofert.pl <https://tabelaofert.pl>


  Oferta Ruczaj Korso III - D7

Telefon:        +48 600 122 258
Adres e-mail:   klaudiastanczyk1@gmail.com
<mailto:klaudiastanczyk1@gmail.com>
Treść wiadomości:       Zainteresowała mnie oferta D7, proszę o
przedstawienie oferty drogą elektroniczną/telefoniczną.
Oferent:        WOJT BUD
Nazwa inwestycji:       Ruczaj Korso III
Adres:  Kraków, Dębniki, ul. Doktora Jana Piltza 23
Numer oferty:   D7
Metraż:         34,20 m²
Link do oferty:         kliknij tutaj
<https://tabelaofert.pl/mieszkanie-dwupokojowe-na-sprzedaz-doktora-jana-piltza-23-krakow-debniki,9970605>


Wiadomość została wygenerowana automatycznie. Prosimy na nią nie odpowiadać.

Bądź na bieżąco - polub tabelaofert.pl na Facebooku
<https://facebook.com/tabelaofert>
Agencje nieruchomości
<https://tabelaofert.pl/oferta-dla-agencji-nieruchomosci?utm_source=mail&utm_medium=e-mail&utm_campaign=zgloszenia>
| Deweloperzy
<https://tabelaofert.pl/oferta-dla-dewelopera?utm_source=mail&utm_medium=e-mail&utm_campaign=zgloszenia>
| Oferta Google Ads - Oficjalny Partner Google
<https://tabelaofert.pl/oferta-google-ads?utm_source=mail&utm_medium=e-mail&utm_campaign=zgloszenia>";

    private $emailWithNoItems = "Mail";

    protected function setUp(): void
    {
        $this->strategy = new TabelaOfertStrategy();
    }


    public function testProcess()
    {
        $expected1 =         [
            'portal_name' => 'tabelaofert.pl',
            'name' => null,
            'email' => 'klaudiastanczyk1@gmail.com',
            'phone' => '+48 600 122 258',
            'investment_name' => 'Ruczaj Korso III',
            'property_name' => 'D7',
            'message' => $this->email1
        ];
        $result1 = $this->strategy->process($this->email1);
        $this->assertEquals($expected1, $result1);

        $expectedWithNoItems = [
            'portal_name' => 'tabelaofert.pl',
            'name' => null,
            'email' => null,
            'phone' => null,
            'investment_name' => null,
            'property_name' => null,
            'message' => $this->emailWithNoItems
        ];
        $resultWithNoItems = $this->strategy->process($this->emailWithNoItems);
        $this->assertEquals($expectedWithNoItems, $resultWithNoItems);
    }

    public function testFindCustomerEmail()
    {
        $expected1 = 'klaudiastanczyk1@gmail.com';
        $result1 = $this->strategy->findCustomerEmail($this->email1);
        $this->assertEquals($expected1, $result1);

        $expectedWithNoEmail = null;
        $resultWithNoEmail = $this->strategy->findCustomerEmail($this->emailWithNoItems);
        $this->assertEquals($expectedWithNoEmail, $resultWithNoEmail);
    }

    public function testFindCustomerPhone()
    {
        $expected1 = '+48 600 122 258';
        $result1 = $this->strategy->findCustomerPhone($this->email1);
        $this->assertEquals($expected1, $result1);

        $expectedWithNoPhone = null;
        $resultWithNoPhone = $this->strategy->findCustomerPhone($this->emailWithNoItems);
        $this->assertEquals($expectedWithNoPhone, $resultWithNoPhone);
    }

    public function testFindInvestmentName()
    {
        $expected1 = 'Ruczaj Korso III';
        $result1 = $this->strategy->findInvestmentName($this->email1);
        $this->assertEquals($expected1, $result1);

        $expectedWithNoInvestment = null;
        $resultWithNoInvestment = $this->strategy->findInvestmentName($this->emailWithNoItems);
        $this->assertEquals($expectedWithNoInvestment, $resultWithNoInvestment);
    }

    public function testFindPropertyName()
    {
        $expected1 = 'D7';
        $result1 = $this->strategy->findPropertyName($this->email1);
        $this->assertEquals($expected1, $result1);

        $expectedWithNoProperty = null;
        $resultWithNoProperty = $this->strategy->findPropertyName($this->emailWithNoItems);
        $this->assertEquals($expectedWithNoProperty, $resultWithNoProperty);
    }
}
