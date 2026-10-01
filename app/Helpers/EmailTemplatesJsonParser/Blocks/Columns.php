<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlocksFactory;
use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;

class Columns extends BaseBlock
{
    private string $view = 'email-templates-json-parser.blocks.columns';
    public string $type = BlockTypes::COLUMNS;

    public array $childBlocks = [];

    public ?array $layout = null;


    public function __construct(array $data = [], $layout = null)
    {
        parent::__construct($data);
        $this->layout = $layout;
    }

    public function parse()
    {
        $this->prepareChildBlocks();
        return view($this->view, [
            'props' => $this->data['props'],
            'style' => $this->parseStyles($this->data['style']),
            'columnVerticalAlign' => $this->parseStyles($this->getVerticalAlign()),
            'columnsGap' => $this->calculateColumnsGap(),
            'renderedColumns' => $this->renderColumns()
        ]);
    }
    

    public function getVerticalAlign()
    {
        if (isset($this->data['props']['contentAlignment'])) {
            return ['verticalAlign' => $this->data['props']['contentAlignment']];
        }

        return null;
    }

    public function calculateColumnsGap()
    {

        $gap = $this->data['props']['columnsGap'];
        if ($gap == 0) {
            return null;
        }


        for ($i =  0; $i < $this->data['props']['columnsCount']; $i++) {
            if ($i == 0) {
                $result[] = [
                    'padding' => [
                        'left' => 0,
                        'right' => 0
                    ]
                ];
            } else {
                $result[] = [
                    'padding' => [
                        'left' => $gap,
                        'right' => 0
                    ]
                ];
            }
        }

        foreach ($result as $key => $value) {
            $result[$key] = $this->parseStyles($value);
        }

        return $result;
    }

    public function parseColumnsFixedWidthsToStyles()
    {

        if (!isset($this->data['props']['fixedWidths'])) {
            return null;
        }

        $result = collect($this->data['props']['fixedWidths'])->map(function ($value) {
            if ($value == null) {
                return null;
            }

            return 'width:' . $value . 'px;';
        });



        return $result->toArray();
    }

    public function getColumnsBlocks()
    {
        if (!$this->layout) {
            return null;
        }

        $columns = $this->data['props']['columns'];

        if (empty($columns)) {
            return null;
        }

        $columns = collect($columns)->map(function ($column) {
            $childrenId = $column['childrenIds'][0];

            return $this->layout[$childrenId];
        });
        return $columns->toArray();
    }

    public function prepareChildBlocks()
    {
        $blocktypes = [BlockTypes::CONTAINER, BlockTypes::EMAIL_LAYOUT];
  
        foreach ($this->layout as $block_id => $block) {
            if (in_array($block['type'], $blocktypes)) {
                continue;
            }
            $this->childBlocks[$block_id] = BlocksFactory::createBlock($block['type'], $block['data']);
        }

    }

    public function renderChildBlocks() {
        
        $result = collect($this->childBlocks)->map(function ($childBlock) {
            return $childBlock->parse()->render();
        });

        return $result;
    }

    private function renderColumns()
    {
        $columns = $this->data['props']['columns'];
        $renderedColumns = [];

        foreach ($columns as $column) {
            $columnContent = '';
            foreach ($column['childrenIds'] as $childId) {
                if (isset($this->childBlocks[$childId])) {
                    $columnContent .= $this->childBlocks[$childId]->parse();
                }
            }
            $renderedColumns[] = $columnContent;
        }

        return $renderedColumns;
    }

    public function containsBlock($block_id)
    {
        foreach ($this->data['props']['columns'] as $column) {
            if (in_array($block_id, $column['childrenIds'])) {
                return true;
            }
        }
        return false;
    }
}
