<?php

namespace Tests\ExternalLeads;

use App\Services\Leads\OwnStrategy;
use PHPUnit\Framework\TestCase;

class OwnStrategyTest extends TestCase
{
    private $strategy;
    private $email1 = "
INWESTYCJA: Testowa 26
MIESZKANIE: 2
IMIE: imie i naziwsko
EMAIL: mail@google.com
TELEFON: 456954283
OPIS:
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam nec purus nec

ZGODY:
ZGODA_MARKETING: 1 || 0
ZGODA_MAIL: 1 || 0
ZGODA_TEL: 1 || 0";
    private $emailWithNoItems = "Test email with no items";

    protected function setUp(): void
    {
        $this->strategy = new OwnStrategy();
    }

    public function testProcess()
    {
        $expected = [
            'portal_name' => 'wlasny',
            'name' => 'imie i naziwsko',
            'email' => 'mail@google.com',
            'phone' => '456954283',
            'investment_name' => 'Testowa 26',
            'property_name' => '2',
            'message' => $this->email1
        ];
        

        $expectedNoItems = [
            'portal_name' => 'wlasny',
            'name' => null,
            'email' => null,
            'phone' => null,
            'investment_name' => null,
            'property_name' => null,
            'message' => $this->emailWithNoItems
        ];

        $result = $this->strategy->process($this->email1);
        $this->assertEquals($expected, $result);

        $resultNoItems = $this->strategy->process($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $resultNoItems);
    }

    public function testFindCustomerName()
    {   
        $expected = 'imie i naziwsko';
        $result = $this->strategy->findCustomerName($this->email1);
        $this->assertEquals($expected, $result);

        $expectedNoItems = null;
        $resultNoItems = $this->strategy->findCustomerName($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $resultNoItems);

    }

    public function testFindCustomerEmail()
    {   

        $expected = 'mail@google.com';
        $result = $this->strategy->findCustomerEmail($this->email1);
        $this->assertEquals($expected, $result);

        $expectedNoItems = null;
        $resultNoItems = $this->strategy->findCustomerEmail($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $resultNoItems);

    }

    public function testFindCustomerPhone()
    {
        $expected = '456954283';
        $result = $this->strategy->findCustomerPhone($this->email1);
        $this->assertEquals($expected, $result);

        $expectedNoItems = null;
        $resultNoItems = $this->strategy->findCustomerPhone($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $resultNoItems);

    }

    public function testFindInvestmentName()
    {   
        $expected = 'Testowa 26';
        $result = $this->strategy->findInvestmentName($this->email1);
        $this->assertEquals($expected, $result);

        $expectedNoItems = null;
        $resultNoItems = $this->strategy->findInvestmentName($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $resultNoItems);

    }

    public function testFindPropertyName()
    {
        $expected = '2';
        $result = $this->strategy->findPropertyName($this->email1);
        $this->assertEquals($expected, $result);

        $expectedNoItems = null;
        $resultNoItems = $this->strategy->findPropertyName($this->emailWithNoItems);
        $this->assertEquals($expectedNoItems, $resultNoItems);
    }
}
