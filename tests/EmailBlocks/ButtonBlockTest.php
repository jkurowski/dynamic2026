<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Helpers\EmailTemplatesJsonParser\Blocks\Button;

class ButtonBlockTest extends TestCase
{
    private $json = '{
      "props": {
        "text": "Przycisk",
        "url": "https://www.usewaypoint.com"
      },
      "style": {
        "padding": {
          "top": 16,
          "bottom": 16,
          "left": 24,
          "right": 24
        }
      }
    }';
    public function testGetButtonShapeStyle()
    {
        $button = new Button(json_decode($this->json, true));

        $this->assertEquals(['border-radius' =>  '0'], $button->getButtonShapeStyle('rectangle'));
        $this->assertEquals(['border-radius' =>  '8px'], $button->getButtonShapeStyle('rounded'));
        $this->assertEquals(['border-radius' =>  '64px'], $button->getButtonShapeStyle('pill'));
        $this->assertEquals(['border-radius' =>  '4px'], $button->getButtonShapeStyle('x'));
    }

    public function testGetFullWidthStyles()
    {
        $button = new Button(json_decode($this->json, true));

        $this->assertEquals(['display' => 'inline-block'], $button->getFullWidthStyles());
    }

    public function testGetSize()
    {
        $button = new Button(json_decode($this->json, true));

        $this->assertEquals(['padding' => '4px 8px'], $button->getSize('x-small'));
        $this->assertEquals(['padding' => '8px 12px'], $button->getSize('small'));
        $this->assertEquals(['padding' => '12px 20px'], $button->getSize('medium'));
        $this->assertEquals(['padding' => '16px 32px'], $button->getSize('large'));
    }

    public function testGetWrapperStyles()
    {


        $button = new Button(json_decode($this->json, true));


        $expected = [
            'fontWeight' => 'bold',
            'padding' => [
                'top' => 16,
                'bottom' => 16,
                'right' => 24,
                'left' => 24
            ]
        ];


        $this->assertEquals($expected, $button->getWrapperStyles());
    }

    public function testGetButtonStyles()
    {
        $button = new Button(json_decode($this->json, true));
        $result = $button->getButtonStyles();

        $expected = [
            'backgroundColor' => '#999999',
            'color' => '#ffffff',
            'display' => 'inline-block',
            'border-radius' => '4px',
            'padding' => '12px 20px'
        ];

        $this->assertEquals($expected, $result);
    }
}
