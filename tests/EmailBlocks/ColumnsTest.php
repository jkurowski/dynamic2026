<?php

namespace Tests\EmailBlocks;

use App\Helpers\EmailTemplatesJsonParser\Blocks\Columns;
use PHPUnit\Framework\TestCase;

class ColumnsTest extends TestCase
{
    private $layout = '{
  "root": {
    "type": "EmailLayout",
    "data": {
      "textColor": "#262626",
      "fontFamily": "MODERN_SANS",
      "canvasColor": "#FFFFFF",
      "childrenIds": [
        "block-1724830129282"
      ],
      "backdropColor": "#F5F5F5"
    }
  },
  "block-1724830129282": {
    "type": "ColumnsContainer",
    "data": {
      "style": {
        "padding": {
          "top": 16,
          "bottom": 16,
          "right": 24,
          "left": 24
        }
      },
      "props": {
        "columnsCount": 3,
        "columnsGap": 16,
        "columns": [
          {
            "childrenIds": [
              "block-1724830130509"
            ]
          },
          {
            "childrenIds": [
              "block-1724830131549"
            ]
          },
          {
            "childrenIds": [
              "block-1724830132517"
            ]
          }
        ]
      }
    }
  },
  "block-1724830130509": {
    "type": "Button",
    "data": {
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
    }
  },
  "block-1724830131549": {
    "type": "Text",
    "data": {
      "props": {
        "text": "Lorem ipsum dolor"
      },
      "style": {
        "padding": {
          "top": 16,
          "bottom": 16,
          "left": 24,
          "right": 24
        },
        "fontWeight": "normal"
      }
    }
  },
  "block-1724830132517": {
    "type": "Heading",
    "data": {
      "props": {
        "text": "Lorem ipsum dolor"
      },
      "style": {
        "padding": {
          "top": 16,
          "bottom": 16,
          "left": 24,
          "right": 24
        }
      }
    }
  }
}';

    private $json = '{
      "style": {
        "padding": {
          "top": 16,
          "bottom": 16,
          "right": 24,
          "left": 24
        }
      },
      "props": {
        "columnsCount": 3,
        "columnsGap": 16,
        "columns": [
          {
            "childrenIds": [
              "block-1724830130509"
            ]
          },
          {
            "childrenIds": [
              "block-1724830131549"
            ]
          },
          {
            "childrenIds": [
              "block-1724830132517"
            ]
          }
        ]
      }
    }';
    public function testParse()
    {
        $this->markTestIncomplete('To be implemented');
    }

    public function testGetVerticalAlign()
    {
        $columns = new Columns(json_decode($this->json, true));
        $result = $columns->getVerticalAlign();
        $expected = null;

        $this->assertEquals($expected, $result);
    }

    public function testCalculateColumnsGap()
    {
        $columns = new Columns(json_decode($this->json, true));
        $result = $columns->calculateColumnsGap();
        $expected = [
            0 => 'padding-left:0px;padding-right:0px;',
            1 => 'padding-left:16px;padding-right:0px;',
            2 => 'padding-left:16px;padding-right:0px;',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testParseColumnsFixedWidthsToStyles()
    {
        $columns = new Columns(json_decode($this->json, true));
        $result = $columns->parseColumnsFixedWidthsToStyles();
        $expected = null;

        $this->assertEquals($expected, $result);
    }

    public function testProcessChildren() {
        $columns = new Columns(json_decode($this->json, true), json_decode($this->layout, true));
        $result = $columns->getColumnsBlocks();
    }

    public function testPrepareChildBlocks() {
      $columns = new Columns(json_decode($this->json, true), json_decode($this->layout, true));
      $columns->prepareChildBlocks();
      
      // dump($columns->childBlocks);
    }
    
    public function testRenderChildBlocks() {
      $columns = new Columns(json_decode($this->json, true), json_decode($this->layout, true));
       $columns->prepareChildBlocks();
      // $columns->prepareChildBlocks();
      
      // dump($columns->parse());
      // dump($columns->renderChildBlocks());
    }
}
