<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;
use App\Helpers\EmailTemplatesJsonParser\Interfaces\Block;

class Divider extends BaseBlock
{
    private string $view = 'email-templates-json-parser.blocks.divider';
    public string $type = BlockTypes::DIVIDER;


    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {
        return view($this->view, [
            'hrStyles' => $this->propsToHrStyles(),
            'style' => $this->parseStyles($this->data['data']['style']),
        ]);
    }

    public  function propsToHrStyles() {

        if (!isset($this->data['data']['props']['lineColor']) || !isset($this->data['data']['props']['lineHeight'])) {
            return '';
        }

        return 'border-top: ' . $this->data['data']['props']['lineHeight'] . 'px solid ' . $this->data['data']['props']['lineColor'] . ';';

    }
}
