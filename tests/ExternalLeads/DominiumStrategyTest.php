<?php

namespace App\Services\Leads\Tests;

use PHPUnit\Framework\TestCase;
use App\Services\Leads\DominiumStrategy;

class DominiumStrategyTest extends TestCase
{
    private $strategy;
    private $email1 = "Jestem zainteresowany inwestycją Ruczaj Korso - etap III ul. Jana Piltza \r\n
23.\r\n
Link do tej oferty: \r\n
https://www.dominium.pl/pl/inwestycje/szczegoly/opis/3707/ul-jana-piltza-23-ruczaj-korso-etap-iii\r\n
\r\n
Proszę o kontakt.\r\n
\r\n
Dodatkowe uwagi: Proszę o ofertę mieszkań do 40 metrów.\r\n
\r\n
\r\n
\r\n
Wyrażono zgodę: * Potwierdzam, że zapoznałem się z treścią Regulaminu \r\n
oraz Polityki Prywatności i je akceptuję.\r\n
\r\n
NIE WYRAŻONO zgody na: otrzymywanie od Ogłoszeniodawcy informacji \r\n
handlowej telefonicznie na podany przeze mnie w formularzu numer telefonu.\r\n
\r\n
Treść przedstawionego użytkownikowi obowiązku informacyjnego: \r\n
Administratorami Twoich danych osobowych są Robert Borzym i Bartłomiej \r\n
Dyląg, działający pod firmą Friscom s.c. Robert Borzym, Bartłomiej Dyląg \r\n
('FRISCOM'). Wysyłając zapytanie i akceptując Regulamin zawierasz umowę \r\n
z FRISCOM, który będzie przetwarzał dane osobowe przekazane przez Ciebie \r\n
za pomocą formularza w celu wykonania tej umowy oraz dla prawnie \r\n
uzasadnionych celów FRISCOM. Dane te będą przekazane Ogłoszeniodawcy ( \r\n
Wojt-Bud Sp. z o.o. z siedzibą 30-348 Kraków, ul. Chmieleniec 2B/7, KRS \r\n
0000015967, NIP 945-19-41-198, REGON 356270169 ), który z chwilą \r\n
zebrania danych staje się również ich administratorem i może je \r\n
przetwarzać w celu skontaktowania się z Tobą w odpowiedzi na przesłane \r\n
przez Ciebie zapytanie. Dane przekazujemy także podmiotom trzecim, z \r\n
którymi łączy FRISCOM stosunek powierzenia. Masz prawo do dostępu do \r\n
swoich danych, ich sprostowania, usunięcia lub ograniczenia \r\n
przetwarzania. W określonych przypadkach masz prawo do wniesienia \r\n
sprzeciwu wobec przetwarzania danych na potrzeby takiego marketingu, \r\n
przenoszenia danych, a także do wniesienia skargi do organu nadzorczego. \r\n
Czytaj więcej...\r\n
\r\n
Telefon kontaktowy: 535970570 E-mail: justyna.dylczyk@gmail.com";

private $email2 = "Jestem zainteresowany(a) lokalem numer: 2.C, metraż: 59,45 m2, piętro: parter, liczba pokoi: 3 w inwestycji Ruczaj Korso - etap II ul. Jana Piltza.\r\n
\r\n
Link do tej oferty: https://www.dominium.pl/pl/inwestycje/szczegoly/obiekt/3367/ul-jana-piltza-ruczaj-korso-etap-ii/603_inw_3367_393759\r\n
\r\n
Proszę o kontakt.\r\n
\r\n
Dodatkowe uwagi: Proszę o podanie ceny tej oferty.\r\n
\r\n
\r\n
\r\n
Wyrażono zgodę: * Potwierdzam, że zapoznałem się z treścią Regulaminu oraz Polityki Prywatności i je akceptuję.\r\n
\r\n
NIE WYRAŻONO zgody na: otrzymywanie od Ogłoszeniodawcy informacji handlowej telefonicznie na podany przeze mnie w formularzu numer telefonu.\r\n
\r\n
Treść przedstawionego użytkownikowi obowiązku informacyjnego: Administratorami Twoich danych osobowych są Robert Borzym i Bartłomiej Dyląg, działający pod firmą Friscom s.c. Robert Borzym, Bartłomiej Dyląg ('FRISCOM'). Wysyłając zapytanie i akceptując Regulamin zawierasz umowę z FRISCOM, który będzie przetwarzał dane osobowe przekazane przez Ciebie za pomocą formularza w celu wykonania tej umowy oraz dla prawnie uzasadnionych celów FRISCOM. Dane te będą przekazane Ogłoszeniodawcy ( Wojt-Bud Sp. z o.o. z siedzibą 30-348 Kraków, ul. Chmieleniec 2B/7, KRS 0000015967, NIP 945-19-41-198, REGON 356270169 ), który z chwilą zebrania danych staje się również ich administratorem i może je przetwarzać w celu skontaktowania się z Tobą w odpowiedzi na przesłane przez Ciebie zapytanie. Dane przekazujemy także podmiotom trzecim, z którymi łączy FRISCOM stosunek powierzenia. Masz prawo do dostępu do swoich danych, ich sprostowania, usunięcia lub ograniczenia przetwarzania. W określonych przypadkach masz prawo do wniesienia sprzeciwu wobec przetwarzania danych na potrzeby takiego marketingu, przenoszenia danych, a także do wniesienia skargi do organu nadzorczego. Czytaj więcej...\r\n
\r\n
Telefon kontaktowy: Wysyłający zapytanie nie podał nr. telefonu, aby odpowiedzieć napisz pod podany niżej adres e-mail.\r\n
E-mail: ulica_radosci0n@icloud.com";
private $emailWithNoItems = 'Test email with no items'; 

    protected function setUp(): void
    {
        $this->strategy = new DominiumStrategy();
    }

    public function testProcess()
    {
        $expected1 = [
            'portal_name' => 'dominium.pl',
            'name' => null,
            'email' => 'justyna.dylczyk@gmail.com',
            'phone' => '535970570',
            'investment_name' => 'Ruczaj Korso - etap III ul. Jana Piltza',
            'property_name' => null,
            'message' => $this->email1
        ];

        $expected2 = [
            'portal_name' => 'dominium.pl',
            'name' => null,
            'email' => 'ulica_radosci0n@icloud.com',
            'phone' => null,
            'investment_name' => 'Ruczaj Korso - etap II ul. Jana Piltza',
            'property_name' => '2.C',
            'message' => $this->email2
        ];

        $expected3 = [
            'portal_name' => 'dominium.pl',
            'name' => null,
            'email' => null,
            'phone' => null,
            'investment_name' => null,
            'property_name' => null,
            'message' => $this->emailWithNoItems
        ];



        $result = $this->strategy->process($this->email1);
        $this->assertEquals($expected1, $result);

        $result = $this->strategy->process($this->email2);
        $this->assertEquals($expected2, $result);

        $result = $this->strategy->process($this->emailWithNoItems);
        $this->assertEquals($expected3, $result);

    }

    public function testFindCustomerEmail()
    {
        $result1 = $this->strategy->findCustomerEmail($this->email1);
        $expected1 = 'justyna.dylczyk@gmail.com';
        $this->assertEquals($expected1, $result1);

        $result2 = $this->strategy->findCustomerEmail($this->email2);
        $expected2 = 'ulica_radosci0n@icloud.com';
        $this->assertEquals($expected2, $result2);

        $result3 = $this->strategy->findCustomerEmail($this->emailWithNoItems);
        $expected3 = null;
        $this->assertEquals($expected3, $result3);
    }

    public function testFindCustomerPhone() {
        $result1 = $this->strategy->findCustomerPhone($this->email1);
        $expected1 = '535970570';
        $this->assertEquals($expected1, $result1);

        $result2 = $this->strategy->findCustomerPhone($this->email2);
        $expected2 = null;
        $this->assertEquals($expected2, $result2);

        $result3 = $this->strategy->findCustomerPhone($this->emailWithNoItems);
        $expected3 = null;
        $this->assertEquals($expected3, $result3);
    }

    public function testFindInvestmentName() {
        $result1 = $this->strategy->findInvestmentName($this->email1);
        $expected1 = 'Ruczaj Korso - etap III ul. Jana Piltza';
        $this->assertEquals($expected1, $result1);

        $result2 = $this->strategy->findInvestmentName($this->email2);
        $expected2 = 'Ruczaj Korso - etap II ul. Jana Piltza';
        $this->assertEquals($expected2, $result2);

        $result3 = $this->strategy->findInvestmentName($this->emailWithNoItems);
        $expected3 = null;
        $this->assertEquals($expected3, $result3);
    }

    public function testFindPropertyName() {
        $result1 = $this->strategy->findPropertyName($this->email1);
        $expected1 = null;
        $this->assertEquals($expected1, $result1);

        $result2 = $this->strategy->findPropertyName($this->email2);
        $expected2 = '2.C';
        $this->assertEquals($expected2, $result2);

        $result3 = $this->strategy->findPropertyName($this->emailWithNoItems);
        $expected3 = null;
        $this->assertEquals($expected3, $result3);
    }
}
