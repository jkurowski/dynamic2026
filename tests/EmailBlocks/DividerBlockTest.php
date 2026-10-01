<?php

namespace App\Helpers\EmailTemplatesJsonParser\Tests\Blocks;



use App\Helpers\EmailTemplatesJsonParser\Blocks\Divider;
use PHPUnit\Framework\TestCase;


class DividerBlockTest extends TestCase
{
    private $json = '{
    "type": "Divider",
    "data": {
      "style": {
        "backgroundColor": "#bc5454",
        "padding": {
          "top": 16,
          "bottom": 16,
          "right": 0,
          "left": 0
        }
      },
      "props": {
        "lineColor": "#CCCCCC",
        "lineHeight": 5
      }
    }
  }';

    public function testPropsToHrStyles()
    {
        $dividerBlock = new Divider(json_decode($this->json, true));

        $this->assertEquals('border-top: 5px solid #CCCCCC;', $dividerBlock->propsToHrStyles());
    }
}
